<?php

use App\Mail\CouponExpiringMail;
use App\Mail\PointsExpiringMail;
use App\Models\Coupon;
use App\Models\LoyaltyTransaction;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule announcement status updates to run every minute
Schedule::command('announcements:update-statuses')->everyMinute();

// ============================================
// Coupon & Loyalty System Scheduled Tasks
// ============================================

// 1. Expire old coupons daily at 1:00 AM
Schedule::call(function () {
    $expiredCount = Coupon::where('status', 'active')
        ->where('valid_until', '<', now())
        ->update(['status' => 'expired']);
    
    if ($expiredCount > 0) {
        \Log::info("Expired {$expiredCount} coupons", ['date' => now()]);
    }
})->dailyAt('01:00')->name('expire-coupons')->description('Mark expired coupons as expired');

// 2. Send coupon expiry warnings daily at 9:00 AM (7 days before expiration)
Schedule::call(function () {
    $warningDate = now()->addDays(7);
    
    // Get user-specific coupons expiring in 7 days
    $expiringCoupons = Coupon::where('status', 'active')
        ->where('coupon_type', 'user_specific')
        ->whereNotNull('specific_user_id')
        ->whereDate('valid_until', $warningDate->toDateString())
        ->with('specificUser')
        ->get();
    
    foreach ($expiringCoupons as $coupon) {
        if ($coupon->specificUser) {
            try {
                Mail::to($coupon->specificUser->email)
                    ->queue(new CouponExpiringMail($coupon->specificUser, $coupon));
                
                \Log::info("Sent coupon expiry warning", [
                    'user_id' => $coupon->specificUser->id,
                    'coupon_id' => $coupon->id,
                    'coupon_code' => $coupon->code
                ]);
            } catch (\Exception $e) {
                \Log::error("Failed to send coupon expiry warning", [
                    'user_id' => $coupon->specificUser->id,
                    'coupon_id' => $coupon->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
})->dailyAt('09:00')->name('coupon-expiry-warnings')->description('Send coupon expiry reminders (7 days before)');

// 3. Send loyalty points expiry warnings daily at 9:30 AM (30 days before expiration)
Schedule::call(function () {
    $warningDate = now()->addDays(30);
    
    // Get expiring points grouped by user
    $expiringPoints = LoyaltyTransaction::where('transaction_type', 'earned')
        ->whereDate('expires_at', $warningDate->toDateString())
        ->where(function($query) {
            $query->whereNull('expiry_warning_sent')
                  ->orWhere('expiry_warning_sent', false);
        })
        ->with('user.loyaltyPoints')
        ->get()
        ->groupBy('user_id');
    
    foreach ($expiringPoints as $userId => $transactions) {
        $user = $transactions->first()->user;
        
        if ($user && $user->loyaltyPoints) {
            try {
                Mail::to($user->email)
                    ->queue(new PointsExpiringMail($user, $transactions));
                
                // Mark warnings as sent
                $transactions->each(function($transaction) {
                    $transaction->update(['expiry_warning_sent' => true]);
                });
                
                \Log::info("Sent points expiry warning", [
                    'user_id' => $userId,
                    'expiring_points' => $transactions->sum('points')
                ]);
            } catch (\Exception $e) {
                \Log::error("Failed to send points expiry warning", [
                    'user_id' => $userId,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
})->dailyAt('09:30')->name('points-expiry-warnings')->description('Send loyalty points expiry reminders (30 days before)');

// 4. Expire old loyalty points daily at 2:00 AM
Schedule::call(function () {
    $expiredTransactions = LoyaltyTransaction::where('transaction_type', 'earned')
        ->where('expires_at', '<', now())
        ->whereNull('expired_at')
        ->with('user.loyaltyPoints')
        ->get();
    
    $totalExpiredPoints = 0;
    $affectedUsers = 0;
    
    foreach ($expiredTransactions as $transaction) {
        if ($transaction->user && $transaction->user->loyaltyPoints) {
            $loyaltyPoints = $transaction->user->loyaltyPoints;
            
            // Deduct expired points from balance
            $loyaltyPoints->decrement('total_points', $transaction->points);
            $loyaltyPoints->decrement('available_points', $transaction->points);
            
            // Create expiration transaction log
            LoyaltyTransaction::create([
                'user_id' => $transaction->user_id,
                'transaction_type' => 'expired',
                'points' => -$transaction->points,
                'source' => 'points_expiry',
                'description' => "Points expired after 12 months (Original transaction: {$transaction->id})",
                'balance_after' => $loyaltyPoints->total_points,
                'expires_at' => null
            ]);
            
            // Mark original transaction as expired
            $transaction->update(['expired_at' => now()]);
            
            $totalExpiredPoints += $transaction->points;
            $affectedUsers++;
            
            \Log::info("Expired loyalty points", [
                'user_id' => $transaction->user_id,
                'points' => $transaction->points,
                'transaction_id' => $transaction->id
            ]);
        }
    }
    
    if ($totalExpiredPoints > 0) {
        \Log::info("Daily points expiry completed", [
            'total_expired_points' => $totalExpiredPoints,
            'affected_users' => $affectedUsers,
            'date' => now()
        ]);
    }
})->dailyAt('02:00')->name('expire-loyalty-points')->description('Expire loyalty points older than 12 months');

// 5. Check and update loyalty tiers daily at 3:00 AM
Schedule::call(function () {
    $users = \App\Models\User::where('role', 'client')
        ->with('loyaltyPoints')
        ->whereHas('loyaltyPoints')
        ->get();
    
    $upgradedCount = 0;
    
    foreach ($users as $user) {
        $currentTier = $user->loyaltyPoints->tier;
        $lifetimePoints = $user->loyaltyPoints->lifetime_earned;
        
        // Determine new tier based on lifetime earned points
        $newTier = 'bronze';
        if ($lifetimePoints >= 50000) {
            $newTier = 'platinum';
        } elseif ($lifetimePoints >= 15000) {
            $newTier = 'gold';
        } elseif ($lifetimePoints >= 5000) {
            $newTier = 'silver';
        }
        
        // Update tier if changed
        if ($newTier !== $currentTier) {
            $user->loyaltyPoints->update([
                'tier' => $newTier,
                'tier_achieved_at' => now()
            ]);
            
            $upgradedCount++;
            
            \Log::info("User tier updated", [
                'user_id' => $user->id,
                'old_tier' => $currentTier,
                'new_tier' => $newTier,
                'lifetime_points' => $lifetimePoints
            ]);
        }
    }
    
    if ($upgradedCount > 0) {
        \Log::info("Daily tier update completed", [
            'upgraded_users' => $upgradedCount,
            'date' => now()
        ]);
    }
})->dailyAt('03:00')->name('update-loyalty-tiers')->description('Check and update user loyalty tiers based on lifetime points');

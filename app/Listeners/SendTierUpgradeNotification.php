<?php

namespace App\Listeners;

use App\Events\TierUpgraded;
use App\Mail\TierUpgradedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendTierUpgradeNotification
{
    /**
     * Handle the event.
     */
    public function handle(TierUpgraded $event): void
    {
        try {
            $event->user->refresh();
            $event->user->load('loyaltyPoints');
            
            Mail::to($event->user->email)
                ->queue(new TierUpgradedMail(
                    $event->user,
                    $event->oldTier,
                    $event->newTier
                ));
            
            Log::info('Tier upgrade email queued', [
                'user_id' => $event->user->id,
                'old_tier' => $event->oldTier,
                'new_tier' => $event->newTier
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send tier upgrade email', [
                'error' => $e->getMessage(),
                'user_id' => $event->user->id,
                'old_tier' => $event->oldTier,
                'new_tier' => $event->newTier
            ]);
        }
    }
}

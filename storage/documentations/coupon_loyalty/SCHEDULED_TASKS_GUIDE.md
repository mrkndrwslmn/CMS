# Coupon & Loyalty System - Scheduled Tasks

## Overview

The Coupon and Loyalty System includes 5 automated scheduled tasks that run daily to maintain the system and send timely notifications to users.

## Scheduled Tasks

### 1. Expire Old Coupons
**Schedule:** Daily at 1:00 AM  
**Task Name:** `expire-coupons`  
**Purpose:** Automatically mark active coupons as expired when their validity period ends

**What it does:**
- Finds all active coupons where `valid_until` has passed
- Updates their status to `expired`
- Logs the number of coupons expired

**Manual execution:**
```bash
php artisan schedule:run
```

---

### 2. Send Coupon Expiry Warnings
**Schedule:** Daily at 9:00 AM  
**Task Name:** `coupon-expiry-warnings`  
**Purpose:** Remind users about user-specific coupons expiring in 7 days

**What it does:**
- Finds user-specific coupons expiring in exactly 7 days
- Sends `CouponExpiringMail` to each coupon owner
- Includes coupon details, discount, and urgency messaging
- Logs successful sends and errors

**Email Template:** `resources/views/emails/coupon-expiring.blade.php`

---

### 3. Send Loyalty Points Expiry Warnings
**Schedule:** Daily at 9:30 AM  
**Task Name:** `points-expiry-warnings`  
**Purpose:** Warn users about loyalty points expiring in 30 days

**What it does:**
- Finds loyalty points transactions expiring in exactly 30 days
- Groups expiring points by user
- Sends `PointsExpiringMail` with total expiring points and breakdown
- Marks transactions as `expiry_warning_sent = true` to prevent duplicate emails
- Logs successful sends and errors

**Email Template:** `resources/views/emails/points-expiring.blade.php`

**Note:** Users receive ONE email per expiry date, even if they have multiple point batches expiring on that date.

---

### 4. Expire Old Loyalty Points
**Schedule:** Daily at 2:00 AM  
**Task Name:** `expire-loyalty-points`  
**Purpose:** Automatically expire loyalty points older than 12 months

**What it does:**
- Finds earned points where `expires_at` has passed
- Deducts expired points from user's `total_points` and `available_points`
- Creates a new "expired" transaction log entry
- Marks original transaction with `expired_at` timestamp
- Logs each expiration for audit trail

**Business Rule:** Points expire 12 months after they are earned (set in `expires_at` when points are awarded).

---

### 5. Update Loyalty Tiers
**Schedule:** Daily at 3:00 AM  
**Task Name:** `update-loyalty-tiers`  
**Purpose:** Check and upgrade user loyalty tiers based on lifetime earned points

**What it does:**
- Iterates through all clients with loyalty accounts
- Calculates appropriate tier based on lifetime earned points:
  - Bronze: 0 - 4,999 points
  - Silver: 5,000 - 14,999 points
  - Gold: 15,000 - 49,999 points
  - Platinum: 50,000+ points
- Updates tier if changed and sets `tier_achieved_at`
- Logs all tier upgrades

**Note:** This task ensures tiers stay accurate even if points were manually adjusted by admins.

---

## Configuration

All scheduled tasks are defined in: `routes/console.php`

### Customizing Schedule Times

To change when tasks run, edit the schedule times in `routes/console.php`:

```php
// Example: Change coupon expiry to run at 11:00 PM
Schedule::call(function () {
    // ... task logic
})->dailyAt('23:00');

// Example: Run every 6 hours instead of daily
})->cron('0 */6 * * *');
```

### Points Expiry Period

To change the 12-month expiry period, update the `expires_at` calculation in:
- `app/Services/LoyaltyService.php` → `earnPoints()` method

```php
// Current: 12 months
'expires_at' => now()->addMonths(12),

// Change to 24 months:
'expires_at' => now()->addMonths(24),
```

Also update the warning period (currently 30 days) in `routes/console.php`:

```php
// Current: 30 days warning
$warningDate = now()->addDays(30);

// Change to 60 days warning:
$warningDate = now()->addDays(60);
```

---

## Running Scheduled Tasks

### Development Environment

Laravel's scheduler requires a single cron entry on your server. In development, you can run scheduled tasks manually:

**Run all due tasks:**
```bash
php artisan schedule:run
```

**Run continuously (checks every minute):**
```bash
php artisan schedule:work
```

**List all scheduled tasks:**
```bash
php artisan schedule:list
```

**Test a specific task (dry run):**
```bash
php artisan schedule:test
```

### Production Environment

Add this single cron entry to your server (runs every minute):

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

**For Windows Task Scheduler:**
- **Action:** Start a program
- **Program:** `C:\path\to\php.exe`
- **Arguments:** `C:\path\to\project\artisan schedule:run`
- **Trigger:** Daily at 12:00 AM, repeat every 1 minute for 24 hours

---

## Monitoring & Logging

### Log Files

All scheduled tasks log their activity to Laravel's log files:

**Location:** `storage/logs/laravel.log`

**Log entries include:**
- Success confirmations with counts
- Individual operation details (user IDs, coupon codes, points)
- Error messages with full context

### Example Log Entries

```
[2025-11-13 01:00:00] INFO: Expired 5 coupons {"date":"2025-11-13 01:00:00"}

[2025-11-13 09:00:00] INFO: Sent coupon expiry warning {"user_id":42,"coupon_id":15,"coupon_code":"WELCOME50"}

[2025-11-13 09:30:00] INFO: Sent points expiry warning {"user_id":42,"expiring_points":5000}

[2025-11-13 02:00:00] INFO: Expired loyalty points {"user_id":42,"points":1000,"transaction_id":123}

[2025-11-13 03:00:00] INFO: User tier updated {"user_id":42,"old_tier":"silver","new_tier":"gold","lifetime_points":15500}
```

### Monitoring Checklist

- [ ] Check logs daily for errors
- [ ] Monitor email queue for delivery failures
- [ ] Verify scheduled tasks are running (check last run times)
- [ ] Review expired points/coupons counts monthly
- [ ] Track tier upgrade frequency

---

## Troubleshooting

### Task Not Running

**Check if scheduler is active:**
```bash
php artisan schedule:list
```

**Manually trigger all due tasks:**
```bash
php artisan schedule:run
```

**Check cron/task scheduler configuration:**
- Linux/Mac: `crontab -l`
- Windows: Open Task Scheduler, find Laravel task

### Emails Not Sending

**Check mail configuration:**
```bash
php artisan config:cache
php artisan queue:work
```

**Check `.env` file:**
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yoursite.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Test email manually:**
```php
use App\Mail\CouponExpiringMail;
use App\Models\User;
use App\Models\Coupon;

$user = User::find(1);
$coupon = Coupon::find(1);

Mail::to($user->email)->send(new CouponExpiringMail($user, $coupon));
```

### Points Not Expiring

**Check database:**
```sql
-- Find points that should be expired
SELECT * FROM loyalty_transactions 
WHERE transaction_type = 'earned' 
AND expires_at < NOW() 
AND expired_at IS NULL;
```

**Manually trigger expiry:**
```bash
php artisan tinker

// Run the expiry task manually
$expiredTransactions = \App\Models\LoyaltyTransaction::where('transaction_type', 'earned')
    ->where('expires_at', '<', now())
    ->whereNull('expired_at')
    ->get();

// Process each...
```

---

## Performance Considerations

### Large User Base

If you have 10,000+ users, consider:

1. **Chunk processing** for tier updates:
```php
User::where('role', 'client')
    ->whereHas('loyaltyPoints')
    ->chunk(100, function($users) {
        // Process each chunk
    });
```

2. **Queue emails** instead of sending synchronously:
```php
Mail::to($user->email)->queue(new PointsExpiringMail($user, $transactions));
```

3. **Add indexes** to frequently queried columns (already included in migrations).

4. **Run intensive tasks during off-peak hours** (current schedule: 1:00-3:00 AM).

---

## Testing Scheduled Tasks

### Manual Testing

**Test coupon expiry:**
```php
php artisan tinker

// Create a test coupon that's already expired
$coupon = Coupon::create([
    'code' => 'TEST_EXPIRED',
    'name' => 'Test Expired Coupon',
    'discount_type' => 'percentage',
    'discount_value' => 10,
    'coupon_type' => 'public',
    'valid_until' => now()->subDay(),
    'status' => 'active',
    'created_by' => 1
]);

// Run the expiry task
php artisan schedule:run

// Check if status changed
$coupon->refresh();
$coupon->status; // Should be 'expired'
```

**Test points expiry warning:**
```php
// Create a transaction expiring in 30 days
$transaction = LoyaltyTransaction::create([
    'user_id' => 1,
    'transaction_type' => 'earned',
    'points' => 500,
    'source' => 'test',
    'description' => 'Test expiry warning',
    'balance_after' => 500,
    'expires_at' => now()->addDays(30),
    'expiry_warning_sent' => false
]);

// Run the warning task
php artisan schedule:run

// Check if email was sent and flag updated
$transaction->refresh();
$transaction->expiry_warning_sent; // Should be true
```

---

## Future Enhancements

- [ ] Add admin dashboard showing upcoming expirations
- [ ] Send reminder emails at multiple intervals (7 days, 3 days, 1 day)
- [ ] Add SMS notifications for premium users
- [ ] Create weekly/monthly summary emails
- [ ] Add task execution history to database
- [ ] Implement retry logic for failed emails
- [ ] Add Slack/Discord notifications for admin monitoring

---

## Related Files

- **Task definitions:** `routes/console.php`
- **Email classes:** `app/Mail/*.php`
- **Email templates:** `resources/views/emails/*.blade.php`
- **Models:** `app/Models/Coupon.php`, `app/Models/LoyaltyTransaction.php`
- **Configuration:** `config/mail.php`, `.env`

---

## Support

For issues or questions about scheduled tasks:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Review task list: `php artisan schedule:list`
3. Test manually: `php artisan schedule:run`
4. Check email queue: `php artisan queue:work`

**Documentation:** [Laravel Task Scheduling](https://laravel.com/docs/12.x/scheduling)

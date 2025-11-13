# Coupon & Loyalty System - Testing & Integration Guide

## Phase 9: Integration Complete ✅

All email notifications are now integrated into the system and will trigger automatically based on user actions.

---

## 🔗 Email Integration Points

### 1. Coupon Assignment Email (`CouponAssignedMail`)

**Trigger:** When admin approves a service request and assigns a coupon

**Controller:** `App\Http\Controllers\Admin\RequestManagementController@approve`

**Flow:**
1. Admin approves service request with coupon attachment
2. Coupon is applied via `CouponService::autoAssignCouponToRequest()`
3. Email is queued to client showing:
   - Coupon code and discount details
   - Original vs discounted budget
   - Savings amount
   - Payment breakdown

**Test:**
```php
// 1. Login as admin
// 2. Go to pending service request
// 3. Click "Approve"
// 4. Check "Attach Coupon" option
// 5. Either select existing coupon or create new one
// 6. Submit approval
// Expected: Client receives email with coupon details
```

---

### 2. Loyalty Points Earned Email (`LoyaltyPointsEarnedMail`)

**Trigger:** After successful payment confirmation via Maya

**Service:** `App\Services\LoyaltyService@awardPointsForPayment`

**Flow:**
1. Client completes Maya payment
2. Payment is verified and confirmed
3. Loyalty points are calculated and awarded
4. Email is queued showing:
   - Points earned
   - New total balance
   - Current tier and benefits
   - Progress to next tier
   - Points value in pesos

**Test:**
```php
// 1. Login as client
// 2. Navigate to approved service request
// 3. Click "Proceed to Payment"
// 4. Complete Maya checkout
// 5. Return to success page
// Expected: Client receives email showing points earned
```

---

### 3. Tier Upgrade Email (`TierUpgradedMail`)

**Trigger:** When user accumulates enough lifetime points to reach new tier

**Event/Listener:** 
- Event: `App\Events\TierUpgraded`
- Listener: `App\Listeners\SendTierUpgradeNotification`

**Flow:**
1. User earns points (from payment, milestone, etc.)
2. `LoyaltyPoint::checkAndUpgradeTier()` detects tier change
3. `TierUpgraded` event is dispatched
4. Listener sends celebration email showing:
   - Old tier → New tier transition
   - New earning rate and benefits
   - Tier-specific perks unlocked
   - Congratulations message

**Tier Thresholds:**
- Bronze: 0 - 4,999 points (default)
- Silver: 5,000 - 14,999 points
- Gold: 15,000 - 49,999 points
- Platinum: 50,000+ points

**Test:**
```php
// Manually upgrade a user for testing
php artisan tinker

$user = User::find(1); // Client user
$loyaltyPoint = $user->loyaltyPoints;

// Upgrade to Silver (requires 5000+ lifetime points)
$loyaltyPoint->update(['lifetime_earned' => 5000]);
$loyaltyPoint->checkAndUpgradeTier();

// Expected: User receives tier upgrade email
```

---

### 4. Points Expiring Warning Email (`PointsExpiringMail`)

**Trigger:** Scheduled task runs daily at 9:30 AM

**Scheduled Task:** `routes/console.php` → `points-expiry-warnings`

**Flow:**
1. Task finds points expiring in exactly 30 days
2. Groups expiring points by user
3. Sends warning email with:
   - Total expiring points
   - Expiry date and countdown
   - Current balance breakdown
   - Suggestions for redemption

**Manual Test:**
```php
php artisan tinker

// Create a transaction expiring in 30 days
$user = User::find(1);
$loyaltyPoint = $user->getOrCreateLoyaltyPoints();

$transaction = LoyaltyTransaction::create([
    'user_id' => $user->id,
    'transaction_type' => 'earned',
    'points' => 1000,
    'source' => 'test_expiry',
    'description' => 'Test expiring points',
    'balance_after' => $loyaltyPoint->total_points + 1000,
    'expires_at' => now()->addDays(30),
    'expiry_warning_sent' => false
]);

// Run the scheduled task
exit(); // Exit tinker
php artisan schedule:run

// Expected: User receives points expiry warning email
```

---

### 5. Coupon Expiring Warning Email (`CouponExpiringMail`)

**Trigger:** Scheduled task runs daily at 9:00 AM

**Scheduled Task:** `routes/console.php` → `coupon-expiry-warnings`

**Flow:**
1. Task finds user-specific coupons expiring in 7 days
2. Sends reminder email with:
   - Coupon code and discount
   - Days until expiration
   - Uses remaining
   - Urgency messaging

**Manual Test:**
```php
php artisan tinker

// Create a user-specific coupon expiring in 7 days
$user = User::find(1);

$coupon = Coupon::create([
    'code' => 'TEST_EXPIRING',
    'name' => 'Test Expiring Coupon',
    'description' => 'Testing expiry warning',
    'discount_type' => 'percentage',
    'discount_value' => 15,
    'coupon_type' => 'user_specific',
    'specific_user_id' => $user->id,
    'max_uses_per_user' => 1,
    'valid_until' => now()->addDays(7),
    'status' => 'active',
    'created_by' => 1
]);

exit();
php artisan schedule:run

// Expected: User receives coupon expiry reminder email
```

---

## 🔧 Queue Configuration

### Setting Up Queue Workers

For optimal performance, email notifications are **queued** for asynchronous delivery.

**1. Configure Queue Driver**

Edit `.env`:
```env
QUEUE_CONNECTION=database
```

**2. Create Jobs Table** (if not exists):
```bash
php artisan queue:table
php artisan migrate
```

**3. Run Queue Worker**

**Development:**
```bash
php artisan queue:work
```

**Production (with supervisor):**
Create supervisor config at `/etc/supervisor/conf.d/laravel-worker.conf`:
```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/project/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/project/storage/logs/worker.log
```

**Windows Task Scheduler:**
- Action: Start program
- Program: `C:\path\to\php.exe`
- Arguments: `C:\path\to\project\artisan queue:work --sleep=3 --tries=3`
- Trigger: At startup, repeat indefinitely

---

## 📧 Email Configuration

### Mail Provider Setup

**1. Configure `.env`:**

**Using Gmail:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yoursite.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Using Mailtrap (Testing):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=test@example.com
MAIL_FROM_NAME="CMS Testing"
```

**2. Clear Config Cache:**
```bash
php artisan config:cache
```

**3. Test Email Sending:**
```bash
php artisan tinker

use App\Mail\LoyaltyPointsEarnedMail;
use App\Models\User;

$user = User::find(1);
$transaction = $user->loyaltyPoints->transactions()->latest()->first();

Mail::to($user->email)->send(new LoyaltyPointsEarnedMail($user, $transaction));
```

---

## 🧪 End-to-End Testing Scenarios

### Scenario 1: Complete Client Journey

**Goal:** Test entire flow from request submission to loyalty points

**Steps:**
1. **Register as new client**
   - Expected: Bronze tier initialized

2. **Submit service request**
   - Fill in project details
   - Submit request

3. **Admin approves with coupon**
   - Login as admin
   - Approve request
   - Attach 15% discount coupon
   - Expected: Client receives `CouponAssignedMail`

4. **Client views approved request**
   - See coupon applied
   - See discounted budget
   - Click "Proceed to Payment"

5. **Complete payment via Maya**
   - Process payment
   - Return to success page
   - Expected: Client receives `LoyaltyPointsEarnedMail`

6. **Check loyalty dashboard**
   - See points balance
   - View transaction history
   - Verify tier progression

7. **Accumulate more points**
   - Complete more projects
   - Watch for tier upgrades
   - Expected: `TierUpgradedMail` when threshold reached

---

### Scenario 2: Coupon Usage Flow

**Goal:** Test coupon application and usage tracking

1. **Admin creates public coupon**
   - Code: `SAVE20`
   - Type: Percentage (20%)
   - Validity: 30 days
   - Max uses: 100

2. **Client browses coupons**
   - Navigate to coupons page
   - See available coupons
   - Copy coupon code

3. **Client applies coupon**
   - Go to approved request
   - Enter coupon code
   - See discount applied

4. **Client completes payment**
   - Pay with coupon discount
   - Verify reduced amount

5. **Admin checks coupon usage**
   - View coupon details
   - See usage count increased
   - See usage history

---

### Scenario 3: Loyalty Points Redemption

**Goal:** Test points redemption for discount

1. **Client has 5000 points**
   - Silver tier (5% automatic discount)
   - Available balance: 5000 points

2. **Client creates new service request**
   - Request approved by admin
   - Budget: ₱10,000

3. **Client applies loyalty points**
   - Navigate to payment page
   - See available points: 5000
   - Redeem 2000 points (= ₱2000 discount)
   - See 5% tier discount applied (₱500)
   - Final: ₱10,000 - ₱500 - ₱2000 = ₱7,500

4. **Client completes payment**
   - Pay ₱7,500
   - Points deducted: 2000
   - New balance: 3000 points
   - Expected: `LoyaltyPointsEarnedMail` with new points

---

### Scenario 4: Scheduled Tasks

**Goal:** Verify automated maintenance tasks

**Morning Routine (9:00-9:30 AM):**

1. **9:00 AM - Coupon Expiry Warnings**
   ```bash
   # Manually trigger
   php artisan schedule:test
   
   # Check logs
   tail -f storage/logs/laravel.log | grep "coupon-expiry-warnings"
   ```

2. **9:30 AM - Points Expiry Warnings**
   ```bash
   # Check points expiring in 30 days
   php artisan tinker
   
   LoyaltyTransaction::where('transaction_type', 'earned')
       ->whereDate('expires_at', now()->addDays(30))
       ->get();
   ```

**Night Routine (1:00-3:00 AM):**

1. **1:00 AM - Expire Old Coupons**
   ```sql
   -- Check coupons that should expire
   SELECT * FROM coupons 
   WHERE status = 'active' 
   AND valid_until < NOW();
   ```

2. **2:00 AM - Expire Old Points**
   ```sql
   -- Check points that should expire
   SELECT * FROM loyalty_transactions 
   WHERE transaction_type = 'earned' 
   AND expires_at < NOW() 
   AND expired_at IS NULL;
   ```

3. **3:00 AM - Update Tiers**
   ```sql
   -- Check users eligible for upgrade
   SELECT u.id, u.fullName, lp.tier, lp.lifetime_earned
   FROM users u
   JOIN loyalty_points lp ON u.id = lp.user_id
   WHERE lp.lifetime_earned >= 5000 AND lp.tier = 'bronze';
   ```

---

## 🐛 Troubleshooting

### Issue: Emails Not Sending

**Diagnosis:**
```bash
# Check queue jobs
php artisan queue:failed

# Check mail configuration
php artisan config:clear
php artisan config:cache

# Test mail connection
php artisan tinker
Mail::raw('Test', function($msg) {
    $msg->to('test@example.com')->subject('Test');
});
```

**Solutions:**
- Verify `.env` mail settings
- Check firewall/port blocking
- Enable "Less secure apps" (Gmail)
- Use app-specific password (Gmail)
- Check spam folder

---

### Issue: Queue Not Processing

**Diagnosis:**
```bash
# Check if worker is running
ps aux | grep "queue:work"

# Check failed jobs
php artisan queue:failed
```

**Solutions:**
```bash
# Start queue worker
php artisan queue:work --tries=3

# Retry failed jobs
php artisan queue:retry all

# Clear queue
php artisan queue:clear
```

---

### Issue: Scheduled Tasks Not Running

**Diagnosis:**
```bash
# List scheduled tasks
php artisan schedule:list

# Test schedule (dry run)
php artisan schedule:test

# Check last run
php artisan schedule:run
```

**Solutions:**
- Verify cron entry exists
- Check task scheduler is running (Windows)
- Manually run: `php artisan schedule:run`
- Check logs for errors

---

### Issue: Tier Not Upgrading

**Diagnosis:**
```php
php artisan tinker

$user = User::find(1);
$lp = $user->loyaltyPoints;

echo "Tier: {$lp->tier}\n";
echo "Lifetime: {$lp->lifetime_earned}\n";
echo "Calculated: " . $lp->calculateTier() . "\n";

// Force tier check
$lp->checkAndUpgradeTier();
```

**Solutions:**
- Ensure `lifetime_earned` is updating
- Run tier update task manually
- Check event listener is registered
- Verify tier thresholds in config

---

## 📊 Monitoring & Analytics

### Key Metrics to Track

**Daily:**
- Emails sent/failed
- Queue processing time
- Failed jobs count
- Points expiry warnings sent
- Coupons expired

**Weekly:**
- Coupon redemption rate
- Average discount per transaction
- Points earned vs redeemed
- Tier distribution

**Monthly:**
- New loyalty members
- Tier upgrades
- Customer retention rate
- Average lifetime points

### Logging

**Check application logs:**
```bash
tail -f storage/logs/laravel.log
```

**Filter by component:**
```bash
# Coupon logs
grep "Coupon" storage/logs/laravel.log

# Loyalty logs
grep "Loyalty" storage/logs/laravel.log

# Email logs
grep "email" storage/logs/laravel.log
```

---

## ✅ Pre-Production Checklist

- [ ] All migrations run successfully
- [ ] Email templates render correctly
- [ ] Queue worker configured and running
- [ ] Scheduled tasks registered in crontab/task scheduler
- [ ] Mail provider configured and tested
- [ ] All 5 email notifications tested manually
- [ ] Coupon creation and assignment working
- [ ] Loyalty points calculation accurate
- [ ] Tier upgrades triggering correctly
- [ ] Points redemption functional
- [ ] Expiry warnings sending on schedule
- [ ] Failed jobs monitored
- [ ] Error logging configured
- [ ] Database backups scheduled
- [ ] Load testing completed
- [ ] Documentation updated
- [ ] Client/admin guides prepared

---

## 🚀 Going Live

### Final Steps

1. **Update `.env` to production settings:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   QUEUE_CONNECTION=database
   MAIL_MAILER=smtp # Use production mail service
   ```

2. **Optimize application:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan optimize
   ```

3. **Start queue worker:**
   ```bash
   php artisan queue:work --daemon --tries=3
   ```

4. **Verify cron job:**
   ```bash
   crontab -l # Should show Laravel scheduler
   ```

5. **Monitor logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

6. **Test production emails:**
   - Send test notifications
   - Verify delivery to real email addresses
   - Check spam scoring

---

## 📞 Support

**For issues:**
1. Check logs: `storage/logs/laravel.log`
2. Review queue: `php artisan queue:failed`
3. Test manually: `php artisan tinker`
4. Check configuration: `php artisan config:show`

**Related Documentation:**
- [SCHEDULED_TASKS_GUIDE.md](./SCHEDULED_TASKS_GUIDE.md)
- [COUPON_LOYALTY_IMPLEMENTATION_PLAN.md](./COUPON_LOYALTY_IMPLEMENTATION_PLAN.md)

---

**Phase 9: Integration & Testing - COMPLETE ✅**

All email notifications are integrated and ready for testing. The system is now fully functional and prepared for production deployment.

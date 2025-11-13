# 🧪 Referral System Testing Guide

## Quick Start Testing

This guide will walk you through testing the complete referral system flow.

---

## Prerequisites

1. ✅ Database migrations run successfully
2. ✅ Two test email accounts available
3. ✅ Maya sandbox credentials configured
4. ✅ Laravel queue worker running (for emails)

---

## Test Scenario 1: Complete Happy Path

### Step 1: Create Referrer Account (John)

1. Visit: `http://localhost/register`
2. Fill in form:
   - Full Name: **John Doe**
   - Email: **john@example.com**
   - Phone: **+63 917 123 4567**
   - Role: **Client**
   - Password: **password123**
3. Click "Create Account"
4. ✅ **Expected:** Account created, redirected to dashboard

### Step 2: Get John's Referral Code

1. Navigate to: `/client/referrals/dashboard`
2. Look for referral code (format: `JOHN2025ABC`)
3. Click "Copy Code" button
4. ✅ **Expected:** Toast notification "Code copied!"
5. **Save this code for Step 3**

### Step 3: Register Referred User (Sarah)

1. Open incognito/private window
2. Visit: `http://localhost/register?ref=JOHN2025ABC` (use actual code)
3. ✅ **Expected:** 
   - Referral code field is pre-filled
   - Green banner shows: "You've been referred!"
   - Banner text: "Complete registration to unlock your welcome bonus: 500 points + 15% off coupon"
   - Validation shows green ✓ + "Valid code from John Doe"

4. Fill in form:
   - Full Name: **Sarah Johnson**
   - Email: **sarah@example.com**
   - Phone: **+63 917 987 6543**
   - Role: **Client**
   - Password: **password123**

5. Click "Create Account"
6. ✅ **Expected:** 
   - Account created
   - Success message: "Welcome to Treis Adiutor! Your account has been created successfully. 🎉 Your referral bonus has been credited!"

### Step 4: Verify Sarah's Welcome Bonus

1. As Sarah, navigate to: `/client/loyalty`
2. ✅ **Expected:** Points balance shows **500 points**
3. Check transaction history:
   - ✅ "Referral Welcome Bonus" transaction
   - ✅ +500 points

4. Navigate to: `/client/coupons`
5. ✅ **Expected:** One coupon available
   - Type: Percentage discount
   - Discount: 15%
   - Valid for 30 days
   - Source: Referral Program

### Step 5: Verify John's Signup Notification

1. Check John's email inbox
2. ✅ **Expected:** Email received
   - Subject: "🎉 Good News! Your Referral Just Signed Up"
   - Content shows: Sarah Johnson signed up
   - Shows: "Pending First Payment" badge
   - Shows: Potential reward (1000 pts + 20% off)

3. As John, check dashboard: `/client/referrals/dashboard`
4. ✅ **Expected:**
   - Total Referrals: **1**
   - Pending Referrals: **1**
   - Successful Referrals: **0**
   - Pending Rewards section shows Sarah

### Step 6: Sarah Creates Service Request

1. As Sarah, navigate to: `/client/requests/create`
2. Fill in service request form:
   - Project Name: **Test Website**
   - Description: **Testing referral system**
   - Budget: **₱10,000**
   - Deadline: Next month
   - Priority: Medium
3. Submit request
4. ✅ **Expected:** Request created successfully

### Step 7: Admin Approves & Generates Payment

1. Login as admin
2. Navigate to: `/admin/requests`
3. Find Sarah's request
4. Click "Approve"
5. Generate payment link
6. ✅ **Expected:** Payment link created

### Step 8: Sarah Makes Payment (CRITICAL)

1. As Sarah, navigate to: `/client/requests`
2. Click on approved request
3. Click "Pay Now" button
4. ✅ **Expected:** Redirected to Maya sandbox
5. Complete payment with Maya test credentials
6. ✅ **Expected:** Redirected to success page

### Step 9: Verify Referral Completion

1. Check application logs:
```bash
php artisan tinker
>>> \Illuminate\Support\Facades\Log::info('Check referral completion logs');
```

2. ✅ **Expected logs:**
   - "Loyalty points awarded for payment"
   - "Referral completion processed for payment"
   - "Referral completed and rewarded successfully"

### Step 10: Verify John's Completion Reward

1. Check John's email inbox
2. ✅ **Expected:** Email received
   - Subject: "🎁 Referral Reward Unlocked! Points & Coupon Earned"
   - Shows: 1,000 points earned
   - Shows: 20% off coupon code
   - Shows: Sarah's name

3. As John, check: `/client/loyalty`
4. ✅ **Expected:** 
   - Points increased by **1,000**
   - Transaction: "Referral Completion Reward - Sarah Johnson"

5. Check: `/client/coupons`
6. ✅ **Expected:** New coupon available
   - Type: Percentage discount
   - Discount: 20%
   - Valid for 60 days
   - Source: Referral Program

### Step 11: Verify Statistics Update

1. As John, check: `/client/referrals/dashboard`
2. ✅ **Expected:**
   - Total Referrals: **1**
   - Successful Referrals: **1**
   - Pending Referrals: **0**
   - Lifetime Earnings: **1,000 points**
   - Conversion Rate: **100%**

3. Check referrals table:
   - Sarah's referral shows "Completed" badge
   - Points Earned: 1,000
   - Coupon: [Code displayed]

---

## Test Scenario 2: Invalid Referral Code

### Steps:

1. Visit: `http://localhost/register`
2. Enter invalid code: `INVALID123`
3. Wait 500ms for validation
4. ✅ **Expected:**
   - Red X icon appears
   - Error message: "✗ Invalid referral code"
5. Try to submit form
6. ✅ **Expected:** Form submits (referral is optional)
7. Account created without referral bonus

---

## Test Scenario 3: Self-Referral Prevention

### Steps:

1. Login as John
2. Get John's referral code: `JOHN2025ABC`
3. Logout
4. Visit: `http://localhost/register?ref=JOHN2025ABC`
5. Fill form with **John's email** (john@example.com)
6. ✅ **Expected:** 
   - Validation passes (code is valid)
   - Account creation fails: "Email already taken"

**Note:** Self-referral prevention happens in `ReferralService::processRegistrationReferral()`:
```php
// Prevent self-referral
if ($referralCode->user_id === $newUser->id) {
    throw new \Exception('Cannot use your own referral code');
}
```

---

## Test Scenario 4: Duplicate Referral Prevention

### Steps:

1. Sarah tries to register again with John's code
2. ✅ **Expected:** Email validation fails (email already exists)

**Database Level:** Unique constraint prevents duplicate referrer-referred pairs:
```sql
UNIQUE KEY unique_referral (referrer_id, referred_id)
```

---

## Test Scenario 5: Second Payment (No Double Reward)

### Steps:

1. As Sarah, create another service request
2. Admin approves and generates payment
3. Sarah makes second payment
4. ✅ **Expected:**
   - Payment succeeds
   - Loyalty points awarded to Sarah
   - **NO referral reward to John** (already rewarded)
   - Logs show: "User has already received referral reward"

---

## Test Scenario 6: Expired/Inactive Code

### Manual Database Update:
```sql
UPDATE referral_codes 
SET is_active = 0 
WHERE code = 'JOHN2025ABC';
```

### Steps:

1. Visit: `http://localhost/register?ref=JOHN2025ABC`
2. ✅ **Expected:**
   - Validation shows red X
   - Error: "This referral code is no longer active"

---

## Database Verification Queries

### Check Referral Created
```sql
SELECT 
    r.*,
    referrer.fullName as referrer_name,
    referred.fullName as referred_name
FROM referrals r
JOIN users referrer ON r.referrer_id = referrer.id
JOIN users referred ON r.referred_id = referred.id
WHERE referred.email = 'sarah@example.com';
```

### Check Loyalty Points
```sql
-- Sarah's welcome bonus
SELECT * FROM loyalty_transactions 
WHERE user_id = (SELECT id FROM users WHERE email = 'sarah@example.com')
AND transaction_type = 'credit'
AND description LIKE '%Referral Welcome Bonus%';

-- John's completion reward
SELECT * FROM loyalty_transactions 
WHERE user_id = (SELECT id FROM users WHERE email = 'john@example.com')
AND transaction_type = 'credit'
AND description LIKE '%Referral Completion Reward%';
```

### Check Coupons Generated
```sql
-- Sarah's welcome coupon (15% off)
SELECT * FROM coupons 
WHERE user_id = (SELECT id FROM users WHERE email = 'sarah@example.com')
AND source = 'referral_program'
AND discount_type = 'percentage'
AND discount_value = 15;

-- John's completion coupon (20% off)
SELECT * FROM coupons 
WHERE user_id = (SELECT id FROM users WHERE email = 'john@example.com')
AND source = 'referral_program'
AND discount_type = 'percentage'
AND discount_value = 20;
```

### Check Referral Status
```sql
SELECT 
    r.status,
    r.referral_code,
    r.pending_points,
    r.earned_points,
    r.completed_at,
    r.rewarded_at
FROM referrals r
WHERE referred_id = (SELECT id FROM users WHERE email = 'sarah@example.com');
```

### Check Statistics
```sql
SELECT 
    code,
    total_referrals,
    pending_referrals,
    successful_referrals,
    lifetime_earnings_points,
    ROUND((successful_referrals * 100.0 / NULLIF(total_referrals, 0)), 2) as conversion_rate
FROM referral_codes
WHERE user_id = (SELECT id FROM users WHERE email = 'john@example.com');
```

---

## Email Testing Checklist

### Email 1: Referral Signup
- [ ] Subject line correct
- [ ] Referrer's name displayed
- [ ] Referred user's name displayed
- [ ] "Pending First Payment" badge visible
- [ ] Potential reward amount shown (1000 pts + 20%)
- [ ] "What Happens Next" section visible
- [ ] CTA button links to dashboard
- [ ] Responsive design (test on mobile)

### Email 2: Referral Completed
- [ ] Subject line correct
- [ ] Points earned (1,000) displayed prominently
- [ ] Coupon code visible and copyable
- [ ] Referred user's name shown
- [ ] Rewards breakdown section complete
- [ ] Dual CTA buttons (View Points / View Coupons)
- [ ] Usage instructions clear
- [ ] Responsive design (test on mobile)

---

## Performance Testing

### Load Test Registration Flow

```bash
# Apache Bench test (100 registrations with referral codes)
ab -n 100 -c 10 -p register.json -T 'application/json' \
   http://localhost/register
```

### Monitor Queue Processing

```bash
# Watch queue worker
php artisan queue:work --verbose

# Check failed jobs
php artisan queue:failed
```

---

## Troubleshooting Guide

### Issue: Referral code validation not working

**Check:**
1. Route exists: `php artisan route:list | grep referrals.validate`
2. CSRF token in page source
3. Browser console for JavaScript errors
4. Network tab for 422/500 errors

### Issue: Welcome bonus not credited

**Check:**
1. Referral record created: `SELECT * FROM referrals ORDER BY id DESC LIMIT 1;`
2. User has `referred_by_user_id`: `SELECT referred_by_user_id FROM users WHERE email = 'sarah@example.com';`
3. Loyalty transaction created: `SELECT * FROM loyalty_transactions WHERE description LIKE '%Welcome Bonus%';`
4. Coupon generated: `SELECT * FROM coupons WHERE source = 'referral_program' AND discount_value = 15;`

### Issue: Completion reward not given

**Check:**
1. Payment confirmed: `SELECT status FROM payments WHERE client_id = [sarah_id] ORDER BY id DESC LIMIT 1;`
2. Is first payment: `SELECT COUNT(*) FROM payments WHERE client_id = [sarah_id] AND status = 'confirmed';`
3. Referral status: `SELECT status FROM referrals WHERE referred_id = [sarah_id];`
4. Application logs: `tail -f storage/logs/laravel.log | grep -i referral`

### Issue: Emails not received

**Check:**
1. Queue worker running: `ps aux | grep 'queue:work'`
2. Mail configuration: `php artisan tinker` → `config('mail')`
3. Failed jobs: `SELECT * FROM failed_jobs;`
4. Email service logs (Mailtrap/SendGrid/etc.)

---

## Clean Up After Testing

### Reset Test Data

```sql
-- Delete test referrals
DELETE FROM referrals WHERE referrer_id IN (
    SELECT id FROM users WHERE email IN ('john@example.com', 'sarah@example.com')
);

-- Delete test coupons
DELETE FROM coupons WHERE user_id IN (
    SELECT id FROM users WHERE email IN ('john@example.com', 'sarah@example.com')
) AND source = 'referral_program';

-- Delete test loyalty transactions
DELETE FROM loyalty_transactions WHERE user_id IN (
    SELECT id FROM users WHERE email IN ('john@example.com', 'sarah@example.com')
) AND description LIKE '%Referral%';

-- Delete test referral codes
DELETE FROM referral_codes WHERE user_id IN (
    SELECT id FROM users WHERE email IN ('john@example.com', 'sarah@example.com')
);

-- Delete test users (cascades to other tables)
DELETE FROM users WHERE email IN ('john@example.com', 'sarah@example.com');
```

---

## Success Criteria

✅ **Registration Flow:**
- [ ] Referral code validates in real-time
- [ ] Welcome bonus (500 pts + 15% coupon) awarded on signup
- [ ] Referred user has correct database fields set

✅ **Payment Completion:**
- [ ] First payment triggers referral completion
- [ ] Referrer receives 1,000 points + 20% coupon
- [ ] Referral status changes: pending → completed → rewarded
- [ ] Second payment does NOT reward referrer again

✅ **Notifications:**
- [ ] Signup email sent to referrer
- [ ] Completion email sent to referrer
- [ ] Both emails display correctly
- [ ] Links in emails work

✅ **Statistics:**
- [ ] Referral code statistics update correctly
- [ ] Conversion rate calculated properly
- [ ] Dashboard shows accurate counts

✅ **Edge Cases:**
- [ ] Invalid codes rejected
- [ ] Self-referral prevented
- [ ] Duplicate referrals prevented
- [ ] Inactive codes rejected
- [ ] Registration without code works normally

---

## 🎉 Testing Complete

Once all scenarios pass, the referral system is ready for production!

**Next Steps:**
1. ✅ Complete all test scenarios
2. 📸 Document any bugs found
3. 🔧 Fix issues and retest
4. 📊 Create admin views for monitoring
5. 🚀 Deploy to production

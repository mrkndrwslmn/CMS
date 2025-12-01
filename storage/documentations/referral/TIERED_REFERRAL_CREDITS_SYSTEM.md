# 💰 Tiered Referral Credits System - Implementation Documentation

**Date:** December 1, 2025  
**Status:** ✅ Implemented  
**Version:** 2.0

---

## 📋 Overview

The enhanced referral system now supports **tiered rewards based on payment amounts** with two benefit types:
1. **Referral Credits** (withdrawable money)
2. **Discount Coupons** (percentage off)

### Who Can Refer?
- ✅ **Clients** - Can refer new clients
- ✅ **Adiutors** - Can refer new clients

### Key Features
- 💵 Tiered percentage rewards based on project payment amount
- 💳 Withdrawable referral credits (like payout system)
- 🎫 Alternative coupon-based rewards
- 📊 Automatic calculation and distribution
- 🔒 Minimum withdrawal thresholds
- 📈 Complete transaction history

---

## 💰 Reward Tier Structure

### Payment Amount Tiers (UPDATED - More Realistic)

| Tier | Payment Range | Referrer Reward (Credits) | Referred Reward (Coupon) |
|------|--------------|---------------------------|--------------------------|
| **Tier 1** | ₱100,000 - ₱200,000 | **3%** of amount | **10%** coupon discount |
| **Tier 2** | ₱200,000 - ₱500,000 | **2%** of amount | **8%** coupon discount |
| **Tier 3** | ₱500,000 - ₱1,000,000 | **1.5%** of amount | **5%** coupon discount |
| **Tier 4** | ₱1,000,000+ | **1%** of amount | **5%** coupon discount |

### ⚠️ Important: Benefit Type Assignment
- **Referrer (Who Referred):** ALWAYS gets **CREDITS** (withdrawable money)
- **Referred (Who Was Referred):** ALWAYS gets **COUPON** (discount percentage)

### Example Calculations

#### Example 1: ₱150,000 Project
- **Referrer gets:** ₱4,500 **credits** (3% of ₱150,000) - withdrawable
- **Referred gets:** **10% discount coupon** for next project

#### Example 2: ₱300,000 Project
- **Referrer gets:** ₱6,000 **credits** (2% of ₱300,000) - withdrawable
- **Referred gets:** **8% discount coupon** for next project

#### Example 3: ₱750,000 Project
- **Referrer gets:** ₱11,250 **credits** (1.5% of ₱750,000) - withdrawable
- **Referred gets:** **5% discount coupon** for next project

#### Example 4: ₱2,000,000 Project
- **Referrer gets:** ₱20,000 **credits** (1% of ₱2,000,000) - withdrawable
- **Referred gets:** **5% discount coupon** for next project

---

## 🏗️ Database Structure

### New Tables

#### 1. `referral_credit_withdrawals`
Tracks withdrawal requests for referral credits.

```sql
- id
- withdrawal_number (unique, e.g., "RW-20251201-0001")
- user_id
- amount
- status (pending, processing, completed, rejected, cancelled)
- withdrawal_method (bank_transfer, gcash, paymaya, paypal)
- withdrawal_details (JSON: account info)
- processed_by (admin user_id)
- requested_at
- processed_at
- completed_at
- reference_number
- proof_of_payment
- user_notes
- admin_notes
- rejection_reason
- timestamps
```

#### 2. `referral_credit_transactions`
Audit log of all credit movements.

```sql
- id
- user_id
- transaction_type (earned, withdrawn, refunded, adjusted)
- amount
- balance_before
- balance_after
- source
- description
- referral_id
- withdrawal_id
- performed_by
- timestamps
```

### Updated Tables

#### `users` Table
```sql
+ referral_credits DECIMAL(10,2) DEFAULT 0
+ referral_credits_pending DECIMAL(10,2) DEFAULT 0
+ referral_credits_withdrawn DECIMAL(10,2) DEFAULT 0
```

#### `referrals` Table
```sql
+ referrer_benefit_type ENUM('coupon', 'credits') DEFAULT 'credits'
+ referrer_credits_earned DECIMAL(10,2) DEFAULT 0
+ referrer_discount_percentage DECIMAL(5,2) DEFAULT 0
+ referred_benefit_type ENUM('coupon', 'credits') DEFAULT 'coupon'
+ referred_credits_earned DECIMAL(10,2) DEFAULT 0
+ referred_discount_percentage DECIMAL(5,2) DEFAULT 0
+ qualifying_payment_amount DECIMAL(10,2) NULLABLE
```

---

## ⚙️ Configuration

**File:** `config/referral.php`

```php
'reward_tiers' => [
    [
        'min_amount' => 100000,
        'max_amount' => 200000,
        'referrer_percentage' => 3.0,   // 3% credits
        'referred_percentage' => 10.0,  // 10% coupon
    ],
    [
        'min_amount' => 200000,
        'max_amount' => 500000,
        'referrer_percentage' => 2.0,   // 2% credits
        'referred_percentage' => 8.0,   // 8% coupon
    ],
    [
        'min_amount' => 500000,
        'max_amount' => 1000000,
        'referrer_percentage' => 1.5,   // 1.5% credits
        'referred_percentage' => 5.0,   // 5% coupon
    ],
    [
        'min_amount' => 1000000,
        'max_amount' => null,
        'referrer_percentage' => 1.0,   // 1% credits
        'referred_percentage' => 5.0,   // 5% coupon
    ],
],

'benefits' => [
    'referrer_default_type' => 'credits',  // ALWAYS credits
    'referred_default_type' => 'coupon',   // ALWAYS coupon
    
    'credits' => [
        'minimum_withdrawal' => 1000,
        'withdrawal_fee_percentage' => 0,
        'withdrawal_methods' => [
            'bank_transfer' => 'Bank Transfer',
            'gcash' => 'GCash',
            'paymaya' => 'PayMaya / Maya',
            'paypal' => 'PayPal',
        ],
    ],
    
    'coupon' => [
        'validity_days' => 90,              // Valid for 90 days
        'min_purchase_amount' => 50000,     // Min ₱50,000 to use
        'max_discount_amount' => 30000,     // Max ₱30,000 discount
        'stackable_with_loyalty' => false,  // Cannot stack
    ],
],

'eligibility' => [
    'allowed_roles' => ['client', 'adiutor'],
    'min_qualifying_amount' => 100000,
],
```

---

## 🔄 System Flow

### 1. Registration with Referral Code
1. New user signs up with referral code
2. System validates code
3. Creates `Referral` record with status `pending`
4. Awards welcome bonus (legacy points + optional coupon)

### 2. First Payment Triggers Rewards
1. Referred user makes first payment ≥ ₱100,000
2. System checks `Referral` status (must be `pending`)
3. Calculates tier based on payment amount
4. Determines benefit types (credits or coupons)
5. Awards benefits:
   - **If Credits:** Adds to `user.referral_credits`
   - **If Coupon:** Generates time-limited discount coupon
6. Updates referral status to `completed` → `rewarded`
7. Logs transaction in `referral_credit_transactions`

### 3. Withdrawal Process
1. User requests withdrawal (min ₱1,000)
2. Credits move from `referral_credits` to `referral_credits_pending`
3. Admin reviews and processes
4. Upon approval:
   - Credits move to `referral_credits_withdrawn`
   - Admin enters reference number
   - User receives notification
5. Upon rejection:
   - Credits return to `referral_credits`
   - User notified with reason

---

## 📁 New Files Created

### Models
- `app/Models/ReferralCreditWithdrawal.php`
- `app/Models/ReferralCreditTransaction.php`

### Migrations
- `database/migrations/2025_12_01_000001_add_referral_credits_system.php`

### Service Updates
- `app/Services/ReferralService.php` - Enhanced with:
  - `awardTieredBenefits()`
  - `getRewardTier()`
  - `awardReferralCredits()`
  - `requestWithdrawal()`
  - `completeWithdrawal()`
  - `rejectWithdrawal()`

### Controller Updates
- `app/Http/Controllers/Client/ReferralController.php` - Added:
  - `credits()` - View credits & withdrawal page
  - `requestWithdrawal()` - Submit withdrawal request
  - `showWithdrawal()` - View withdrawal details
  - `cancelWithdrawal()` - Cancel pending withdrawal

---

## 🌐 New Routes

### Client/Adiutor Routes
```php
GET  /client/referrals/credits               // View credits & withdrawal page
POST /client/referrals/withdrawals            // Request withdrawal
GET  /client/referrals/withdrawals/{id}       // View withdrawal details
POST /client/referrals/withdrawals/{id}/cancel // Cancel pending withdrawal
```

### Admin Routes
```php
GET  /admin/referrals/withdrawals/pending           // View all pending withdrawals
GET  /admin/referrals/withdrawals/{withdrawal}      // View withdrawal details
POST /admin/referrals/withdrawals/{withdrawal}/process // Mark as processing
POST /admin/referrals/withdrawals/{withdrawal}/complete // Complete withdrawal
POST /admin/referrals/withdrawals/{withdrawal}/reject  // Reject withdrawal
```

---

## 🎯 User Workflows

### Client/Adiutor: Refer Someone
1. Navigate to **Referrals Dashboard**
2. Copy referral link or code
3. Share via email, social media, or direct message
4. Track referrals in dashboard

### Client/Adiutor: Withdraw Credits
1. Navigate to **Referrals > Credits**
2. View available balance
3. Click "Request Withdrawal"
4. Enter:
   - Amount (min ₱1,000)
   - Payment method
   - Account details
5. Submit request
6. Wait for admin processing
7. Receive payment

### Admin: Process Withdrawal
1. Navigate to **Admin > Referrals > Withdrawals > Pending**
2. Click on withdrawal request
3. Review details:
   - User info
   - Amount
   - Payment method
   - Account details
4. Mark as "Processing"
5. Make payment off-system
6. Complete withdrawal:
   - Enter reference number
   - Upload proof (optional)
   - Add notes
7. System marks as completed

---

## 📊 Key Features

### Automatic Tier Calculation
- System automatically determines tier based on payment amount
- No manual configuration needed per referral
- Scales seamlessly from small to large projects

### Dual Benefit System
- **Credits** for immediate value (withdrawable cash)
- **Coupons** for future discounts (incentivizes repeat business)
- Configurable default per user type

### Security & Validation
- Minimum withdrawal threshold (₱1,000)
- Pending state prevents double-withdrawal
- Admin approval required
- Complete audit trail

### Transaction History
- All credit movements logged
- Balance tracking (before/after)
- Source attribution (referral completion, withdrawal, etc.)
- Filterable and searchable

---

## 🔧 Migration Instructions

### 1. Run Migration
```bash
php artisan migrate
```

This creates:
- `referral_credit_withdrawals` table
- `referral_credit_transactions` table
- Adds columns to `users` and `referrals` tables

### 2. Update Config
Config file already updated with tiered structure.

### 3. Test Flow
1. Create test referral
2. Make payment ≥ ₱100,000
3. Verify credits awarded
4. Test withdrawal request
5. Admin processes withdrawal

---

## 📈 Benefits Analysis

### Why Tiered System?
- **Fair Rewards:** Larger projects = larger rewards
- **Scalable:** Works for projects of any size
- **Motivating:** Clear progression encourages more referrals
- **Sustainable:** Percentages stay proportional to value

### Why Credits Over Points?
- **Real Value:** Withdrawable money is more attractive
- **Flexibility:** Can be used outside platform
- **Trust:** Tangible benefit builds confidence
- **Retention:** Users stay engaged to reach withdrawal threshold

### Why Keep Coupon Option?
- **Platform Loyalty:** Encourages repeat business
- **Higher Margins:** Discounts applied to future work
- **Flexibility:** Some users prefer immediate discounts
- **Stacking:** Can combine with other offers

---

## 🚨 Important Notes

### Minimum Qualifying Amount
- Payment must be **≥ ₱100,000** to trigger rewards
- Below this amount, referral stays `pending`
- Prevents abuse with micro-transactions

### Withdrawal Limits
- **Minimum:** ₱1,000 per withdrawal
- **No Maximum:** Can withdraw full balance
- **Processing Time:** Admin dependent (typically 1-3 business days)

### Legacy System Compatibility
- Old point-based rewards **still active**
- Users get both points AND credits/coupons
- Gradual transition to new system
- No data loss from previous referrals

---

## 📝 Next Steps

### Views to Create
1. `resources/views/client/referrals/credits.blade.php`
2. `resources/views/client/referrals/withdrawal-details.blade.php`
3. `resources/views/admin/referrals/withdrawals-pending.blade.php`
4. `resources/views/admin/referrals/withdrawal-show.blade.php`

### Email Notifications
1. Withdrawal requested (to admin)
2. Withdrawal approved (to user)
3. Withdrawal rejected (to user)
4. Credits earned (to both referrer & referred)

### Testing Checklist
- [ ] Tier calculation accuracy
- [ ] Credit balance updates
- [ ] Withdrawal request flow
- [ ] Admin processing workflow
- [ ] Transaction logging
- [ ] Email notifications
- [ ] Balance validation
- [ ] Minimum threshold enforcement

---

## 📞 Support

For questions or issues:
- **Technical:** Check logs in `storage/logs/laravel.log`
- **Database:** Run `php artisan migrate:status`
- **Config:** Review `config/referral.php`

---

**Implementation Complete! 🎉**

The tiered referral credits system is now ready for testing and deployment.

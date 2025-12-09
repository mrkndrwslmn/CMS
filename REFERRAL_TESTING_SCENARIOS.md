# Referral System Testing Scenarios

This document outlines comprehensive testing scenarios for the referral system, including specific reward amounts for both referrers and referred users.

---

## Overview: Reward Flow

### Two-Stage Reward Process

1. **Stage 1: Registration** - Referred user signs up using a referral code
2. **Stage 2: Qualifying Payment** - Referred user makes a payment of ₱100,000 or more

---

## Stage 1: Registration Rewards (Immediate)

When a new user signs up using a referral code:

| Recipient | Reward Type | Amount |
|-----------|-------------|--------|
| **Referred User** | Welcome Points | 500 loyalty points |
| **Referred User** | Welcome Coupon | 15% off (valid 30 days, min purchase ₱1,000) |
| **Referrer** | Pending Points | 1,000 points (awarded upon completion) |

### Test Case 1.1: New User Registration with Referral Code

**Steps:**
1. Referrer shares their referral code (e.g., `REF-ABC123`)
2. New user visits registration page with code
3. New user completes registration

**Expected Results:**
- ✅ Referred user receives 500 loyalty points immediately
- ✅ Referred user receives 15% welcome coupon
- ✅ Referral record created with status `pending`
- ✅ Referrer sees new pending referral in dashboard

---

## Stage 2: Payment-Based Tiered Rewards

Tiered rewards are triggered when the referred user makes their **first qualifying payment** (minimum ₱100,000).

### Reward Tiers

| Payment Amount | Referrer Credits | Referred Coupon |
|----------------|------------------|-----------------|
| ₱100,000 - ₱199,999 | **3%** of payment | **10%** off next project |
| ₱200,000 - ₱499,999 | **2%** of payment | **8%** off next project |
| ₱500,000 - ₱999,999 | **1.5%** of payment | **5%** off next project |
| ₱1,000,000+ | **1%** of payment | **5%** off next project |

**Note:** Referrer always receives CREDITS (withdrawable cash). Referred always receives COUPON.

---

## Test Case 2.1: Tier 1 - Payment ₱100,000

**Scenario:** Referred user makes a ₱100,000 payment (lowest qualifying tier)

**Steps:**
1. Referred user (who registered with referral code) makes ₱100,000 payment
2. Payment is marked as completed

**Expected Results:**

| Recipient | Reward | Calculation | Amount |
|-----------|--------|-------------|--------|
| **Referrer** | Credits | ₱100,000 × 3% | **₱3,000** |
| **Referrer** | Loyalty Points | Legacy reward | **1,000 points** |
| **Referred** | Coupon | 10% off next project | **10% discount** (max ₱30,000, min purchase ₱50,000) |

**Coupon Details:**
- Discount: 10% off
- Validity: 90 days
- Minimum purchase: ₱50,000
- Maximum discount: ₱30,000

---

## Test Case 2.2: Tier 1 - Payment ₱150,000

**Scenario:** Referred user makes a ₱150,000 payment (mid Tier 1)

**Expected Results:**

| Recipient | Reward | Calculation | Amount |
|-----------|--------|-------------|--------|
| **Referrer** | Credits | ₱150,000 × 3% | **₱4,500** |
| **Referrer** | Loyalty Points | Legacy reward | **1,000 points** |
| **Referred** | Coupon | 10% off next project | **10% discount** |

---

## Test Case 2.3: Tier 2 - Payment ₱200,000

**Scenario:** Referred user makes exactly ₱200,000 payment (Tier 2 boundary)

**Expected Results:**

| Recipient | Reward | Calculation | Amount |
|-----------|--------|-------------|--------|
| **Referrer** | Credits | ₱200,000 × 2% | **₱4,000** |
| **Referrer** | Loyalty Points | Legacy reward | **1,000 points** |
| **Referred** | Coupon | 8% off next project | **8% discount** |

---

## Test Case 2.4: Tier 2 - Payment ₱350,000

**Scenario:** Referred user makes a ₱350,000 payment (mid Tier 2)

**Expected Results:**

| Recipient | Reward | Calculation | Amount |
|-----------|--------|-------------|--------|
| **Referrer** | Credits | ₱350,000 × 2% | **₱7,000** |
| **Referrer** | Loyalty Points | Legacy reward | **1,000 points** |
| **Referred** | Coupon | 8% off next project | **8% discount** |

---

## Test Case 2.5: Tier 3 - Payment ₱500,000

**Scenario:** Referred user makes exactly ₱500,000 payment (Tier 3 boundary)

**Expected Results:**

| Recipient | Reward | Calculation | Amount |
|-----------|--------|-------------|--------|
| **Referrer** | Credits | ₱500,000 × 1.5% | **₱7,500** |
| **Referrer** | Loyalty Points | Legacy reward | **1,000 points** |
| **Referred** | Coupon | 5% off next project | **5% discount** |

---

## Test Case 2.6: Tier 3 - Payment ₱750,000

**Scenario:** Referred user makes a ₱750,000 payment (mid Tier 3)

**Expected Results:**

| Recipient | Reward | Calculation | Amount |
|-----------|--------|-------------|--------|
| **Referrer** | Credits | ₱750,000 × 1.5% | **₱11,250** |
| **Referrer** | Loyalty Points | Legacy reward | **1,000 points** |
| **Referred** | Coupon | 5% off next project | **5% discount** |

---

## Test Case 2.7: Tier 4 - Payment ₱1,000,000

**Scenario:** Referred user makes exactly ₱1,000,000 payment (Tier 4 boundary)

**Expected Results:**

| Recipient | Reward | Calculation | Amount |
|-----------|--------|-------------|--------|
| **Referrer** | Credits | ₱1,000,000 × 1% | **₱10,000** |
| **Referrer** | Loyalty Points | Legacy reward | **1,000 points** |
| **Referred** | Coupon | 5% off next project | **5% discount** |

---

## Test Case 2.8: Tier 4 - Payment ₱2,500,000

**Scenario:** Referred user makes a ₱2,500,000 payment (high value project)

**Expected Results:**

| Recipient | Reward | Calculation | Amount |
|-----------|--------|-------------|--------|
| **Referrer** | Credits | ₱2,500,000 × 1% | **₱25,000** |
| **Referrer** | Loyalty Points | Legacy reward | **1,000 points** |
| **Referred** | Coupon | 5% off next project | **5% discount** |

---

## Edge Case Tests

### Test Case 3.1: Payment Below Minimum (₱99,999)

**Scenario:** Referred user makes a ₱99,999 payment (below ₱100,000 minimum)

**Expected Results:**
- ❌ NO tiered rewards triggered
- ❌ Referral remains in `pending` status
- ❌ Referrer does not receive credits
- ❌ Referred does not receive tiered coupon
- ✅ Payment is processed normally

---

### Test Case 3.2: Multiple Payments - First Qualifying

**Scenario:** Referred user makes payments of ₱50,000 → ₱30,000 → ₱100,000

**Expected Results:**
- First two payments: No rewards (below minimum)
- Third payment (₱100,000): Triggers Tier 1 rewards
  - Referrer: ₱3,000 credits + 1,000 points
  - Referred: 10% coupon

---

### Test Case 3.3: User Without Referral Code

**Scenario:** User makes a ₱500,000 payment but was NOT referred

**Expected Results:**
- ❌ No referral rewards triggered
- ✅ Payment processed normally
- ✅ Normal loyalty points earned (if applicable)

---

### Test Case 3.4: Referral Already Completed

**Scenario:** Referred user makes second qualifying payment after referral completed

**Expected Results:**
- ❌ No additional referral rewards (one-time only)
- ✅ Payment processed normally

---

## Credit Withdrawal Tests (Referrers Only)

### Test Case 4.1: Minimum Withdrawal (₱1,000)

**Scenario:** Referrer has ₱3,000 credits and requests ₱1,000 withdrawal

**Expected Results:**
- ✅ Withdrawal request accepted
- ✅ ₱1,000 marked as pending withdrawal
- ✅ Available balance shows ₱2,000

---

### Test Case 4.2: Below Minimum Withdrawal

**Scenario:** Referrer with ₱500 credits attempts withdrawal

**Expected Results:**
- ❌ Withdrawal rejected (minimum ₱1,000)
- ✅ Error message displayed

---

### Test Case 4.3: Adiutor Withdrawal via Wallet

**Scenario:** Adiutor referrer wants to withdraw credits

**Expected Results:**
- ✅ Referral credits visible on Credits page
- ✅ User redirected to Earnings/Wallet section for withdrawal
- ✅ Referral credits included in total withdrawable balance

---

## Summary: Complete Reward Examples

### Example A: Budget Project (₱120,000)

| Stage | Recipient | Reward | Amount |
|-------|-----------|--------|--------|
| Registration | Referred | Welcome Points | 500 pts |
| Registration | Referred | Welcome Coupon | 15% off |
| Payment | Referrer | Credits | **₱3,600** |
| Payment | Referrer | Points | 1,000 pts |
| Payment | Referred | Tiered Coupon | **10% off** |

---

### Example B: Mid-Range Project (₱300,000)

| Stage | Recipient | Reward | Amount |
|-------|-----------|--------|--------|
| Registration | Referred | Welcome Points | 500 pts |
| Registration | Referred | Welcome Coupon | 15% off |
| Payment | Referrer | Credits | **₱6,000** |
| Payment | Referrer | Points | 1,000 pts |
| Payment | Referred | Tiered Coupon | **8% off** |

---

### Example C: Premium Project (₱800,000)

| Stage | Recipient | Reward | Amount |
|-------|-----------|--------|--------|
| Registration | Referred | Welcome Points | 500 pts |
| Registration | Referred | Welcome Coupon | 15% off |
| Payment | Referrer | Credits | **₱12,000** |
| Payment | Referrer | Points | 1,000 pts |
| Payment | Referred | Tiered Coupon | **5% off** |

---

### Example D: Enterprise Project (₱1,500,000)

| Stage | Recipient | Reward | Amount |
|-------|-----------|--------|--------|
| Registration | Referred | Welcome Points | 500 pts |
| Registration | Referred | Welcome Coupon | 15% off |
| Payment | Referrer | Credits | **₱15,000** |
| Payment | Referrer | Points | 1,000 pts |
| Payment | Referred | Tiered Coupon | **5% off** |

---

## Quick Reference: Reward Calculations

```
Tier 1 (₱100K-200K): Credits = Payment × 0.03
Tier 2 (₱200K-500K): Credits = Payment × 0.02
Tier 3 (₱500K-1M):   Credits = Payment × 0.015
Tier 4 (₱1M+):       Credits = Payment × 0.01
```

### Sample Calculations

| Payment | Tier | Rate | Credits |
|---------|------|------|---------|
| ₱100,000 | 1 | 3% | ₱3,000 |
| ₱175,000 | 1 | 3% | ₱5,250 |
| ₱250,000 | 2 | 2% | ₱5,000 |
| ₱400,000 | 2 | 2% | ₱8,000 |
| ₱600,000 | 3 | 1.5% | ₱9,000 |
| ₱900,000 | 3 | 1.5% | ₱13,500 |
| ₱1,200,000 | 4 | 1% | ₱12,000 |
| ₱3,000,000 | 4 | 1% | ₱30,000 |

---

## Coupon Details Reference

### Welcome Coupon (on Registration)
- Discount: 15%
- Validity: 30 days
- Min Purchase: ₱1,000
- Stackable: Yes (with loyalty tier and points)

### Tiered Coupon (on Payment Completion)
- Discount: 5-10% (based on tier)
- Validity: 90 days
- Min Purchase: ₱50,000
- Max Discount: ₱30,000
- Stackable: No (with points)

---

## Database Verification Queries

```sql
-- Check referral status
SELECT * FROM referrals WHERE referred_id = ?;

-- Check referral credits
SELECT referral_credits FROM users WHERE id = ?;

-- Check credit transactions
SELECT * FROM referral_credit_transactions WHERE user_id = ?;

-- Check coupons generated
SELECT * FROM coupons WHERE id IN (
    SELECT referred_coupon_id FROM referrals WHERE referred_id = ?
);
```

---

*Document last updated: Based on config/referral.php and app/Services/ReferralService.php*

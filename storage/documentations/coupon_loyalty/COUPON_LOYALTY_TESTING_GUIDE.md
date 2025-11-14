# Coupon & Loyalty System - Testing Guide

This guide provides step-by-step testing flows for the complete coupon and loyalty system. Follow these scenarios in order to test the full lifecycle.

---

## 🎯 CATEGORY 1: COUPON CREATION & MANAGEMENT (ADMIN)

### Test Flow 1.1: Create Public Coupon
**Goal**: Admin creates a public coupon available to all users

**Steps**:
1. **Login as Admin**
   - URL: `/login`
   - Use admin credentials

2. **Navigate to Coupon Management**
   - Click "Coupons" in admin sidebar
   - URL: `/admin/coupons`

3. **Click Create New Coupon**
   - Click "Create New Coupon" button
   - URL: `/admin/coupons/create`

4. **Fill Coupon Details**
   - **Code**: WELCOME20
   - **Name**: Welcome 20% Discount
   - **Description**: Get 20% off your first service
   - **Type**: Percentage
   - **Value**: 20
   - **Maximum Discount**: 5000 (optional cap)
   - **Visibility**: Public
   - **Usage Limit**: 100 (total uses)
   - **Per User Limit**: 1
   - **Valid From**: Today
   - **Valid Until**: 30 days from now
   - **Status**: Active

5. **Save Coupon**
   - Click "Create Coupon"
   - Verify success message

6. **Verify Coupon in List**
   - Return to `/admin/coupons`
   - Check WELCOME20 appears in list
   - Verify status shows "Active"
   - Check usage: 0 / 100

**Expected Results**:
- ✅ Coupon created successfully
- ✅ Shows in admin list
- ✅ Visible to all clients
- ✅ Usage tracking initialized

---

### Test Flow 1.2: Create Fixed Amount Coupon
**Goal**: Create coupon with fixed discount amount

**Steps**:
1. **Navigate to Create Coupon**
   - URL: `/admin/coupons/create`

2. **Fill Details**
   - **Code**: SAVE1000
   - **Name**: ₱1000 Off Service
   - **Type**: Fixed Amount
   - **Value**: 1000
   - **Visibility**: Public
   - **Usage Limit**: 50
   - **Valid Until**: 60 days from now

3. **Save and Verify**
   - Save coupon
   - Check displays ₱1,000 discount
   - Verify type shows "Fixed Amount"

**Expected Results**:
- ✅ Fixed amount coupon created
- ✅ Currency symbol displayed correctly
- ✅ Type clearly indicated

---

### Test Flow 1.3: Create User-Specific Coupon
**Goal**: Create coupon for a specific user

**Steps**:
1. **Create New Coupon**
   - Code: VIP50USER
   - Name: VIP 50% Discount
   - Type: Percentage
   - Value: 50
   - **Visibility**: User-specific
   - **Select User**: Choose specific client from dropdown
   - Valid Until: 90 days

2. **Save and Verify**
   - Save coupon
   - Check "Assigned To" column shows user name
   - Verify visibility: "User-specific"

3. **Test Visibility**
   - Login as the assigned user
   - Check coupon appears in their coupons page
   - Login as different user
   - Verify coupon does NOT appear

**Expected Results**:
- ✅ Coupon only visible to assigned user
- ✅ Other users cannot see it
- ✅ Assignment tracked correctly

---

### Test Flow 1.4: Bulk Generate Coupons
**Goal**: Generate multiple unique coupon codes at once

**Steps**:
1. **Navigate to Coupons Page**
   - Click "Bulk Generate" button (if available)
   - Or use artisan command

2. **Generate Coupons**
   ```bash
   php artisan coupon:generate --prefix=PROMO --count=10 --type=percentage --value=15 --days=30
   ```

3. **Verify Generation**
   - Check 10 coupons created
   - Codes: PROMO-XXXXX format
   - All have 15% discount
   - All expire in 30 days

**Expected Results**:
- ✅ Multiple coupons generated
- ✅ Unique codes assigned
- ✅ Same settings applied to all

---

### Test Flow 1.5: Edit Existing Coupon
**Goal**: Modify coupon settings

**Steps**:
1. **Select Coupon to Edit**
   - From `/admin/coupons`
   - Click "Edit" on WELCOME20

2. **Update Details**
   - Change usage limit: 100 → 200
   - Update expiry: Extend by 15 days
   - Add description text

3. **Save Changes**
   - Click "Update Coupon"
   - Verify success message

4. **Confirm Updates**
   - Check list shows new usage limit
   - Verify new expiry date
   - Existing usages remain unchanged

**Expected Results**:
- ✅ Changes saved successfully
- ✅ Historical data preserved
- ✅ New limits apply to future uses

---

### Test Flow 1.6: Deactivate and Reactivate Coupon
**Goal**: Toggle coupon active status

**Steps**:
1. **Deactivate Coupon**
   - From `/admin/coupons`
   - Click "Toggle Status" on SAVE1000
   - Confirm deactivation

2. **Verify Deactivation**
   - Status badge changes to "Inactive"
   - Try to use as client (should fail)
   - Check error: "Coupon is not active"

3. **Reactivate Coupon**
   - Click "Toggle Status" again
   - Status changes to "Active"
   - Try to use as client (should work)

**Expected Results**:
- ✅ Deactivation prevents usage
- ✅ Clear error messages
- ✅ Reactivation restores functionality

---

### Test Flow 1.7: View Coupon Usage History
**Goal**: Track who used which coupons

**Steps**:
1. **Select Coupon**
   - Click on WELCOME20 code
   - Or click "View Usage" button
   - URL: `/admin/coupons/{id}/usage`

2. **Review Usage Table**
   - See all redemptions
   - Check columns:
     - User name/email
     - Service request ID
     - Discount amount applied
     - Date used
     - Payment reference

3. **Filter Usage**
   - Filter by date range
   - Filter by user
   - Sort by amount

4. **Export Usage Data**
   - Click "Export" button
   - Download CSV file
   - Verify data completeness

**Expected Results**:
- ✅ Complete usage history
- ✅ Accurate discount amounts
- ✅ Filters work correctly
- ✅ Export includes all data

---

## 🎯 CATEGORY 2: COUPON BUNDLING WITH APPROVAL (ADMIN)

### Test Flow 2.1: Approve Request with Existing Coupon
**Goal**: Admin assigns existing coupon during request approval

**Steps**:
1. **Navigate to Pending Requests**
   - Go to: `/admin/requests`
   - Filter: Status = "Pending"

2. **Select Request to Approve**
   - Click on pending request
   - Review request details
   - Check service type and estimated cost

3. **Click Approve Button**
   - Click "Approve Request"
   - Approval modal/form opens

4. **Attach Coupon**
   - Check "Attach Coupon" checkbox
   - **Select Existing Coupon**:
     - Choose from dropdown: WELCOME20
   - Preview discount calculation
   - Original: ₱10,000
   - With 20% off: ₱8,000
   - Savings: ₱2,000

5. **Confirm Approval**
   - Add approval notes (optional)
   - Click "Approve & Send"

6. **Verify Email Sent**
   - Check email queue
   - Verify "Coupon Assigned" email sent to client
   - Email should include:
     - Coupon code: WELCOME20
     - Discount: 20%
     - Expiry date
     - How to use instructions

7. **Check Request Status**
   - Request status: "Approved"
   - Coupon attached: WELCOME20
   - Client can proceed to payment

**Expected Results**:
- ✅ Request approved successfully
- ✅ Coupon assigned to request
- ✅ Email notification sent
- ✅ Discount ready to apply at payment

---

### Test Flow 2.2: Approve Request with New Coupon
**Goal**: Admin creates and assigns new coupon during approval

**Steps**:
1. **Select Pending Request**
   - Open pending request

2. **Click Approve**
   - Approval form opens

3. **Create New Coupon**
   - Check "Attach Coupon"
   - Select "Create New Coupon"
   - Inline coupon form appears

4. **Fill New Coupon Details**
   - **Code**: SPECIAL50 (auto-generated or manual)
   - **Type**: Percentage
   - **Value**: 50
   - **Visibility**: Request-specific (auto-set)
   - **Valid Until**: 30 days
   - **Usage Limit**: 1 (one-time use)

5. **Approve with New Coupon**
   - Click "Approve & Create Coupon"
   - System creates coupon and approves request

6. **Verify Creation**
   - Coupon created in database
   - Assigned to this specific request
   - Email sent to client with details

7. **Check Coupon List**
   - Go to `/admin/coupons`
   - Find SPECIAL50
   - Verify visibility: "Request-specific"
   - Linked request: Request #12345

**Expected Results**:
- ✅ New coupon created on-the-fly
- ✅ Automatically linked to request
- ✅ Client notified immediately
- ✅ One-time use enforced

---

### Test Flow 2.3: Approve Without Coupon
**Goal**: Approve request without attaching any coupon

**Steps**:
1. **Select Request**
   - Open pending request

2. **Approve Normally**
   - Click "Approve"
   - Leave "Attach Coupon" unchecked
   - Click "Approve"

3. **Verify No Coupon**
   - Request approved
   - No coupon attached
   - Standard approval email sent (no coupon details)
   - Client pays full price

**Expected Results**:
- ✅ Approval works without coupon
- ✅ No discount applied
- ✅ Normal email notification

---

## 🎯 CATEGORY 3: CLIENT COUPON BROWSING & USAGE

### Test Flow 3.1: Browse Available Coupons
**Goal**: Client views available coupons

**Steps**:
1. **Login as Client**
   - URL: `/login`
   - Use client credentials

2. **Navigate to Coupons Page**
   - Click "Coupons" in client menu
   - URL: `/client/coupons`

3. **View Coupon Gallery**
   - See public coupons (WELCOME20, SAVE1000)
   - See user-specific coupons (if any)
   - Check coupon cards display:
     - Coupon code (styled)
     - Discount value
     - Expiry date
     - Terms & conditions
     - Copy button

4. **Filter Coupons**
   - Filter by "Active only"
   - Filter by expiry date
   - Sort by discount amount

5. **Copy Coupon Code**
   - Click "Copy Code" button
   - Verify copied to clipboard
   - Toast notification: "Copied!"

**Expected Results**:
- ✅ All applicable coupons shown
- ✅ Clear expiry information
- ✅ Easy code copying
- ✅ No request-specific coupons shown (unless assigned)

---

### Test Flow 3.2: View Coupon Details
**Goal**: See full coupon information

**Steps**:
1. **On Coupons Page**
   - Click on WELCOME20 card

2. **View Details Modal/Page**
   - Full description
   - Terms and conditions
   - Usage limits
   - Validity period
   - How to use instructions
   - Example calculation

3. **Close and Return**
   - Close modal
   - Return to coupon list

**Expected Results**:
- ✅ Complete information displayed
- ✅ Clear usage instructions
- ✅ Visual appeal

---

### Test Flow 3.3: Automatic Coupon Application at Payment
**Goal**: Coupon auto-applies when client pays for approved request

**Steps**:
1. **Scenario Setup**
   - Admin approved request with WELCOME20 coupon
   - Client received email notification

2. **Navigate to Service Requests**
   - URL: `/client/requests`
   - Find approved request

3. **Click Pay Now**
   - Request shows: "Approved - Ready for Payment"
   - Coupon badge displayed: 20% off
   - Click "Pay Now" button

4. **Payment Page**
   - URL: `/client/requests/{id}/payment`
   - **Verify Auto-Applied Coupon**:
     - Coupon section shows: WELCOME20
     - Original amount: ₱10,000
     - Discount (20%): -₱2,000
     - **Total to Pay**: ₱8,000
   - Coupon cannot be removed (auto-applied)
   - See savings highlight

5. **Proceed to Payment Gateway**
   - Click "Proceed to Payment"
   - Maya gateway opens
   - Amount charged: ₱8,000 (discounted)

6. **Complete Payment**
   - Complete Maya payment
   - Return to success page

7. **Verify Coupon Usage**
   - Coupon marked as used
   - Usage count increments
   - Cannot use same coupon again (if per-user limit = 1)

**Expected Results**:
- ✅ Coupon auto-applies correctly
- ✅ Discount calculated accurately
- ✅ Payment amount matches discounted total
- ✅ Usage tracked properly

---

### Test Flow 3.4: Manual Coupon Application (if feature exists)
**Goal**: Client manually enters coupon code at payment

**Steps**:
1. **Navigate to Payment Page**
   - Request has no auto-applied coupon
   - See "Have a coupon?" section

2. **Enter Coupon Code**
   - Type: SAVE1000
   - Click "Apply"

3. **Validate Coupon**
   - AJAX validation occurs
   - Success: Discount applied
   - Original: ₱10,000
   - Discount: -₱1,000
   - New total: ₱9,000

4. **Try Invalid Coupon**
   - Remove current coupon
   - Try: EXPIRED123
   - Error: "Coupon has expired"
   - Try: USED456
   - Error: "Coupon usage limit reached"

5. **Proceed with Valid Coupon**
   - Apply valid coupon
   - Complete payment

**Expected Results**:
- ✅ Valid coupons apply successfully
- ✅ Invalid coupons show clear errors
- ✅ Real-time validation works
- ✅ Discount reflects immediately

---

## 🎯 CATEGORY 4: LOYALTY SYSTEM SETUP & TIER MANAGEMENT

### Test Flow 4.1: Initial Loyalty Profile Creation
**Goal**: New client automatically gets loyalty profile

**Steps**:
1. **New User Registration**
   - Register new client account
   - Complete registration

2. **Check Loyalty Profile Auto-Created**
   ```bash
   php artisan tinker
   $user = User::where('email', 'newclient@test.com')->first();
   $user->loyaltyPoints; // Should exist
   ```

3. **Verify Default Values**
   - Total points: 0
   - Available points: 0
   - Lifetime earned: 0
   - Current tier: Bronze
   - Tier discount: 0%

**Expected Results**:
- ✅ Loyalty profile created automatically
- ✅ Starts at Bronze tier
- ✅ Zero balance initialized

---

### Test Flow 4.2: View Loyalty Dashboard (Client)
**Goal**: Client views their loyalty information

**Steps**:
1. **Login as Client**
   - Use existing client account

2. **Navigate to Loyalty Dashboard**
   - Click "Loyalty" in client menu
   - URL: `/client/loyalty`

3. **View Dashboard Components**
   - **Points Balance Card**:
     - Available points: X,XXX
     - Points value: ₱X,XXX
     - Expiring soon warning (if any)
   
   - **Current Tier Badge**:
     - Bronze/Silver/Gold/Platinum
     - Tier color and icon
     - Current benefits listed
   
   - **Progress to Next Tier**:
     - Progress bar showing advancement
     - "X more points to Silver"
     - Percentage complete
   
   - **Recent Transactions Table**:
     - Date
     - Type (Earned/Redeemed/Expired)
     - Amount
     - Balance after
     - Source/Reason
   
   - **Tier Benefits Table**:
     - All 4 tiers listed
     - Points required for each
     - Discount percentage
     - Additional benefits
     - Current tier highlighted

4. **Filter Transactions**
   - Filter by type: Earned only
   - Filter by date range
   - View all transactions

**Expected Results**:
- ✅ Clear points balance displayed
- ✅ Tier status visible
- ✅ Progress bar accurate
- ✅ Complete transaction history
- ✅ Benefits clearly explained

---

### Test Flow 4.3: Earn Points from Payment
**Goal**: Client earns loyalty points after paying for service

**Steps**:
1. **Scenario Setup**
   - Client has Bronze tier (1% earning rate)
   - Approved request amount: ₱10,000
   - No coupons or tier discounts

2. **Complete Payment**
   - Go to payment page
   - Original amount: ₱10,000
   - Proceed to Maya gateway
   - Complete payment successfully

3. **Check Points Earned**
   - Payment confirmation shows:
     - "You earned 100 points!"
     - Calculation: ₱10,000 × 1% = 100 points

4. **Verify Email Notification**
   - Check email: "Loyalty Points Earned"
   - Shows: +100 points
   - New balance displayed
   - Tier progress updated

5. **Check Loyalty Dashboard**
   - Navigate to `/client/loyalty`
   - Available points: +100
   - Lifetime earned: +100
   - Transaction logged:
     - Type: Earned
     - Amount: +100
     - Source: Payment #12345
     - Expires: 12 months from now

**Expected Results**:
- ✅ Points calculated correctly (1% of payment)
- ✅ Added to available balance
- ✅ Email notification sent
- ✅ Transaction recorded
- ✅ Expiry date set (12 months)

---

### Test Flow 4.4: Automatic Tier Upgrade
**Goal**: Client tier upgrades when reaching threshold

**Steps**:
1. **Scenario Setup**
   - Client currently at Bronze
   - Lifetime earned: 4,500 points
   - Silver threshold: 5,000 points
   - Need 500 more points

2. **Make Payment that Crosses Threshold**
   - Service cost: ₱50,000
   - Points earned: ₱50,000 × 1% = 500 points
   - Complete payment

3. **Check Tier Upgrade Trigger**
   - After payment processing
   - New lifetime earned: 5,000 points
   - System detects: ≥ Silver threshold
   - Tier upgraded: Bronze → Silver

4. **Verify Email Notification**
   - "Congratulations! Tier Upgraded"
   - Shows: Silver tier badge
   - New benefits listed:
     - 2% points earning
     - 5% tier discount
     - Priority support
   - Celebration graphics

5. **Check Loyalty Dashboard**
   - Tier badge changed to Silver
   - Tier discount: 5%
   - Earning rate: 2%
   - Tier achieved date recorded
   - Progress bar resets for Gold

6. **Verify Next Purchase Benefits**
   - Make another payment
   - Tier discount (5%) auto-applies
   - Points earned at 2% rate (doubled)

**Expected Results**:
- ✅ Tier upgrade automatic when threshold reached
- ✅ Email notification sent
- ✅ Dashboard updated immediately
- ✅ New benefits apply to future transactions
- ✅ Earning rate increased

---

### Test Flow 4.5: Test All Tier Levels
**Goal**: Verify each tier threshold and benefits

**Test Cases**:

**Bronze (0 - 4,999 points)**
- Earning rate: 1%
- Tier discount: 0%
- Default tier

**Silver (5,000 - 14,999 points)**
- Earning rate: 2%
- Tier discount: 5%
- Priority email support

**Gold (15,000 - 49,999 points)**
- Earning rate: 3%
- Tier discount: 10%
- Dedicated account manager

**Platinum (50,000+ points)**
- Earning rate: 5%
- Tier discount: 15%
- VIP support hotline

**Verification Method**:
```bash
php artisan tinker

$user = User::find(1);

# Simulate tier upgrades
$user->loyaltyPoints->update(['lifetime_earned' => 5000]);
php artisan loyalty:update-tiers

$user->fresh()->loyaltyPoints->current_tier; // Should be 'silver'
```

**Expected Results**:
- ✅ Each tier activates at correct threshold
- ✅ Benefits apply correctly
- ✅ Earning rates update
- ✅ Discounts apply to payments

---

## 🎯 CATEGORY 5: POINTS REDEMPTION

### Test Flow 5.1: Redeem Points for Discount
**Goal**: Client uses loyalty points to reduce payment amount

**Steps**:
1. **Navigate to Payment Page**
   - Approved request amount: ₱5,000
   - Client has 1,000 available points

2. **View Points Redemption Section**
   - Shows: "Use Loyalty Points"
   - Available: 1,000 points (₱1,000 value)
   - Input field for points to redeem
   - Max allowed: 50% of order (₱2,500)

3. **Enter Points to Redeem**
   - Enter: 1000 points
   - See preview:
     - Original: ₱5,000
     - Points discount: -₱1,000
     - **New total**: ₱4,000

4. **Apply Points**
   - Click "Apply Points"
   - Discount applied
   - Total updates immediately
   - Points reserved (not yet deducted)

5. **Complete Payment**
   - Proceed to Maya gateway
   - Pay: ₱4,000
   - Confirm payment

6. **Verify Points Deduction**
   - After payment confirmation
   - Available points: 0 (1,000 redeemed)
   - Transaction logged:
     - Type: Redeemed
     - Amount: -1,000
     - Reference: Payment #12345

7. **Check Email Notification**
   - Payment confirmation email
   - Shows points redeemed
   - New balance displayed

**Expected Results**:
- ✅ Points redemption works correctly
- ✅ Maximum 50% rule enforced
- ✅ Points deducted after payment
- ✅ Transaction recorded
- ✅ Cannot use points not available

---

### Test Flow 5.2: Test Points Redemption Limits
**Goal**: Verify min/max redemption rules

**Test Cases**:

**Case A: Below Minimum**
- Available: 500 points
- Try to redeem: 50 points
- Minimum: 100 points
- **Expected**: Error - "Minimum 100 points required"

**Case B: Above Maximum (50% rule)**
- Order: ₱10,000
- Available: 8,000 points
- Max allowed: 5,000 points (50%)
- Try: 6,000 points
- **Expected**: Error - "Maximum ₱5,000 (50%) allowed"

**Case C: Insufficient Balance**
- Available: 200 points
- Try: 500 points
- **Expected**: Error - "Insufficient points balance"

**Case D: Valid Redemption**
- Available: 2,000 points
- Order: ₱10,000
- Redeem: 1,500 points
- **Expected**: Success - Applied ₱1,500 discount

**Expected Results**:
- ✅ Minimum 100 points enforced
- ✅ Maximum 50% enforced
- ✅ Balance checked
- ✅ Clear error messages

---

### Test Flow 5.3: Combine Coupon + Tier Discount + Points
**Goal**: Test discount stacking with all three sources

**Steps**:
1. **Scenario Setup**
   - Client tier: Silver (5% tier discount)
   - Coupon: SAVE1000 (₱1,000 fixed)
   - Available points: 2,000
   - Service cost: ₱20,000

2. **Payment Page Calculation**
   - **Original Amount**: ₱20,000
   
   - **Step 1 - Coupon Applied**:
     - Coupon: -₱1,000
     - Subtotal: ₱19,000
   
   - **Step 2 - Tier Discount**:
     - Silver 5%: -₱950 (5% of ₱19,000)
     - Subtotal: ₱18,050
   
   - **Step 3 - Points Redemption**:
     - Redeem: 2,000 points
     - Max allowed: ₱9,025 (50% of ₱18,050)
     - Apply: -₱2,000
     - **Final Total**: ₱16,050

3. **Verify Discount Breakdown**
   - Payment page shows clear breakdown
   - Original: ₱20,000
   - Coupon: -₱1,000
   - Tier (5%): -₱950
   - Points: -₱2,000
   - **Total Savings**: ₱3,950
   - **Pay**: ₱16,050

4. **Complete Payment**
   - Proceed to gateway
   - Charged: ₱16,050
   - Confirm payment

5. **Verify All Deductions**
   - Coupon marked as used
   - Points deducted: -2,000
   - Tier discount logged
   - All tracked separately

**Expected Results**:
- ✅ Correct stacking order (coupon → tier → points)
- ✅ Each discount calculated correctly
- ✅ Maximum 70% total discount not exceeded
- ✅ Clear breakdown shown
- ✅ All usages tracked

---

## 🎯 CATEGORY 6: POINTS EXPIRY & WARNINGS

### Test Flow 6.1: Points Expiry (Automated)
**Goal**: Points automatically expire after 12 months

**Steps**:
1. **Create Old Transaction**
   ```bash
   php artisan tinker
   
   $user = User::find(1);
   $user->loyaltyPoints->transactions()->create([
       'type' => 'earned',
       'amount' => 500,
       'balance_after' => 500,
       'source_type' => 'payment',
       'expires_at' => now()->subDays(1), // Already expired
       'created_at' => now()->subMonths(13),
   ]);
   ```

2. **Run Scheduled Task**
   ```bash
   php artisan schedule:run
   # Or specifically:
   php artisan loyalty:expire-points
   ```

3. **Verify Expiry**
   - Check loyalty_transactions table
   - New entry created:
     - Type: Expired
     - Amount: -500
     - Reference to original transaction
   - Available points reduced by 500
   - Total points unchanged (lifetime counter)

4. **Check Dashboard**
   - Login as affected client
   - Available points reduced
   - Transaction shows: "Expired - 500 points"
   - Date of expiry noted

**Expected Results**:
- ✅ Points expire after 12 months
- ✅ Automated daily check works
- ✅ Expiry transaction logged
- ✅ Balance updated correctly

---

### Test Flow 6.2: Points Expiry Warning Email
**Goal**: Client receives warning 30 days before points expire

**Steps**:
1. **Create Transaction Expiring Soon**
   ```bash
   $user->loyaltyPoints->transactions()->create([
       'type' => 'earned',
       'amount' => 1000,
       'balance_after' => 1000,
       'source_type' => 'payment',
       'expires_at' => now()->addDays(29), // 29 days from now
       'expiry_warning_sent' => false,
   ]);
   ```

2. **Run Scheduled Task**
   ```bash
   php artisan schedule:run
   # Or specifically:
   php artisan loyalty:send-expiry-warnings
   ```

3. **Verify Email Sent**
   - Check queue/email logs
   - Email: "Points Expiring Soon"
   - Shows:
     - 1,000 points expiring
     - Expiry date: [29 days from now]
     - "Use them before they expire!"
     - CTA: "Redeem Now"
   - Orange/yellow warning theme

4. **Check Warning Flag**
   - Transaction updated
   - `expiry_warning_sent`: true
   - Won't send duplicate warning

5. **Client Views Dashboard**
   - Banner: "1,000 points expiring soon"
   - Highlighted in transactions
   - Urgency indicator

**Expected Results**:
- ✅ Warning sent 30 days before expiry
- ✅ Email delivered successfully
- ✅ No duplicate warnings
- ✅ Clear urgency messaging

---

## 🎯 CATEGORY 7: COUPON EXPIRY & WARNINGS

### Test Flow 7.1: Coupon Expiry Warning Email
**Goal**: Client receives warning 7 days before user-specific coupon expires

**Steps**:
1. **Create User-Specific Coupon Expiring Soon**
   - Admin creates coupon
   - Code: EXPIRINGSOON
   - Assigned to specific user
   - Valid until: 6 days from now

2. **Run Scheduled Task**
   ```bash
   php artisan schedule:run
   # Or:
   php artisan coupon:send-expiry-warnings
   ```

3. **Verify Email Sent**
   - Email: "Coupon Expiring Soon"
   - Shows:
     - Coupon code: EXPIRINGSOON
     - Discount: 20%
     - Expires: [6 days from now]
     - "Don't miss out!"
     - CTA: "Use Now"
   - Red warning theme

4. **Check Email Schedule**
   - Sent daily at 9:00 AM
   - Only sent once per coupon
   - Flag set to prevent duplicates

5. **Client Action**
   - Client receives email
   - Clicks "Use Now"
   - Goes to coupons page
   - Sees coupon highlighted with expiry warning

**Expected Results**:
- ✅ Warning sent 7 days before expiry
- ✅ User-specific coupons tracked
- ✅ No duplicate warnings
- ✅ Clear urgency messaging

---

### Test Flow 7.2: Automatic Coupon Deactivation
**Goal**: Expired coupons automatically deactivate

**Steps**:
1. **Create Expiring Coupon**
   - Valid until: Yesterday

2. **Run Scheduled Task**
   ```bash
   php artisan coupon:expire-old
   ```

3. **Verify Deactivation**
   - Coupon status: Inactive
   - Cannot be used
   - Shows "Expired" badge in admin panel

4. **Try to Use Expired Coupon**
   - Client attempts to apply
   - Error: "This coupon has expired"
   - Cannot proceed with discount

**Expected Results**:
- ✅ Expired coupons auto-deactivate
- ✅ Cannot be used after expiry
- ✅ Clear error messages

---

## 🎯 CATEGORY 8: ADMIN LOYALTY MANAGEMENT

### Test Flow 8.1: View All User Loyalty Stats
**Goal**: Admin reviews loyalty program overview

**Steps**:
1. **Login as Admin**

2. **Navigate to Loyalty Management**
   - Click "Loyalty" in admin sidebar
   - URL: `/admin/loyalty`

3. **View Summary Cards**
   - Total active users in program
   - Total points distributed
   - Total points redeemed
   - Current month activity

4. **View User List**
   - Table with all users:
     - Name
     - Email
     - Current tier
     - Available points
     - Lifetime earned
     - Last transaction date
   - Sort by tier, points, activity
   - Search by name/email

5. **Export Report**
   - Click "Export"
   - Download CSV with all loyalty data

**Expected Results**:
- ✅ Complete loyalty overview
- ✅ User-level statistics
- ✅ Sortable and searchable
- ✅ Export functionality

---

### Test Flow 8.2: View Individual User Loyalty Details
**Goal**: Admin reviews specific user's loyalty account

**Steps**:
1. **From Loyalty List**
   - Click on user: John Doe
   - URL: `/admin/loyalty/user/{id}`

2. **View User Loyalty Profile**
   - **Summary Section**:
     - Current tier with badge
     - Available points
     - Lifetime earned/redeemed
     - Points expiring soon
   
   - **Complete Transaction History**:
     - All earned/redeemed/expired transactions
     - Dates and amounts
     - Sources (payments, bonuses, etc.)
     - Filter by type and date
   
   - **Tier Progress**:
     - Current tier
     - Next tier threshold
     - Progress bar
     - History of tier changes

3. **Quick Actions**
   - Manually adjust points
   - View user's payments
   - View user's coupons

**Expected Results**:
- ✅ Complete user loyalty view
- ✅ Full transaction history
- ✅ Admin actions available

---

### Test Flow 8.3: Manually Adjust User Points
**Goal**: Admin adds or deducts points for special reasons

**Steps**:
1. **On User Loyalty Page**
   - Click "Adjust Points" button

2. **Fill Adjustment Form**
   - **Type**: Add or Deduct
   - **Amount**: 500 points
   - **Reason**: "Compensation for service delay"
   - **Send Notification**: Yes

3. **Submit Adjustment**
   - Click "Adjust Points"
   - Confirm action

4. **Verify Adjustment**
   - Transaction created:
     - Type: Adjusted
     - Amount: +500
     - Admin user noted
     - Reason recorded
   - User balance updated
   - Email sent to user

5. **Check User View**
   - Login as that user
   - See transaction: "Adjusted by Admin"
   - Reason visible
   - Points available to use

**Expected Results**:
- ✅ Manual adjustments work
- ✅ Reason tracked
- ✅ User notified
- ✅ Audit trail maintained

---

### Test Flow 8.4: Update Tier Configuration
**Goal**: Admin modifies tier thresholds and benefits

**Steps**:
1. **Navigate to Tier Settings**
   - URL: `/admin/loyalty/settings/tiers`

2. **View Current Tier Configuration**
   - All 4 tiers listed
   - Thresholds and benefits shown

3. **Edit Silver Tier**
   - Change threshold: 5,000 → 4,000 points
   - Change discount: 5% → 7%
   - Update earning rate: 2% → 2.5%

4. **Save Changes**
   - Click "Update Tiers"
   - Confirm action

5. **Verify Updates**
   - Configuration saved
   - **Run update command**:
     ```bash
     php artisan loyalty:update-tiers
     ```
   - Users re-evaluated based on new thresholds
   - Some users may auto-upgrade

6. **Check Affected Users**
   - User with 4,500 points now qualifies for Silver
   - Tier upgraded automatically
   - Email notification sent

**Expected Results**:
- ✅ Tier settings updatable
- ✅ Changes apply retroactively
- ✅ Users auto-upgraded if applicable
- ✅ Notifications sent

---

## 🎯 CATEGORY 9: BONUS POINTS SCENARIOS

### Test Flow 9.1: First Project Bonus
**Goal**: Client earns bonus points for first completed project

**Steps**:
1. **Scenario Setup**
   - New client with no completed projects
   - First project bonus: 500 points

2. **Complete First Project**
   - Project marked as completed
   - System detects: First project

3. **Award Bonus**
   - Bonus transaction created:
     - Type: Earned
     - Amount: 500
     - Source: First Project Bonus
   - Email: "Bonus Points Earned!"
   - Celebration message

4. **Verify One-Time Award**
   - Complete second project
   - No bonus awarded (only first time)

**Expected Results**:
- ✅ Bonus awarded for first project
- ✅ One-time only
- ✅ Email notification sent

---

### Test Flow 9.2: Referral Bonus Integration
**Goal**: Client earns points from successful referral

**Steps**:
1. **Scenario Setup**
   - Client refers friend
   - Friend completes first payment
   - Referral system triggers reward

2. **Check Loyalty Integration**
   - Referral completion detected
   - Loyalty points awarded: 1,000 points
   - Transaction created:
     - Type: Earned
     - Amount: 1,000
     - Source: Referral Bonus

3. **Verify Both Systems**
   - Referral system: Marks referral as rewarded
   - Loyalty system: Points added
   - Both tracked independently

**Expected Results**:
- ✅ Referral triggers loyalty points
- ✅ Integration works smoothly
- ✅ Both systems track properly

---

### Test Flow 9.3: Milestone Completion Bonus
**Goal**: Client earns points for project milestones

**Steps**:
1. **Complete Milestone**
   - Project with milestones
   - Mark milestone as completed

2. **Award Bonus**
   - Milestone bonus: 200 points
   - Transaction created
   - Email notification

3. **Multiple Milestones**
   - Complete 3 milestones
   - Earn 200 × 3 = 600 points total

**Expected Results**:
- ✅ Milestone bonuses awarded
- ✅ Multiple milestones tracked
- ✅ Points accumulate correctly

---

## 🎯 CATEGORY 10: EDGE CASES & VALIDATION

### Test Flow 10.1: Prevent Negative Points Balance
**Goal**: System prevents balance from going negative

**Steps**:
1. **Scenario Setup**
   - User has 100 points available
   - Try to redeem 200 points

2. **Attempt Redemption**
   - Enter 200 points at payment
   - Click "Apply"

3. **Verify Validation**
   - Error: "Insufficient points balance"
   - Cannot proceed
   - Balance remains 100

**Expected Results**:
- ✅ Validation prevents negative balance
- ✅ Clear error message
- ✅ No partial deduction

---

### Test Flow 10.2: Coupon Usage Limit Enforcement
**Goal**: Coupon stops working after limit reached

**Steps**:
1. **Create Limited Coupon**
   - Code: LIMITED10
   - Total uses: 10
   - Per user: 1

2. **Use Coupon 10 Times**
   - 10 different users apply coupon
   - All successful

3. **11th Attempt**
   - New user tries to use LIMITED10
   - Error: "Coupon usage limit reached"
   - Cannot apply

4. **Per User Limit**
   - Previous user tries again
   - Error: "You have already used this coupon"

**Expected Results**:
- ✅ Total usage limit enforced
- ✅ Per-user limit enforced
- ✅ Clear error messages

---

### Test Flow 10.3: Maximum Discount Cap
**Goal**: Verify maximum 70% total discount rule

**Steps**:
1. **Scenario Setup**
   - Order: ₱10,000
   - Coupon: 50% (₱5,000)
   - Tier: 15% Platinum
   - Points: 5,000 available

2. **Apply All Discounts**
   - Coupon: -₱5,000 (50%)
   - Tier 15%: -₱750 (on ₱5,000)
   - Points: Try to use 5,000

3. **Check Maximum Enforcement**
   - System calculates:
     - After coupon + tier: ₱4,250
     - 70% max of original: ₱7,000
     - Already discounted: ₱5,750 (57.5%)
     - Remaining allowable: ₱1,250
   - Can only redeem: 1,250 points (not 5,000)

4. **Verify Cap**
   - Final total: ₱3,000 (70% discount applied)
   - Cannot go lower

**Expected Results**:
- ✅ Maximum 70% discount enforced
- ✅ Clear explanation shown
- ✅ Points redemption capped accordingly

---

### Test Flow 10.4: Expired Points Cannot Be Used
**Goal**: Verify expired points excluded from available balance

**Steps**:
1. **Scenario Setup**
   - User had 1,000 points
   - 500 points expired yesterday
   - Available should be: 500

2. **Check Dashboard**
   - Shows 500 available
   - Expired 500 not counted

3. **Try to Redeem More**
   - Try to redeem 600 points
   - Error: "Insufficient points"
   - Can only use 500

**Expected Results**:
- ✅ Expired points excluded from balance
- ✅ Cannot use expired points
- ✅ Clear separation in transactions

---

## 🎯 CATEGORY 11: COMPLETE END-TO-END TEST

### Test Flow 11.1: Full Lifecycle - Coupon & Loyalty Combined
**Goal**: Complete workflow from registration to payout with all features

**Day 1 - Registration & Setup**
1. New client registers
2. Loyalty profile created (Bronze tier)
3. Admin creates public coupon: WELCOME25 (25% off)
4. Client views available coupons

**Day 2 - First Request**
1. Client submits service request (₱20,000)
2. Admin approves with WELCOME25 coupon
3. Client receives email with coupon

**Day 3 - First Payment**
1. Client goes to payment
2. WELCOME25 auto-applied: -₱5,000 (25%)
3. Total: ₱15,000
4. Complete Maya payment
5. Points earned: ₱15,000 × 1% = 150 points
6. Email: "Payment successful + 150 points earned"

**Day 10 - Second Request**
1. Client submits another request (₱30,000)
2. Admin approves (no coupon this time)
3. Client has 150 points available

**Day 11 - Second Payment with Points**
1. Payment page shows ₱30,000
2. Client redeems 150 points: -₱150
3. Total: ₱29,850
4. Complete payment
5. Points earned: ₱29,850 × 1% = 298 points
6. Total lifetime: 448 points

**Day 30 - Multiple Purchases**
1. Multiple transactions over time
2. Lifetime earned reaches 5,000 points
3. **Tier upgraded to Silver!**
4. Email: "Congratulations - Silver Tier"
5. New earning rate: 2%
6. Tier discount: 5%

**Day 31 - Silver Benefits**
1. New request: ₱10,000
2. Admin approves (no coupon)
3. Payment page:
   - Original: ₱10,000
   - Tier discount (5%): -₱500
   - Total: ₱9,500
4. Also redeem 500 points: -₱500
5. Final: ₱9,000
6. Points earned: ₱9,500 × 2% = 190 points

**Ongoing - Maintenance**
1. Points expiry warnings sent monthly
2. Admin monitors usage via dashboard
3. Special coupons for VIP clients
4. Bonus points for milestones

**Expected Results**:
- ✅ Complete integration works smoothly
- ✅ All features interact correctly
- ✅ Calculations accurate throughout
- ✅ Notifications timely
- ✅ User experience seamless

---

## 📋 TESTING CHECKLIST

### Coupon System
- [ ] Create public coupon (percentage)
- [ ] Create public coupon (fixed amount)
- [ ] Create user-specific coupon
- [ ] Create request-specific coupon
- [ ] Bulk generate coupons
- [ ] Edit existing coupon
- [ ] Deactivate/reactivate coupon
- [ ] View coupon usage history
- [ ] Export coupon data
- [ ] Approve request with existing coupon
- [ ] Approve request with new coupon
- [ ] Client browse coupons
- [ ] Client copy coupon code
- [ ] Auto-apply coupon at payment
- [ ] Manual coupon entry (if available)
- [ ] Coupon validation (expired, limit)
- [ ] Coupon expiry warning email
- [ ] Automatic coupon deactivation

### Loyalty System
- [ ] Auto-create loyalty profile on registration
- [ ] View loyalty dashboard
- [ ] Earn points from payment (all tiers)
- [ ] Points calculation correct for each tier
- [ ] Automatic tier upgrade
- [ ] Tier upgrade email notification
- [ ] View transaction history
- [ ] Redeem points for discount
- [ ] Minimum redemption (100 points)
- [ ] Maximum redemption (50% of order)
- [ ] Insufficient points validation
- [ ] Combine coupon + tier + points
- [ ] Maximum 70% discount rule
- [ ] Points expiry (12 months)
- [ ] Points expiry warning email
- [ ] Expired points excluded from balance
- [ ] Bonus points scenarios (first project, referral, milestone)
- [ ] Admin view all user stats
- [ ] Admin view individual user
- [ ] Admin manually adjust points
- [ ] Admin update tier configuration
- [ ] Export loyalty reports

### Integration
- [ ] Payment completion triggers points
- [ ] Coupon assignment during approval
- [ ] Email notifications queued
- [ ] Scheduled tasks run correctly
- [ ] Referral system integration
- [ ] Project milestone bonuses
- [ ] Maya payment gateway integration

### Edge Cases
- [ ] Prevent negative points balance
- [ ] Coupon usage limits enforced
- [ ] Per-user coupon limits
- [ ] Maximum discount cap
- [ ] Expired coupons cannot be used
- [ ] Expired points cannot be used
- [ ] Validation error messages clear
- [ ] No duplicate email notifications

---

## 🐛 COMMON ISSUES & TROUBLESHOOTING

### Issue 1: Points Not Credited After Payment
**Check**:
- Payment status confirmed
- Queue worker running
- Event listener registered
- Check `loyalty_transactions` table
- Review logs: `storage/logs/laravel.log`

### Issue 2: Email Notifications Not Sent
**Check**:
- Mail configuration in `.env`
- Queue worker running
- Failed jobs table: `php artisan queue:failed`
- Test email manually: `php artisan tinker`

### Issue 3: Tier Not Upgrading
**Check**:
- Lifetime earned points correct
- Tier threshold configuration
- Run manual update: `php artisan loyalty:update-tiers`
- Check tier calculation logic

### Issue 4: Coupon Not Applying
**Check**:
- Coupon status is Active
- Validity dates are current
- Usage limits not exceeded
- User hasn't used it before (per-user limit)
- Request-specific coupon assigned correctly

### Issue 5: Discount Calculations Wrong
**Check**:
- Stacking order: Coupon → Tier → Points
- 50% points redemption max applied
- 70% total discount max applied
- Rounding handled correctly

---

## 📝 TEST RESULT TEMPLATE

```
Test Date: _______________
Tester: _______________
Environment: [ ] Development [ ] Staging [ ] Production

Category: _______________
Test Flow: _______________

Results:
[ ] PASS - All steps completed successfully
[ ] FAIL - Issues encountered

Issues Found:
1. _______________
2. _______________

Notes:
_______________

Screenshots/Evidence:
_______________
```

---

## ✅ SIGN-OFF

Complete this section when all tests pass:

- [ ] All coupon features working
- [ ] All loyalty features working
- [ ] Email notifications sending
- [ ] Scheduled tasks running
- [ ] Integration with payment gateway
- [ ] Admin management tools functional
- [ ] No critical issues found
- [ ] Documentation reviewed

**Tested By**: _______________ **Date**: _______________  
**Approved By**: _______________ **Date**: _______________  

---

**END OF TESTING GUIDE**

Ready for production deployment! 🚀

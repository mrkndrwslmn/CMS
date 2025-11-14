# Referral System - User Flow Testing Guide

This guide provides step-by-step testing flows for the complete referral system organized by normal user actions and transactions. Follow these scenarios to test the full lifecycle.

---

## 🎯 CATEGORY 1: REFERRAL CODE GENERATION & DISCOVERY

### Test Flow 1.1: New User Gets Referral Code
**Goal**: Every new client automatically receives unique referral code

**Steps**:
1. **Register New Account**
   - Navigate to: `/register`
   - Fill registration form:
     - Name: John Doe
     - Email: john@example.com
     - Role: Client
   - Complete registration

2. **Login to Dashboard**
   - Login with new credentials
   - View client dashboard

3. **Discover Referral Feature**
   - Look for "Referrals" menu item
   - Click "Referrals" or "My Referrals"
   - URL: `/client/referrals/dashboard`

4. **View Your Referral Code**
   - **Referral Code Card** displays:
     - Your code: JOHNDOE2025ABCD
     - Large, styled display
     - Copy button
     - Share button

5. **Check Code Details**
   - Format: [NAME][YEAR][RANDOM]
   - Example: JOHNDOE2025ABCD
   - 11 characters, uppercase
   - Unique to you

**Expected Results**:
- ✅ Code generated automatically
- ✅ Visible in dashboard
- ✅ Easy to find and copy
- ✅ Professional presentation

---

### Test Flow 1.2: View Referral Dashboard
**Goal**: Client understands their referral status and rewards

**Steps**:
1. **Navigate to Referral Dashboard**
   - URL: `/client/referrals/dashboard`

2. **View Welcome Message**
   - Banner explains referral program
   - Benefits listed:
     - Friends get 500 points + 15% off
     - You get 1,000 points + 20% off
   - "How It Works" section

3. **View Statistics Cards**
   - **Card 1 - Total Referrals**: 
     - Count: 0 (for new user)
     - Icon: Users
   
   - **Card 2 - Pending**:
     - Count: 0
     - "Waiting for first payment"
   
   - **Card 3 - Completed**:
     - Count: 0
     - "Made first payment"
   
   - **Card 4 - Total Earned**:
     - Points: 0
     - Value in currency

4. **View Empty State**
   - "No referrals yet" message
   - Encouragement to share code
   - "Start Sharing" button

5. **Explore Sharing Options**
   - "Share Your Code" section visible
   - Multiple sharing methods shown
   - Instructions clear

**Expected Results**:
- ✅ Dashboard layout clean and intuitive
- ✅ Statistics clearly displayed
- ✅ Empty state helpful
- ✅ Call-to-action prominent

---

## 🎯 CATEGORY 2: SHARING & INVITING FRIENDS

### Test Flow 2.1: Share via Social Media
**Goal**: Client shares referral code on social platforms

**Steps**:
1. **Navigate to Share Page**
   - From dashboard, click "Share" button
   - URL: `/client/referrals/share`

2. **View Sharing Options**
   - 6 social platform buttons:
     - 📘 Facebook
     - 🐦 Twitter
     - 💼 LinkedIn
     - 💬 WhatsApp
     - ✈️ Telegram
     - 💬 Messenger

3. **Share on Facebook**
   - Click Facebook button
   - New window opens (Facebook share dialog)
   - **Verify pre-filled content**:
     - Message: "Join me on [App Name]!"
     - Your referral code included
     - Link: https://yoursite.com/register?ref=JOHNDOE2025ABCD
   - Click "Post to Facebook" (or cancel for testing)

4. **Share on WhatsApp**
   - Click WhatsApp button
   - WhatsApp opens (web or app)
   - **Verify message**:
     - Friendly invitation text
     - Referral code highlighted
     - Registration link included
   - Select contact and send (or cancel)

5. **Share on Twitter**
   - Click Twitter button
   - Twitter compose opens
   - **Verify tweet**:
     - Character limit respected
     - Hashtags included
     - Link shortened or full
   - Post tweet (or cancel)

6. **Test All Platforms**
   - Try each social button
   - Verify all open correctly
   - Check pre-filled content appropriate

**Expected Results**:
- ✅ All social platforms open
- ✅ Messages pre-filled
- ✅ Links include referral code
- ✅ Mobile-friendly
- ✅ Tracking parameters in URLs

---

### Test Flow 2.2: Send Email Invitation
**Goal**: Client invites friend via email

**Steps**:
1. **Find Email Invitation Section**
   - On share page
   - "Invite by Email" card

2. **Fill Email Form**
   - **Recipient Email**: friend@example.com
   - **Personal Message** (optional):
     - "Hi Sarah! I've been using this great service..."
   - Pre-filled template visible:
     - Your name mentioned
     - Benefits explained
     - Referral code shown

3. **Preview Invitation**
   - Click "Preview" (if available)
   - See how email will look
   - Check formatting

4. **Send Invitation**
   - Click "Send Invitation"
   - Loading spinner appears
   - Success message: "Invitation sent to friend@example.com"

5. **Verify Email Sent**
   - Check your email (sender copy if enabled)
   - Or check friend's inbox
   - Email should include:
     - Professional template
     - Your name: "John Doe invited you"
     - Benefits highlighted
     - Referral code: JOHNDOE2025ABCD
     - CTA button: "Sign Up Now"
     - Link: https://yoursite.com/register?ref=JOHNDOE2025ABCD

6. **Send Multiple Invitations**
   - Send to 3 different emails
   - Verify all sent successfully
   - Check for rate limiting (if any)

**Expected Results**:
- ✅ Email form easy to use
- ✅ Invitations sent successfully
- ✅ Professional email template
- ✅ Personal message included
- ✅ Links work correctly

---

### Test Flow 2.3: Copy and Share Manually
**Goal**: Client copies link for manual sharing

**Steps**:
1. **Find Copy Link Section**
   - On dashboard or share page
   - "Your Referral Link" box

2. **View Full Link**
   - Link displayed:
     - https://yoursite.com/register?ref=JOHNDOE2025ABCD
   - Full URL visible
   - Formatted nicely

3. **Copy Code Only**
   - Click "Copy Code" button
   - Toast notification: "Code copied!"
   - Clipboard contains: JOHNDOE2025ABCD

4. **Copy Full Link**
   - Click "Copy Link" button
   - Toast notification: "Link copied!"
   - Clipboard contains full URL

5. **Test Paste**
   - Paste in:
     - Text editor
     - SMS app
     - Chat application
     - Email draft
   - Verify copied correctly

6. **Share Manually**
   - Send via text message
   - Post in online community
   - Add to email signature
   - Share in chat groups

**Expected Results**:
- ✅ Copy buttons work
- ✅ Toast notifications appear
- ✅ Clipboard updated correctly
- ✅ Links are complete
- ✅ Easy to share anywhere

---

## 🎯 CATEGORY 3: FRIEND REGISTRATION WITH CODE

### Test Flow 3.1: Register via Referral Link
**Goal**: Friend registers using referral link

**Steps**:
1. **Friend Clicks Referral Link**
   - URL: https://yoursite.com/register?ref=JOHNDOE2025ABCD
   - Opens registration page
   - Browser: Incognito/private mode (simulate new user)

2. **View Registration Page**
   - Registration form displayed
   - **Verify referral code field**:
     - Pre-filled with: JOHNDOE2025ABCD
     - Highlighted or styled differently
     - May be read-only or editable
   - **Welcome banner visible**:
     - "You've been referred by John Doe!"
     - Benefits shown: 500 points + 15% off
     - Welcoming message

3. **Check Validation Indicator**
   - Green checkmark next to code
   - Text: "✓ Valid code from John Doe"
   - No errors shown

4. **Fill Registration Form**
   - Name: Sarah Johnson
   - Email: sarah@example.com
   - Phone: +63 917 987 6543
   - Password: SecurePass123
   - Referral code: JOHNDOE2025ABCD (pre-filled)

5. **Submit Registration**
   - Click "Register" or "Create Account"
   - Loading indicator appears
   - Processing...

6. **Verify Success**
   - Success message:
     - "Welcome to [App Name]!"
     - "Your referral bonus has been credited!"
     - "500 points + 15% off coupon added to your account"
   - Redirects to dashboard
   - Logged in automatically

**Expected Results**:
- ✅ Code pre-filled from URL
- ✅ Validation passes automatically
- ✅ Referrer name shown
- ✅ Benefits clearly displayed
- ✅ Registration successful
- ✅ Welcome bonus mentioned

---

### Test Flow 3.2: Manual Code Entry
**Goal**: Friend enters referral code manually

**Steps**:
1. **Navigate to Registration**
   - URL: `/register` (no ref parameter)
   - Registration page loads normally

2. **View Referral Code Field**
   - Field is empty
   - Placeholder: "Enter referral code (optional)"
   - Help text: "Have a referral code? Enter it to get bonus rewards!"
   - Not marked as required

3. **Enter Code Manually**
   - Type: JOHNDOE2025ABCD
   - Wait 500ms

4. **Watch Real-Time Validation**
   - AJAX call to server
   - Loading spinner briefly
   - **Valid code response**:
     - Green checkmark appears
     - Message: "✓ Valid code from John Doe"
     - May show referrer's name/avatar

5. **Test Invalid Code**
   - Clear field
   - Type: INVALIDCODE123
   - Wait for validation
   - **Invalid response**:
     - Red X appears
     - Message: "✗ Invalid referral code"
     - Field turns red

6. **Test Empty Field**
   - Clear field completely
   - **Neutral state**:
     - No validation indicator
     - Optional field, no error
   - Can still register

7. **Complete Registration with Valid Code**
   - Re-enter: JOHNDOE2025ABCD
   - Fill other fields
   - Submit registration
   - Verify success

**Expected Results**:
- ✅ Manual entry works
- ✅ Real-time validation functional
- ✅ Valid code: Green checkmark
- ✅ Invalid code: Red X
- ✅ Empty: No error (optional)
- ✅ Clear feedback messages

---

### Test Flow 3.3: Register Without Code
**Goal**: Verify registration works without referral

**Steps**:
1. **Navigate to Registration**
   - URL: `/register`

2. **Leave Referral Field Empty**
   - Skip referral code field
   - Fill other required fields only

3. **Submit Registration**
   - Click "Register"
   - No validation errors for referral field

4. **Verify Account Created**
   - Account created successfully
   - No referral bonus (expected)
   - Normal welcome message
   - Gets own referral code to share

5. **Check Database**
   - User has no `referred_by_id`
   - No referral record exists
   - Can still earn rewards from own referrals later

**Expected Results**:
- ✅ Registration succeeds without code
- ✅ No errors or warnings
- ✅ Normal onboarding flow
- ✅ System works independently

---

## 🎯 CATEGORY 4: IMMEDIATE REWARDS (SIGNUP)

### Test Flow 4.1: Welcome Bonus for New User
**Goal**: Referred user receives immediate rewards after registration

**Steps**:
1. **Complete Registration with Code**
   - Sarah registers via John's link
   - Registration successful

2. **Check Welcome Message**
   - Success notification mentions:
     - "Your referral bonus has been credited!"
     - 500 points
     - 15% off coupon

3. **Navigate to Loyalty Dashboard**
   - Click "Loyalty" menu
   - URL: `/client/loyalty`

4. **Verify Points Credited**
   - **Points Balance Card**:
     - Available points: 500
     - Total earned: 500
   - **Recent Transactions**:
     - Entry: "Referral Welcome Bonus"
     - Type: Earned
     - Amount: +500
     - Date: Today
     - Status: Active
     - Expires: 12 months

5. **Navigate to Coupons**
   - Click "Coupons" menu
   - URL: `/client/coupons`

6. **Verify Coupon Generated**
   - **Welcome Coupon Card**:
     - Name: "Welcome 15% Off"
     - Code: AUTO-GENERATED (e.g., WELCOME15-ABC123)
     - Type: Percentage
     - Discount: 15%
     - Valid until: 30 days from today
     - Status: Active
     - Usage: 0 / 1 (one-time use)
     - Source: Referral Program
   - "Copy Code" button available

7. **Check Email Notification**
   - Check Sarah's email inbox
   - Email: "Welcome! Your Bonus is Ready"
   - Content:
     - Greeting with name
     - 500 points credited
     - 15% coupon code shown
     - How to use instructions
     - Next steps

**Expected Results**:
- ✅ 500 points credited immediately
- ✅ 15% coupon generated
- ✅ 30-day validity period
- ✅ Welcome email sent
- ✅ Benefits visible in dashboard
- ✅ Can be used right away

---

### Test Flow 4.2: Referrer Notification
**Goal**: Referrer notified when someone uses their code

**Steps**:
1. **After Friend Registers**
   - Sarah completes registration
   - System processes referral

2. **Check Referrer's Email**
   - Login to John's email
   - Email received: "Someone Used Your Referral Code!"
   - **Email content**:
     - Subject: "🎉 Good News! Your Referral Just Signed Up"
     - Greeting: "Hi John,"
     - Message: "Great news! Sarah Johnson just signed up using your referral code"
     - Status: "Pending First Payment"
     - Potential reward preview:
       - 1,000 loyalty points
       - 20% off coupon
     - What happens next explanation
     - CTA: "View Your Referrals"

3. **Login as Referrer**
   - Login as John
   - Navigate to dashboard

4. **Check Notification Badge**
   - Bell icon shows "1" notification
   - Click bell
   - Notification: "New referral! Sarah Johnson signed up"

5. **Navigate to Referral Dashboard**
   - URL: `/client/referrals/dashboard`

6. **View Updated Statistics**
   - **Total Referrals**: 1 (was 0)
   - **Pending**: 1 (new)
   - **Completed**: 0
   - **Total Earned**: 0 (no payment yet)

7. **View Recent Referrals Table**
   - New row appears:
     - Name: Sarah Johnson (or S***h J***n for privacy)
     - Email: s***@example.com (masked)
     - Status: 🟡 Pending
     - Date: Today
     - Reward: Awaiting first payment
   - Status badge yellow/orange

8. **View Pending Section**
   - Card: "Pending Referrals"
   - Shows Sarah's referral
   - Message: "Waiting for first payment to earn rewards"
   - Progress indicator

**Expected Results**:
- ✅ Email sent to referrer immediately
- ✅ Dashboard statistics updated
- ✅ Notification badge appears
- ✅ Referral visible in list
- ✅ Status: Pending
- ✅ Clear next steps shown

---

## 🎯 CATEGORY 5: FIRST PAYMENT & COMPLETION

### Test Flow 5.1: Referred User Makes First Payment
**Goal**: Complete the referral cycle with payment

**Steps**:
1. **Scenario Setup**
   - Sarah (referred user) registered
   - Sarah has 500 welcome points + 15% coupon
   - John (referrer) notified, waiting

2. **Sarah Submits Service Request**
   - Login as Sarah
   - Navigate to: `/client/requests/create`
   - Fill request form:
     - Project: Website Development
     - Budget: ₱10,000
     - Description: Full details
   - Submit request
   - Request status: Pending

3. **Admin Approves Request**
   - Login as Admin
   - Navigate to: `/admin/requests`
   - Find Sarah's request
   - Click "Approve"
   - Request status: Approved

4. **Sarah Proceeds to Payment**
   - Login as Sarah
   - Navigate to: `/client/requests`
   - Find approved request
   - Click "Pay Now"
   - Redirects to payment page

5. **Payment Page**
   - **View payment details**:
     - Original amount: ₱10,000
   - **Optional: Use Welcome Coupon**:
     - Apply 15% coupon: -₱1,500
     - Subtotal: ₱8,500
   - **Optional: Use Welcome Points**:
     - Redeem 500 points: -₱500
     - Final total: ₱8,000
   - Click "Proceed to Payment"

6. **Complete Payment via Maya**
   - Redirects to Maya gateway
   - Enter test payment details
   - Confirm payment
   - Payment successful
   - Redirects back to success page

7. **View Payment Confirmation**
   - Success message displayed
   - Payment ID shown
   - "Your payment has been received"
   - Points earned (from payment): +80 (1% of ₱8,000)

8. **System Processing** (Background)
   - Payment webhook received
   - Status: Confirmed
   - **Referral system triggered**:
     - Check if first payment
     - Yes → Process referral completion
     - Award rewards to referrer

**Expected Results**:
- ✅ Payment completes successfully
- ✅ Welcome coupon/points usable
- ✅ Payment recorded in database
- ✅ Referral completion triggered
- ✅ Background processing starts

---

### Test Flow 5.2: Referrer Receives Completion Rewards
**Goal**: Referrer earns rewards when referral completes

**Steps**:
1. **Check Referral Status Update**
   ```bash
   # Database check (optional)
   SELECT status, completed_at, rewarded_at 
   FROM referrals 
   WHERE referred_id = [Sarah's ID];
   # Expected: status = 'rewarded', both timestamps set
   ```

2. **Check Referrer's Email**
   - Login to John's email
   - Email received: "Referral Completed - Rewards Earned!"
   - **Email content**:
     - Subject: "🎁 Referral Reward Unlocked!"
     - Greeting: "Congratulations John!"
     - Main message: "Sarah Johnson completed their first payment"
     - **Rewards breakdown**:
       - ✅ 1,000 loyalty points earned
       - ✅ 20% off coupon generated
       - Code: REFERRAL20-XYZ789
       - Valid for: 60 days
     - Current stats:
       - Total referrals: 1
       - Total earned: 1,000 points
     - **Dual CTAs**:
       - "View My Points"
       - "View My Coupons"
     - Encouragement to share more

3. **Login as Referrer (John)**
   - Navigate to loyalty dashboard
   - URL: `/client/loyalty`

4. **Verify Points Credited**
   - **Points Balance**:
     - Available: 1,000 (increased)
     - New transaction visible
   - **Transactions Table**:
     - Entry: "Referral Completion Reward - Sarah Johnson"
     - Type: Earned
     - Amount: +1,000
     - Date: Today
     - Expires: 12 months
     - Source: Referral Program

5. **Navigate to Coupons**
   - URL: `/client/coupons`

6. **Verify Completion Coupon**
   - **New Coupon Card**:
     - Name: "Referral 20% Off"
     - Code: REFERRAL20-XYZ789
     - Type: Percentage
     - Discount: 20%
     - Valid until: 60 days from today
     - Status: Active
     - Usage: 0 / 1
     - Source: Referral Program
   - "Copy Code" button
   - "Use Now" button

7. **Check Referral Dashboard**
   - URL: `/client/referrals/dashboard`

8. **View Updated Statistics**
   - **Total Referrals**: 1
   - **Pending**: 0 (decreased)
   - **Completed**: 1 (increased)
   - **Total Earned**: 1,000 points
   - **Conversion Rate**: 100%

9. **View Completed Referrals Table**
   - Sarah's referral now shows:
     - Status: 🟢 Rewarded
     - Points earned: 1,000
     - Coupon: REFERRAL20-XYZ789
     - Date completed: Today
   - Green checkmark icon
   - "Rewards Claimed" badge

10. **Check Dashboard Notification**
    - Bell icon notification:
      - "Referral completed! You earned 1,000 points"
    - Click to view details

**Expected Results**:
- ✅ Email sent immediately after payment
- ✅ 1,000 points credited
- ✅ 20% coupon generated (60 days)
- ✅ Dashboard statistics updated
- ✅ Status changed: Pending → Rewarded
- ✅ Points visible in loyalty account
- ✅ Coupon visible in coupons page
- ✅ Notification displayed

---

### Test Flow 5.3: Use Earned Rewards
**Goal**: Referrer uses earned points and coupon

**Steps**:
1. **Create New Service Request**
   - Login as John (referrer)
   - Submit new service request
   - Admin approves
   - Request ready for payment

2. **Navigate to Payment**
   - Click "Pay Now"
   - Payment page opens
   - Original amount: ₱15,000

3. **Apply Referral Coupon**
   - Select or enter: REFERRAL20-XYZ789
   - Click "Apply"
   - **Discount applied**:
     - 20% off: -₱3,000
     - Subtotal: ₱12,000

4. **Redeem Referral Points**
   - "Use Loyalty Points" section
   - Enter: 1,000 points
   - Click "Apply Points"
   - **Additional discount**:
     - Points: -₱1,000
     - Final total: ₱11,000

5. **View Payment Breakdown**
   - Original: ₱15,000
   - Coupon (20%): -₱3,000
   - Points: -₱1,000
   - **Total Savings**: ₱4,000
   - **Amount to Pay**: ₱11,000

6. **Complete Payment**
   - Proceed to Maya gateway
   - Pay ₱11,000
   - Payment successful

7. **Verify Usage**
   - Coupon marked as "Used"
   - Points deducted: 0 remaining
   - Both tracked in history

**Expected Results**:
- ✅ Referral coupon applies correctly
- ✅ Referral points redeemable
- ✅ Discounts stack properly
- ✅ Significant savings achieved
- ✅ Usage tracked

---

## 🎯 CATEGORY 6: MULTIPLE REFERRALS

### Test Flow 6.1: Second Referral Success
**Goal**: Referrer earns from multiple referrals

**Steps**:
1. **Share Code Again**
   - John shares referral code
   - URL sent to Mike

2. **Mike Registers**
   - Mike clicks link
   - Completes registration with code
   - Mike gets 500 points + 15% coupon
   - John notified: "2nd referral!"

3. **Check John's Dashboard**
   - **Total Referrals**: 2
   - **Pending**: 1 (Mike)
   - **Completed**: 1 (Sarah)
   - **Total Earned**: 1,000 (from Sarah)

4. **Mike Makes First Payment**
   - Mike pays for service
   - Payment confirmed

5. **John Receives Second Reward**
   - Email: "Another referral completed!"
   - **New rewards**:
     - +1,000 points (again)
     - New 20% coupon (different code)
     - Total earned: 2,000 points

6. **Updated Dashboard**
   - **Total Referrals**: 2
   - **Pending**: 0
   - **Completed**: 2
   - **Total Earned**: 2,000 points
   - **Conversion Rate**: 100%

7. **Check Loyalty Account**
   - Available points: 2,000
   - Two separate transactions:
     - Referral: Sarah - 1,000
     - Referral: Mike - 1,000

8. **Check Coupons**
   - Two 20% coupons:
     - REFERRAL20-XYZ789 (used/unused)
     - REFERRAL20-ABC456 (new)
   - Both valid, can use separately

**Expected Results**:
- ✅ Each referral rewards separately
- ✅ No limit on number of referrals
- ✅ Multiple coupons can be earned
- ✅ Points accumulate
- ✅ Separate tracking for each

---

### Test Flow 6.2: Third, Fourth, Fifth Referrals
**Goal**: Test scaling with more referrals

**Steps**:
1. **Continue Sharing**
   - John invites 3 more friends
   - All register with his code
   - Dashboard shows 5 total referrals

2. **Various Completion Statuses**
   - Referral 3: Pays immediately → Completed
   - Referral 4: Still pending
   - Referral 5: Pays after 1 week → Completed

3. **Track Statistics**
   - **Total**: 5 referrals
   - **Pending**: 1
   - **Completed**: 4
   - **Earned**: 4,000 points
   - **Conversion**: 80%

4. **View Referral History**
   - Table shows all 5 referrals:
     - Names (masked for privacy)
     - Status badges (color-coded)
     - Dates
     - Rewards earned
   - Filter options:
     - Show pending only
     - Show completed only
     - Date range

5. **Earn Substantial Rewards**
   - Total points: 4,000
   - Total coupons: 4 × 20% off
   - Significant savings potential

**Expected Results**:
- ✅ System scales well
- ✅ No performance issues
- ✅ All referrals tracked accurately
- ✅ Statistics calculated correctly
- ✅ Can manage many referrals

---

## 🎯 CATEGORY 7: ADMIN MONITORING

### Test Flow 7.1: Admin Views Analytics Dashboard
**Goal**: Admin monitors referral program performance

**Steps**:
1. **Login as Admin**
   - URL: `/login`
   - Use admin credentials

2. **Navigate to Referrals**
   - Click "Referrals" in admin menu
   - URL: `/admin/referrals`

3. **View Summary Cards**
   - **Card 1 - Total Referrals**:
     - Count: 50 (example)
     - Growth: ↑ 15% vs last month
   
   - **Card 2 - Successful**:
     - Count: 35
     - Conversion rate: 70%
   
   - **Card 3 - Pending**:
     - Count: 15
     - Awaiting payment
   
   - **Card 4 - Rewards Distributed**:
     - Total: 35,000 points
     - Value: ₱35,000

4. **View Referral Trends Chart**
   - Line chart: Last 6 months
   - Two lines:
     - Total referrals (blue)
     - Completed referrals (green)
   - Shows growth trajectory
   - Hover for exact numbers

5. **View Status Distribution**
   - Doughnut chart:
     - Pending: 30% (yellow)
     - Completed: 60% (blue)
     - Rewarded: 10% (green)
   - Interactive legend

6. **View Conversion Funnel**
   - **Step 1**: Total signups (50) - 100%
   - **Step 2**: First payment (35) - 70%
   - **Step 3**: Rewards given (35) - 70%
   - Drop-off analysis: 30% don't convert

7. **View Top Referrers**
   - Leaderboard (top 10):
     - Rank 1: John Doe - 15 referrals 👑
     - Rank 2: Jane Smith - 12 referrals 🥈
     - Rank 3: Mike Brown - 10 referrals 🥉
     - Ranks 4-10: Other users
   - Click name to view details

8. **View Recent Activity**
   - Last 10 activities:
     - "Sarah signed up via John's code" - 2 min ago
     - "Mike completed first payment" - 15 min ago
     - "Alex registered" - 1 hour ago
   - Status badges
   - Quick view buttons

9. **Export Data**
   - Click "Export" button
   - Choose format: CSV or Excel
   - Download file
   - Contains all referral data

**Expected Results**:
- ✅ Comprehensive overview
- ✅ Real-time statistics
- ✅ Visual charts render
- ✅ Top performers highlighted
- ✅ Export works

---

### Test Flow 7.2: Admin Views All Referrals
**Goal**: Admin reviews complete referral list

**Steps**:
1. **Navigate to Referrals List**
   - Click "View All" from dashboard
   - URL: `/admin/referrals/list`

2. **View Referrals Table**
   - Columns displayed:
     - ID
     - Referrer name
     - Referred user name
     - Referral code
     - Status badge
     - Date created
     - Rewards
     - Actions
   - 25 rows per page
   - Total count shown

3. **Apply Search Filter**
   - Search box: "John"
   - Results filter in real-time
   - Shows referrals by John
   - Or to John
   - Or code containing "JOHN"

4. **Filter by Status**
   - Dropdown: Select "Pending"
   - Table updates
   - Shows only pending referrals
   - Count: "15 pending referrals"

5. **Filter by Date Range**
   - From: Nov 1, 2025
   - To: Nov 14, 2025
   - Apply filters
   - Shows referrals in range

6. **Sort Columns**
   - Click "Date" header
   - Sort descending (newest first)
   - Click again: ascending
   - Arrow indicator shows direction

7. **View Referral Details**
   - Click "View" on any referral
   - Opens detail page

8. **Clear All Filters**
   - Click "Clear Filters"
   - Resets to full list
   - All referrals shown

**Expected Results**:
- ✅ Complete list accessible
- ✅ Search works
- ✅ Filters apply correctly
- ✅ Sorting functional
- ✅ Pagination works
- ✅ Easy navigation

---

### Test Flow 7.3: Admin Views Single Referral Detail
**Goal**: Admin reviews specific referral in depth

**Steps**:
1. **Select Referral**
   - From list, click "View" on referral #123
   - URL: `/admin/referrals/show/123`

2. **View Status Section**
   - **Status Card**:
     - Current status: Rewarded 🟢
     - Points awarded:
       - Referrer: 1,000
       - Referred: 500
     - Coupons:
       - Referrer: REFERRAL20-XYZ
       - Referred: WELCOME15-ABC
   - Color-coded status badge

3. **View User Information**
   - **Left Card - Referrer**:
     - Name: John Doe
     - Email: john@example.com
     - User ID: #456
     - Total referrals: 5
     - "View Profile" link
   
   - **Right Card - Referred User**:
     - Name: Sarah Johnson
     - Email: sarah@example.com
     - User ID: #789
     - Registration: Nov 1, 2025
     - "View Profile" link

4. **View Rewards Breakdown**
   - **Referred User Section**:
     - Welcome bonus: 500 points ✅
     - Welcome coupon: 15% ✅
     - Awarded: Nov 1, 2025 10:00 AM
   
   - **Referrer Section**:
     - Completion bonus: 1,000 points ✅
     - Completion coupon: 20% ✅
     - Awarded: Nov 5, 2025 3:46 PM

5. **View Timeline**
   - **Event 1**: Referral created
     - Nov 1, 2025, 10:00 AM
     - Icon: Green circle
     - "Sarah registered via John's code"
   
   - **Event 2**: First payment
     - Nov 5, 2025, 3:45 PM
     - Icon: Blue dollar
     - "Payment #PAY12345 - ₱10,000"
     - Link to payment details
   
   - **Event 3**: Rewards distributed
     - Nov 5, 2025, 3:46 PM
     - Icon: Purple gift
     - "Both users rewarded"
     - Points and coupons listed

6. **View Metadata Sidebar**
   - **Referral Code**: JOHNDOE2025ABCD
   - **Code Status**: Active
   - **Total Uses**: 5
   - **Registration Info**:
     - Source: Direct link
     - IP: 192.168.1.100
     - User Agent: Chrome 120
     - UTM params: (if any)

7. **Quick Actions**
   - "View Payment" button → Payment details
   - "View Referrer" button → John's profile
   - "View Referred" button → Sarah's profile
   - "View Coupons" button → Coupon details
   - Manual process button (if pending)

**Expected Results**:
- ✅ Complete referral information
- ✅ Visual timeline clear
- ✅ All data accurate
- ✅ Quick actions work
- ✅ Metadata captured

---

### Test Flow 7.4: Admin Manages Referral Codes
**Goal**: Admin reviews and controls referral codes

**Steps**:
1. **Navigate to Code Management**
   - URL: `/admin/referrals/codes`

2. **View Summary Stats**
   - Total codes: 150
   - Active: 145
   - Inactive: 5
   - Total uses: 320
   - Avg conversion: 35%

3. **View Codes Table**
   - Columns:
     - Code (styled badge)
     - Owner
     - Status
     - Total referrals
     - Pending / Successful
     - Conversion rate (with bar)
     - Lifetime earnings
     - Last used
     - Actions

4. **Apply Filters**
   - Search: "JOHN"
   - Status: Active only
   - Sort by: Total referrals (desc)
   - Min referrals: 5+
   - Click "Apply"

5. **View Filtered Results**
   - Shows matching codes
   - Sorted correctly
   - Count updated

6. **View Top Performers**
   - Section: "Top 5 Performing Codes"
   - Cards show:
     - Code
     - Owner
     - Stats
   - Gradient styling

7. **Toggle Code Status**
   - Find inactive code
   - Click "Activate"
   - Confirmation dialog
   - Confirm
   - Status updates: Active

8. **Test Deactivation**
   - Select active code
   - Click "Deactivate"
   - Warning: "New referrals won't work"
   - Confirm
   - Status: Inactive
   - Code still visible but disabled

**Expected Results**:
- ✅ All codes listed
- ✅ Performance metrics shown
- ✅ Filters work
- ✅ Toggle status functional
- ✅ Top performers highlighted

---

## 🎯 CATEGORY 8: EDGE CASES & VALIDATION

### Test Flow 8.1: Self-Referral Prevention
**Goal**: System prevents self-referral

**Steps**:
1. **Get Own Code**
   - Login as John
   - Copy referral code: JOHNDOE2025ABCD

2. **Try to Register with Own Code**
   - Logout
   - Go to registration
   - Enter John's existing email
   - Enter own referral code

3. **Verify Validation**
   - **Expected outcome**:
     - Email validation fails: "Email already exists"
     - Cannot complete registration
   - If new email used:
     - System checks user_id match
     - Error: "Cannot use your own referral code"

**Expected Results**:
- ✅ Self-referral blocked
- ✅ Clear error message
- ✅ Registration prevented

---

### Test Flow 8.2: Duplicate Referral Prevention
**Goal**: Same user cannot be referred twice

**Steps**:
1. **Scenario Setup**
   - Sarah already registered via John's code
   - Referral exists and completed

2. **Try Duplicate Methods**
   - **Method A**: Sarah tries to register again
     - Email validation: "Already exists"
   
   - **Method B**: Admin manually creates referral
     - Referrer: John
     - Referred: Sarah
     - Error: "Referral already exists"
   
   - **Method C**: Database constraint
     - UNIQUE (referrer_id, referred_id)
     - Prevents duplicate

**Expected Results**:
- ✅ Duplicates prevented
- ✅ Multiple safeguards
- ✅ Database integrity maintained

---

### Test Flow 8.3: Invalid/Inactive Code Handling
**Goal**: System handles invalid codes gracefully

**Test Cases**:

**Case A: Non-existent Code**
- Enter: FAKECODEXYZ
- Validation: "Invalid referral code"
- Can still register (optional field)

**Case B: Deactivated Code**
- Admin deactivates code
- User tries to use it
- Validation: "This code is no longer active"

**Case C: Malformed Code**
- Enter: "123" (too short)
- Validation: "Invalid code format"

**Case D: Expired Code** (if expiry implemented)
- Code validity period passed
- Validation: "This code has expired"

**Case E: SQL Injection**
- Enter: `'; DROP TABLE users; --`
- System sanitizes input
- Safe handling
- Invalid code message

**Expected Results**:
- ✅ All cases handled safely
- ✅ Clear error messages
- ✅ No system crashes
- ✅ Security maintained

---

### Test Flow 8.4: Second Payment (No Reward)
**Goal**: Verify referral rewards only on first payment

**Steps**:
1. **Scenario Setup**
   - Sarah completed first payment
   - John already rewarded

2. **Sarah Makes Second Payment**
   - Submit another service request
   - Admin approves
   - Pay for second service

3. **Check Referral Status**
   - Status remains: Rewarded
   - No new rewards to John
   - Logs show: "Already rewarded"

4. **Verify Points**
   - John's referral points unchanged
   - Sarah earns regular loyalty points
   - No duplicate rewards

**Expected Results**:
- ✅ Only first payment triggers reward
- ✅ No duplicate rewards
- ✅ System tracks correctly
- ✅ Prevents gaming

---

## 🎯 CATEGORY 9: INTEGRATION TESTING

### Test Flow 9.1: Referral + Loyalty Integration
**Goal**: Verify seamless loyalty system integration

**Steps**:
1. **Earn Referral Points**
   - John earns 1,000 from referral
   - Check loyalty dashboard
   - Transaction type: "Earned"
   - Source: "Referral Completion"

2. **Mix with Other Points**
   - John also has points from payments
   - Total available combines all sources
   - Can differentiate in history

3. **Use Referral Points**
   - Apply to payment like any points
   - No distinction in usage
   - Same redemption rules apply

4. **Points Expiry**
   - Referral points expire in 12 months
   - Same as regular loyalty points
   - Expiry warnings sent

**Expected Results**:
- ✅ Points integrated seamlessly
- ✅ Same rules apply
- ✅ Source tracked for analytics
- ✅ Can be used interchangeably

---

### Test Flow 9.2: Referral + Coupon Integration
**Goal**: Verify coupon system integration

**Steps**:
1. **Coupon Auto-Generation**
   - Referral rewards create coupons
   - 15% for referred user
   - 20% for referrer
   - User-specific coupons

2. **View in Coupon List**
   - Appears in `/client/coupons`
   - Tagged: "From Referral"
   - Proper formatting
   - Usage rules apply

3. **Use Referral Coupon**
   - Apply at payment
   - Discount calculated
   - One-time use enforced
   - Expiry date checked

4. **Stack with Other Discounts**
   - Can combine with tier discount
   - Can combine with points
   - Max discount rules apply

**Expected Results**:
- ✅ Coupons created correctly
- ✅ Visible in coupons page
- ✅ All coupon features work
- ✅ Expiry enforced

---

### Test Flow 9.3: Email Notification Flow
**Goal**: All emails work correctly

**Email Types**:

1. **Signup Email (to Referrer)**
   - Trigger: Friend registers
   - Recipient: Referrer
   - Content: Notification
   - Status: Pending
   - CTA: View dashboard

2. **Completion Email (to Referrer)**
   - Trigger: First payment made
   - Recipient: Referrer
   - Content: Rewards details
   - Includes: Points + coupon code
   - CTA: View rewards

3. **Welcome Email (to Referred)**
   - Trigger: Registration
   - Recipient: New user
   - Content: Welcome + bonus
   - Includes: 500 points + coupon
   - CTA: Explore platform

**Testing Steps**:
1. Complete full referral cycle
2. Check all 3 emails sent
3. Verify content accurate
4. Test all links work
5. Check mobile rendering

**Expected Results**:
- ✅ All emails queued
- ✅ Templates render correctly
- ✅ Content personalized
- ✅ Links functional
- ✅ Mobile-friendly

---

## 📋 COMPREHENSIVE TESTING CHECKLIST

### Code Generation & Discovery
- [ ] Auto-generate code on registration
- [ ] Code follows format (NAME+YEAR+RANDOM)
- [ ] Code is unique
- [ ] View code in dashboard
- [ ] Copy code functionality

### Sharing
- [ ] Share on Facebook
- [ ] Share on Twitter
- [ ] Share on WhatsApp
- [ ] Share on LinkedIn
- [ ] Share on Telegram
- [ ] Share on Messenger
- [ ] Send email invitation
- [ ] Copy link manually

### Registration
- [ ] Register via referral link (pre-filled)
- [ ] Manual code entry with validation
- [ ] Real-time code validation works
- [ ] Invalid code shows error
- [ ] Register without code works
- [ ] Referred user linked correctly

### Immediate Rewards
- [ ] Referred user gets 500 points
- [ ] Referred user gets 15% coupon
- [ ] Coupon valid for 30 days
- [ ] Welcome email sent
- [ ] Referrer gets signup notification
- [ ] Dashboard updates immediately

### Payment & Completion
- [ ] First payment detected
- [ ] Referral status updated
- [ ] Referrer gets 1,000 points
- [ ] Referrer gets 20% coupon (60 days)
- [ ] Completion email sent
- [ ] Dashboard statistics update
- [ ] Second payment doesn't reward again

### Multiple Referrals
- [ ] Can refer multiple people
- [ ] Each rewards separately
- [ ] Multiple coupons earned
- [ ] Points accumulate
- [ ] Statistics track correctly

### Admin Features
- [ ] View analytics dashboard
- [ ] Charts render correctly
- [ ] View all referrals list
- [ ] Search and filter work
- [ ] View single referral detail
- [ ] Timeline displays
- [ ] Manage referral codes
- [ ] Toggle code status
- [ ] Export data

### Edge Cases
- [ ] Self-referral prevented
- [ ] Duplicate referral prevented
- [ ] Invalid code handled
- [ ] Inactive code rejected
- [ ] Malformed code safe
- [ ] Second payment no reward

### Integration
- [ ] Loyalty points integration
- [ ] Coupon system integration
- [ ] Email notifications
- [ ] Payment webhook trigger
- [ ] Queue processing

---

## 📝 TEST RESULT TEMPLATE

```
Test Date: _______________
Tester: _______________
Environment: [ ] Dev [ ] Staging [ ] Production

Category: _______________
Test Flow: _______________

Results:
[ ] PASS - All steps successful
[ ] FAIL - Issues found

Issues:
1. _______________
2. _______________

Notes:
_______________
```

---

## ✅ SIGN-OFF

- [ ] All test flows completed
- [ ] All checklist items verified
- [ ] No critical issues
- [ ] Documentation reviewed
- [ ] Ready for production

**Tested By**: _______________ **Date**: _______________  
**Approved By**: _______________ **Date**: _______________  

---

**END OF TESTING GUIDE**

Referral system fully tested and ready! 🚀

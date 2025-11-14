# Earnings & Payout System - Testing Guide

This guide provides step-by-step testing flows for the complete earnings and payout system. Follow these scenarios in order to test the full lifecycle.

---

## 🎯 CATEGORY 1: INITIAL SETUP & CONFIGURATION

### Test Flow 1.1: Adiutor Profile Setup
**Goal**: Configure adiutor's earnings settings

**Steps**:
1. **Login as Adiutor**
   - URL: `/login`
   - Use adiutor credentials

2. **Navigate to Earnings Settings**
   - Click profile menu → "Earnings Settings"
   - Or go to: `/adiutor/profile/earnings`

3. **Set Standard Hourly Rate**
   - Enter rate (e.g., 500 PHP)
   - Verify: Rate is saved and displayed

4. **Configure Payout Settings**
   - Set minimum payout amount (e.g., 1000 PHP)
   - Select preferred payout method (e.g., "GCash")
   - Fill in payment details (mobile number: 09171234567)
   - Click "Save Settings"

5. **Verify Settings**
   - Check success message appears
   - Return to earnings settings page
   - Confirm all values are saved correctly

**Expected Results**:
- ✅ Standard hourly rate saved
- ✅ Minimum payout amount set
- ✅ Payment method configured
- ✅ Payment details stored

---

## 🎯 CATEGORY 2: PROJECT & TASK ASSIGNMENT (ADMIN)

### Test Flow 2.1: Assign Adiutor to Project with Custom Rate
**Goal**: Admin assigns adiutor with specific hourly rate

**Steps**:
1. **Login as Admin**
   - URL: `/login`
   - Use admin credentials

2. **Navigate to Projects**
   - Go to: `/admin/projects`
   - Select an existing project or create new one

3. **View Project Details**
   - Click on project name
   - URL pattern: `/admin/projects/{id}`

4. **Assign Adiutor**
   - Click "Assign Adiutor" button
   - Select adiutor from dropdown
   - **Verify**: Hourly rate auto-fills with adiutor's standard rate

5. **Set Project Rate**
   - Option A: Keep default rate (standard rate)
   - Option B: Override with custom rate (e.g., 600 PHP)
   - Check "Requires Time Tracking" checkbox
   - Click "Assign"

6. **Verify Assignment**
   - Check adiutor appears in project team
   - Verify hourly rate is displayed
   - Confirm time tracking status shows correctly

**Expected Results**:
- ✅ Adiutor assigned to project
- ✅ Custom hourly rate set (if changed)
- ✅ Time tracking enabled
- ✅ Rate hierarchy applies (project > standard)

---

### Test Flow 2.2: Create Task with Hourly Payment
**Goal**: Create task with hourly rate configuration

**Steps**:
1. **Navigate to Task Creation**
   - From project page, click "Create Task"
   - Or go to: `/admin/tasks/create`

2. **Fill Basic Task Details**
   - Task name: "Design Homepage Banner"
   - Description: "Create responsive banner design"
   - Assign to adiutor
   - Set deadline

3. **Configure Payment (Hourly)**
   - Scroll to "Payment Configuration" section
   - Select payment type: **"Hourly Rate"**
   - **Verify**: Rate auto-fills based on:
     - Task rate (if set)
     - Or project rate (from assignment)
     - Or adiutor standard rate

4. **Set Optional Budget Cap**
   - Check "Set Budget Cap" (optional)
   - Enter amount: 5000 PHP
   - Check "Requires Time Tracking"

5. **Save Task**
   - Click "Create Task"
   - Verify success message

6. **Verify Task Details**
   - Return to project/task list
   - Check task shows payment type: "Hourly"
   - Verify rate is displayed correctly

**Expected Results**:
- ✅ Task created with hourly payment
- ✅ Correct rate applied (hierarchy: task > project > standard)
- ✅ Budget cap set (if applicable)
- ✅ Time tracking enabled

---

### Test Flow 2.3: Create Task with Fixed Budget
**Goal**: Create task with fixed payment amount

**Steps**:
1. **Navigate to Task Creation**
   - Click "Create Task" button

2. **Fill Basic Details**
   - Task name: "Logo Design"
   - Description: "Design company logo"
   - Assign to adiutor

3. **Configure Payment (Fixed)**
   - Select payment type: **"Fixed Budget"**
   - **Verify**: Hourly rate fields are hidden
   - Enter fixed budget: 3000 PHP
   - Note: Time tracking checkbox should be unchecked/hidden

4. **Save Task**
   - Click "Create Task"
   - Verify task created

5. **Verify Task Details**
   - Check task shows payment type: "Fixed"
   - Verify allocated budget: 3000 PHP
   - Confirm no time tracking required

**Expected Results**:
- ✅ Task created with fixed budget
- ✅ No hourly rate set
- ✅ Time tracking not required
- ✅ Budget allocated correctly

---

### Test Flow 2.4: Create Task with No Payment
**Goal**: Create task without payment tracking

**Steps**:
1. **Create New Task**
   - Fill basic task details
   - Task name: "Internal Review Task"

2. **Configure Payment**
   - Select payment type: **"No Payment"**
   - **Verify**: All payment fields are hidden

3. **Save and Verify**
   - Save task
   - Confirm no payment configuration shown
   - Verify task can still be completed

**Expected Results**:
- ✅ Task created without payment tracking
- ✅ No earnings generated
- ✅ Task functions normally

---

## 🎯 CATEGORY 3: TIME TRACKING & EARNINGS GENERATION

### Test Flow 3.1: Track Time and Generate Earnings
**Goal**: Adiutor tracks time and earnings are automatically calculated

**Steps**:
1. **Login as Adiutor**
   - URL: `/login`

2. **Navigate to Time Tracking**
   - Go to: `/adiutor/time-tracking`
   - Or click "Time Tracking" in menu

3. **View Available Tasks**
   - See list of assigned tasks
   - **Verify**: Tasks with hourly payment show hourly rate
   - Check tasks with fixed budget show "Fixed" label

4. **Start Time Tracking**
   - Select a task (hourly payment type)
   - Click "Start Timer" or "Start Tracking"
   - **Verify**: 
     - Timer starts counting
     - Hourly rate is displayed (e.g., "₱500/hr")
     - Current project shows

5. **Work on Task**
   - Let timer run for test (e.g., 5 minutes or 0.083 hours)
   - Add notes: "Working on initial design concepts"

6. **Stop Timer**
   - Click "Stop Timer"
   - **Verify Calculated Earnings**:
     - Example: 0.083 hours × ₱500 = ₱41.50
     - Earnings amount displays immediately
     - Status shows as "Pending"

7. **Submit Time Entry**
   - Review time entry details
   - Click "Submit" or "Save"

8. **View Time Entry in List**
   - Check time tracking history
   - Verify entry shows:
     - Date and duration
     - Task name
     - Calculated amount (₱41.50)
     - Status: "Pending Approval"

**Expected Results**:
- ✅ Timer tracks time accurately
- ✅ Earnings calculated automatically (hours × rate)
- ✅ Time entry saved with pending status
- ✅ Earnings not yet approved for payout

---

### Test Flow 3.2: Track Multiple Time Entries
**Goal**: Create multiple time entries for different tasks

**Steps**:
1. **Track Time on Task 1** (Hourly - 500 PHP/hr)
   - Start timer: 10:00 AM
   - Stop timer: 11:30 AM (1.5 hours)
   - Calculated: ₱750
   - Notes: "Homepage design - initial draft"

2. **Track Time on Task 2** (Hourly - 600 PHP/hr - custom project rate)
   - Start timer: 2:00 PM
   - Stop timer: 4:00 PM (2 hours)
   - Calculated: ₱1,200
   - Notes: "API integration work"

3. **Track Time on Task 3** (Hourly - 450 PHP/hr - custom task rate)
   - Start timer: 4:30 PM
   - Stop timer: 5:30 PM (1 hour)
   - Calculated: ₱450
   - Notes: "Code review and testing"

4. **View All Entries**
   - Go to time tracking history
   - **Verify**:
     - 3 entries listed
     - Total pending earnings: ₱2,400
     - Each shows correct rate and calculation
     - All status: "Pending"

**Expected Results**:
- ✅ Multiple time entries tracked
- ✅ Different rates applied correctly
- ✅ Total earnings calculated
- ✅ All entries pending approval

---

### Test Flow 3.3: Fixed Budget Task Completion
**Goal**: Complete fixed budget task (no time tracking)

**Steps**:
1. **Navigate to Tasks**
   - Go to: `/adiutor/tasks`

2. **Select Fixed Budget Task**
   - Find task with "Fixed Budget" label
   - Example: "Logo Design - ₱3,000"

3. **Work on Task**
   - Upload deliverables
   - Add completion notes
   - Mark task as completed

4. **Verify Earnings**
   - Check that fixed amount (₱3,000) is added to earnings
   - Status: "Pending Approval"
   - No time tracking involved

**Expected Results**:
- ✅ Fixed budget added to earnings
- ✅ No time entries created
- ✅ Earnings pending approval

---

## 🎯 CATEGORY 4: EARNINGS DASHBOARD & MONITORING

### Test Flow 4.1: View Earnings Dashboard
**Goal**: Adiutor monitors all earnings

**Steps**:
1. **Login as Adiutor**

2. **Navigate to Earnings**
   - Click "My Earnings" in sidebar
   - URL: `/adiutor/earnings`

3. **Review Summary Cards**
   - **Total Earnings**: Sum of all earnings
   - **Approved**: Earnings approved by admin
   - **Paid Out**: Already withdrawn earnings
   - **Pending**: Awaiting approval

4. **View Earnings Table**
   - See all time entries and fixed tasks
   - Check columns:
     - Date
     - Project/Task
     - Hours (for hourly)
     - Rate
     - Amount
     - Status (Pending/Approved/Paid)

5. **Apply Filters**
   - Filter by date range (e.g., "This Month")
   - Filter by status (e.g., "Approved")
   - Filter by project

6. **View Breakdown**
   - Check earnings by project chart
   - View earnings by month chart

**Expected Results**:
- ✅ All earnings displayed correctly
- ✅ Summary cards show accurate totals
- ✅ Filters work properly
- ✅ Charts display data

---

### Test Flow 4.2: Check Eligible for Payout
**Goal**: Verify if earnings meet minimum payout threshold

**Steps**:
1. **On Earnings Dashboard**
   - Look for "Request Payout" button
   - Check if button is:
     - **Enabled**: If approved earnings ≥ minimum payout amount
     - **Disabled**: If below threshold

2. **View Payout Eligibility Alert**
   - If below threshold, see message:
     - "You need ₱X more to request payout"
     - "Minimum payout: ₱1,000"

3. **Check Approved Earnings**
   - Only approved earnings count toward payout
   - Pending earnings don't count yet

**Expected Results**:
- ✅ Payout button shows correct state
- ✅ Clear messaging about eligibility
- ✅ Only approved earnings considered

---

## 🎯 CATEGORY 5: ADMIN APPROVAL PROCESS

### Test Flow 5.1: Review and Approve Time Entries
**Goal**: Admin reviews and approves adiutor's time entries

**Steps**:
1. **Login as Admin**

2. **Navigate to Payout Management**
   - Click "Payouts" in admin sidebar
   - URL: `/admin/payouts`

3. **View Adiutor Earnings**
   - Click "Adiutor Earnings" tab
   - Or go to: `/admin/payouts/adiutor-earnings`

4. **Select Adiutor**
   - Choose adiutor from dropdown
   - View all their time entries

5. **Filter Pending Entries**
   - Filter status: "Pending Approval"
   - See list of unapproved time entries

6. **Review Time Entry**
   - Check task name and project
   - Verify hours worked
   - Review notes/description
   - Check calculated amount

7. **Approve Single Entry**
   - Click "Approve" button on one entry
   - Verify status changes to "Approved"
   - Check approved earnings total updates

8. **Bulk Approve Entries**
   - Select multiple entries (checkbox)
   - Click "Approve Selected" button
   - Confirm action
   - **Verify**: All selected entries approved

9. **Reject Entry (if needed)**
   - Click "Reject" on questionable entry
   - Add rejection reason: "Hours seem excessive for task"
   - Confirm rejection
   - **Verify**: 
     - Status changes to "Rejected"
     - Earnings not added to approved total
     - Adiutor receives notification

**Expected Results**:
- ✅ Time entries reviewed successfully
- ✅ Approved entries add to approved earnings
- ✅ Rejected entries don't count
- ✅ Bulk actions work correctly

---

### Test Flow 5.2: Approve Fixed Budget Task
**Goal**: Admin approves completed fixed budget task

**Steps**:
1. **Navigate to Tasks**
   - Go to: `/admin/tasks`

2. **Find Completed Fixed Budget Task**
   - Filter: Status = "Completed"
   - Find task marked as complete by adiutor

3. **Review Task Deliverables**
   - Check uploaded files
   - Review completion notes
   - Verify quality meets requirements

4. **Approve Task**
   - Click "Approve Task" button
   - Confirm approval
   - **Verify**:
     - Task status: "Approved"
     - Fixed budget added to approved earnings
     - Adiutor's approved total increases

**Expected Results**:
- ✅ Fixed budget task approved
- ✅ Full amount added to approved earnings
- ✅ Earnings available for payout

---

## 🎯 CATEGORY 6: PAYOUT REQUEST PROCESS

### Test Flow 6.1: Request Payout
**Goal**: Adiutor requests payout of approved earnings

**Steps**:
1. **Login as Adiutor**

2. **Navigate to Earnings Dashboard**
   - URL: `/adiutor/earnings`

3. **Check Eligibility**
   - Verify approved earnings ≥ minimum payout
   - Example: Approved = ₱2,400, Minimum = ₱1,000 ✅

4. **Click Request Payout**
   - Click "Request Payout" button
   - Redirects to: `/adiutor/earnings/request-payout`

5. **Select Date Range**
   - Choose start date: (e.g., Nov 1, 2025)
   - Choose end date: (e.g., Nov 14, 2025)
   - **Verify**: System shows eligible earnings in range

6. **Review Eligible Earnings**
   - See list of approved time entries in period
   - Check total amount to be paid out
   - Example breakdown:
     - Time Entry 1: ₱750
     - Time Entry 2: ₱1,200
     - Time Entry 3: ₱450
     - **Total**: ₱2,400

7. **Confirm Payment Method**
   - **Verify**: Pre-filled with saved preference (GCash)
   - Payment details shown: 09171234567
   - Option to change if needed

8. **Add Notes (Optional)**
   - Add message: "Please process by end of week"

9. **Preview Summary**
   - Review all details:
     - Total amount: ₱2,400
     - Number of items: 3
     - Payment method: GCash
     - Mobile number: 09171234567

10. **Submit Request**
    - Click "Submit Payout Request"
    - Verify success message
    - **Verify**:
      - Request created
      - Status: "Pending"
      - Earnings status changed to "In Payout"

11. **View Confirmation**
    - See payout reference number
    - Example: "PO-20251114-001"
    - Redirects to payout details page

**Expected Results**:
- ✅ Payout request created
- ✅ Correct amount calculated
- ✅ Payment method saved
- ✅ Status: Pending admin processing

---

### Test Flow 6.2: View Payout History
**Goal**: Track all payout requests and their status

**Steps**:
1. **Navigate to Payout History**
   - Click "Payouts" in adiutor menu
   - URL: `/adiutor/earnings/payouts`

2. **View All Payouts**
   - See list of all payout requests
   - Check columns:
     - Reference number
     - Date requested
     - Amount
     - Status badge (Pending/Processing/Completed/Cancelled)
     - Payment method

3. **Click on Payout**
   - Select recent payout request
   - View details page

4. **Review Payout Details**
   - Payout information:
     - Reference: PO-20251114-001
     - Amount: ₱2,400
     - Status: Pending
     - Requested: Nov 14, 2025
   - Payment method & details
   - Itemized breakdown (3 time entries)
   - Timeline of status changes

**Expected Results**:
- ✅ All payouts listed
- ✅ Status clearly visible
- ✅ Details accessible
- ✅ History tracked

---

## 🎯 CATEGORY 7: ADMIN PAYOUT PROCESSING

### Test Flow 7.1: Process Payout Request
**Goal**: Admin reviews and processes payout request

**Steps**:
1. **Login as Admin**

2. **Navigate to Payouts**
   - Click "Payouts" in admin menu
   - URL: `/admin/payouts`

3. **View Pending Payouts**
   - See statistics cards:
     - Pending requests
     - Processing
     - Completed this month
     - Total amount pending
   - Filter: Status = "Pending"

4. **Select Payout Request**
   - Click on payout (PO-20251114-001)
   - View details page: `/admin/payouts/{id}`

5. **Review Payout Details**
   - Check adiutor information
   - Verify payment method: GCash - 09171234567
   - Review itemized breakdown:
     - All time entries included
     - Total: ₱2,400
   - Check that all items are approved

6. **Mark as Processing**
   - Click "Process Payment" button
   - Add notes: "Processing via GCash transfer"
   - Confirm action
   - **Verify**: Status changes to "Processing"

7. **Complete Payment**
   - After making actual payment (external)
   - Click "Mark as Completed" button
   - Enter transaction details:
     - Transaction ID: GC123456789
     - Payment date: Nov 14, 2025
     - Upload proof (optional): screenshot.png
   - Add notes: "Paid via GCash, confirmed by adiutor"
   - Click "Confirm Completion"

8. **Verify Completion**
   - **Verify**:
     - Status: "Completed"
     - Payment date recorded
     - Transaction ID saved
     - Proof uploaded
     - Timeline updated

9. **Check Earnings Status**
   - Navigate to adiutor earnings
   - **Verify**: 
     - Time entries status changed to "Paid"
     - Approved earnings decreased by ₱2,400
     - Paid out earnings increased by ₱2,400

**Expected Results**:
- ✅ Payout processed successfully
- ✅ Payment proof uploaded
- ✅ Status tracked through workflow
- ✅ Earnings marked as paid

---

### Test Flow 7.2: Cancel Payout Request
**Goal**: Admin cancels a payout request (if needed)

**Steps**:
1. **Open Payout Details**
   - Select a pending payout request

2. **Click Cancel Payout**
   - Click "Cancel" button
   - Warning popup appears

3. **Provide Cancellation Reason**
   - Select reason: "Payment details incorrect"
   - Add notes: "Please update GCash number and resubmit"
   - Confirm cancellation

4. **Verify Cancellation**
   - **Verify**:
     - Status: "Cancelled"
     - Reason recorded
     - Earnings returned to "Approved" status
     - Adiutor can request payout again

5. **Check Adiutor View**
   - Login as adiutor
   - View payout history
   - See cancellation reason
   - Verify earnings are still approved and available

**Expected Results**:
- ✅ Payout cancelled successfully
- ✅ Reason communicated to adiutor
- ✅ Earnings remain available for new request
- ✅ Timeline tracked

---

### Test Flow 7.3: Export Payout Report
**Goal**: Admin exports payout data for records

**Steps**:
1. **Navigate to Payouts List**
   - URL: `/admin/payouts`

2. **Apply Filters**
   - Date range: Nov 1-30, 2025
   - Status: All or Completed
   - Adiutor: (optional filter)

3. **Click Export**
   - Click "Export to CSV" button
   - File downloads: payouts_20251114.csv

4. **Verify Export Contents**
   - Open CSV file
   - Check columns:
     - Payout reference
     - Adiutor name
     - Amount
     - Status
     - Payment method
     - Date requested
     - Date completed
     - Transaction ID

**Expected Results**:
- ✅ CSV exports correctly
- ✅ All filtered data included
- ✅ Proper formatting
- ✅ Useful for accounting

---

## 🎯 CATEGORY 8: EDGE CASES & VALIDATION

### Test Flow 8.1: Budget Cap Enforcement
**Goal**: Verify task budget cap prevents overspending

**Steps**:
1. **Create Task with Budget Cap**
   - Task: "Design Project"
   - Hourly rate: ₱500/hr
   - Budget cap: ₱2,000 (4 hours max)

2. **Track Time Beyond Cap**
   - Entry 1: 2 hours = ₱1,000
   - Entry 2: 2 hours = ₱1,000
   - Entry 3: 1 hour = ₱500
   - **Total**: 5 hours = ₱2,500 (exceeds cap)

3. **Check Warnings**
   - **Verify**: System shows warning at ₱2,000
   - Example: "Budget cap reached! Additional time may not be paid."
   - Admin sees budget exceeded flag

4. **Admin Review**
   - Admin reviews entries
   - Can approve up to cap (₱2,000)
   - Can approve excess with note
   - Or reject excess hours

**Expected Results**:
- ✅ Budget cap enforced
- ✅ Warnings displayed
- ✅ Admin controls approval
- ✅ Overage tracked

---

### Test Flow 8.2: Minimum Payout Not Met
**Goal**: Verify payout request blocked if below minimum

**Steps**:
1. **Check Low Balance**
   - Approved earnings: ₱800
   - Minimum payout: ₱1,000
   - Difference: ₱200 short

2. **Try to Request Payout**
   - **Verify**: "Request Payout" button is disabled
   - See message: "You need ₱200 more to request payout"

3. **Add More Earnings**
   - Complete more tasks
   - Get admin approval
   - Check when threshold is reached

4. **Retry Payout Request**
   - **Verify**: Button enables once minimum reached

**Expected Results**:
- ✅ Minimum enforced
- ✅ Clear messaging
- ✅ Button state correct
- ✅ Threshold tracked

---

### Test Flow 8.3: Rate Hierarchy Verification
**Goal**: Confirm correct rate is applied based on hierarchy

**Test Cases**:

**Case A: Only Standard Rate**
- Adiutor standard rate: ₱500/hr
- No project rate
- No task rate
- **Expected**: ₱500/hr

**Case B: Project Rate Override**
- Adiutor standard rate: ₱500/hr
- Project rate: ₱600/hr
- No task rate
- **Expected**: ₱600/hr

**Case C: Task Rate Override**
- Adiutor standard rate: ₱500/hr
- Project rate: ₱600/hr
- Task rate: ₱450/hr (special task)
- **Expected**: ₱450/hr

**Verification**:
- Create time entries for each case
- Check calculated amount matches expected rate
- Verify in earnings breakdown

**Expected Results**:
- ✅ Hierarchy: Task > Project > Standard
- ✅ Correct rate always applied
- ✅ Rate source shown in UI

---

## 🎯 CATEGORY 9: NOTIFICATIONS & ALERTS

### Test Flow 9.1: Notification Flow
**Goal**: Verify all stakeholders receive appropriate notifications

**Notification Scenarios**:

1. **Time Entry Submitted**
   - Adiutor submits time entry
   - Admin receives notification: "New time entry from [Adiutor]"

2. **Time Entry Approved**
   - Admin approves entry
   - Adiutor receives notification: "Your time entry has been approved - ₱750"

3. **Time Entry Rejected**
   - Admin rejects entry
   - Adiutor receives notification: "Time entry rejected - [Reason]"

4. **Payout Requested**
   - Adiutor requests payout
   - Admin receives notification: "New payout request - ₱2,400"

5. **Payout Processing**
   - Admin marks as processing
   - Adiutor receives notification: "Your payout is being processed"

6. **Payout Completed**
   - Admin marks as completed
   - Adiutor receives notification: "Payout completed - ₱2,400 sent via GCash"

7. **Payout Cancelled**
   - Admin cancels payout
   - Adiutor receives notification: "Payout cancelled - [Reason]"

**Verification Steps**:
- Check notification bell icon
- Verify email notifications (if configured)
- Check notification log

**Expected Results**:
- ✅ All notifications sent
- ✅ Timely delivery
- ✅ Clear messaging
- ✅ Actionable links

---

## 🎯 CATEGORY 10: COMPLETE END-TO-END TEST

### Test Flow 10.1: Full Lifecycle Test
**Goal**: Complete workflow from setup to payout completion

**Complete Scenario**:

1. **Day 1 - Setup** (30 mins)
   - Adiutor sets up earnings settings (₱500/hr, min ₱1,000)
   - Admin creates project and assigns adiutor (₱500/hr)
   - Admin creates 3 tasks:
     - Task A: Hourly (₱500/hr)
     - Task B: Hourly with custom rate (₱600/hr)
     - Task C: Fixed budget (₱2,000)

2. **Day 2-5 - Work & Track** (4 days)
   - Adiutor tracks time on Task A: 3 hours = ₱1,500
   - Adiutor tracks time on Task B: 2 hours = ₱1,200
   - Adiutor completes Task C: ₱2,000 (fixed)
   - **Total Pending**: ₱4,700

3. **Day 6 - Review & Approve** (1 hour)
   - Admin reviews all time entries
   - Admin approves Task A entries: ₱1,500
   - Admin approves Task B entries: ₱1,200
   - Admin approves Task C completion: ₱2,000
   - **Total Approved**: ₱4,700

4. **Day 7 - Request Payout** (15 mins)
   - Adiutor checks approved earnings: ₱4,700
   - Adiutor requests payout for all earnings
   - Payout reference: PO-20251107-001
   - Status: Pending

5. **Day 8 - Process Payment** (30 mins)
   - Admin reviews payout request
   - Admin marks as "Processing"
   - Admin transfers ₱4,700 via GCash
   - Admin marks as "Completed" with proof

6. **Day 9 - Verify & Confirm** (15 mins)
   - Adiutor receives notification
   - Adiutor checks payout history
   - Adiutor confirms payment received
   - Earnings dashboard updated:
     - Approved: ₱0
     - Paid: ₱4,700

**Expected Results**:
- ✅ Complete workflow successful
- ✅ All calculations correct
- ✅ Proper status transitions
- ✅ Notifications at each step
- ✅ Accurate reporting
- ✅ Payment completed

---

## 📋 TESTING CHECKLIST

Use this checklist to ensure complete testing coverage:

### Configuration
- [ ] Adiutor can set standard hourly rate
- [ ] Adiutor can configure payout settings
- [ ] Admin can assign adiutor with custom rate
- [ ] Rates auto-fill correctly

### Task & Assignment
- [ ] Create task with hourly payment
- [ ] Create task with fixed budget
- [ ] Create task with no payment
- [ ] Budget cap enforced
- [ ] Rate hierarchy works correctly

### Time Tracking
- [ ] Start/stop timer works
- [ ] Earnings calculated automatically
- [ ] Multiple entries tracked
- [ ] Different rates applied correctly

### Earnings Dashboard
- [ ] Summary cards show correct totals
- [ ] Filters work properly
- [ ] Charts display data
- [ ] Status badges correct

### Admin Approval
- [ ] Approve single time entry
- [ ] Bulk approve entries
- [ ] Reject entries with reason
- [ ] Approve fixed budget tasks

### Payout Request
- [ ] Request payout when eligible
- [ ] Date range selection works
- [ ] Payment method pre-filled
- [ ] Summary accurate

### Payout Processing
- [ ] Admin can mark as processing
- [ ] Admin can complete with proof
- [ ] Admin can cancel with reason
- [ ] Export report works

### Validations
- [ ] Minimum payout enforced
- [ ] Budget cap warnings shown
- [ ] Rate hierarchy validated
- [ ] Status transitions correct

### Notifications
- [ ] Time entry notifications
- [ ] Approval notifications
- [ ] Payout notifications
- [ ] Email notifications (if enabled)

### Reporting
- [ ] Earnings reports accurate
- [ ] Payout history complete
- [ ] Export functionality works
- [ ] Charts and graphs correct

---

## 🐛 COMMON ISSUES & TROUBLESHOOTING

### Issue 1: Rate Not Auto-Filling
**Problem**: Rate doesn't auto-fill when selecting adiutor  
**Check**:
- Adiutor has set standard rate in profile
- JavaScript running properly
- API endpoint responding
- Browser console for errors

### Issue 2: Earnings Not Calculating
**Problem**: Time entry saved but no earnings amount  
**Check**:
- Task has hourly rate set
- Time entry duration recorded
- Database trigger working
- Check `calculated_amount` column

### Issue 3: Payout Button Disabled
**Problem**: Can't request payout even with approved earnings  
**Check**:
- Approved earnings ≥ minimum payout
- Not pending another payout
- Payment method configured
- Browser console for JavaScript errors

### Issue 4: Budget Cap Not Warning
**Problem**: No warning when exceeding budget cap  
**Check**:
- Budget cap is set on task
- `allocated_budget` column has value
- Frontend validation running
- Check task details page

---

## 📝 TEST RESULT TEMPLATE

Use this template to document test results:

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

- [ ] All test flows completed
- [ ] All checklist items verified
- [ ] No critical issues found
- [ ] Documentation reviewed
- [ ] Training materials prepared

**Tested By**: _______________ **Date**: _______________  
**Approved By**: _______________ **Date**: _______________  

---

**END OF TESTING GUIDE**

Ready for production deployment! 🚀

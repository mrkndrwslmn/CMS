# Adiutor Earnings & Payout System Documentation

## Overview
This comprehensive earnings and payout system enables adiutors to track their earnings through time tracking and manage payout requests, while admins can process payments off-system.

## System Architecture

### 1. **Rate Hierarchy**
The system uses a 3-tier hourly rate system with priority-based selection:

```
Priority 1: Task-specific hourly rate (task.hourly_rate)
    ↓
Priority 2: Project assignment rate (project_assignments.hourly_rate)
    ↓
Priority 3: Adiutor standard rate (adiutor_profiles.standard_hourly_rate)
```

**How it works:**
- When a task is created, admin can set a specific hourly rate
- If not set, uses the rate from project assignment
- If project assignment has no rate, uses adiutor's standard rate
- This allows flexibility: standard rates, project-specific rates, or task-specific rates

### 2. **Two Earning Models**

#### A. **Time-Based Earnings** (Hourly Tracking)
- **When to use**: Tasks requiring detailed time tracking
- **How it works**:
  - Task has `requires_time_tracking = true`
  - Adiutor logs time entries for the task
  - Each time entry calculates: `earnings = (duration_minutes / 60) * hourly_rate`
  - Total task earnings = sum of all approved time entries
  - **Formula**: `Earnings = Hours × Hourly Rate`

#### B. **Fixed Budget Earnings**
- **When to use**: Tasks with predetermined payment regardless of time spent
- **How it works**:
  - Task has `use_fixed_budget = true`
  - Task has `allocated_budget` set (e.g., ₱5,000)
  - When task is completed, adiutor earns the full allocated budget
  - No time tracking required
  - **Formula**: `Earnings = Allocated Budget`

### 3. **Flexible Task Configuration**

Admins have 3 options when creating tasks:

**Option 1: Hourly with Time Tracking**
```php
requires_time_tracking = true
use_fixed_budget = false
hourly_rate = 500 (optional, can use adiutor's standard rate)
allocated_budget = null (or set as maximum budget cap)
```
- Adiutor must track time
- Pays per hour worked
- Good for: development, design, consulting

**Option 2: Fixed Budget (No Time Tracking)**
```php
requires_time_tracking = false
use_fixed_budget = true
hourly_rate = null
allocated_budget = 5000
```
- Adiutor gets fixed amount when task completes
- No time tracking needed
- Good for: deliverables, milestones, fixed-scope work

**Option 3: Optional Time Tracking**
```php
requires_time_tracking = false
use_fixed_budget = false
hourly_rate = 500
allocated_budget = 10000 (as budget cap)
```
- Time tracking available but not required
- Can use for estimation vs actual tracking
- Admin decides payment based on work

---

## Database Schema

### New Tables

#### `payouts`
Tracks payout requests and payments
```sql
- id
- payout_number (unique, e.g., PAYOUT-2025-001)
- adiutor_id (who receives payment)
- processed_by (admin who processed)
- amount (total payout amount)
- currency (PHP, USD, etc.)
- status (pending, processing, completed, cancelled, failed)
- payout_method (bank_transfer, paypal, gcash, paymaya, etc.)
- period_start, period_end (earning period covered)
- payout_details (JSON: account info)
- notes (admin notes)
- adiutor_notes (adiutor's instructions)
- reference_number (bank ref, transaction ID)
- proof_of_payment (file path)
- requested_at, processed_at, completed_at
```

#### `payout_items`
Detailed breakdown of what's included in each payout
```sql
- id
- payout_id
- time_entry_id (link to specific time entry)
- task_id, project_id (reference)
- item_type (time_entry, fixed_task, bonus, adjustment)
- description
- amount
- hours (for time entries)
- rate (for time entries)
```

### Modified Tables

#### `adiutor_profiles`
```sql
+ standard_hourly_rate (their default rate)
+ currency (PHP, USD, etc.)
+ minimum_payout_amount (minimum for withdrawal, default: 500)
+ preferred_payout_method
+ payout_details (JSON: bank account, PayPal email, etc.)
```

#### `project_assignments`
```sql
+ hourly_rate (rate for this project assignment)
+ requires_time_tracking (whether tracking is required)
+ total_earnings (calculated sum)
+ total_hours_worked (calculated sum)
```

#### `tasks`
```sql
+ hourly_rate (task-specific rate override)
+ requires_time_tracking (is tracking required?)
+ total_hours_tracked (sum from time entries)
+ calculated_earnings (sum from time entries)
+ use_fixed_budget (use fixed budget instead of hourly?)
```

#### `time_entries`
```sql
+ calculated_amount (duration × hourly_rate)
+ is_paid (has been paid in a payout)
+ payout_id (which payout includes this)
```

---

## User Workflows

### Adiutor Workflow

#### 1. **Set Standard Rate** (One-time Setup)
Navigate to: `Profile Settings > Earnings & Payout`
- Set standard hourly rate (e.g., ₱500/hour)
- Set payout preferences:
  - Preferred payment method (bank transfer, GCash, PayPal, etc.)
  - Bank account details / Payment account info
  - Minimum payout amount (default ₱500)

#### 2. **View Assigned Task with Rate**
When viewing a task:
- See if time tracking is required
- See hourly rate for the task
- See if it's fixed budget or hourly

#### 3. **Track Time (for hourly tasks)**
```
Navigate to: Time Tracking
→ Select Task
→ Start Timer
→ Work on task
→ Stop Timer
→ System automatically calculates earnings based on rate
```

#### 4. **Monitor Earnings**
Navigate to: `Earnings Dashboard`

View:
- **Total Earnings**: All-time earnings
- **Approved & Unpaid**: Ready to request payout
- **Paid**: Already received
- **Pending Approval**: Waiting for admin approval

Breakdown by:
- Project
- Task
- Time period (week, month, all time)

#### 5. **Request Payout**
When ready to withdraw earnings:
```
Navigate to: Earnings > Request Payout
→ Select period (date range)
→ System shows approved unpaid earnings for that period
→ Confirm payout method & account details
→ Add any notes/instructions
→ Submit request
```

**Requirements:**
- Minimum amount met (default ₱500)
- Only approved time entries included
- Time entries must not be already paid

#### 6. **Track Payout Status**
Navigate to: `Earnings > Payout History`

Statuses:
- **Pending**: Submitted, waiting for admin review
- **Processing**: Admin is processing payment
- **Completed**: Payment sent (see reference number & proof)
- **Cancelled**: Payout cancelled (see reason)

### Admin Workflow

#### 1. **Assign Adiutor to Project**
When assigning adiutor:
```
Form shows:
- Adiutor's standard rate: ₱500/hr (auto-filled)
- Project hourly rate: [Input or use standard]
- Requires time tracking: [Yes/No]
```

**Auto-fill behavior:**
- System automatically fills the rate input with adiutor's standard rate
- Admin can override if needed for this project
- If left empty, system uses adiutor's standard rate

#### 2. **Create Task with Payment Config**
When creating task:
```
Payment Configuration:
○ Hourly with Time Tracking
  - Hourly Rate: ₱500 (from project assignment)
  - Budget Cap: ₱10,000 (optional max)
  - ☑ Require time tracking
  
○ Fixed Budget
  - Fixed Amount: ₱5,000
  - ☐ No time tracking needed
  
○ No Payment (volunteer/internal)
```

#### 3. **Review & Approve Time Entries**
Navigate to: `Admin > Payouts > Adiutor Earnings`

For each adiutor:
- View all time entries (approved, pending, paid)
- Review pending time entries
- Approve/reject with notes
- Bulk approve multiple entries

**Approval triggers:**
- Email notification to adiutor
- Earnings become eligible for payout
- Task earnings updated

#### 4. **Process Payout Requests**
Navigate to: `Admin > Payouts`

View pending payout requests with:
- Adiutor name
- Amount
- Period covered
- Payment method
- Account details

**Process payout:**
```
1. Review payout details & breakdown
2. Mark as "Processing"
3. Make payment off-system (bank transfer, PayPal, etc.)
4. Complete payout:
   - Enter reference number
   - Upload proof of payment (optional)
   - Add admin notes
5. System marks all included time entries as "paid"
```

#### 5. **View Earnings Reports**
Navigate to: `Admin > Payouts > Reports`

View:
- Total payouts by month
- Payouts by adiutor
- Average hourly rates
- Export to CSV

---

## API / Controller Methods

### Adiutor Controllers

#### `EarningsController`
- `index()` - Earnings dashboard with filters
- `payouts()` - Payout history
- `showPayout($id)` - Payout details
- `showRequestForm()` - Show payout request form
- `requestPayout(Request)` - Submit payout request

#### `TimeTrackingController` (Enhanced)
- Time entries now automatically calculate earnings
- Stores hourly rate with each entry
- Calculates amount when stopping timer

### Admin Controllers

#### `PayoutManagementController`
- `index()` - List all payouts with filters
- `show($id)` - Payout details
- `markAsProcessing($id)` - Start processing
- `complete(Request, $id)` - Complete payout
- `cancel(Request, $id)` - Cancel payout
- `adiutorEarnings($adiutorId)` - View adiutor's earnings
- `approveTimeEntries(Request)` - Bulk approve time entries
- `export(Request)` - Export payouts to CSV

#### `ProjectManagementController` (Enhanced)
- When assigning adiutor, form shows standard rate
- Can set project-specific hourly rate

#### `TaskManagementController` (Enhanced)
- Task creation includes payment configuration
- Auto-calculates earnings for completed tasks

---

## Key Features & Business Logic

### 1. **Automatic Earnings Calculation**
- When timer stops: `earnings = (duration / 60) * hourly_rate`
- Stored in `time_entries.calculated_amount`
- Task totals updated automatically

### 2. **Approval Workflow**
```
Time Entry Created (by adiutor)
    ↓
is_approved = false
    ↓
Admin Reviews & Approves
    ↓
is_approved = true
    ↓
Eligible for Payout
    ↓
Included in Payout Request
    ↓
Admin Processes Payment
    ↓
is_paid = true
```

### 3. **Payout Request Validation**
- Only approved, unpaid time entries
- Meets minimum payout amount
- Valid payment method configured
- No duplicate entries in multiple payouts

### 4. **Rate Precedence System**
```php
public function getEffectiveHourlyRate() {
    // 1. Check task-specific rate
    if ($this->hourly_rate) return $this->hourly_rate;
    
    // 2. Check project assignment rate
    $assignment = ProjectAssignment::find(...);
    if ($assignment->hourly_rate) return $assignment->hourly_rate;
    
    // 3. Check adiutor standard rate
    $profile = AdiutorProfile::find(...);
    if ($profile->standard_hourly_rate) return $profile->standard_hourly_rate;
    
    return null; // No rate set
}
```

### 5. **Fixed Budget vs Hourly**
```php
// Fixed budget task
Task::create([
    'allocated_budget' => 5000,
    'use_fixed_budget' => true,
    'requires_time_tracking' => false
]);
// Earns ₱5,000 when completed

// Hourly task
Task::create([
    'hourly_rate' => 500,
    'use_fixed_budget' => false,
    'requires_time_tracking' => true
]);
// Earns based on tracked time
```

---

## Frontend Implementation (Views Needed)

### Adiutor Views

1. **`adiutor/profile/earnings-settings.blade.php`**
   - Set standard hourly rate
   - Configure payout preferences
   - Add bank account / payment details

2. **`adiutor/earnings/index.blade.php`**
   - Earnings dashboard
   - Summary cards (total, approved, paid, pending)
   - Filters (period, status)
   - Time entries table with earnings
   - Earnings by project chart

3. **`adiutor/earnings/request-payout.blade.php`**
   - Payout request form
   - Period selector
   - Shows unpaid earnings in period
   - Payment method & account confirmation
   - Preview before submit

4. **`adiutor/earnings/payouts.blade.php`**
   - Payout history list
   - Status badges
   - Amount, date, method

5. **`adiutor/earnings/payout-detail.blade.php`**
   - Detailed breakdown
   - All time entries included
   - Reference number & proof
   - Timeline of status changes

### Admin Views

1. **`admin/payouts/index.blade.php`**
   - All payouts list with filters
   - Status filter (pending, processing, completed)
   - Adiutor filter
   - Date range filter
   - Statistics cards
   - Quick actions

2. **`admin/payouts/show.blade.php`**
   - Payout details
   - Adiutor info & payment details
   - Breakdown of earnings (table)
   - Actions: Process, Complete, Cancel
   - Timeline/Activity log

3. **`admin/payouts/adiutor-earnings.blade.php`**
   - Specific adiutor earnings overview
   - All time entries
   - Approve/reject time entries
   - Payout history
   - Earnings charts

4. **`admin/projects/assign-adiutor-modal.blade.php`** (Enhanced)
   - Shows adiutor's standard rate
   - Auto-fills project rate
   - Time tracking toggle

5. **`admin/tasks/create-modal.blade.php`** (Enhanced)
   - Payment configuration section
   - Radio: Hourly / Fixed Budget / No Payment
   - Conditional fields based on selection

---

## Integration Points

### 1. **Project Assignment Enhancement**
In `admin/projects/show.blade.php` - Assign Adiutor Modal:
```html
<div class="form-group">
    <label>Hourly Rate</label>
    <input type="number" name="hourly_rate" 
           id="hourly_rate" 
           value="{{ old('hourly_rate') }}"
           placeholder="Adiutor's standard rate will be used if empty"
           step="0.01">
    <small>Standard Rate: <span id="adiutor-standard-rate">₱0.00/hr</span></small>
</div>

<div class="form-check">
    <input type="checkbox" name="requires_time_tracking" value="1">
    <label>Require time tracking for this project</label>
</div>

<script>
// Auto-fill standard rate when adiutor selected
$('#adiutor_id').on('change', function() {
    let adiutorId = $(this).val();
    // AJAX call to get adiutor standard rate
    $.get(`/api/adiutor/${adiutorId}/standard-rate`, function(data) {
        $('#adiutor-standard-rate').text(`₱${data.rate}/hr`);
        if (!$('#hourly_rate').val()) {
            $('#hourly_rate').val(data.rate);
        }
    });
});
</script>
```

### 2. **Task Creation Enhancement**
In `admin/tasks/create.blade.php`:
```html
<div class="payment-config">
    <h4>Payment Configuration</h4>
    
    <div class="radio-group">
        <input type="radio" name="payment_type" value="hourly" checked>
        <label>Hourly with Time Tracking</label>
        
        <input type="radio" name="payment_type" value="fixed">
        <label>Fixed Budget</label>
        
        <input type="radio" name="payment_type" value="none">
        <label>No Payment</label>
    </div>
    
    <div id="hourly-fields" class="payment-fields">
        <input type="number" name="hourly_rate" placeholder="Hourly Rate (optional)">
        <input type="number" name="budget_cap" placeholder="Maximum Budget (optional)">
        <input type="checkbox" name="requires_time_tracking" checked> Require time tracking
    </div>
    
    <div id="fixed-fields" class="payment-fields" style="display:none;">
        <input type="number" name="allocated_budget" placeholder="Fixed Budget Amount">
    </div>
</div>
```

### 3. **Time Tracking Enhancement**
Existing `adiutor/time-tracking/index.blade.php` already works!
Just needs to display:
- Hourly rate for the task
- Calculated earnings when timer stops
- "Earnings: ₱XXX.XX" badge

### 4. **Navigation Menu Updates**

**Adiutor Menu:**
```html
<a href="{{ route('adiutor.earnings.index') }}">
    <i class="fas fa-money-bill-wave"></i> Earnings
</a>
```

**Admin Menu:**
```html
<a href="{{ route('admin.payouts.index') }}">
    <i class="fas fa-hand-holding-usd"></i> Payouts
    @if($pendingPayoutsCount > 0)
        <span class="badge">{{ $pendingPayoutsCount }}</span>
    @endif
</a>
```

---

## Configuration & Settings

### `.env` Configuration
```env
# Payout Settings
PAYOUT_MINIMUM_AMOUNT=500.00
PAYOUT_DEFAULT_CURRENCY=PHP
PAYOUT_AUTO_APPROVE_TIME_ENTRIES=false
```

### `config/payout.php` (New)
```php
return [
    'minimum_amount' => env('PAYOUT_MINIMUM_AMOUNT', 500.00),
    'default_currency' => env('PAYOUT_DEFAULT_CURRENCY', 'PHP'),
    'auto_approve_time_entries' => env('PAYOUT_AUTO_APPROVE_TIME_ENTRIES', false),
    
    'payout_methods' => [
        'bank_transfer' => 'Bank Transfer',
        'paypal' => 'PayPal',
        'gcash' => 'GCash',
        'paymaya' => 'PayMaya',
        'cash' => 'Cash',
        'check' => 'Check',
        'other' => 'Other',
    ],
];
```

---

## Routes to Add

### Web Routes (`routes/web.php`)

```php
// Adiutor Routes
Route::middleware(['auth', 'role:adiutor'])->group(function () {
    Route::prefix('adiutor')->name('adiutor.')->group(function () {
        
        // Earnings & Payouts
        Route::prefix('earnings')->name('earnings.')->group(function () {
            Route::get('/', [EarningsController::class, 'index'])->name('index');
            Route::get('/request-payout', [EarningsController::class, 'showRequestForm'])->name('request-form');
            Route::post('/request-payout', [EarningsController::class, 'requestPayout'])->name('request');
            Route::get('/payouts', [EarningsController::class, 'payouts'])->name('payouts');
            Route::get('/payouts/{id}', [EarningsController::class, 'showPayout'])->name('payout.show');
        });
    });
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // Payout Management
        Route::prefix('payouts')->name('payouts.')->group(function () {
            Route::get('/', [PayoutManagementController::class, 'index'])->name('index');
            Route::get('/{id}', [PayoutManagementController::class, 'show'])->name('show');
            Route::post('/{id}/process', [PayoutManagementController::class, 'markAsProcessing'])->name('process');
            Route::post('/{id}/complete', [PayoutManagementController::class, 'complete'])->name('complete');
            Route::post('/{id}/cancel', [PayoutManagementController::class, 'cancel'])->name('cancel');
            Route::get('/adiutor/{adiutorId}', [PayoutManagementController::class, 'adiutorEarnings'])->name('adiutor-earnings');
            Route::post('/approve-time-entries', [PayoutManagementController::class, 'approveTimeEntries'])->name('approve-entries');
            Route::get('/export', [PayoutManagementController::class, 'export'])->name('export');
        });
    });
});

// API Routes (for AJAX)
Route::middleware(['auth'])->group(function () {
    Route::get('/api/adiutor/{id}/standard-rate', function($id) {
        $adiutor = User::with('adiutorProfile')->findOrFail($id);
        return response()->json([
            'rate' => $adiutor->adiutorProfile->standard_hourly_rate ?? 0
        ]);
    });
});
```

---

## Testing Checklist

### Adiutor Tests
- [ ] Set standard hourly rate
- [ ] View rate when assigned to project
- [ ] Start/stop timer with automatic earnings calculation
- [ ] View earnings dashboard with correct totals
- [ ] Filter earnings by period
- [ ] Request payout with minimum amount validation
- [ ] View payout history and status
- [ ] View payout details and breakdown

### Admin Tests
- [ ] Assign adiutor with auto-filled rate
- [ ] Override rate for specific project
- [ ] Create task with hourly payment
- [ ] Create task with fixed budget
- [ ] Approve time entries
- [ ] View pending payout requests
- [ ] Process payout with reference number
- [ ] Upload proof of payment
- [ ] Cancel payout with reason
- [ ] View adiutor earnings summary
- [ ] Export payouts to CSV

---

## Best Practices & Recommendations

### 1. **Rate Management**
- Always set adiutor standard rates during onboarding
- Review and update rates periodically (annually)
- Document rate changes in notes

### 2. **Time Entry Approval**
- Review and approve time entries weekly
- Add notes if rejecting entries
- Bulk approve similar entries

### 3. **Payout Processing**
- Process payouts within 3-5 business days
- Always upload proof of payment
- Keep reference numbers organized

### 4. **Task Configuration**
- Use hourly for ongoing work (development, support)
- Use fixed budget for deliverables (designs, documents)
- Set budget caps for hourly tasks to control costs

### 5. **Communication**
- Notify adiutors when time entries are approved
- Notify adiutors when payouts are completed
- Provide clear payment timelines

---

## Migration & Rollout Plan

### Phase 1: Database Setup
1. Run migrations
2. Seed existing adiutor profiles with default values
3. Verify data integrity

### Phase 2: Update Existing Records
```php
// Artisan command to set defaults
php artisan earnings:setup-existing-data
```

### Phase 3: UI Implementation
1. Adiutor earnings dashboard
2. Payout request form
3. Admin payout management
4. Task/project assignment updates

### Phase 4: Testing
1. Test with select adiutors
2. Process test payouts
3. Gather feedback

### Phase 5: Full Rollout
1. Train admins on payout processing
2. Train adiutors on earnings tracking
3. Monitor first month closely
4. Iterate based on feedback

---

## Support & Maintenance

### Common Issues

**Issue: Time entries not calculating earnings**
- Check if hourly rate is set (task/project/profile)
- Verify time entry has end_time
- Check duration_minutes is not null

**Issue: Cannot request payout**
- Verify minimum amount is met
- Check time entries are approved
- Ensure payment method is configured

**Issue: Earnings don't match expectations**
- Review which hourly rate was used
- Check approval status of time entries
- Verify fixed budget vs hourly configuration

---

## Future Enhancements (v2.0)

1. **Automatic Payouts**
   - Scheduled automatic payouts (monthly)
   - Direct integration with payment gateways

2. **Tax Handling**
   - Withholding tax calculations
   - Tax documents generation

3. **Invoicing**
   - Auto-generate invoices for payouts
   - PDF export

4. **Analytics**
   - Earnings trends
   - Rate comparison
   - Project profitability

5. **Multi-Currency**
   - Support for international adiutors
   - Currency conversion

6. **Mobile App**
   - Time tracking on mobile
   - Push notifications for approvals/payouts

---

This system provides a complete, flexible solution for managing adiutor earnings and payouts while maintaining admin control and transparency for all parties involved.

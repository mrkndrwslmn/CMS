# Milestone Management System Documentation

**Feature:** Three Payment Types Support  
**Created:** October 22, 2025  
**Status:** Implementation Complete - Backend

---

## Overview

The Milestone Management System extends the CMS to support three different payment models for projects:

1. **Full Payment** - Client pays entire project amount upfront
2. **Milestone Payment** - Client pays per phase/milestone
3. **Downpayment** - Client pays initial downpayment, then remaining balance

This system controls access to tasks and documents based on payment status.

---

## Database Schema

### New Tables

#### 1. **project_milestones**
Stores phases/milestones for projects using milestone-based payments.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Milestone ID |
| `project_id` | BIGINT UNSIGNED | FOREIGN KEY (projects) | Parent project |
| `phase_name` | VARCHAR(255) | NOT NULL | Phase name (e.g., "Phase 1: Design") |
| `phase_description` | TEXT | NULLABLE | Phase description |
| `phase_order` | INTEGER | DEFAULT 1 | Sequence number (1, 2, 3...) |
| `percentage` | DECIMAL(5,2) | NOT NULL | Percentage of total budget (0-100) |
| `amount` | DECIMAL(10,2) | NOT NULL | Calculated amount for this phase |
| `start_date` | DATE | NULLABLE | Phase start date |
| `due_date` | DATE | NULLABLE | Phase due date |
| `completed_date` | DATE | NULLABLE | Phase completion date |
| `status` | ENUM | DEFAULT 'pending' | 'pending', 'in_progress', 'completed', 'paid' |
| `is_paid` | BOOLEAN | DEFAULT false | Payment status |
| `paid_at` | TIMESTAMP | NULLABLE | Payment timestamp |
| `notes` | TEXT | NULLABLE | Phase notes |
| `deliverables` | JSON | NULLABLE | Expected deliverables list |
| `created_at` | TIMESTAMP | AUTO | Record creation |
| `updated_at` | TIMESTAMP | AUTO | Record update |

**Indexes:**
- `project_id`, `phase_order` (composite)
- `project_id`, `is_paid` (composite)
- `status`

**Relationships:**
- Belongs to: project
- Has many: tasks (via phase_id)
- Has one: milestone_payment
- Has many: payments

---

#### 2. **milestone_payments**
Tracks payment records for each milestone/phase.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Record ID |
| `milestone_id` | BIGINT UNSIGNED | FOREIGN KEY (project_milestones) | Parent milestone |
| `payment_id` | BIGINT UNSIGNED | FOREIGN KEY (payments), NULLABLE | Actual payment record |
| `service_request_id` | BIGINT UNSIGNED | FOREIGN KEY (service_requests) | Source request |
| `client_id` | BIGINT UNSIGNED | FOREIGN KEY (users) | Client |
| `amount_due` | DECIMAL(10,2) | NOT NULL | Amount due for this milestone |
| `amount_paid` | DECIMAL(10,2) | DEFAULT 0.00 | Amount paid |
| `status` | ENUM | DEFAULT 'pending' | 'pending', 'partial', 'paid', 'overdue', 'cancelled' |
| `due_date` | DATE | NULLABLE | Payment due date |
| `paid_at` | TIMESTAMP | NULLABLE | Payment timestamp |
| `notes` | TEXT | NULLABLE | Payment notes |
| `confirmed_by` | BIGINT UNSIGNED | FOREIGN KEY (users), NULLABLE | Admin who confirmed |
| `created_at` | TIMESTAMP | AUTO | Record creation |
| `updated_at` | TIMESTAMP | AUTO | Record update |

**Indexes:**
- `milestone_id`, `status` (composite)
- `service_request_id`, `status` (composite)
- `status`

**Relationships:**
- Belongs to: milestone, payment, service_request, client, confirmed_by (user)

---

### Modified Tables

#### 3. **service_requests** (New Columns)

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `payment_type` | ENUM | NULLABLE | 'full_payment', 'milestone_payment', 'downpayment' |
| `downpayment_percentage` | DECIMAL(5,2) | NULLABLE | Downpayment % (0-100) |
| `downpayment_amount` | DECIMAL(10,2) | NULLABLE | Calculated downpayment amount |
| `remaining_balance` | DECIMAL(10,2) | NULLABLE | Remaining balance after downpayment |
| `downpayment_paid` | BOOLEAN | DEFAULT false | Downpayment status |
| `downpayment_paid_at` | TIMESTAMP | NULLABLE | Downpayment timestamp |
| `remaining_balance_paid` | BOOLEAN | DEFAULT false | Balance payment status |
| `remaining_balance_paid_at` | TIMESTAMP | NULLABLE | Balance payment timestamp |
| `total_milestones` | INTEGER | NULLABLE | Number of milestones |

**New Index:**
- `payment_type`

**New Relationships:**
- Has many: milestone_payments

---

#### 4. **tasks** (New Columns)

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `phase_id` | BIGINT UNSIGNED | FOREIGN KEY (project_milestones), NULLABLE | Associated milestone phase |

**New Indexes:**
- `project_id`, `phase_id` (composite)
- `phase_id`, `status` (composite)

**New Relationships:**
- Belongs to: phase (ProjectMilestone)

---

#### 5. **payments** (New Columns)

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `milestone_id` | BIGINT UNSIGNED | FOREIGN KEY (project_milestones), NULLABLE | Associated milestone |
| `payment_type` | ENUM | NULLABLE | 'full_payment', 'milestone_payment', 'downpayment', 'remaining_balance' |

**New Indexes:**
- `milestone_id`, `status` (composite)
- `payment_type`, `status` (composite)
- `service_request_id`, `payment_type` (composite)

**New Relationships:**
- Belongs to: milestone (ProjectMilestone)

---

## Models

### New Models

#### **ProjectMilestone**
`App\Models\ProjectMilestone`

**Key Methods:**
- `isPaid()` - Check if milestone is paid
- `isAccessible()` - Check if milestone content is accessible
- `markAsPaid()` - Mark milestone as paid
- `calculateAmount()` - Calculate amount based on percentage
- `isCurrent()` - Check if this is the active phase
- `nextMilestone()` - Get next phase
- `previousMilestone()` - Get previous phase
- `arePreviousMilestonesPaid()` - Verify payment sequence
- `getTaskCompletionPercentage()` - Get phase progress

**Scopes:**
- `paid()` - Get paid milestones
- `unpaid()` - Get unpaid milestones
- `ordered()` - Order by phase_order
- `byStatus($status)` - Filter by status

---

#### **MilestonePayment**
`App\Models\MilestonePayment`

**Key Methods:**
- `isPaid()` - Check payment status
- `isOverdue()` - Check if overdue
- `getRemainingBalance()` - Get unpaid amount
- `markAsPaid($paymentId, $confirmedBy)` - Record payment
- `recordPartialPayment($amount)` - Record partial payment
- `checkAndUpdateOverdueStatus()` - Update overdue status

**Scopes:**
- `paid()` - Get paid records
- `pending()` - Get pending records
- `overdue()` - Get overdue records
- `forClient($clientId)` - Filter by client

---

### Updated Models

#### **ServiceRequest**
New methods:
- `isFullPayment()` - Check if full payment type
- `isMilestonePayment()` - Check if milestone payment type
- `isDownpayment()` - Check if downpayment type
- `isDownpaymentPaid()` - Check downpayment status
- `isRemainingBalancePaid()` - Check remaining balance status
- `calculateDownpaymentAmount()` - Calculate downpayment
- `calculateRemainingBalance()` - Calculate remaining balance
- `getPaymentTypeLabel()` - Get human-readable type
- `hasInitialPayment()` - Check if initial payment made

---

#### **Project**
New relationships:
- `milestones()` - Get all milestones
- `orderedMilestones()` - Get milestones in order
- `currentMilestone()` - Get active milestone
- `paidMilestones()` - Get paid milestones
- `unpaidMilestones()` - Get unpaid milestones

---

#### **Task**
New methods:
- `isAccessibleToClient()` - Check task accessibility
- `areDocumentsAccessible()` - Check document accessibility
- `getLockStatus()` - Get lock status for UI

New relationship:
- `phase()` - Get associated milestone

---

#### **Document**
New methods:
- `isAccessibleToClient()` - Check document accessibility
- `getLockStatus()` - Get lock status for UI

---

#### **Payment**
New methods:
- `isFullPayment()` - Check type
- `isMilestonePayment()` - Check type
- `isDownpayment()` - Check type
- `isRemainingBalance()` - Check type
- `getPaymentTypeLabel()` - Get label

New scopes:
- `milestonePayments()` - Filter milestone payments
- `downpayments()` - Filter downpayments
- `fullPayments()` - Filter full payments

---

## Service Class

### **MilestoneService**
`App\Services\MilestoneService`

**Key Methods:**

#### Project Setup
- `createMilestones(Project $project, array $phases)` - Create all milestones for a project
- `getMilestoneSummary(Project $project)` - Get complete milestone info

#### Payment Processing
- `processMilestonePayment(ProjectMilestone $milestone, Payment $payment, $confirmedBy)` - Process milestone payment
- `processDownpayment(ServiceRequest $request, Payment $payment)` - Process downpayment
- `processRemainingBalance(ServiceRequest $request, Payment $payment)` - Process remaining balance

#### Status Management
- `updateMilestoneStatus(ProjectMilestone $milestone, string $status)` - Update phase status
- `getNextPayableMilestone(Project $project)` - Get next unpaid milestone

#### Calculations
- `getTotalPaidAmount(Project $project)` - Calculate paid amount
- `getTotalRemainingAmount(Project $project)` - Calculate remaining
- `getPaymentProgress(Project $project)` - Get progress percentage

#### Accessibility
- `isTaskAccessible(Task $task)` - Check task access
- `isDocumentAccessible(Document $document)` - Check document access

---

## Payment Type Workflows

### 1. Full Payment Workflow

```
1. Client submits service request
2. Admin approves with payment_type='full_payment'
3. Client pays full approved_budget
4. Payment confirmed → status='paid'
5. Request → Project
6. ALL tasks and documents are accessible immediately
```

**Access Rules:**
- ✅ All tasks accessible once payment confirmed
- ✅ All documents accessible once payment confirmed

---

### 2. Milestone Payment Workflow

```
1. Client submits service request
2. Admin approves with payment_type='milestone_payment'
3. Admin defines phases (Phase 1: 30%, Phase 2: 40%, Phase 3: 30%)
4. System creates ProjectMilestones and MilestonePayments
5. Client MUST pay Phase 1 to start
6. Payment confirmed → Phase 1 marked as paid
7. Request → Project
8. Admin assigns tasks to phases
9. Client can only access Phase 1 tasks/documents
10. When Phase 1 work completed, client can pay Phase 2
11. Process repeats for each phase
```

**Access Rules:**
- ✅ Tasks in paid phases are accessible
- ❌ Tasks in unpaid phases are LOCKED
- ✅ Documents from paid phase tasks are accessible
- ❌ Documents from unpaid phase tasks are LOCKED
- ℹ️ Phase 1 payment is REQUIRED to start project

---

### 3. Downpayment Workflow

```
1. Client submits service request
2. Admin approves with payment_type='downpayment'
3. Admin sets downpayment_percentage (e.g., 30%)
4. System calculates downpayment_amount and remaining_balance
5. Client pays downpayment
6. Downpayment confirmed → downpayment_paid=true
7. Request → Project
8. Admin assigns tasks, adiutors deliver
9. Client can see tasks/documents but marked as LOCKED
10. Client pays remaining_balance
11. Remaining balance confirmed → remaining_balance_paid=true
12. ALL tasks and documents now accessible
```

**Access Rules:**
- ❌ ALL tasks LOCKED until remaining_balance_paid
- ❌ ALL documents LOCKED until remaining_balance_paid
- ℹ️ Downpayment required to start project
- ✅ Everything unlocks after remaining balance paid

---

## Usage Examples

### Creating Milestones

```php
use App\Services\MilestoneService;

$milestoneService = new MilestoneService();

// Define phases
$phases = [
    [
        'name' => 'Phase 1: Research & Planning',
        'description' => 'Initial research and project planning',
        'percentage' => 30,
        'due_date' => '2025-11-30',
        'deliverables' => ['Project Plan', 'Research Report'],
    ],
    [
        'name' => 'Phase 2: Development',
        'description' => 'Core development work',
        'percentage' => 50,
        'due_date' => '2025-12-31',
        'deliverables' => ['Working Prototype', 'Code Repository'],
    ],
    [
        'name' => 'Phase 3: Testing & Delivery',
        'description' => 'Testing and final delivery',
        'percentage' => 20,
        'due_date' => '2026-01-31',
        'deliverables' => ['Test Reports', 'Final Deliverable'],
    ],
];

$milestones = $milestoneService->createMilestones($project, $phases);
```

### Processing Payments

```php
// Milestone payment
$milestone = ProjectMilestone::find(1);
$payment = Payment::find(10);
$milestoneService->processMilestonePayment($milestone, $payment, auth()->id());

// Downpayment
$milestoneService->processDownpayment($serviceRequest, $payment);

// Remaining balance
$milestoneService->processRemainingBalance($serviceRequest, $payment);
```

### Checking Accessibility

```php
// Check if task is accessible
if ($task->isAccessibleToClient()) {
    // Show task details
} else {
    // Show lock icon with message
    $lockStatus = $task->getLockStatus();
    // $lockStatus = ['locked' => true, 'message' => 'Locked: Payment required for Phase 2']
}

// Check document accessibility
if ($document->isAccessibleToClient()) {
    // Allow download
} else {
    // Show locked state
}
```

### Getting Milestone Summary

```php
$summary = $milestoneService->getMilestoneSummary($project);

/*
[
    'total_milestones' => 3,
    'paid_milestones' => 1,
    'pending_milestones' => 2,
    'total_amount' => 100000.00,
    'paid_amount' => 30000.00,
    'remaining_amount' => 70000.00,
    'progress_percentage' => 33.33,
    'milestones' => Collection<ProjectMilestone>
]
*/
```

---

## Frontend Integration Notes

### Admin Approval Interface
When admin approves a service request, they should:
1. Select payment type (radio buttons)
2. If **Full Payment**: No additional config needed
3. If **Milestone Payment**: Define phases with percentages (must total 100%)
4. If **Downpayment**: Set downpayment percentage

### Client Payment Interface
Display payment options based on payment_type:
1. **Full Payment**: Show total amount
2. **Milestone Payment**: Show current phase amount, list all phases with status
3. **Downpayment**: 
   - First payment: Show downpayment amount
   - After downpayment: Show remaining balance

### Task/Document Display
For clients viewing tasks/documents:
```blade
@if($task->isAccessibleToClient())
    <!-- Show full task details -->
@else
    @php
        $lockStatus = $task->getLockStatus();
    @endphp
    <div class="locked-content">
        <i class="icon-{{ $lockStatus['icon'] }}"></i>
        <p>{{ $lockStatus['message'] }}</p>
    </div>
@endif
```

---

## Migration Files

1. `2025_10_22_120000_create_milestone_management_system.php` - Creates project_milestones and milestone_payments tables
2. `2025_10_22_120001_add_payment_type_to_service_requests.php` - Adds payment type fields to service_requests
3. `2025_10_22_120002_add_phase_to_tasks.php` - Adds phase_id to tasks
4. `2025_10_22_120003_add_milestone_tracking_to_payments.php` - Adds milestone tracking to payments

---

## Next Steps (Frontend Implementation)

1. **Admin Request Approval Interface** - Add payment type selection and milestone configuration
2. **Milestone Management UI** - Admin interface to manage phases
3. **Client Payment Interface** - Update to show phase-based payment options
4. **Task/Document Lock UI** - Visual indicators for locked content
5. **Payment Progress Indicators** - Show milestone payment progress
6. **Access Control Middleware** - Enforce access rules at route level

---

## Testing Checklist

- [ ] Create project with full payment - verify all content accessible
- [ ] Create project with milestones - verify phase-based access
- [ ] Create project with downpayment - verify content locked until balance paid
- [ ] Test milestone payment sequence (Phase 1 → 2 → 3)
- [ ] Test partial payments for milestones
- [ ] Test downpayment + remaining balance flow
- [ ] Verify task accessibility based on payment status
- [ ] Verify document accessibility based on payment status
- [ ] Test payment progress calculations
- [ ] Test milestone status transitions

---

**Documentation Version:** 1.0  
**Last Updated:** October 22, 2025  
**Status:** Backend Complete - Frontend Pending

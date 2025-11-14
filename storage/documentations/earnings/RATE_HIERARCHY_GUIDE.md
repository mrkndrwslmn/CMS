# Earnings Rate Hierarchy - Quick Reference

## 🎯 How Hourly Rates Are Determined

The system uses a **3-tier hierarchy** to determine which hourly rate to use when calculating earnings:

```
┌─────────────────────────────────────┐
│  1. Task-Specific Rate              │  ← HIGHEST PRIORITY
│     (set by admin when creating)    │
├─────────────────────────────────────┤
│  2. Project Assignment Rate         │  ← MEDIUM PRIORITY
│     (set when assigning adiutor)    │
├─────────────────────────────────────┤
│  3. Adiutor Standard Rate           │  ← FALLBACK (LOWEST)
│     (set by adiutor in profile)     │
└─────────────────────────────────────┘
```

---

## 📋 Rate Selection Logic

### Example Scenario:
- **Adiutor Standard Rate**: ₱500/hour (set in profile)
- **Project Assignment Rate**: ₱600/hour (admin override)
- **Task Specific Rate**: ₱750/hour (urgent task)

**When adiutor logs time on this task**:
- ✅ Uses **₱750/hour** (task rate takes precedence)

### Another Scenario:
- **Adiutor Standard Rate**: ₱500/hour
- **Project Assignment Rate**: ₱600/hour
- **Task Specific Rate**: NULL (not set)

**When adiutor logs time on this task**:
- ✅ Uses **₱600/hour** (project rate takes precedence)

### Fallback Scenario:
- **Adiutor Standard Rate**: ₱500/hour
- **Project Assignment Rate**: NULL (not set)
- **Task Specific Rate**: NULL (not set)

**When adiutor logs time on this task**:
- ✅ Uses **₱500/hour** (fallback to standard rate)

---

## 💻 Code Implementation

### In Model: `Task.php`
```php
public function getEffectiveHourlyRate()
{
    // Priority 1: Task-specific rate
    if ($this->hourly_rate) {
        return $this->hourly_rate;
    }
    
    // Priority 2: Project assignment rate
    $assignment = ProjectAssignment::where('project_id', $this->projectID)
        ->where('adiutor_id', $this->adiutorID)
        ->first();
    
    if ($assignment && $assignment->hourly_rate) {
        return $assignment->hourly_rate;
    }
    
    // Priority 3: Adiutor standard rate
    $adiutor = User::with('adiutorProfile')->find($this->adiutorID);
    return $adiutor->adiutorProfile->standard_hourly_rate ?? 0;
}
```

### In Model: `ProjectAssignment.php`
```php
public function getEffectiveHourlyRate()
{
    // Priority 1: Project assignment rate
    if ($this->hourly_rate) {
        return $this->hourly_rate;
    }
    
    // Priority 2: Adiutor standard rate
    $adiutor = User::with('adiutorProfile')->find($this->adiutor_id);
    return $adiutor->adiutorProfile->standard_hourly_rate ?? 0;
}
```

---

## 🔄 Earnings Calculation Flow

### Step 1: Adiutor Starts Timer
```php
// In TimeTrackingController::start()
$task = Task::find($taskId);
$hourlyRate = $task->getEffectiveHourlyRate(); // Uses hierarchy

TimeEntry::create([
    'task_id' => $taskId,
    'started_at' => now(),
    'hourly_rate' => $hourlyRate, // Store for record
]);
```

### Step 2: Adiutor Stops Timer
```php
// In TimeTrackingController::stop()
$entry->end_time = now();
$hours = $entry->duration / 3600; // Convert seconds to hours

// Calculate earnings using stored rate
$entry->calculated_amount = $hours * $entry->hourly_rate;
$entry->save();
```

### Step 3: Auto-update Task Totals
```php
// In Task model or observer
public function updateEarnings()
{
    $totals = $this->timeEntries()
        ->selectRaw('SUM(calculated_amount) as total_earnings')
        ->selectRaw('SUM(duration) / 3600 as total_hours')
        ->first();
    
    $this->calculated_earnings = $totals->total_earnings;
    $this->total_hours_tracked = $totals->total_hours;
    $this->save();
}
```

---

## 📊 Database Schema

### Table: `adiutor_profiles`
```sql
standard_hourly_rate DECIMAL(8,2) NULL  -- Fallback rate
```

### Table: `project_assignments`
```sql
hourly_rate DECIMAL(8,2) NULL  -- Project-specific override
```

### Table: `tasks`
```sql
hourly_rate DECIMAL(8,2) NULL  -- Task-specific override (highest priority)
```

### Table: `time_entries`
```sql
calculated_amount DECIMAL(10,2) NULL  -- Auto-calculated: hours × rate
```

---

## 🎯 Use Cases

### Use Case 1: Standard Project Work
**Setup**:
- Adiutor rate: ₱500/hour
- Project rate: Not set
- Task rate: Not set

**Result**: All tasks billed at ₱500/hour

---

### Use Case 2: Premium Project
**Setup**:
- Adiutor rate: ₱500/hour
- Project rate: ₱650/hour (premium client)
- Task rate: Not set

**Result**: All tasks in this project billed at ₱650/hour

---

### Use Case 3: Urgent Task
**Setup**:
- Adiutor rate: ₱500/hour
- Project rate: ₱650/hour
- Task rate: ₱800/hour (urgent deadline)

**Result**: This specific task billed at ₱800/hour

---

### Use Case 4: Fixed Budget Task
**Setup**:
- Task payment type: Fixed budget
- Fixed amount: ₱5,000
- Time tracking: Optional

**Result**: 
- Adiutor paid ₱5,000 regardless of hours
- No hourly rate used
- Time entries for reference only

---

## 🛠️ Admin Controls

### When Assigning Adiutor to Project:
```
┌─────────────────────────────────────┐
│ Assign Adiutor                      │
├─────────────────────────────────────┤
│ Adiutor: [Select Adiutor ▼]        │
│                                     │
│ Hourly Rate:                        │
│ ₱ [________] /hour                  │
│ Standard Rate: ₱500.00/hr           │
│                                     │
│ ☐ Require time tracking            │
└─────────────────────────────────────┘
```

### When Creating Task:
```
┌─────────────────────────────────────┐
│ Payment Configuration               │
├─────────────────────────────────────┤
│ ○ Hourly with Time Tracking         │
│   Rate: ₱[___] (optional)           │
│   Budget Cap: ₱[___] (optional)     │
│                                     │
│ ○ Fixed Budget                      │
│   Amount: ₱[___]                    │
│                                     │
│ ○ No Payment                        │
└─────────────────────────────────────┘
```

---

## ✅ Best Practices

1. **Set Adiutor Standard Rate First**
   - Every adiutor should have a standard rate
   - This is the safety fallback

2. **Override at Project Level for Special Projects**
   - Premium clients
   - Long-term contracts
   - Bulk discounts

3. **Override at Task Level Sparingly**
   - Urgent tasks
   - Special expertise required
   - One-off situations

4. **Use Fixed Budget for**
   - Well-defined deliverables
   - Fixed-scope work
   - Non-hourly agreements

5. **Track Rates in Time Entries**
   - Always store the rate used at time of tracking
   - Prevents disputes if rates change later
   - Provides audit trail

---

## 🔍 Verification Queries

### Check Adiutor's Current Rates:
```sql
SELECT 
    u.fullName,
    ap.standard_hourly_rate,
    pa.hourly_rate as project_rate,
    t.hourly_rate as task_rate
FROM users u
LEFT JOIN adiutor_profiles ap ON u.id = ap.user_id
LEFT JOIN project_assignments pa ON u.id = pa.adiutor_id
LEFT JOIN tasks t ON u.id = t.adiutorID
WHERE u.id = ?;
```

### Check Time Entry Calculations:
```sql
SELECT 
    te.id,
    te.duration / 3600 as hours,
    te.hourly_rate,
    te.calculated_amount,
    (te.duration / 3600) * te.hourly_rate as expected_amount
FROM time_entries te
WHERE te.calculated_amount IS NOT NULL;
```

---

**Last Updated**: November 14, 2025  
**Implementation Status**: Models ready, awaiting admin forms in Phase 2

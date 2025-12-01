# 🧪 Revision Workflow Testing Guide

**Date:** December 1, 2025  
**Version:** 1.0  
**Status:** Ready for Testing

---

## 🎯 Quick Test Scenarios

### Test 1: Client Task Revision Request (5 minutes)

**Objective:** Verify client can request revision for a specific completed task

**Prerequisites:**
- ✅ Client account logged in
- ✅ Project with at least one completed task

**Steps:**
1. Navigate to: **Dashboard → Projects → [Select a project with completed tasks]**
2. Scroll to **"Project Tasks"** section
3. Find a task with green "Completed" badge
4. **Expected:** See orange button "Request Revision for This Task" below the task
5. Click the **"Request Revision for This Task"** button
6. **Expected:** Modal opens with title "Request Task Revision"
7. **Expected:** Task title appears in modal header
8. Fill in the form:
   - **Reason:** "The design doesn't match the approved mockups. Need adjustments to color scheme." (min 20 chars)
   - **Due Date:** [Select tomorrow or later]
   - **Priority:** Select "High"
9. Click **"Submit Task Revision"**
10. **Expected:** Redirect to revision details page
11. **Expected:** Success message appears
12. **Expected:** Revision shows `Source Type: task`

**Database Verification:**
```sql
SELECT * FROM revision_requests 
WHERE source_type = 'task' 
ORDER BY created_at DESC 
LIMIT 1;

-- Should show:
-- source_type: 'task'
-- task_id: [the task ID]
-- status: 'pending'
-- requested_by: [your user ID]
```

**Notification Verification:**
- Check admin dashboard for new notification
- Check assigned adiutor dashboard for notification

**Pass Criteria:**
- ✅ Button appears on completed tasks only
- ✅ Modal opens with correct task title
- ✅ Form validation works (min 20 chars)
- ✅ Revision created successfully
- ✅ Admins notified
- ✅ Adiutor notified

---

### Test 2: Admin Selective Task Reopening (7 minutes)

**Objective:** Verify admin can select specific tasks to reopen (not all)

**Prerequisites:**
- ✅ Admin account logged in
- ✅ Project with multiple completed tasks
- ✅ Pending project-wide revision request

**Setup:** Create test revision first
```sql
-- If you don't have a pending revision, create one:
INSERT INTO revision_requests 
(project_id, requested_by, reason, source_type, status, created_at, updated_at)
VALUES 
(1, 1, 'Need revisions on some tasks', 'project', 'pending', NOW(), NOW());
```

**Steps:**
1. Navigate to: **Admin Dashboard → Revisions**
2. Find a revision with `Source Type: project`
3. Click **"View"** or **"Approve"**
4. **Expected:** See section "Select Tasks to Reopen (Optional)"
5. **Expected:** See list of completed tasks with checkboxes
6. **Expected:** See "Select All" and "Deselect All" buttons
7. Count total completed tasks (e.g., 5 tasks)
8. Click **"Select All"**
9. **Expected:** All checkboxes are checked
10. Click **"Deselect All"**
11. **Expected:** All checkboxes are unchecked
12. Manually check **only 2-3 tasks** (e.g., Task A and Task B)
13. Leave other tasks unchecked
14. (Optional) Check **"Allow creating new tasks for this revision"**
15. Enter approval message: "Approved. Please revise selected tasks."
16. Click **"Approve Request"**
17. **Expected:** Success message appears

**Database Verification:**
```sql
-- Check which tasks were reopened
SELECT taskID, taskTitle, status 
FROM tasks 
WHERE project_id = [project_id]
ORDER BY status;

-- Should show:
-- Task A: status = 'in_progress' (REOPENED)
-- Task B: status = 'in_progress' (REOPENED)
-- Task C: status = 'completed' (STILL COMPLETED)
-- Task D: status = 'completed' (STILL COMPLETED)

-- Check revision record
SELECT reopened_task_ids, allows_new_tasks 
FROM revision_requests 
WHERE id = [revision_id];

-- Should show:
-- reopened_task_ids: [taskA_id, taskB_id] (JSON array)
-- allows_new_tasks: 1 or 0 (depending on checkbox)
```

**Pass Criteria:**
- ✅ Task selection UI appears for project-level revisions
- ✅ Select All / Deselect All buttons work
- ✅ Only selected tasks are reopened
- ✅ Unselected tasks remain completed
- ✅ `reopened_task_ids` stored correctly in database
- ✅ `allows_new_tasks` stored correctly

---

### Test 3: Admin Approves Task Revision (3 minutes)

**Objective:** Verify task automatically reopens when admin approves task-level revision

**Prerequisites:**
- ✅ Admin account logged in
- ✅ Pending task-level revision request (from Test 1)

**Steps:**
1. Navigate to: **Admin Dashboard → Revisions**
2. Find the revision created in Test 1 (Source Type: task)
3. Click **"View"** or **"Approve"**
4. **Expected:** No task selection UI (because it's a task-level revision)
5. **Expected:** See task details and reason
6. Enter approval message: "Approved. Adiutor will revise the task."
7. Click **"Approve Request"**
8. **Expected:** Success message appears

**Database Verification:**
```sql
-- Check task status
SELECT taskID, taskTitle, status, completed_at 
FROM tasks 
WHERE taskID = [task_id_from_test_1];

-- Should show:
-- status: 'in_progress' (REOPENED automatically)
-- completed_at: NULL (cleared)

-- Check revision status
SELECT status, approved_by, approved_at 
FROM revision_requests 
WHERE id = [revision_id];

-- Should show:
-- status: 'approved'
-- approved_by: [admin user ID]
-- approved_at: [timestamp]
```

**Pass Criteria:**
- ✅ No task selection UI for task-level revisions
- ✅ Task automatically reopened after approval
- ✅ `completed_at` cleared
- ✅ Revision status updated to 'approved'
- ✅ Adiutor receives notification

---

### Test 4: Validation Tests (5 minutes)

**Objective:** Verify form validations work correctly

#### Test 4A: Task Revision - Reason Too Short
1. Open task revision modal
2. Enter reason: "Fix this" (only 8 characters)
3. Try to submit
4. **Expected:** Validation error: "Reason must be at least 20 characters"

#### Test 4B: Task Revision - Non-Completed Task
1. Try to access: `POST /client/revisions/tasks/{task_id}` where task status is "in_progress"
2. **Expected:** Error: "Only completed tasks can have revision requests"

#### Test 4C: Task Revision - Not Your Project
1. As Client A, try to request revision for task in Client B's project
2. **Expected:** 404 Not Found (task not found due to ownership check)

#### Test 4D: Admin Approval - Invalid Task ID
1. In admin approval form, manually edit checkbox value to non-existent task ID
2. Try to approve
3. **Expected:** Validation error: "Selected task is invalid"

**Pass Criteria:**
- ✅ All validations trigger appropriate error messages
- ✅ Cannot bypass ownership checks
- ✅ Cannot request revision on non-completed tasks
- ✅ Cannot select invalid tasks in admin approval

---

### Test 5: Notification Tests (5 minutes)

**Objective:** Verify correct users receive notifications

#### Test 5A: Task Revision Notifications
1. Client requests task revision (Test 1)
2. Check admin user notifications:
   - **Expected:** New notification about task revision request
3. Check assigned adiutor notifications:
   - **Expected:** New notification about task revision request
4. Check other adiutors (not assigned to this task):
   - **Expected:** No notification

#### Test 5B: Project Revision Notifications
1. Admin approves project revision with selected tasks (Test 2)
2. Check adiutors assigned to reopened tasks:
   - **Expected:** Notification about project reopening
3. Check adiutors NOT assigned to reopened tasks:
   - **Expected:** No notification

**Database Verification:**
```sql
-- Check notifications sent
SELECT * FROM notifications 
WHERE type = 'App\\Notifications\\RevisionRequestedNotification' 
ORDER BY created_at DESC 
LIMIT 5;
```

**Pass Criteria:**
- ✅ Admins receive task revision notifications
- ✅ Assigned adiutor receives task revision notification
- ✅ Only affected adiutors receive notifications
- ✅ Notification data includes correct revision details

---

### Test 6: Edge Cases (5 minutes)

#### Test 6A: No Tasks Selected (Admin Approval)
1. Admin opens project-level revision
2. Do NOT check any tasks
3. Do NOT check "Allow new tasks"
4. Approve revision
5. **Expected:** Revision approved, but no tasks reopened
6. **Expected:** `reopened_task_ids` is empty array `[]`

#### Test 6B: Project with No Completed Tasks
1. Admin opens project-level revision for project with no completed tasks
2. **Expected:** No task selection UI appears (or shows "No completed tasks")
3. Approve revision
4. **Expected:** Revision approved successfully

#### Test 6C: Task Already Has Pending Revision
1. Client requests revision for Task A
2. Before admin approves, client requests revision for Task A again
3. **Expected:** Either:
   - New revision created (duplicate allowed)
   - OR error: "Task already has pending revision"
   
**Note:** Check current behavior in existing code

**Pass Criteria:**
- ✅ System handles edge cases gracefully
- ✅ No crashes or unexpected errors
- ✅ Clear user feedback for all scenarios

---

## 🔍 Visual Inspection Checklist

### Client UI
- [ ] Task revision button is **orange-themed** (matches design)
- [ ] Button has hover effect
- [ ] Modal has **gradient header** (orange)
- [ ] Modal is **centered** and responsive
- [ ] Form fields are properly labeled
- [ ] Submit button has icon + text
- [ ] Close button (X) works

### Admin UI
- [ ] Task checkboxes are **aligned properly**
- [ ] Task list is **scrollable** (max-height: 256px)
- [ ] Checkbox hover effect works
- [ ] "Select All" / "Deselect All" buttons are visible
- [ ] "Allow new tasks" checkbox is clear
- [ ] Form validation errors are visible

### Mobile Responsiveness
- [ ] Task revision button visible on mobile (< 768px width)
- [ ] Modal is usable on mobile
- [ ] Form fields are tap-friendly
- [ ] Admin task selection works on mobile

---

## 📊 Performance Checks

### Database Queries
1. Open Laravel Debugbar (if installed)
2. Perform Test 1 (Task Revision Request)
3. **Expected:** < 10 database queries
4. **Expected:** < 500ms total execution time

### Page Load Times
- Client project page: < 1 second
- Admin revision approval page: < 1 second
- Task revision modal: Opens instantly (no AJAX)

---

## 🐛 Bug Report Template

If you find any issues, report them using this format:

```markdown
### Bug Report

**Test Case:** [e.g., Test 2: Admin Selective Task Reopening]
**Step:** [e.g., Step 7: Click "Select All" button]
**Expected:** [What should happen]
**Actual:** [What actually happened]
**Error Message:** [Copy exact error message]
**Browser:** [Chrome 120, Firefox 121, etc.]
**Screenshot:** [Attach if applicable]

**Database State (if relevant):**
```sql
-- Copy relevant query results
```

**Console Errors (if any):**
```
// Copy browser console errors
```
```

---

## ✅ Sign-Off Checklist

After completing all tests:

- [ ] Test 1 passed (Client task revision)
- [ ] Test 2 passed (Admin selective reopening)
- [ ] Test 3 passed (Admin approves task revision)
- [ ] Test 4 passed (All validations work)
- [ ] Test 5 passed (Notifications sent correctly)
- [ ] Test 6 passed (Edge cases handled)
- [ ] Visual inspection passed
- [ ] Performance is acceptable
- [ ] No critical bugs found

**Tested By:** _________________  
**Date:** _________________  
**Status:** ✅ PASS / ⚠️ PASS WITH ISSUES / ❌ FAIL  

**Notes:**
_________________________________________________________________
_________________________________________________________________
_________________________________________________________________

---

## 🚀 Ready for Production

Once all tests pass:

1. ✅ Create backup of production database
2. ✅ Deploy code to production
3. ✅ Run migration in production
4. ✅ Clear caches
5. ✅ Verify routes
6. ✅ Perform quick smoke test
7. ✅ Monitor logs for 24 hours
8. ✅ Collect user feedback

---

**END OF TESTING GUIDE**

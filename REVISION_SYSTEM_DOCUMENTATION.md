# Document Revision Request System

## Overview
The revision request system allows clients to request revisions for documents delivered by adiutors. This system supports both task-based and project-based documents with full admin review workflow.

## Database Structure

### `revision_requests` table
- `id` - Primary key
- `document_id` - Foreign key to documents table
- `requested_by` - Client who requested the revision
- `reason` - Detailed reason for revision request
- `requested_due_date` - When client needs revision completed
- `revision_number` - Tracks multiple revisions (1, 2, 3...)
- `status` - pending | approved | rejected | completed | cancelled
- `reviewed_by` - Admin who reviewed the request
- `admin_notes` - Admin's notes about approval/rejection
- `reviewed_at` - When admin reviewed
- `task_id` - If document is task-based
- `project_id` - If document is project-based
- `service_request_id` - Related service request
- `source_type` - 'task' or 'project'
- `assigned_adiutor_id` - Adiutor assigned to handle revision
- `completed_at` - When adiutor completed revision
- `completed_by` - Adiutor who completed revision

## Workflow

### 1. Client Requests Revision
**Route:** `GET /client/revisions/documents/{document}/create`
**Controller:** `ClientRevisionRequestController@create`

- Client views a document and clicks "Request Revision"
- System shows form with:
  - Document name and source (Task or Project)
  - Reason field (required, min 10 chars)
  - Requested due date (optional)
- System automatically identifies:
  - Source type (task or project)
  - Assigned adiutor
  - Related IDs (task_id, project_id, service_request_id)

**Submission:**
- Route: `POST /client/revisions/documents/{document}`
- Creates revision_request record with status='pending'
- Notifies admins via email and in-app notification
- Notifies assigned adiutor (if exists)

### 2. Admin Reviews Revision Request
**Route:** `GET /admin/revisions`
**Controller:** `AdminRevisionController@index`

- Admin sees list of all revision requests
- Can filter by status (pending, approved, rejected, completed)
- Can filter by source type (task, project)
- Can search by document name, client name, reason

**Review Screen:**
- Route: `GET /admin/revisions/{revision}`
- Shows:
  - Document details
  - Client information
  - Source description ("Task: [Task Name]" or "Project: [Project Title]")
  - Revision reason
  - Requested due date
  - Assigned adiutor

**Admin Actions:**

#### Approve:
- Route: `POST /admin/revisions/{revision}/approve`
- Can add admin notes
- Can reassign to different adiutor
- **If task-based:** Automatically reopens task (status → 'in_progress')
- Sends email to adiutor with revision details
- Sends in-app notification to adiutor
- Notifies client of approval

#### Reject:
- Route: `POST /admin/revisions/{revision}/reject`
- Must provide reason in admin notes
- Sends email to client with rejection reason
- Sends in-app notification to client

### 3. Adiutor Handles Revision
**Route:** `GET /adiutor/revisions`
**Controller:** `AdiutorRevisionController@index`

- Adiutor sees approved revisions assigned to them
- Shows:
  - Document name
  - Source type and name (Task or Project)
  - Revision reason
  - Requested due date
  - Revision number

**Revision Details:**
- Route: `GET /adiutor/revisions/{revision}`
- Shows full context:
  - Original document
  - Client's revision reason
  - Task/Project details
  - Due date
  - Admin notes

**Upload Revised Document:**
- Route: `GET /adiutor/revisions/{revision}/upload`
- Adiutor uploads new version of document
- (Document upload handled by existing document upload system)

**Mark as Completed:**
- Route: `POST /adiutor/revisions/{revision}/complete`
- Updates revision status to 'completed'
- **If task-based:** Marks task as completed again
- Optional completion notes
- Notifies client

## Key Features

### Automatic Task Reopening
When a task-based document revision is approved:
```php
if ($revision->isTaskBased() && $revision->task->status === 'completed') {
    $revision->task->update(['status' => 'in_progress']);
}
```

### Source Identification
System automatically determines if document came from task or project:
```php
$sourceDescription = $revision->getSourceDescription();
// Returns: "Task: Research and Analysis" or "Project: Website Redesign"
```

### Revision History
- Each document can have multiple revisions
- `revision_number` auto-increments
- Full audit trail of all requests

### Notifications

**When Client Requests:**
- Admins receive: `RevisionRequestedNotification`
- Adiutor receives: `RevisionRequestedNotification`

**When Admin Approves:**
- Adiutor receives: `RevisionApprovedNotification` + `RevisionApproved` email
- Client receives: `RevisionApprovedNotification`

**When Admin Rejects:**
- Client receives: `RevisionRejectedNotification` + `RevisionRejected` email

## Models

### RevisionRequest Model
**Location:** `app/Models/RevisionRequest.php`
**Key Methods:**
- `isTaskBased()` - Check if from task
- `isProjectBased()` - Check if from project
- `getSourceDescription()` - Get human-readable source
- `getStatusColor()` - Get badge color for UI
- Scopes: `pending()`, `approved()`, `forAdiutor()`, `forClient()`

### Document Model Updates
**Location:** `app/Models/Document.php`
**New Methods:**
- `revisionRequests()` - Get all revisions
- `latestRevisionRequest()` - Get latest revision
- `pendingRevisionRequests()` - Get pending revisions
- `hasPendingRevision()` - Check if has pending
- `hasRevisionRequests()` - Check if has any
- `canRequestRevision()` - Check if can request new

## Routes

### Client Routes
```php
GET    /client/revisions                          - List all revisions
GET    /client/revisions/{revision}               - View revision details
GET    /client/revisions/documents/{document}/create - Create revision request form
POST   /client/revisions/documents/{document}     - Submit revision request
POST   /client/revisions/{revision}/cancel        - Cancel pending revision
```

### Admin Routes
```php
GET    /admin/revisions                    - List all revisions
GET    /admin/revisions/{revision}         - View revision details
POST   /admin/revisions/{revision}/approve - Approve revision
POST   /admin/revisions/{revision}/reject  - Reject revision
POST   /admin/revisions/{revision}/reassign - Reassign to different adiutor
```

### Adiutor Routes
```php
GET    /adiutor/revisions                  - List assigned revisions
GET    /adiutor/revisions/{revision}       - View revision details
GET    /adiutor/revisions/{revision}/upload - Upload form for revised document
POST   /adiutor/revisions/{revision}/complete - Mark revision as completed
```

## UI Integration Points

### Client Document View
Add "Request Revision" button when:
- Document is accessible
- No pending/approved revision exists
- Check with: `$document->canRequestRevision()`

### Admin Dashboard
Add revision requests count to dashboard:
```php
$pendingRevisions = RevisionRequest::pending()->count();
```

### Adiutor Dashboard
Show approved revisions needing attention:
```php
$myRevisions = RevisionRequest::forAdiutor(auth()->id())
    ->where('status', 'approved')
    ->count();
```

## Email Templates Needed
- `resources/views/emails/revision-approved.blade.php`
- `resources/views/emails/revision-rejected.blade.php`

## Views Needed

### Client Views
- `resources/views/client/revisions/index.blade.php` - List revisions
- `resources/views/client/revisions/show.blade.php` - View revision details
- `resources/views/client/revisions/create.blade.php` - Create revision request form

### Admin Views
- `resources/views/admin/revisions/index.blade.php` - List all revisions
- `resources/views/admin/revisions/show.blade.php` - Review revision request

### Adiutor Views
- `resources/views/adiutor/revisions/index.blade.php` - List assigned revisions
- `resources/views/adiutor/revisions/show.blade.php` - View revision details
- `resources/views/adiutor/revisions/upload.blade.php` - Upload revised document

## Testing Checklist

1. **Client Flow:**
   - [ ] Can view documents
   - [ ] Can click "Request Revision"
   - [ ] Can submit revision with reason and due date
   - [ ] Receives notification when approved/rejected
   - [ ] Can view revision status
   - [ ] Can cancel pending revision

2. **Admin Flow:**
   - [ ] Receives notification of new revision request
   - [ ] Can view all revision requests
   - [ ] Can filter by status and source type
   - [ ] Can approve with optional notes
   - [ ] Can reject with required notes
   - [ ] Can reassign to different adiutor

3. **Adiutor Flow:**
   - [ ] Receives notification when revision approved
   - [ ] Can view assigned revisions
   - [ ] Sees task/project context
   - [ ] Task automatically reopens if was completed
   - [ ] Can upload revised document
   - [ ] Can mark revision as completed

4. **Task Behavior:**
   - [ ] Completed task reopens when revision approved
   - [ ] Task status changes to 'in_progress'
   - [ ] Task completes again after revision submitted

## Migration Command
```bash
php artisan migrate
```

This will create the `revision_requests` table with all necessary fields and indexes.

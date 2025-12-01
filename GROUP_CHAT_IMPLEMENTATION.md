# Group Chat Feature Implementation

## Overview
This implementation adds a project-based group chat feature for admins and adiutors (NOT clients). The system reuses the existing `messages` table and integrates seamlessly with your current messaging infrastructure.

## Features Implemented

### 1. **Automatic Group Chat Creation**
- Group chats are automatically created when a project is created
- All active admins are automatically added as members

### 2. **Dynamic Membership**
- When adiutors are assigned to a project, they're automatically added to the group chat
- Only admins and assigned adiutors can access the group chat
- **Clients are excluded from group chats** (they only have access to direct admin-client messaging)

### 3. **Archive/Close Functionality**
- Admins can archive group chats (e.g., when a project is completed)
- Archived chats remain readable but no new messages can be sent
- Admins can reopen archived chats if needed
- Archive action is **not automatic** — must be triggered by admin through UI

### 4. **Message Features**
- Text messages with file attachments (up to 10MB per file)
- Real-time push notifications via Firebase
- Unread message counts per user
- Message history with pagination
- Soft delete support

## Database Schema

### New Tables Created

#### `group_chats`
```sql
- id
- project_id (FK to projects)
- name (optional custom name)
- status (enum: 'open', 'archived')
- archived_at
- archived_by (FK to users)
- last_message_id (FK to messages)
- last_message_at
- timestamps
```

#### `group_chat_members`
```sql
- id
- group_chat_id (FK to group_chats)
- user_id (FK to users)
- unread_count
- last_read_at
- timestamps
- UNIQUE (group_chat_id, user_id)
```

### Modified Tables

#### `messages`
Added column:
- `group_chat_id` (nullable FK to group_chats)

Existing messages remain unchanged. Group messages use `message_type = 'group'`.

## API Endpoints

All endpoints require authentication and are prefixed with `/api/group-chats`:

### GET `/api/group-chats`
Get all group chats for the authenticated user
- **Admins**: See all group chats
- **Adiutors**: See only group chats they're members of
- **Clients**: Blocked (403)

**Response:**
```json
{
  "success": true,
  "group_chats": {
    "data": [
      {
        "id": 1,
        "project_id": 5,
        "name": "Website Redesign Project",
        "status": "open",
        "last_message_at": "2025-12-01T10:30:00Z",
        "members_count": 5,
        "my_unread_count": 3
      }
    ]
  }
}
```

### GET `/api/group-chats/{groupChatId}`
Get messages and details for a specific group chat

**Response:**
```json
{
  "success": true,
  "group_chat": {...},
  "project": {
    "id": 5,
    "title": "Website Redesign Project"
  },
  "members": [...],
  "can_send_messages": true,
  "messages": {
    "data": [...]
  }
}
```

### POST `/api/group-chats/{groupChatId}`
Send a message to a group chat

**Request:**
```json
{
  "message": "Text content here",
  "attachments": [] // Optional file uploads
}
```

**Response:**
```json
{
  "success": true,
  "message": {...},
  "group_chat": {...}
}
```

### POST `/api/group-chats/{groupChatId}/mark-read`
Mark all messages in a group chat as read

### POST `/api/group-chats/{groupChatId}/archive`
Archive a group chat (admin only)

**Response:**
```json
{
  "success": true,
  "message": "Group chat archived successfully",
  "group_chat": {...}
}
```

### POST `/api/group-chats/{groupChatId}/reopen`
Reopen an archived group chat (admin only)

### GET `/api/group-chats/unread-count`
Get total unread message count across all group chats

**Response:**
```json
{
  "success": true,
  "unread_count": 7
}
```

## Models

### GroupChat Model
Location: `app/Models/GroupChat.php`

**Key Methods:**
- `canAccess(User $user)` - Check if user can access the chat
- `canSendMessages(User $user)` - Check if user can send messages (must be open)
- `archive(User $admin)` - Archive the chat (admin only)
- `reopen(User $admin)` - Reopen archived chat (admin only)
- `addMember(int $userId)` - Add a single member
- `addMembers(array $userIds)` - Add multiple members
- `syncMembersFromProject()` - Sync all admins + assigned adiutors
- `getOrCreateForProject(int $projectId)` - Get or create group chat for a project

**Relationships:**
- `project()` - BelongsTo Project
- `members()` - BelongsToMany User (through group_chat_members)
- `messages()` - HasMany Message
- `lastMessage()` - BelongsTo Message

### Message Model (Updated)
Added support for group chat messages via `group_chat_id` field.

**New Methods:**
- `groupChat()` - BelongsTo GroupChat
- `scopeForGroupChat($query, $groupChatId)` - Filter by group chat

## Automatic Behaviors

### Project Creation
When a project is created:
```php
// In Project::boot()
static::created(function ($project) {
    GroupChat::getOrCreateForProject($project->id);
    // Automatically adds all active admins as members
});
```

### Adiutor Assignment
When an adiutor is assigned to a project:
```php
// In ProjectAssignment::boot()
static::created(function ($assignment) {
    $groupChat = GroupChat::getOrCreateForProject($assignment->project_id);
    $groupChat->addMember($assignment->adiutor_id);
});
```

## Firebase Push Notifications

Added `sendGroupChatNotification()` method to `FirebaseService`:
```php
public function sendGroupChatNotification(Message $message, User $recipient): bool
```

Sends notifications to all group members except the sender when a new message is posted.

## Running Migrations

To apply the database changes:

```powershell
php artisan migrate
```

This will create:
1. `group_chats` table
2. `group_chat_members` table
3. Add `group_chat_id` to `messages` table
4. Add foreign key constraint from `group_chats.last_message_id` to `messages.id`

## Security & Access Control

### Access Rules:
- **Admins**: Can access all group chats
- **Adiutors**: Can only access group chats they're members of
- **Clients**: Cannot access group chats at all (403 error)

### Archive Rules:
- Only admins can archive/reopen group chats
- Archived chats block new messages but remain readable
- Archive action must be triggered manually (not automatic)

## Integration Points

### Frontend Implementation Needed:
1. **Group Chat List View** - Display all accessible group chats with unread counts
2. **Group Chat Detail View** - Show messages and member list
3. **Message Composer** - Send messages (disabled if archived)
4. **Archive Button** - Admin-only, shown in group chat detail view
5. **Unread Badge** - Show total unread count in navigation

### Example UI Flow:
```
Admin/Adiutor Dashboard
  └── Group Chats (with unread badge)
      ├── List all group chats (by project)
      │   └── Each shows: project name, last message, unread count
      └── Click to open chat
          ├── Show members list
          ├── Show messages (paginated)
          ├── Message input (if open)
          └── Archive button (admin only, if open)
```

## Testing Checklist

- [ ] Create a new project → Group chat auto-created with admins
- [ ] Assign adiutor to project → Adiutor added to group chat
- [ ] Admin sends message → All members notified
- [ ] Adiutor sends message → All members notified
- [ ] Client tries to access group chat → 403 error
- [ ] Admin archives chat → Status changes to 'archived'
- [ ] Try to send message in archived chat → Error message
- [ ] Admin reopens chat → Can send messages again
- [ ] Check unread counts → Accurate per user

## Notes

- Group chat members are never automatically removed (even if unassigned from project) to preserve chat history
- File attachments are stored in Cloudflare R2 under `group-chat-attachments/` directory
- Push notifications use the same Firebase infrastructure as direct messages
- The system reuses the existing `messages` table to keep all messaging unified

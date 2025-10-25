# Database Schema Documentation

**Project:** Client Management System (CMS)  
**Database Type:** SQLite (Development)  
**Laravel Version:** 12.33.0  
**Last Updated:** October 21, 2025

---

## Table of Contents
1. [Overview](#overview)
2. [Database Diagram](#database-diagram)
3. [Core Tables](#core-tables)
4. [Supporting Tables](#supporting-tables)
5. [Relationships Summary](#relationships-summary)
6. [Indexes & Performance](#indexes--performance)

---

## Overview

The CMS database consists of **19 tables** organized into the following categories:

- **User Management:** users, client_profiles, adiutor_profiles, skills, adiutor_skills
- **Service & Project Management:** service_requests, request_attachments, projects, project_assignments, project_feedback
- **Task Management:** tasks, budget_change_requests
- **Document Management:** documents
- **Communication:** messages, feedbacks
- **Payment:** payments
- **Supporting:** services, notifications, cache, cache_locks, jobs, job_batches, failed_jobs
- **Authentication:** password_reset_tokens, sessions

---

## Database Diagram

```
┌─────────────┐
│    users    │──┐
└─────────────┘  │
       │         │
       ├─────────┼───────────────────────────────┐
       │         │                               │
┌──────▼──────┐ │ ┌──────────────┐       ┌──────▼──────────┐
│client_profiles│ │adiutor_profiles│       │service_requests │
└─────────────┘ │ └───────┬──────┘       └────────┬────────┘
                │         │                       │
                │   ┌─────▼──────┐                │
                │   │adiutor_skills│              │
                │   └────────────┘                │
                │                            ┌────▼────────┐
                │                            │request_     │
                │                            │attachments  │
                │                            └─────────────┘
                │                                  │
                │                            ┌─────▼─────┐
                │                            │ projects  │
                │                            └─────┬─────┘
                │                                  │
                ├──────────────────────────────────┼──────────────┐
                │                                  │              │
         ┌──────▼──────────┐              ┌───────▼──────┐ ┌─────▼────────┐
         │project_assignments│              │    tasks     │ │project_      │
         └─────────────────┘              └───────┬──────┘ │feedback      │
                                                  │         └──────────────┘
                                          ┌───────▼────────┐
                                          │budget_change_  │
                                          │requests        │
                                          └────────────────┘
```

---

## Core Tables

### 1. **users**
Central table for all system users (Admin, Client, Adiutor).

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Unique user identifier |
| `fullName` | VARCHAR(100) | NOT NULL | User's full name |
| `email` | VARCHAR(255) | UNIQUE, NOT NULL | Email address for login |
| `email_verified_at` | TIMESTAMP | NULLABLE | Email verification timestamp |
| `password` | VARCHAR(255) | NOT NULL | Hashed password |
| `role` | ENUM | NOT NULL | 'admin', 'client', 'adiutor' |
| `phoneNumber` | VARCHAR(20) | NULLABLE | Contact phone number |
| `profilePic` | VARCHAR(255) | NULLABLE | Profile picture path |
| `status` | ENUM | DEFAULT 'active' | 'active', 'inactive' |
| `dateCreated` | TIMESTAMP | DEFAULT now() | Account creation date |
| `remember_token` | VARCHAR(100) | NULLABLE | Laravel remember token |
| `created_at` | TIMESTAMP | AUTO | Record creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Record update timestamp |

**Indexes:**
- `email` (unique)
- `role`, `status` (composite)

**Relationships:**
- Has many: service_requests, projects, tasks, messages, feedbacks, notifications
- Has one: client_profile, adiutor_profile

---

### 2. **client_profiles**
Extended profile information for client users.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Profile ID |
| `user_id` | BIGINT UNSIGNED | FOREIGN KEY (users), UNIQUE | Reference to user |
| `company_name` | VARCHAR(255) | NULLABLE | Company name |
| `company_description` | TEXT | NULLABLE | About the company |
| `industry` | VARCHAR(255) | NULLABLE | Industry type |
| `company_size` | VARCHAR(255) | NULLABLE | 'small', 'medium', 'large', 'enterprise' |
| `address` | TEXT | NULLABLE | Company address |
| `website` | VARCHAR(255) | NULLABLE | Company website URL |
| `preferred_contact_methods` | JSON | NULLABLE | Array of contact preferences |
| `timezone` | VARCHAR(255) | NULLABLE | Client timezone |
| `business_hours` | JSON | NULLABLE | Operating hours |
| `notes` | TEXT | NULLABLE | Internal admin notes |
| `is_verified` | BOOLEAN | DEFAULT false | Admin verification status |
| `total_projects` | INTEGER | DEFAULT 0 | Count of projects |
| `total_spent` | DECIMAL(12,2) | DEFAULT 0.00 | Total money spent |
| `client_type` | ENUM | DEFAULT 'individual' | 'individual', 'small_business', 'enterprise' |
| `contact_person` | VARCHAR(255) | NULLABLE | Primary contact name |
| `contact_email` | VARCHAR(255) | NULLABLE | Alternative email |
| `contact_phone` | VARCHAR(255) | NULLABLE | Alternative phone |
| `billing_address` | VARCHAR(255) | NULLABLE | Billing address |
| `tax_id` | VARCHAR(255) | NULLABLE | Tax identification number |
| `created_at` | TIMESTAMP | AUTO | Record creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Record update timestamp |

**Indexes:**
- `client_type`, `is_verified` (composite)
- `industry`

**Relationships:**
- Belongs to: user

---

### 3. **adiutor_profiles**
Extended profile information for adiutor (service provider) users.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Profile ID |
| `user_id` | BIGINT UNSIGNED | FOREIGN KEY (users), UNIQUE | Reference to user |
| `bio` | TEXT | NULLABLE | Professional biography |
| `title` | VARCHAR(255) | NULLABLE | Professional title/role |
| `hourly_rate` | DECIMAL(8,2) | NULLABLE | Hourly rate in PHP |
| `availability` | JSON | NULLABLE | Available hours, timezone |
| `portfolio_url` | VARCHAR(255) | NULLABLE | Portfolio website |
| `linkedin_url` | VARCHAR(255) | NULLABLE | LinkedIn profile |
| `github_url` | VARCHAR(255) | NULLABLE | GitHub profile |
| `experience` | TEXT | NULLABLE | Work experience description |
| `languages` | JSON | NULLABLE | Spoken languages array |
| `location` | VARCHAR(255) | NULLABLE | Geographic location |
| `is_verified` | BOOLEAN | DEFAULT false | Admin verification status |
| `rating` | DECIMAL(3,2) | DEFAULT 0.00 | Average rating (0-5) |
| `total_projects` | INTEGER | DEFAULT 0 | Completed project count |
| `status` | ENUM | DEFAULT 'active' | 'active', 'inactive', 'busy' |
| `created_at` | TIMESTAMP | AUTO | Record creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Record update timestamp |

**Indexes:**
- `status`, `is_verified` (composite)
- `rating`

**Relationships:**
- Belongs to: user
- Has many: adiutor_skills
- Belongs to many: projects (via project_assignments)

---

### 4. **skills**
Master list of available skills.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Skill ID |
| `name` | VARCHAR(255) | UNIQUE, NOT NULL | Skill name |
| `description` | TEXT | NULLABLE | Skill description |
| `category` | VARCHAR(255) | NULLABLE | Skill category (programming, design, etc.) |
| `is_active` | BOOLEAN | DEFAULT true | Active status |
| `created_at` | TIMESTAMP | AUTO | Record creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Record update timestamp |

**Indexes:**
- `category`, `is_active` (composite)

**Relationships:**
- Belongs to many: adiutor_profiles (via adiutor_skills)

---

### 5. **adiutor_skills**
Junction table linking adiutors to their skills.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Record ID |
| `adiutor_id` | BIGINT UNSIGNED | FOREIGN KEY (adiutor_profiles) | Adiutor profile reference |
| `skill_id` | BIGINT UNSIGNED | FOREIGN KEY (skills) | Skill reference |
| `proficiency_level` | ENUM | DEFAULT 'intermediate' | 'beginner', 'intermediate', 'advanced', 'expert' |
| `years_experience` | INTEGER | DEFAULT 0 | Years of experience with skill |
| `created_at` | TIMESTAMP | AUTO | Record creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Record update timestamp |

**Indexes:**
- `adiutor_id`, `skill_id` (unique composite)
- `proficiency_level`

**Relationships:**
- Belongs to: adiutor_profile, skill

---

### 6. **service_requests**
Client service request submissions (start of workflow).

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Request ID |
| `client_id` | BIGINT UNSIGNED | FOREIGN KEY (users) | Client who submitted |
| `contact_method` | VARCHAR(255) | NOT NULL | 'email', 'messenger', 'phone' |
| `contact_details` | VARCHAR(255) | NOT NULL | Contact value |
| `service_type` | VARCHAR(255) | NOT NULL | Type of service requested |
| `project_name` | VARCHAR(255) | NOT NULL | Project name/title |
| `request_description` | TEXT | NOT NULL | Detailed description |
| `deadline` | DATE | NULLABLE | Requested deadline |
| `expectations` | TEXT | NULLABLE | Client expectations |
| `additional_notes` | TEXT | NULLABLE | Extra notes |
| `requirements` | JSON | NULLABLE | Structured requirements |
| `status` | ENUM | DEFAULT 'pending' | 'pending', 'approved', 'rejected', 'pending_payment', 'paid', 'in_progress', 'completed' |
| `priority` | ENUM | DEFAULT 'medium' | 'low', 'medium', 'high', 'urgent' |
| `estimated_budget` | DECIMAL(10,2) | NULLABLE | Initial budget estimate |
| `approved_budget` | DECIMAL(10,2) | NULLABLE | Admin-approved budget |
| `approved_at` | TIMESTAMP | NULLABLE | Approval timestamp |
| `approved_by` | BIGINT UNSIGNED | FOREIGN KEY (users), NULLABLE | Admin who approved |
| `reviewed_at` | TIMESTAMP | NULLABLE | Review timestamp |
| `payment_method` | VARCHAR(255) | NULLABLE | Payment method used |
| `payment_due_date` | TIMESTAMP | NULLABLE | Payment deadline |
| `payment_confirmed_at` | TIMESTAMP | NULLABLE | Payment confirmation time |
| `payment_reference` | VARCHAR(255) | NULLABLE | Payment reference number |
| `payment_instructions` | TEXT | NULLABLE | Payment instructions |
| `admin_notes` | TEXT | NULLABLE | Internal admin notes |
| `rejection_reason` | TEXT | NULLABLE | Reason if rejected |
| `created_at` | TIMESTAMP | AUTO | Record creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Record update timestamp |

**Indexes:**
- `status`, `priority` (composite)
- `client_id`, `status` (composite)
- `deadline`

**Relationships:**
- Belongs to: user (client), user (approved_by)
- Has many: request_attachments
- Has one: project

---

### 7. **request_attachments**
File attachments for service requests.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Attachment ID |
| `service_request_id` | BIGINT UNSIGNED | FOREIGN KEY (service_requests) | Parent request |
| `original_filename` | VARCHAR(255) | NOT NULL | Original file name |
| `stored_filename` | VARCHAR(255) | NOT NULL | Stored file name |
| `file_path` | VARCHAR(255) | NOT NULL | Storage path |
| `mime_type` | VARCHAR(255) | NOT NULL | File MIME type |
| `file_size` | BIGINT | NOT NULL | File size in bytes |
| `file_hash` | VARCHAR(255) | NULLABLE | File hash for duplicate detection |
| `description` | TEXT | NULLABLE | File description |
| `created_at` | TIMESTAMP | AUTO | Record creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Record update timestamp |

**Indexes:**
- `mime_type`
- `file_hash`

**Relationships:**
- Belongs to: service_request

---

### 8. **projects**
Main project entity created from approved and paid service requests.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Project ID |
| `service_request_id` | BIGINT UNSIGNED | FOREIGN KEY (service_requests), UNIQUE | Source service request |
| `client_id` | BIGINT UNSIGNED | FOREIGN KEY (users) | Project client |
| `title` | VARCHAR(255) | NOT NULL | Project title |
| `description` | TEXT | NOT NULL | Project description |
| `requirements` | JSON | NULLABLE | Technical requirements |
| `skills_required` | JSON | NULLABLE | Required skill IDs |
| `budget` | DECIMAL(10,2) | NULLABLE | Project budget |
| `budget_type` | ENUM | DEFAULT 'fixed' | 'fixed', 'hourly' |
| `deadline` | DATE | NULLABLE | Project deadline |
| `started_at` | TIMESTAMP | NULLABLE | Start date |
| `completed_at` | TIMESTAMP | NULLABLE | Completion date |
| `priority` | ENUM | DEFAULT 'medium' | 'low', 'medium', 'high', 'urgent' |
| `status` | ENUM | DEFAULT 'active' | 'active', 'in_progress', 'review', 'completed', 'cancelled' |
| `attachments` | JSON | NULLABLE | File paths array |
| `created_at` | TIMESTAMP | AUTO | Record creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Record update timestamp |

**Indexes:**
- `status`, `priority` (composite)
- `client_id`, `status` (composite)
- `deadline`
- `service_request_id` (unique)

**Relationships:**
- Belongs to: service_request, user (client)
- Has many: tasks, project_feedback, documents
- Belongs to many: users (adiutors via project_assignments)

---

### 9. **project_assignments**
Junction table linking adiutors to projects.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Assignment ID |
| `project_id` | BIGINT UNSIGNED | FOREIGN KEY (projects) | Project reference |
| `adiutor_id` | BIGINT UNSIGNED | FOREIGN KEY (users) | Adiutor reference |
| `agreed_rate` | DECIMAL(8,2) | NULLABLE | Agreed payment rate |
| `start_date` | DATE | NULLABLE | Assignment start date |
| `expected_completion` | DATE | NULLABLE | Expected completion date |
| `status` | ENUM | DEFAULT 'assigned' | 'assigned', 'active', 'completed', 'removed' |
| `notes` | TEXT | NULLABLE | Assignment notes |
| `progress_percentage` | INTEGER | DEFAULT 0 | Progress (0-100) |
| `created_at` | TIMESTAMP | AUTO | Record creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Record update timestamp |

**Indexes:**
- `project_id`, `adiutor_id` (unique composite)
- `adiutor_id`, `status` (composite)

**Relationships:**
- Belongs to: project, user (adiutor)

---

### 10. **project_feedback**
Client feedback on completed projects.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Feedback ID |
| `project_id` | BIGINT UNSIGNED | FOREIGN KEY (projects) | Project reference |
| `client_id` | BIGINT UNSIGNED | FOREIGN KEY (users) | Client who gave feedback |
| `adiutor_id` | BIGINT UNSIGNED | FOREIGN KEY (users), NULLABLE | Specific adiutor rated |
| `rating` | INTEGER | NULLABLE | Rating 1-5 stars |
| `feedback` | TEXT | NULLABLE | Feedback text |
| `feedback_type` | ENUM | DEFAULT 'project' | 'project', 'adiutor', 'overall' |
| `is_public` | BOOLEAN | DEFAULT true | Public visibility |
| `created_at` | TIMESTAMP | AUTO | Record creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Record update timestamp |

**Indexes:**
- `project_id`, `feedback_type` (composite)
- `rating`

**Relationships:**
- Belongs to: project, user (client), user (adiutor)

---

### 11. **tasks**
Individual tasks within projects.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `taskID` | BIGINT UNSIGNED | PRIMARY KEY | Task ID (non-standard naming) |
| `project_id` | BIGINT UNSIGNED | FOREIGN KEY (projects) | Parent project |
| `assignedTo` | BIGINT UNSIGNED | FOREIGN KEY (users), NULLABLE | Assigned adiutor |
| `createdBy` | BIGINT UNSIGNED | FOREIGN KEY (users) | User who created task |
| `client_id` | BIGINT UNSIGNED | FOREIGN KEY (users) | Project client |
| `taskTitle` | VARCHAR(255) | NOT NULL | Task title |
| `taskDescription` | TEXT | NOT NULL | Task description |
| `status` | ENUM | DEFAULT 'pending' | 'pending', 'in_progress', 'completed', 'cancelled' |
| `priority` | ENUM | DEFAULT 'medium' | 'low', 'medium', 'high', 'urgent' |
| `deadline` | TIMESTAMP | NULLABLE | Task deadline |
| `dateAssigned` | TIMESTAMP | DEFAULT now() | Assignment date |
| `completedAt` | TIMESTAMP | NULLABLE | Completion timestamp |
| `allocated_budget` | DECIMAL(10,2) | NULLABLE | Budget allocated to task |
| `actual_cost` | DECIMAL(10,2) | NULLABLE | Actual cost incurred |
| `progress_percentage` | INTEGER | DEFAULT 0 | Progress (0-100) |
| `notes` | TEXT | NULLABLE | Task notes |
| `completion_notes` | TEXT | NULLABLE | Notes on completion |
| `service_request_id` | BIGINT UNSIGNED | FOREIGN KEY (service_requests), NULLABLE | **DEPRECATED** - Use project relationship |
| `created_at` | TIMESTAMP | AUTO | Record creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Record update timestamp |

**Indexes:**
- `status`, `priority`, `deadline` (composite)
- `assignedTo`, `status` (composite)
- `project_id`, `status` (composite)
- `client_id`, `status` (composite)

**Relationships:**
- Belongs to: project, user (assignedTo), user (createdBy), user (client)
- Has many: budget_change_requests, documents

**⚠️ Note:** Column naming is inconsistent (camelCase instead of snake_case). Primary key is `taskID` instead of standard `id`.

---

### 12. **budget_change_requests**
Adiutor requests to increase task budget.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Request ID |
| `task_id` | BIGINT UNSIGNED | FOREIGN KEY (tasks.taskID) | Task reference |
| `adiutor_id` | BIGINT UNSIGNED | FOREIGN KEY (users) | Requesting adiutor |
| `current_budget` | DECIMAL(10,2) | NOT NULL | Current task budget |
| `requested_budget` | DECIMAL(10,2) | NOT NULL | Requested new budget |
| `reason` | TEXT | NOT NULL | Justification for increase |
| `status` | ENUM | DEFAULT 'pending' | 'pending', 'approved', 'rejected' |
| `reviewed_by` | BIGINT UNSIGNED | FOREIGN KEY (users), NULLABLE | Admin reviewer |
| `reviewed_at` | TIMESTAMP | NULLABLE | Review timestamp |
| `review_notes` | TEXT | NULLABLE | Admin review notes |
| `created_at` | TIMESTAMP | AUTO | Record creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Record update timestamp |

**Indexes:**
- `status`, `created_at` (composite)
- `adiutor_id`

**Relationships:**
- Belongs to: task, user (adiutor), user (reviewed_by)

---

### 13. **documents**
File documents attached to various entities (polymorphic).

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `documentID` | BIGINT UNSIGNED | PRIMARY KEY | Document ID (non-standard naming) |
| `documentable_type` | VARCHAR(255) | NULLABLE | Polymorphic type (ServiceRequest, Task, Project) |
| `documentable_id` | BIGINT UNSIGNED | NULLABLE | Polymorphic ID |
| `taskID` | BIGINT UNSIGNED | FOREIGN KEY (tasks.taskID), NULLABLE | **Legacy** - Task reference |
| `service_request_id` | BIGINT UNSIGNED | FOREIGN KEY (service_requests), NULLABLE | **Legacy** - Request reference |
| `project_id` | BIGINT UNSIGNED | FOREIGN KEY (projects), NULLABLE | **Legacy** - Project reference |
| `client_id` | BIGINT UNSIGNED | FOREIGN KEY (users), NULLABLE | **Legacy** - Client reference |
| `fileName` | VARCHAR(255) | NOT NULL | Original filename |
| `filePath` | VARCHAR(500) | NOT NULL | Storage path |
| `fileType` | VARCHAR(100) | NOT NULL | MIME type |
| `fileSize` | BIGINT UNSIGNED | DEFAULT 0 | File size in bytes |
| `document_type` | VARCHAR(50) | NULLABLE | 'requirement', 'deliverable', 'reference', 'contract' |
| `description` | TEXT | NULLABLE | Document description |
| `is_public` | BOOLEAN | DEFAULT false | Client visibility |
| `is_archived` | BOOLEAN | DEFAULT false | Archive status |
| `uploaded_by` | BIGINT UNSIGNED | FOREIGN KEY (users), NULLABLE | Uploader user |
| `uploadedAt` | TIMESTAMP | NULLABLE | Upload timestamp |
| `verified_by` | BIGINT UNSIGNED | FOREIGN KEY (users), NULLABLE | Admin verifier |
| `verified_at` | TIMESTAMP | NULLABLE | Verification timestamp |
| `created_at` | TIMESTAMP | AUTO | Record creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Record update timestamp |

**Indexes:**
- `documentable_type`, `documentable_id` (composite)

**Relationships:**
- Morph to: documentable (polymorphic)
- Belongs to: task, service_request, project, user (client), user (uploaded_by), user (verified_by)

**⚠️ Note:** Uses polymorphic relationships but also maintains legacy foreign keys. Inconsistent naming (camelCase, non-standard PK).

---

### 14. **payments**
Payment tracking for service requests.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Payment ID |
| `service_request_id` | BIGINT UNSIGNED | FOREIGN KEY (service_requests) | Related service request |
| `client_id` | BIGINT UNSIGNED | FOREIGN KEY (users) | Client making payment |
| `amount` | DECIMAL(10,2) | NOT NULL | Payment amount |
| `payment_method` | VARCHAR(255) | NOT NULL | Payment method (maya, bank, etc.) |
| `payment_reference` | VARCHAR(255) | NULLABLE | Payment reference number |
| `status` | ENUM | DEFAULT 'pending' | 'pending', 'confirmed', 'failed', 'refunded', 'cancelled' |
| `notes` | TEXT | NULLABLE | Payment notes |
| `confirmed_at` | TIMESTAMP | NULLABLE | Confirmation timestamp |
| `confirmed_by` | BIGINT UNSIGNED | FOREIGN KEY (users), NULLABLE | Admin who confirmed |
| `payment_details` | JSON | NULLABLE | Additional payment info |
| `transaction_id` | VARCHAR(255) | NULLABLE | Gateway transaction ID |
| `gateway_response` | TEXT | NULLABLE | Gateway response JSON |
| `gateway_fee` | DECIMAL(8,2) | NULLABLE | Gateway processing fee |
| `created_at` | TIMESTAMP | AUTO | Record creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Record update timestamp |

**Indexes:**
- `service_request_id`, `status` (composite)
- `status`, `confirmed_at` (composite)
- `payment_reference`

**Relationships:**
- Belongs to: service_request, user (client), user (confirmed_by)

---

### 15. **feedbacks**
General feedback and support system.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Feedback ID |
| `client_id` | BIGINT UNSIGNED | FOREIGN KEY (users) | Client submitting feedback |
| `adiutor_id` | BIGINT UNSIGNED | FOREIGN KEY (users), NULLABLE | Related adiutor |
| `task_id` | BIGINT UNSIGNED | FOREIGN KEY (tasks.taskID), NULLABLE | Related task |
| `project_id` | BIGINT UNSIGNED | FOREIGN KEY (projects), NULLABLE | Related project |
| `title` | VARCHAR(255) | NULLABLE | Feedback title |
| `message` | TEXT | NOT NULL | Feedback message |
| `rating` | INTEGER | NULLABLE | Rating 1-5 stars |
| `type` | ENUM | DEFAULT 'general' | 'general', 'service', 'technical', 'complaint', 'suggestion' |
| `status` | ENUM | DEFAULT 'pending' | 'pending', 'reviewed', 'in_progress', 'resolved', 'closed' |
| `priority` | ENUM | DEFAULT 'medium' | 'low', 'medium', 'high', 'urgent' |
| `admin_response` | TEXT | NULLABLE | Admin response text |
| `responded_by` | BIGINT UNSIGNED | FOREIGN KEY (users), NULLABLE | Admin responder |
| `responded_at` | TIMESTAMP | NULLABLE | Response timestamp |
| `resolved_by` | BIGINT UNSIGNED | FOREIGN KEY (users), NULLABLE | Admin resolver |
| `resolved_at` | TIMESTAMP | NULLABLE | Resolution timestamp |
| `internal_notes` | TEXT | NULLABLE | Internal admin notes |
| `category` | VARCHAR(255) | NULLABLE | Feedback category |
| `tags` | JSON | NULLABLE | Feedback tags |
| `created_at` | TIMESTAMP | AUTO | Record creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Record update timestamp |

**Indexes:**
- `client_id`
- `adiutor_id`
- `status`
- `rating`
- `created_at`

**Relationships:**
- Belongs to: user (client), user (adiutor), task, project, user (responded_by), user (resolved_by)

---

### 16. **services**
Service catalog/offerings management.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Service ID |
| `name` | VARCHAR(255) | NOT NULL | Service name |
| `description` | TEXT | NULLABLE | Service description |
| `base_price` | DECIMAL(10,2) | NULLABLE | Base price |
| `category` | VARCHAR(255) | NULLABLE | Service category |
| `features` | JSON | NULLABLE | Service features array |
| `is_active` | BOOLEAN | DEFAULT true | Active status |
| `icon` | VARCHAR(255) | NULLABLE | Icon name/path |
| `estimated_duration_days` | INTEGER | NULLABLE | Estimated duration |
| `required_skills` | JSON | NULLABLE | Required skill IDs |
| `requirements` | TEXT | NULLABLE | Service requirements |
| `created_at` | TIMESTAMP | AUTO | Record creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Record update timestamp |

**Indexes:**
- `category`, `is_active` (composite)
- `is_active`

**Relationships:**
- None (reference table)

---

### 17. **messages**
Internal messaging system between users.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Message ID |
| `sender_id` | BIGINT UNSIGNED | FOREIGN KEY (users) | Message sender |
| `recipient_id` | BIGINT UNSIGNED | FOREIGN KEY (users) | Message recipient |
| `subject` | VARCHAR(255) | NULLABLE | Message subject |
| `message` | TEXT | NOT NULL | Message content |
| `is_read` | BOOLEAN | DEFAULT false | Read status |
| `message_type` | ENUM | DEFAULT 'general' | 'general', 'project', 'task', 'system' |
| `project_id` | BIGINT UNSIGNED | FOREIGN KEY (projects), NULLABLE | Related project |
| `task_id` | BIGINT UNSIGNED | FOREIGN KEY (tasks.taskID), NULLABLE | Related task |
| `read_at` | TIMESTAMP | NULLABLE | Read timestamp |
| `created_at` | TIMESTAMP | AUTO | Record creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Record update timestamp |

**Indexes:**
- `recipient_id`, `is_read` (composite)
- `sender_id`, `created_at` (composite)
- `project_id`, `message_type` (composite)

**Relationships:**
- Belongs to: user (sender), user (recipient), project, task

---

### 18. **notifications**
Laravel notification system.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | UUID | PRIMARY KEY | Notification UUID |
| `type` | VARCHAR(255) | NOT NULL | Notification class name |
| `notifiable_type` | VARCHAR(255) | NOT NULL | Polymorphic type (User) |
| `notifiable_id` | BIGINT UNSIGNED | NOT NULL | Polymorphic ID (user_id) |
| `data` | TEXT | NOT NULL | Notification data JSON |
| `read_at` | TIMESTAMP | NULLABLE | Read timestamp |
| `created_at` | TIMESTAMP | AUTO | Record creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Record update timestamp |

**Indexes:**
- `notifiable_type`, `notifiable_id` (composite)

**Relationships:**
- Morph to: notifiable (polymorphic, typically User)

---

## Supporting Tables

### 19. **password_reset_tokens**
Laravel password reset functionality.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `email` | VARCHAR(255) | PRIMARY KEY | User email |
| `token` | VARCHAR(255) | NOT NULL | Reset token |
| `created_at` | TIMESTAMP | NULLABLE | Token creation time |

---

### 20. **sessions**
Laravel session management.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | VARCHAR(255) | PRIMARY KEY | Session ID |
| `user_id` | BIGINT UNSIGNED | NULLABLE, INDEXED | User ID if authenticated |
| `ip_address` | VARCHAR(45) | NULLABLE | Client IP address |
| `user_agent` | TEXT | NULLABLE | Browser user agent |
| `payload` | LONGTEXT | NOT NULL | Session data |
| `last_activity` | INTEGER | INDEXED | Last activity timestamp |

---

### 21. **cache**
Laravel cache storage.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `key` | VARCHAR(255) | PRIMARY KEY | Cache key |
| `value` | MEDIUMTEXT | NOT NULL | Cached value |
| `expiration` | INTEGER | NOT NULL | Expiration timestamp |

---

### 22. **cache_locks**
Laravel cache lock mechanism.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `key` | VARCHAR(255) | PRIMARY KEY | Lock key |
| `owner` | VARCHAR(255) | NOT NULL | Lock owner |
| `expiration` | INTEGER | NOT NULL | Lock expiration |

---

### 23. **jobs**
Laravel queue jobs.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Job ID |
| `queue` | VARCHAR(255) | INDEXED | Queue name |
| `payload` | LONGTEXT | NOT NULL | Job payload |
| `attempts` | TINYINT UNSIGNED | NOT NULL | Attempt count |
| `reserved_at` | INTEGER UNSIGNED | NULLABLE | Reserved timestamp |
| `available_at` | INTEGER UNSIGNED | NOT NULL | Available timestamp |
| `created_at` | INTEGER UNSIGNED | NOT NULL | Creation timestamp |

---

### 24. **job_batches**
Laravel job batching.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | VARCHAR(255) | PRIMARY KEY | Batch ID |
| `name` | VARCHAR(255) | NOT NULL | Batch name |
| `total_jobs` | INTEGER | NOT NULL | Total jobs |
| `pending_jobs` | INTEGER | NOT NULL | Pending count |
| `failed_jobs` | INTEGER | NOT NULL | Failed count |
| `failed_job_ids` | LONGTEXT | NOT NULL | Failed job IDs |
| `options` | MEDIUMTEXT | NULLABLE | Batch options |
| `cancelled_at` | INTEGER | NULLABLE | Cancellation time |
| `created_at` | INTEGER | NOT NULL | Creation time |
| `finished_at` | INTEGER | NULLABLE | Finish time |

---

### 25. **failed_jobs**
Laravel failed job tracking.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY | Failed job ID |
| `uuid` | VARCHAR(255) | UNIQUE | Job UUID |
| `connection` | TEXT | NOT NULL | Queue connection |
| `queue` | TEXT | NOT NULL | Queue name |
| `payload` | LONGTEXT | NOT NULL | Job payload |
| `exception` | LONGTEXT | NOT NULL | Exception details |
| `failed_at` | TIMESTAMP | DEFAULT CURRENT | Failure timestamp |

---

## Relationships Summary

### **User Relationships**
```
User (role: client)
├── has one: ClientProfile
├── has many: ServiceRequest (as client)
├── has many: Project (as client)
├── has many: Task (as client)
├── has many: Payment (as client)
├── has many: Feedback (as client)
└── has many: Message (as sender/recipient)

User (role: adiutor)
├── has one: AdiutorProfile
│   └── has many: AdiutorSkill
│       └── belongs to: Skill
├── belongs to many: Project (via ProjectAssignment)
├── has many: Task (as assignedTo)
├── has many: BudgetChangeRequest (as adiutor)
└── has many: Document (as uploaded_by)

User (role: admin)
├── has many: ServiceRequest (as approved_by)
├── has many: Payment (as confirmed_by)
└── has many: BudgetChangeRequest (as reviewed_by)
```

### **Workflow Relationships**
```
ServiceRequest
├── belongs to: User (client)
├── has many: RequestAttachment
├── has many: Payment
└── has one: Project
    ├── belongs to many: User (adiutors via ProjectAssignment)
    ├── has many: Task
    │   ├── has many: BudgetChangeRequest
    │   └── morph many: Document
    ├── has many: ProjectFeedback
    └── morph many: Document
```

---

## Indexes & Performance

### **Critical Indexes**

#### High-Traffic Queries
```sql
-- User authentication & role checking
users: INDEX(email), INDEX(role, status)

-- Service request filtering
service_requests: INDEX(status, priority), INDEX(client_id, status), INDEX(deadline)

-- Project management
projects: INDEX(status, priority), INDEX(client_id, status), INDEX(deadline)

-- Task assignment & filtering
tasks: INDEX(status, priority, deadline), INDEX(assignedTo, status), INDEX(project_id, status)

-- Message inbox
messages: INDEX(recipient_id, is_read), INDEX(sender_id, created_at)

-- Payment tracking
payments: INDEX(service_request_id, status), INDEX(payment_reference)
```

#### Polymorphic Relationships
```sql
documents: INDEX(documentable_type, documentable_id)
notifications: INDEX(notifiable_type, notifiable_id)
```

#### Junction Tables
```sql
project_assignments: UNIQUE(project_id, adiutor_id), INDEX(adiutor_id, status)
adiutor_skills: UNIQUE(adiutor_id, skill_id), INDEX(proficiency_level)
```

---

## Database Issues & Technical Debt

### ⚠️ **Naming Inconsistencies**

#### Non-Standard Primary Keys
- `tasks.taskID` → Should be `tasks.id`
- `documents.documentID` → Should be `documents.id`

#### CamelCase Columns
```sql
-- tasks table
assignedTo → assigned_to
createdBy → created_by
completedAt → completed_at
taskTitle → task_title
taskDescription → task_description

-- documents table
taskID → task_id (or remove if using polymorphic)
fileName → file_name
filePath → file_path
fileType → file_type
fileSize → file_size
uploadedAt → uploaded_at
```

### ⚠️ **Redundant Columns**

#### tasks table
- `service_request_id` is redundant (accessible via `project.service_request_id`)
- `client_id` is redundant (accessible via `project.client_id`)

#### documents table
- Legacy foreign keys (`taskID`, `service_request_id`, `project_id`, `client_id`) are redundant when using polymorphic `documentable_*` columns

### ⚠️ **Missing Constraints**

#### Soft Deletes
Consider adding `deleted_at` timestamp to main tables for soft delete functionality:
- users
- projects
- tasks
- service_requests

#### Default Values
Some NULLABLE columns might benefit from defaults:
- `progress_percentage` columns (should always default to 0)
- Status enums (ensure all have sensible defaults)

---

## Migration Order

When rebuilding database, run migrations in this order:

1. `2025_10_15_000001_create_users_system.php`
2. `2025_10_15_000002_create_profile_system.php`
3. `2025_10_15_000003_create_service_request_system.php`
4. `2025_10_15_000004_create_project_system.php`
5. `2025_10_15_000005_create_task_system.php`
6. `2025_10_15_000006_create_payment_system.php`
7. `2025_10_15_000007_create_services_system.php`
8. `2025_10_15_000008_create_supporting_system_tables.php`
9. `2025_10_15_000009_create_feedbacks_table.php`
10. `2025_10_15_000010_create_documents_table.php`
11. `2025_10_15_054011_create_budget_change_requests_table.php`

---

## Recommendations

### **Immediate Actions**

1. **Standardize naming conventions** - Create migration to rename non-standard columns
2. **Remove redundant columns** - Clean up legacy columns in tasks and documents
3. **Add soft deletes** - Implement `deleted_at` for main entities
4. **Document polymorphic usage** - Ensure consistent use of polymorphic relationships

### **Future Enhancements**

1. **Add audit trail table** - Track all data changes with user, timestamp, old/new values
2. **Implement table partitioning** - For large tables (notifications, messages) as data grows
3. **Add full-text indexes** - For search functionality on description/message fields
4. **Create database views** - For common complex queries (dashboard statistics, etc.)

---

**Document Version:** 1.0  
**Generated:** October 21, 2025  
**Total Tables:** 25 (19 application + 6 Laravel framework)

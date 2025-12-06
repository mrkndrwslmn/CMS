# CMS Feature Inventory
**Complete System Documentation**
*Last Updated: December 6, 2025*

---

## Table of Contents
1. [Authentication & Security](#1-authentication--security)
2. [User Management](#2-user-management)
3. [Service Request Management](#3-service-request-management)
4. [Project Management](#4-project-management)
5. [Task Management](#5-task-management)
6. [Time Tracking & Earnings](#6-time-tracking--earnings)
7. [Payment Management](#7-payment-management)
8. [Document Management](#8-document-management)
9. [Communication System](#9-communication-system)
10. [Loyalty & Rewards](#10-loyalty--rewards)
11. [Revision Management](#11-revision-management)
12. [Reporting & Analytics](#12-reporting--analytics)
13. [System Administration](#13-system-administration)
14. [Public Website](#14-public-website)
15. [Integrations](#15-integrations)

---

## 1. Authentication & Security

### 1.1 Authentication System
**Controller:** `Auth\AuthController`

| Function | Description | Access |
|----------|-------------|--------|
| `showLogin()` | Display login form | Guest |
| `login()` | Process login with rate limiting (5 attempts/min), lockout protection, session regeneration | Guest |
| `showRegister()` | Display registration form | Guest |
| `register()` | Process user registration with rate limiting (3 attempts/min) | Guest |
| `logout()` | Log out user and invalidate session | Authenticated |
| `showForgotPassword()` | Display password reset request form | Guest |
| `sendResetLink()` | Send password reset email with rate limiting (3 attempts/min) | Guest |
| `showResetPassword()` | Display password reset form with token | Guest |
| `resetPassword()` | Process password reset with rate limiting (5 attempts/min) | Guest |

### 1.2 Firebase Social Authentication
**Controller:** `Auth\FirebaseAuthController`

| Function | Description | Access |
|----------|-------------|--------|
| `handleCallback()` | Verify Firebase ID token, find/create user, handle social login (Google, Facebook) | Guest |
| `initiateLink()` | Begin account linking process for existing users | Authenticated |
| `unlinkAccount()` | Unlink Firebase social account from user | Authenticated |
| `getConfig()` | Return Firebase configuration for frontend | Guest |

---

## 2. User Management

### 2.1 Admin User Management
**Controller:** `Admin\UserManagementController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List all users with search, role filter, status filter, pagination | Admin |
| `create()` | Display user creation form | Admin |
| `store()` | Create new user, auto-generate credentials, send welcome email | Admin |
| `show()` | View user details | Admin |
| `edit()` | Display user edit form | Admin |
| `update()` | Update user information | Admin |
| `destroy()` | Delete user | Admin |
| `toggleStatus()` | Activate/deactivate user account | Admin |
| `bulkAction()` | Bulk activate, deactivate, or delete users | Admin |

### 2.2 Client Management
**Controller:** `Admin\ClientManagementController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List clients with search and filters | Admin |
| `archived()` | List archived/soft-deleted clients | Admin |
| `create()` | Display client creation form | Admin |
| `store()` | Create new client account | Admin |
| `show()` | View client details with project history | Admin |
| `edit()` | Display client edit form | Admin |
| `update()` | Update client information | Admin |
| `destroy()` | Soft delete client | Admin |
| `restore()` | Restore archived client | Admin |
| `export()` | Export client list to CSV | Admin |
| `bulkAction()` | Bulk actions on clients (activate, archive, delete) | Admin |
| `addNote()` | Add CRM note to client profile | Admin |
| `updateNote()` | Edit existing client note | Admin |
| `deleteNote()` | Remove client note | Admin |

### 2.3 Client Dashboard & Profile
**Controller:** `Client\ClientController`

| Function | Description | Access |
|----------|-------------|--------|
| `dashboard()` | Display client dashboard with project overview, stats | Client |
| `index()` | List client's projects | Client |
| `tasks()` | View tasks across client's projects | Client |
| `documents()` | View accessible documents (payment-gated) | Client |
| `requests()` | View service requests history | Client |
| `feedback()` | View submitted feedback | Client |
| `profile()` | Display profile page | Client |
| `updateProfile()` | Update client profile information | Client |
| `showProject()` | View specific project details | Client |
| `downloadDocument()` | Download document (with payment verification) | Client |

### 2.4 Adiutor Dashboard & Profile
**Controller:** `Adiutor\AdiutorController`

| Function | Description | Access |
|----------|-------------|--------|
| `dashboard()` | Display adiutor dashboard with assignments, earnings summary | Adiutor |
| `clients()` | View assigned clients | Adiutor |
| `documents()` | View project documents | Adiutor |
| `feedback()` | View feedback received | Adiutor |
| `groupChats()` | List project group chats | Adiutor |
| `showGroupChat()` | View specific group chat | Adiutor |

### 2.5 Adiutor Profile Management
**Controller:** `Adiutor\ProfileController`

| Function | Description | Access |
|----------|-------------|--------|
| `show()` | Display profile with skills and settings | Adiutor |
| `edit()` | Display profile edit form | Adiutor |
| `update()` | Update profile information | Adiutor |
| `updateSkills()` | Update skill set | Adiutor |
| `earningsSettings()` | View earnings and payout settings | Adiutor |
| `updateEarningsSettings()` | Update payout method and preferences | Adiutor |

---

## 3. Service Request Management

### 3.1 Public Service Request
**Controller:** `PublicServiceRequestController`

| Function | Description | Access |
|----------|-------------|--------|
| `create()` | Display public service request form (Get Started page) | Public |
| `store()` | Submit service request, auto-create client account if new | Public |

### 3.2 Client Service Request
**Controller:** `Client\ServiceRequestController`

| Function | Description | Access |
|----------|-------------|--------|
| `create()` | Display service request form for logged-in clients | Client |
| `store()` | Submit service request with attachments | Client |
| `show()` | View service request details | Client |
| `showPayment()` | View payment information for request | Client |
| `downloadAttachment()` | Download request attachment | Client |

### 3.3 Admin Request Management
**Controller:** `Admin\RequestManagementController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List all service requests with filters (status, type, priority, client, date) | Admin |
| `show()` | View service request details with client info, attachments, payments | Admin |
| `approve()` | Approve request with budget and payment type | Admin |
| `reject()` | Reject request with reason | Admin |
| `reopen()` | Reopen rejected request | Admin |
| `requestPayment()` | Send payment request to client | Admin |
| `confirmPayment()` | Manually confirm payment (deprecated - Maya handles automatically) | Admin |
| `updatePriority()` | Change request priority | Admin |
| `addNote()` | Add admin note to request | Admin |
| `bulkAction()` | Bulk actions on requests | Admin |
| `downloadFile()` | Download request attachment | Admin |
| `export()` | Export requests to CSV | Admin |

---

## 4. Project Management

### 4.1 Admin Project Management
**Controller:** `Admin\ProjectManagementController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List projects with filters (status, priority, client, search, date) | Admin |
| `create()` | Display project creation form | Admin |
| `store()` | Create project from service request | Admin |
| `show()` | View project details with budget overview, team, tasks | Admin |
| `edit()` | Display project edit form | Admin |
| `update()` | Update project information | Admin |
| `destroy()` | Delete project | Admin |
| `schedule()` | View project scheduling calendar | Admin |
| `addNote()` | Add note to project | Admin |
| `updateStatus()` | Change project status | Admin |
| `complete()` | Mark project as completed | Admin |
| `bulkAction()` | Bulk actions on projects | Admin |
| `assignAdiutor()` | Assign adiutor to project with rate type (hourly/fixed) | Admin |
| `removeAdiutor()` | Remove adiutor from project | Admin |
| `getTeamMembers()` | Get list of assigned team members | Admin |
| `getProjectPhases()` | Get project phases/milestones | Admin |
| `approveFixedRate()` | Approve fixed rate for adiutor assignment | Admin |
| `revokeFixedRateApproval()` | Revoke fixed rate approval | Admin |
| `updateAssignmentPayment()` | Update assignment payment settings | Admin |
| `getAdiutorTimeEntries()` | View adiutor time entries for project | Admin |

### 4.2 Adiutor Project View
**Controller:** `Adiutor\ProjectController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List assigned projects | Adiutor |
| `show()` | View project details and tasks | Adiutor |
| `accept()` | Accept project assignment | Adiutor |
| `decline()` | Decline project assignment with reason | Adiutor |
| `updateProgress()` | Update assignment progress | Adiutor |
| `createTask()` | Create task within project | Adiutor |

### 4.3 Project Templates
**Controller:** `Admin\AdminTemplateController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List project templates | Admin |
| `create()` | Display template creation form | Admin |
| `store()` | Create project template with phases and tasks | Admin |
| `show()` | View template details | Admin |
| `edit()` | Display template edit form | Admin |
| `update()` | Update template | Admin |
| `destroy()` | Delete template | Admin |
| `toggle()` | Activate/deactivate template | Admin |
| `duplicate()` | Duplicate existing template | Admin |

### 4.4 Project Workflow
**Controller:** `Admin\WorkflowController`

| Function | Description | Access |
|----------|-------------|--------|
| `approveRequest()` | Approve service request and transition to project | Admin |
| `rejectRequest()` | Reject service request | Admin |
| `confirmPayment()` | Confirm payment and activate project | Admin |
| `createTask()` | Create task within project workflow | Admin |
| `getProjectBudgetOverview()` | Get project budget summary | Admin |

---

## 5. Task Management

### 5.1 Admin Task Management
**Controller:** `Admin\TaskManagementController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List all tasks with filters (status, priority, project, adiutor) | Admin |
| `create()` | Display task creation form | Admin |
| `store()` | Create task with budget, deadline, assignment | Admin |
| `show()` | View task details with time entries, files | Admin |
| `edit()` | Display task edit form | Admin |
| `update()` | Update task information | Admin |
| `destroy()` | Delete task | Admin |
| `assign()` | Assign task to adiutor | Admin |
| `updateStatus()` | Change task status | Admin |
| `updateNotes()` | Update task notes | Admin |
| `updateBudget()` | Update task allocated budget | Admin |
| `budgetOverview()` | View budget overview for service request | Admin |
| `bulkAction()` | Bulk status change or delete | Admin |
| `reorder()` | Reorder tasks within project | Admin |

### 5.2 Adiutor Task Management
**Controller:** `Adiutor\TaskController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List assigned tasks with filters | Adiutor |
| `show()` | View task details with subtasks, deliverables | Adiutor |
| `store()` | Create new task (if permitted) | Adiutor |
| `updateStatus()` | Update task status | Adiutor |
| `markCompleted()` | Mark task as completed | Adiutor |
| `addNote()` | Add note to task | Adiutor |
| `uploadFile()` | Upload file/deliverable to task | Adiutor |
| `addLinkDeliverable()` | Add link-based deliverable | Adiutor |
| `downloadFile()` | Download task file | Adiutor |
| `deleteFile()` | Delete uploaded file | Adiutor |
| `requestBudgetChange()` | Request task budget modification | Adiutor |
| `updateTaskProgress()` | Update task progress percentage | Adiutor |
| `getDeliverables()` | Get list of task deliverables | Adiutor |

### 5.3 Subtask Management (Admin)
**Controller:** `Admin\SubtaskController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List subtasks for a task | Admin |
| `store()` | Create subtask | Admin |
| `bulkStore()` | Create multiple subtasks at once | Admin |
| `update()` | Update subtask | Admin |
| `toggle()` | Toggle subtask completion | Admin |
| `complete()` | Mark subtask complete | Admin |
| `incomplete()` | Mark subtask incomplete | Admin |
| `destroy()` | Delete subtask | Admin |
| `reorder()` | Reorder subtasks | Admin |

### 5.4 Subtask Management (Adiutor)
**Controller:** `Adiutor\SubtaskController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List subtasks for assigned task | Adiutor |
| `store()` | Create subtask | Adiutor |
| `update()` | Update subtask | Adiutor |
| `toggle()` | Toggle subtask completion | Adiutor |
| `destroy()` | Delete subtask | Adiutor |
| `reorder()` | Reorder subtasks | Adiutor |

### 5.5 Task Deliverables (Admin)
**Controller:** `Admin\TaskDeliverableController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List deliverables for a task | Admin |
| `store()` | Create deliverable requirement | Admin |
| `update()` | Update deliverable | Admin |
| `approve()` | Approve submitted deliverable | Admin |
| `reject()` | Reject deliverable with reason | Admin |
| `revokeApproval()` | Revoke previous approval | Admin |
| `destroy()` | Delete deliverable | Admin |
| `checkDeliverables()` | Check deliverable completion status | Admin |

### 5.6 Task Deliverables (Adiutor)
**Controller:** `Adiutor\TaskDeliverableController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List deliverables for assigned task | Adiutor |
| `store()` | Upload/submit deliverable | Adiutor |
| `update()` | Update deliverable | Adiutor |
| `destroy()` | Delete deliverable | Adiutor |
| `check()` | Check deliverable requirements | Adiutor |

---

## 6. Time Tracking & Earnings

### 6.1 Time Tracking
**Controller:** `Adiutor\TimeTrackingController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | Display time tracking dashboard | Adiutor |
| `start()` | Start timer for a task | Adiutor |
| `stop()` | Stop timer and save time entry | Adiutor |
| `status()` | Get current timer status | Adiutor |
| `entries()` | List time entries with filters | Adiutor |
| `update()` | Edit time entry (before approval) | Adiutor |
| `delete()` | Delete time entry (before approval) | Adiutor |

### 6.2 Adiutor Earnings
**Controller:** `Adiutor\EarningsController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | View earnings overview and history | Adiutor |
| `wallet()` | View wallet balance and transactions | Adiutor |
| `payouts()` | List payout history | Adiutor |
| `showPayout()` | View specific payout details | Adiutor |
| `showRequestForm()` | Display payout request form | Adiutor |
| `requestPayout()` | Submit payout request | Adiutor |

### 6.3 Hour Increase Requests (Adiutor)
**Controller:** `Adiutor\HourIncreaseRequestController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List hour increase requests | Adiutor |
| `create()` | Display request form | Adiutor |
| `store()` | Submit hour increase request | Adiutor |
| `show()` | View request details | Adiutor |
| `cancel()` | Cancel pending request | Adiutor |

### 6.4 Hour Increase Requests (Admin)
**Controller:** `Admin\HourIncreaseRequestController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List all hour increase requests | Admin |
| `show()` | View request details | Admin |
| `approve()` | Approve request and update max hours | Admin |
| `reject()` | Reject request with reason | Admin |
| `quickApprove()` | Quick approve with default settings | Admin |

### 6.5 Budget Change Requests (Adiutor)
**Controller:** `Adiutor\BudgetChangeRequestController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List budget change requests | Adiutor |
| `create()` | Display request form | Adiutor |
| `store()` | Submit budget change request | Adiutor |
| `show()` | View request details | Adiutor |
| `cancel()` | Cancel pending request | Adiutor |

### 6.6 Budget Change Requests (Admin)
**Controller:** `Admin\BudgetChangeRequestController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List all budget change requests | Admin |
| `show()` | View request details | Admin |
| `approve()` | Approve and update task budget | Admin |
| `reject()` | Reject with reason | Admin |
| `destroy()` | Delete request | Admin |

### 6.7 Payout Management
**Controller:** `Admin\PayoutManagementController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List all payout requests | Admin |
| `show()` | View payout details with items | Admin |
| `markAsProcessing()` | Mark payout as being processed | Admin |
| `complete()` | Complete payout with reference number | Admin |
| `cancel()` | Cancel payout request | Admin |
| `adiutorEarnings()` | View specific adiutor's earnings | Admin |
| `timeEntryApprovals()` | List pending time entry approvals | Admin |
| `approveTimeEntries()` | Bulk approve time entries | Admin |
| `getTimeEntryDetails()` | Get time entry details for review | Admin |
| `approveTimeEntry()` | Approve single time entry with optional adjustment | Admin |
| `rejectTimeEntry()` | Reject time entry with reason | Admin |
| `export()` | Export payouts to CSV | Admin |

### 6.8 Earnings Analytics
**Controller:** `Admin\EarningsAnalyticsController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | Earnings analytics dashboard | Admin |
| `leaderboard()` | Adiutor earnings leaderboard | Admin |
| `projectCosts()` | Project cost breakdown | Admin |
| `auditLog()` | Time entry and adjustment audit log | Admin |
| `payoutHistory()` | Complete payout history | Admin |
| `export()` | Export earnings data | Admin |

---

## 7. Payment Management

### 7.1 Maya Payment Gateway (Client)
**Controller:** `Client\MayaPaymentController`

| Function | Description | Access |
|----------|-------------|--------|
| `checkout()` | Initiate Maya payment checkout | Client |
| `success()` | Handle successful payment callback | Client |
| `failure()` | Handle failed payment callback | Client |
| `cancel()` | Handle cancelled payment callback | Client |

### 7.2 Payment History (Client)
**Controller:** `Client\PaymentHistoryController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List payment history | Client |
| `show()` | View payment details | Client |
| `receipt()` | Generate/download payment receipt | Client |

### 7.3 Payment Management (Admin)
**Controller:** `Admin\PaymentManagementController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List all payments with filters | Admin |
| `show()` | View payment details | Admin |
| `updateStatus()` | Update payment status | Admin |
| `export()` | Export payments to CSV | Admin |

---

## 8. Document Management

### 8.1 Document Management (Admin)
**Controller:** `Admin\DocumentManagementController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List all documents with filters | Admin |
| `create()` | Display document upload form | Admin |
| `store()` | Upload document to Cloudflare R2 | Admin |
| `show()` | View document details | Admin |
| `edit()` | Display document edit form | Admin |
| `update()` | Update document metadata | Admin |
| `destroy()` | Soft delete document | Admin |
| `download()` | Download document file | Admin |
| `preview()` | Preview document | Admin |
| `bulkAction()` | Bulk archive, delete, or restore | Admin |
| `search()` | Search documents | Admin |
| `uploadToProject()` | Upload document to specific project | Admin |
| `bulkCreate()` | Display bulk upload form | Admin |
| `bulkStore()` | Process bulk document upload | Admin |
| `trash()` | View trashed documents | Admin |
| `restore()` | Restore soft-deleted document | Admin |
| `forceDelete()` | Permanently delete document | Admin |
| `bulkRestore()` | Bulk restore documents | Admin |
| `emptyTrash()` | Permanently delete all trashed documents | Admin |
| `pendingDeliverables()` | List deliverables awaiting approval | Admin |
| `approveDeliverable()` | Approve document as deliverable | Admin |
| `rejectDeliverable()` | Reject deliverable | Admin |
| `revokeApproval()` | Revoke deliverable approval | Admin |

---

## 9. Communication System

### 9.1 Project Messaging (Client-Admin)
**Controller:** `Api\MessageController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List conversations | Authenticated |
| `show()` | Get messages for a project conversation | Authenticated |
| `store()` | Send message with optional attachments | Authenticated |
| `markAsRead()` | Mark messages as read | Authenticated |
| `updateFcmToken()` | Update Firebase Cloud Messaging token | Authenticated |
| `unreadCount()` | Get unread message count | Authenticated |
| `destroy()` | Delete message | Authenticated |
| `search()` | Search messages | Authenticated |

### 9.2 Group Chat (Admin-Adiutor)
**Controller:** `Api\GroupChatController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List group chats | Admin/Adiutor |
| `show()` | Get group chat messages | Admin/Adiutor |
| `store()` | Send message to group chat | Admin/Adiutor |
| `markAsRead()` | Mark group messages as read | Admin/Adiutor |
| `archive()` | Archive group chat | Admin |
| `reopen()` | Reopen archived group chat | Admin |
| `unreadCount()` | Get unread group message count | Admin/Adiutor |

### 9.3 Meeting Scheduling
**Controller:** `Api\MeetingController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List meetings for a project | Authenticated |
| `store()` | Request/create meeting | Authenticated |
| `approve()` | Approve meeting request | Admin/Client |
| `reschedule()` | Request meeting reschedule | Authenticated |
| `approveReschedule()` | Approve reschedule request | Admin/Client |
| `rejectReschedule()` | Reject reschedule request | Admin/Client |
| `reject()` | Reject meeting request | Admin/Client |
| `destroy()` | Delete/cancel meeting | Authenticated |

### 9.4 Notifications
**Controller:** `NotificationController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | Display notifications page | Authenticated |
| `fetch()` | Fetch notifications via AJAX | Authenticated |
| `markAsRead()` | Mark single notification as read | Authenticated |
| `markAllAsRead()` | Mark all notifications as read | Authenticated |
| `destroy()` | Delete notification | Authenticated |

### 9.5 Announcements
**Controller:** `Admin\AnnouncementController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List all announcements | Admin |
| `store()` | Create announcement with target audience | Admin |
| `update()` | Update announcement | Admin |
| `destroy()` | Delete announcement | Admin |
| `getActive()` | Get active announcements for display | Public |

---

## 10. Loyalty & Rewards

### 10.1 Coupon Management (Admin)
**Controller:** `Admin\CouponController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List all coupons with filters | Admin |
| `create()` | Display coupon creation form | Admin |
| `store()` | Create coupon (public or user-specific) | Admin |
| `show()` | View coupon details with usage stats | Admin |
| `edit()` | Display coupon edit form | Admin |
| `update()` | Update coupon | Admin |
| `destroy()` | Delete coupon | Admin |
| `toggleStatus()` | Activate/deactivate coupon | Admin |
| `usageHistory()` | View coupon usage history | Admin |
| `bulkGenerate()` | Generate multiple unique coupon codes | Admin |
| `checkCode()` | Validate coupon code availability | Admin |

### 10.2 Coupon Usage (Client)
**Controller:** `Client\CouponController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List available coupons | Client |
| `show()` | View coupon details | Client |
| `validateCode()` | Validate coupon code | Client |
| `applyCoupon()` | Apply coupon to service request | Client |
| `removeCoupon()` | Remove applied coupon | Client |
| `copyCode()` | Copy coupon code | Client |

### 10.3 Loyalty Program (Admin)
**Controller:** `Admin\LoyaltyController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | Loyalty program dashboard | Admin |
| `show()` | View user loyalty details | Admin |
| `adjustPoints()` | Manually adjust user points | Admin |
| `leaderboard()` | View loyalty leaderboard | Admin |
| `transactions()` | View all loyalty transactions | Admin |
| `userTransactions()` | View specific user's transactions | Admin |
| `tierSettings()` | View tier configuration | Admin |
| `updateTierSettings()` | Update tier thresholds and benefits | Admin |
| `exportLoyaltyReport()` | Export loyalty data | Admin |
| `exportUserReport()` | Export specific user's loyalty data | Admin |
| `sendExpiryWarnings()` | Send point expiration warnings | Admin |
| `dashboardWidget()` | Get loyalty widget data | Admin |

### 10.4 Loyalty Program (Client)
**Controller:** `Client\LoyaltyController`

| Function | Description | Access |
|----------|-------------|--------|
| `dashboard()` | View loyalty dashboard with tier info | Client |
| `transactions()` | View points transaction history | Client |
| `redeemPoints()` | Redeem points for discount | Client |
| `removeRedemption()` | Remove points redemption | Client |
| `widgetData()` | Get loyalty widget data | Client |
| `calculateEarning()` | Calculate points to be earned | Client |
| `calculateDiscount()` | Calculate discount from points | Client |

### 10.5 Referral Program (Admin)
**Controller:** `Admin\ReferralController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | Referral program dashboard | Admin |
| `list()` | List all referrals | Admin |
| `show()` | View referral details | Admin |
| `codes()` | Manage referral codes | Admin |
| `toggleCodeStatus()` | Activate/deactivate referral code | Admin |
| `analytics()` | View referral analytics | Admin |
| `export()` | Export referral data | Admin |
| `processPending()` | Process pending referral | Admin |
| `withdrawalsPending()` | List pending credit withdrawals | Admin |
| `showWithdrawal()` | View withdrawal request details | Admin |
| `processWithdrawal()` | Begin processing withdrawal | Admin |
| `completeWithdrawal()` | Complete withdrawal | Admin |
| `rejectWithdrawal()` | Reject withdrawal request | Admin |

### 10.6 Referral Program (Client)
**Controller:** `Client\ReferralController`

| Function | Description | Access |
|----------|-------------|--------|
| `dashboard()` | View referral dashboard | Client |
| `share()` | View sharing options | Client |
| `getCode()` | Get personal referral code | Client |
| `validateCode()` | Validate referral code | Client |
| `getStats()` | Get referral statistics | Client |
| `history()` | View referral history | Client |
| `sendInvitation()` | Send referral invitation email | Client |
| `generateLink()` | Generate shareable referral link | Client |
| `credits()` | View earned credits | Client |
| `requestWithdrawal()` | Request credit withdrawal | Client |
| `showWithdrawal()` | View withdrawal status | Client |
| `cancelWithdrawal()` | Cancel pending withdrawal | Client |

---

## 11. Revision Management

### 11.1 Revision Requests (Client)
**Controller:** `Client\RevisionRequestController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List revision requests | Client |
| `show()` | View revision request details | Client |
| `create()` | Display document revision form | Client |
| `store()` | Submit document revision request | Client |
| `storeForProject()` | Submit project-level revision | Client |
| `storeForTask()` | Submit task-level revision | Client |
| `cancel()` | Cancel pending revision request | Client |

### 11.2 Revision Management (Admin)
**Controller:** `Admin\RevisionController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List all revision requests | Admin |
| `show()` | View revision request details | Admin |
| `approve()` | Approve revision and assign adiutor | Admin |
| `reject()` | Reject revision with reason | Admin |
| `reassign()` | Reassign revision to different adiutor | Admin |

### 11.3 Revision Completion (Adiutor)
**Controller:** `Adiutor\RevisionController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List assigned revisions | Adiutor |
| `show()` | View revision details | Adiutor |
| `complete()` | Mark revision as completed with upload | Adiutor |
| `uploadForm()` | Display revision upload form | Adiutor |

---

## 12. Reporting & Analytics

### 12.1 Admin Dashboard
**Controller:** `Admin\AdminController`

| Function | Description | Access |
|----------|-------------|--------|
| `dashboard()` | Main admin dashboard with KPIs | Admin |
| `refreshDashboard()` | Refresh dashboard data via AJAX | Admin |
| `downloadReport()` | Download dashboard report | Admin |
| `messages()` | View messaging interface | Admin |
| `showMessages()` | View project messages | Admin |
| `profile()` | View admin profile | Admin |
| `updateProfile()` | Update admin profile | Admin |

### 12.2 Reporting
**Controller:** `Admin\ReportingController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | Reports overview page | Admin |
| `dashboard()` | Dashboard report | Admin |
| `users()` | User statistics report | Admin |
| `tasks()` | Task analytics report | Admin |
| `requests()` | Service request report | Admin |
| `projects()` | Project analytics report | Admin |
| `documents()` | Document statistics report | Admin |
| `export()` | Export report data | Admin |
| `customReport()` | Custom report builder | Admin |
| `generateCustomReport()` | Generate custom report | Admin |
| `previewCustomReport()` | Preview custom report | Admin |
| `saveCustomTemplate()` | Save report template | Admin |
| `getCustomTemplates()` | Get saved templates | Admin |
| `getFilterOptions()` | Get filter options for reports | Admin |

### 12.3 Audit Logging
**Controller:** `Admin\AdminAuditController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | Audit log viewer | Admin |
| `show()` | View audit log entry details | Admin |
| `logs()` | Get audit logs via API | Admin |
| `statistics()` | Get audit statistics | Admin |
| `export()` | Export audit logs | Admin |
| `cleanup()` | Clean up old audit logs | Admin |

### 12.4 Feedback Management
**Controller:** `Admin\FeedbackManagementController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List all feedback | Admin |
| `show()` | View feedback details | Admin |
| `respond()` | Respond to feedback | Admin |
| `updateStatus()` | Update feedback status | Admin |
| `assignTo()` | Assign feedback to team member | Admin |
| `addNote()` | Add internal note | Admin |
| `bulkAction()` | Bulk actions on feedback | Admin |
| `analytics()` | Feedback analytics | Admin |
| `export()` | Export feedback data | Admin |
| `summary()` | Get feedback summary | Admin |

### 12.5 Client Feedback
**Controller:** `Client\FeedbackController`

| Function | Description | Access |
|----------|-------------|--------|
| `create()` | Display feedback form for completed project | Client |
| `store()` | Submit project feedback and rating | Client |

---

## 13. System Administration

### 13.1 Calendar Management (Admin)
**Controller:** `Admin\CalendarController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | Admin calendar view with all projects/tasks | Admin |

### 13.2 Google Calendar Integration (Adiutor)
**Controller:** `CalendarController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | Calendar dashboard | Adiutor |
| `connection()` | View connection status | Adiutor |
| `connect()` | Initiate Google OAuth | Adiutor |
| `callback()` | Handle OAuth callback | Adiutor |
| `disconnect()` | Disconnect Google Calendar | Adiutor |
| `testConnection()` | Test calendar connection | Adiutor |
| `previewConnected()` | Preview synced events | Adiutor |
| `syncDeadlineTasks()` | Sync task deadlines to calendar | Adiutor |
| `syncAllTasks()` | Sync all tasks to calendar | Adiutor |

---

## 14. Public Website

### 14.1 Public Pages
**Routes:** Direct view returns

| Page | Route | Description |
|------|-------|-------------|
| Home | `/` | Welcome page with active announcements |
| Services | `/services` | Services overview |
| About | `/about` | About the company |
| FAQ | `/faq` | Frequently asked questions |
| Featured Projects | `/featured-projects` | Portfolio showcase |
| Client Testimonials | `/client-testimonials` | Testimonials page |
| Referral Program | `/referral-program` | Referral program info |
| Privacy Policy | `/privacy-policy` | Privacy policy |
| Terms & Conditions | `/terms-and-conditions` | Terms of service |
| Get Started | `/get-started` | Service request form |

### 14.2 AI Chatbot
**Controller:** `ChatbotController`

| Function | Description | Access |
|----------|-------------|--------|
| `chat()` | Process chat message via Gemini API | Public |
| `greeting()` | Get initial greeting message | Public |

### 14.3 Portfolio Showcase API
**Controller:** `Api\ShowcaseController`

| Function | Description | Access |
|----------|-------------|--------|
| `index()` | List showcase projects | Public |
| `show()` | View showcase project details | Public |
| `techStack()` | Get technology stack list | Public |

---

## 15. Integrations

### 15.1 External Services

| Integration | Purpose | Configuration |
|-------------|---------|---------------|
| **Maya Payment Gateway** | Client payments (full, milestone, downpayment) | `config/maya.php` |
| **Firebase Authentication** | Social login (Google, Facebook) | `config/firebase.php` |
| **Firebase Cloud Messaging** | Push notifications | `config/firebase.php` |
| **Cloudflare R2** | Document and file storage | `config/filesystems.php` |
| **Google Calendar API** | Task synchronization for adiutors | `config/services.php` |
| **Google Gemini API** | AI chatbot responses | `config/services.php` |
| **Brandfetch** | Technology stack logos | API calls |
| **Zoom** | Meeting integration | `config/services.php` |

### 15.2 API Endpoints

| Endpoint Group | Base Path | Description |
|----------------|-----------|-------------|
| Services | `/api/services` | Public service listing |
| AI Search | `/api/aiSearch` | AI-powered search |
| Showcases | `/api/showcases` | Portfolio API |
| Chatbot | `/api/chatbot` | Chatbot interactions |
| Messages | `/api/messages` | Project messaging |
| Group Chats | `/api/group-chats` | Team group chats |
| Meetings | `/api/meetings` | Meeting scheduling |
| Calendar | `/api/calendar` | Calendar integration |
| Schedule | `/api/schedule` | Task scheduling |

---

## Models Summary

| Model | Primary Key | Description |
|-------|-------------|-------------|
| `User` | `id` | All user types (admin, client, adiutor) |
| `AdiutorProfile` | `id` | Adiutor-specific profile data |
| `ClientProfile` | `id` | Client-specific profile data |
| `ServiceRequest` | `id` | Client service requests |
| `RequestAttachment` | `id` | Service request attachments |
| `Project` | `id` | Projects created from requests |
| `ProjectAssignment` | `id` | Adiutor-project assignments |
| `ProjectMilestone` | `id` | Project milestones |
| `ProjectTemplate` | `id` | Reusable project templates |
| `Task` | `taskID` | Project tasks |
| `Subtask` | `id` | Task subtasks |
| `TaskDeliverable` | `id` | Task deliverables |
| `TaskSchedule` | `id` | Task scheduling data |
| `TimeEntry` | `id` | Adiutor time entries |
| `Payment` | `id` | Client payments |
| `MilestonePayment` | `id` | Milestone payment tracking |
| `Payout` | `id` | Adiutor payout requests |
| `PayoutItem` | `id` | Payout line items |
| `WalletTransaction` | `id` | Wallet transactions |
| `Document` | `id` | Uploaded documents/files |
| `Message` | `id` | Chat messages |
| `Conversation` | `id` | Message conversations |
| `GroupChat` | `id` | Group chat rooms |
| `Meeting` | `id` | Scheduled meetings |
| `Notification` | `id` | System notifications |
| `Announcement` | `id` | Admin announcements |
| `Coupon` | `id` | Discount coupons |
| `CouponUsage` | `id` | Coupon usage tracking |
| `LoyaltyPoint` | `id` | User loyalty points |
| `LoyaltyTier` | `id` | Loyalty tier definitions |
| `LoyaltyTransaction` | `id` | Points transactions |
| `Referral` | `id` | Referral records |
| `ReferralCode` | `id` | Referral codes |
| `ReferralCreditTransaction` | `id` | Referral credit transactions |
| `ReferralCreditWithdrawal` | `id` | Withdrawal requests |
| `RevisionRequest` | `id` | Revision requests |
| `Feedback` | `id` | Project feedback |
| `ProjectFeedback` | `id` | Project feedback (alternate) |
| `Note` | `id` | Client/project notes |
| `AuditLog` | `id` | Audit trail records |
| `HourIncreaseRequest` | `id` | Hour increase requests |
| `BudgetChangeRequest` | `id` | Budget change requests |
| `AdiutorCalendarIntegration` | `id` | Calendar integrations |
| `Showcase` | `id` | Portfolio showcases |
| `ShowcaseScreenshot` | `id` | Showcase images |
| `TechStack` | `id` | Technology definitions |
| `Skill` | `id` | Adiutor skills |
| `CustomReportTemplate` | `id` | Saved report templates |

---

## User Roles & Access

| Role | Access Level | Description |
|------|--------------|-------------|
| **Admin** | Full system access | Manages all aspects of the system |
| **Client** | Limited to own data | Service requests, projects, payments, loyalty |
| **Adiutor** | Task-focused access | Assigned projects, tasks, time tracking, earnings |

---

## Core Workflows

### Service Request → Project → Task Flow
```
1. Client submits Service Request (public or authenticated)
2. Admin reviews and approves with budget/payment type
3. Client receives payment request
4. Client pays via Maya gateway
5. Admin creates Project from approved request
6. Group chat auto-created for project
7. Admin assigns Adiutors to project
8. Admin creates Tasks within project
9. Tasks assigned to specific Adiutors
10. Adiutors track time and submit deliverables
11. Admin approves time entries and deliverables
12. Project marked complete
13. Client submits feedback
14. Adiutors request payouts for approved earnings
15. Admin processes payouts
```

---

*Document generated from codebase analysis - December 2025*

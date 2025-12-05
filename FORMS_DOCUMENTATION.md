# Forms Documentation

This document provides a comprehensive analysis of all user-facing forms in the Treis Adiutor CMS system. Forms are grouped by their functional relationships and workflows.

---

## Table of Contents

1. [Authentication & Account Forms](#1-authentication--account-forms)
2. [Service Request Flow (Client → Project → Tasks)](#2-service-request-flow-client--project--tasks)
3. [Project & Task Management Forms](#3-project--task-management-forms)
4. [Payment & Financial Forms](#4-payment--financial-forms)
5. [Referral & Loyalty Program Forms](#5-referral--loyalty-program-forms)
6. [Communication Forms](#6-communication-forms)
7. [Feedback & Review Forms](#7-feedback--review-forms)
8. [Adiutor (Staff) Forms](#8-adiutor-staff-forms)
9. [Admin Management Forms](#9-admin-management-forms)

---

## 1. Authentication & Account Forms

### 1.1 User Registration Form

**Form Title:** User Registration  
**Form Description:** Allows new users to create a client account to access project management services, track progress, and collaborate with the team.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Full Name | ✅ Required | Text | `John Doe` |
| Email Address | ✅ Required | Email | `john@example.com` |
| Phone Number | ✅ Required | Tel | `+63 917 123 4567` |
| Referral Code | ❌ Optional | Text | `WELCOME2024` |
| Password | ✅ Required | Password | `SecurePass123!` |
| Confirm Password | ✅ Required | Password | `SecurePass123!` |

**Notes:**
- Referral code can be pre-filled via URL query parameter `?ref=CODE`
- Successful referral provides: 500 points + 15% discount coupon

---

### 1.2 User Login Form

**Form Title:** User Login  
**Form Description:** Authenticates existing users to access their dashboard, projects, and manage their account.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Email Address | ✅ Required | Email | `john@example.com` |
| Password | ✅ Required | Password | `SecurePass123!` |
| Remember Me | ❌ Optional | Checkbox | `checked` |

**Additional Options:**
- Google OAuth login available
- Forgot password link available

---

### 1.3 Forgot Password Form

**Form Title:** Password Reset Request  
**Form Description:** Allows users to request a password reset link via email.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Email Address | ✅ Required | Email | `john@example.com` |

---

### 1.4 Reset Password Form

**Form Title:** Reset Password  
**Form Description:** Allows users to set a new password using the reset token from email.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Email | ✅ Required | Email | `john@example.com` |
| Password | ✅ Required | Password | `NewSecurePass123!` |
| Confirm Password | ✅ Required | Password | `NewSecurePass123!` |

---

### 1.5 Client Profile Form

**Form Title:** Profile Settings  
**Form Description:** Allows clients to update their personal information, business details, and social presence.

**Form Fields:**

**Personal Information Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Full Name | ✅ Required | Text | `John Doe` |
| Email Address | ✅ Required | Email | `john@example.com` |
| Phone Number | ❌ Optional | Tel | `+1 (555) 123-4567` |
| Industry | ❌ Optional | Select | `Technology`, `Healthcare`, `Finance`, `Education`, `Retail`, `Manufacturing`, `Marketing`, `Real Estate`, `Non-profit`, `Other` |

**Business Information Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Company Name | ❌ Optional | Text | `Your Company Inc.` |
| Business Address | ❌ Optional | Textarea | `123 Business St, City, Country` |
| Professional Bio | ❌ Optional | Textarea | `Experienced entrepreneur with 10+ years...` |

**Social Presence Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Website | ❌ Optional | URL | `https://yourcompany.com` |
| LinkedIn | ❌ Optional | URL | `https://linkedin.com/in/johndoe` |
| Twitter/X | ❌ Optional | URL | `https://twitter.com/johndoe` |
| Facebook | ❌ Optional | URL | `https://facebook.com/johndoe` |

---

## 2. Service Request Flow (Client → Project → Tasks)

> **WORKFLOW RELATIONSHIP:**  
> `Service Request (Get Started)` → `Admin Approval` → `Project Created` → `Tasks Assigned` → `Task Revisions` → `Project Completion` → `Feedback`

---

### 2.1 Get Started / Service Request Form (Public)

**Form Title:** Get Started with Your Project  
**Form Description:** The main entry point for new and existing users to submit a service request. For new users, an account is automatically created. Once approved by admin, this becomes a project.

**Form Fields:**

**Contact Information (New Users Only):**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Full Name | ✅ Required | Text | `John Doe` |
| Email Address | ✅ Required | Email | `john@example.com` |
| Phone Number | ❌ Optional | Tel | `+1 (555) 000-0000` |
| Preferred Contact Method | ✅ Required | Select | `Email`, `Facebook Messenger`, `Phone` |
| Contact Details | ✅ Required | Text | `john@example.com` or `m.me/johndoe` |

**Project Details Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Project Name | ✅ Required | Text | `E-commerce Website Redesign` |
| Service Type/Category | ✅ Required | Select | (Dynamic from database - Web Development, Mobile App, etc.) |
| Project Description | ✅ Required | Textarea | `I need a modern e-commerce website with...` |
| Deadline | ❌ Optional | Date | `2024-03-15` (min: tomorrow) |
| Expectations | ❌ Optional | Textarea | `Looking for responsive design, fast loading...` |

**Additional Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Additional Notes | ❌ Optional | Textarea | `Any special requirements or questions...` |
| File Attachments | ❌ Optional | File (Multiple) | Documents, images (max 10MB each) |

**Notes:**
- Protected by reCAPTCHA
- Pre-selected service may be shown if coming from service page

---

### 2.2 Client Service Request Form (Authenticated)

**Form Title:** Create Service Request  
**Form Description:** Same as Get Started form but for authenticated clients. No contact information required.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Project Name | ✅ Required | Text | `Mobile App Development` |
| Service Type/Category | ✅ Required | Select | (Dynamic categories) |
| Project Description | ✅ Required | Textarea | `Building a cross-platform mobile app...` |
| Deadline | ❌ Optional | Date | `2024-04-01` |
| Expectations | ❌ Optional | Textarea | `Native performance, offline support...` |
| Contact Preference (Email) | ❌ Optional | Checkbox | Use account email for updates |
| Additional Notes | ❌ Optional | Textarea | `Additional context...` |
| File Attachments | ❌ Optional | File (Multiple) | Reference documents, mockups |

---

### 2.3 Apply Coupon Form

**Form Title:** Apply Coupon Code  
**Form Description:** Allows clients to apply a discount coupon to their service request before payment.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Coupon Code | ✅ Required | Text | `SAVE20`, `WELCOME2024` |

**Connected To:** Service Request → Payment

---

### 2.4 Project Revision Request Form (Client)

**Form Title:** Request Project Revision  
**Form Description:** Allows clients to request revisions for a completed or in-progress project. Can target entire project or specific tasks.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Revision Scope | ✅ Required | Radio | `Entire Project` or `Specific Task(s)` |
| Task Selection | ❌ Conditional | Checkbox (Multiple) | Select specific tasks if scope is task-based |
| Revision Details | ✅ Required | Textarea (min 20 chars) | `The design doesn't match the approved mockups...` |
| Requested Completion Date | ❌ Optional | Date | `2024-03-20` (min: tomorrow) |
| Priority Level | ✅ Required | Select | `Normal`, `High`, `Urgent` |

**Connected To:** Project → Task(s)

---

### 2.5 Task Revision Request Form (Client)

**Form Title:** Request Task Revision  
**Form Description:** Allows clients to request revisions for a specific completed task. Automatically assigned to the adiutor who completed the task.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Revision Details | ✅ Required | Textarea (min 20 chars) | `Please fix the login functionality...` |
| Requested Completion Date | ❌ Optional | Date | `2024-03-18` |
| Priority Level | ✅ Required | Select | `Normal`, `High`, `Urgent` |

**Connected To:** Task → Adiutor Assignment

---

## 3. Project & Task Management Forms

### 3.1 Create Task Form (Adiutor)

**Form Title:** Create New Task  
**Form Description:** Allows adiutors to propose new tasks for their assigned projects. Tasks require admin approval before becoming active.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Task Title | ✅ Required | Text | `Implement User Authentication` |
| Task Description | ✅ Required | Textarea | `Set up secure login with OAuth...` |
| Priority | ✅ Required | Select | `Low`, `Medium`, `High`, `Urgent` |
| Deadline | ❌ Optional | Date | `2024-03-10` |
| Requested Budget | ❌ Optional | Number | `5000.00` |
| Notes | ❌ Optional | Textarea | `Additional implementation notes...` |

**Notes:** Created with "Pending Approval" status

**Connected To:** Project → Admin Approval → Active Task

---

### 3.2 Update Task Progress Form (Adiutor)

**Form Title:** Update Task Progress  
**Form Description:** Allows adiutors to update the progress percentage of their assigned tasks.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Progress Percentage | ✅ Required | Number (0-100) | `75` |

**Connected To:** Task → Project Progress

---

### 3.3 Upload Task File Form (Adiutor)

**Form Title:** Upload File  
**Form Description:** Allows adiutors to upload deliverables and work files for their tasks.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| File | ✅ Required | File | PDF, DOC, DOCX, XLS, XLSX, PNG, JPG, ZIP (max 10MB) |
| Description | ❌ Optional | Textarea | `Final design mockups for review` |

**Connected To:** Task → Documents → Project Deliverables

---

### 3.4 Admin Task Create Form

**Form Title:** Create New Task (Admin)  
**Form Description:** Allows administrators to create and assign tasks to projects with full control over all settings.

**Form Fields:**

**Project & Assignment Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Related Project | ✅ Required | Select | Project with client name |
| Assign To | ❌ Optional | Select | Adiutor (dynamic based on project) |
| Project Phase | ❌ Conditional | Select | Phase/Milestone (if milestone payment) |

**Task Details Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Task Title | ✅ Required | Text | `Backend API Development` |
| Task Description | ✅ Required | Textarea | `Develop RESTful API endpoints...` |
| Notes | ❌ Optional | Textarea | `Internal notes for team...` |

**Settings Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Status | ✅ Required | Select | `Pending`, `In Progress`, `Completed`, `Cancelled` |
| Priority | ✅ Required | Select | `Low`, `Medium`, `High`, `Urgent` |
| Deadline | ❌ Optional | Date | `2024-03-25` |
| Allocated Budget | ❌ Optional | Number | `15000.00` |

---

## 4. Payment & Financial Forms

### 4.1 Payment Proof Upload Form (Client)

**Form Title:** Upload Payment Receipt  
**Form Description:** Allows clients to submit proof of manual bank/e-wallet payment for verification.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Payment Receipt/Screenshot | ✅ Required | File | PNG, JPG, PDF (max 5MB) |
| Additional Notes | ❌ Optional | Textarea | `Transaction ref: ABC123, sent from BDO account` |
| Confirm Payment | ✅ Required | Checkbox | Confirm amount and accuracy |
| Agree to Terms | ✅ Required | Checkbox | Accept Terms and Privacy Policy |

**Notes:** Receipt should show transaction amount, date, and reference number

**Connected To:** Service Request → Payment Verification

---

### 4.2 Referral Credit Withdrawal Form (Client)

**Form Title:** Request Withdrawal  
**Form Description:** Allows clients to withdraw their earned referral credits to external payment methods.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Amount | ✅ Required | Number | `1500.00` (min: ₱500, max: available balance) |
| Withdrawal Method | ✅ Required | Select | `GCash`, `PayMaya`, `Bank Transfer`, etc. |
| Account Details | ✅ Required | Textarea | `GCash: 09171234567` or `BDO Acct: 1234567890` |

**Notes:** Minimum withdrawal amount applies

---

### 4.3 Payout Request Form (Adiutor)

**Form Title:** Request Payout  
**Form Description:** Allows adiutors to request payout for their approved earnings from completed work.

**Form Fields:**

**Payout Period Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Start Date | ✅ Required | Date | `2024-02-01` |
| End Date | ✅ Required | Date | `2024-02-29` |

**Payment Method Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Preferred Method | ❌ Optional | Select | `Bank Transfer`, `GCash`, `PayMaya`, `PayPal`, `Cash`, `Check`, `Other` |

**Additional Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Notes | ❌ Optional | Textarea (max 1000 chars) | `Please process by end of month` |

**Notes:** Minimum payout amount required (configurable per adiutor)

---

### 4.4 Budget Change Request Form (Adiutor)

**Form Title:** Request Budget Change  
**Form Description:** Allows adiutors to request a budget adjustment for their assigned tasks.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Task | ✅ Required | Select | Task from assigned tasks list |
| Requested Budget | ✅ Required | Number | `8000.00` |
| Reason | ✅ Required | Textarea (min 20, max 1000 chars) | `Scope has increased to include additional features...` |

**Connected To:** Task → Admin Approval

---

### 4.5 Hour Increase Request Form (Adiutor)

**Form Title:** Request Hour Increase  
**Form Description:** Allows adiutors to request additional hours for their project assignment when current allocation is insufficient.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Assignment | ✅ Required | Hidden | Pre-selected assignment |
| New Max Hours Requested | ✅ Required | Number | `50` (must be > current max) |
| Reason | ✅ Required | Textarea (min 20, max 1000 chars) | `Project scope has expanded to include...` |

**Connected To:** Project Assignment → Admin Approval

---

## 5. Referral & Loyalty Program Forms

### 5.1 Referral Email Invitation Form

**Form Title:** Send Direct Invitation  
**Form Description:** Allows clients to send referral invitations directly via email to friends.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Friend's Email Address | ✅ Required | Email | `friend@example.com` |
| Personal Message | ❌ Optional | Textarea (max 500 chars) | `Hey! I've been using Treis Adiutor and...` |

**Connected To:** Referral Code → New User Registration → Rewards

---

## 6. Communication Forms

### 6.1 Project Message Form (Client)

**Form Title:** Send Message  
**Form Description:** Allows clients to communicate with the project team through the messaging system.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Message | ✅ Required | Textarea (max 5000 chars) | `Hi team, I have an update regarding...` |
| Attachments | ❌ Optional | File (Multiple) | PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG, GIF (max 10MB each) |

**Connected To:** Project → Team Communication

---

### 6.2 Schedule Meeting Form (Client)

**Form Title:** Schedule a Meeting  
**Form Description:** Allows clients to request a meeting with the project team or admin.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Meeting Title | ✅ Required | Text (max 255 chars) | `Project Review Meeting` |
| Preferred Date | ✅ Required | Date | `2024-03-15` (min: tomorrow) |
| Preferred Time | ✅ Required | Time | `14:00` |
| Meeting Agenda | ❌ Optional | Textarea (max 1000 chars) | `Discuss design revisions and timeline...` |

**Notes:**
- Request sent to admin for approval
- Zoom link generated upon approval
- Client notified of status

**Connected To:** Project → Admin → Meeting Confirmation

---

## 7. Feedback & Review Forms

### 7.1 Project Feedback Form (Client)

**Form Title:** Rate This Project  
**Form Description:** Allows clients to provide detailed feedback and ratings for completed projects.

**Form Fields:**

**Overall Rating Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Overall Rating | ✅ Required | Radio (1-5 stars) | ⭐⭐⭐⭐⭐ (5) |

**Detailed Ratings Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Quality of Work | ✅ Required | Radio (1-5 stars) | ⭐⭐⭐⭐⭐ (5) |
| Communication | ✅ Required | Radio (1-5 stars) | ⭐⭐⭐⭐ (4) |
| Timeliness | ✅ Required | Radio (1-5 stars) | ⭐⭐⭐⭐⭐ (5) |

**Written Feedback Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Detailed Feedback | ✅ Required | Textarea (min 10, max 1000 chars) | `The team delivered exceptional work...` |
| Would Recommend | ❌ Optional | Checkbox | Yes/No |
| Public Testimonial Consent | ❌ Optional | Checkbox | Allow as testimonial |

**Connected To:** Completed Project → Adiutor Performance Metrics

---

## 8. Adiutor (Staff) Forms

### 8.1 Adiutor Profile Edit Form

**Form Title:** Edit Profile  
**Form Description:** Allows adiutors to update their professional information and settings.

**Form Fields:**

**Profile Picture Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Profile Picture | ❌ Optional | File (Image) | JPG, PNG (max 2MB) |

**Basic Information Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Full Name | ❌ Read-only | Text | (Admin managed) |
| Email | ❌ Read-only | Email | (Admin managed) |
| Phone Number | ❌ Optional | Tel | `+63 917 123 4567` |

**Professional Information Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Professional Title | ❌ Optional | Text | `Senior Developer` |
| Location | ❌ Optional | Text | `Manila, Philippines` |
| Biography | ❌ Optional | Textarea (max 1000 chars) | `Experienced full-stack developer...` |

**Skills Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Skills | ❌ Optional | Dynamic List | Add skills with proficiency level (Beginner/Intermediate/Expert) |

---

### 8.2 Adiutor Earnings Settings Form

**Form Title:** Earnings Settings  
**Form Description:** Allows adiutors to configure their payment preferences and payout details.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Preferred Payout Method | ✅ Required | Select | `Bank Transfer`, `GCash`, `PayMaya`, etc. |
| Minimum Payout Amount | ❌ Optional | Number | `500.00` |
| Payout Details | ✅ Required | Textarea | Bank account info, e-wallet numbers |

---

## 9. Admin Management Forms

### 9.1 Create Client Form (Admin)

**Form Title:** Create New Client  
**Form Description:** Allows administrators to manually create client accounts.

**Form Fields:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Full Name | ✅ Required | Text | `Jane Smith` |
| Email Address | ✅ Required | Email | `jane@company.com` |
| Phone Number | ❌ Optional | Tel | `+63 918 765 4321` |
| Company | ❌ Optional | Text | `Smith Enterprises` |
| Password | ✅ Required | Password | Auto-generated or manual |

---

### 9.2 Create Coupon Form (Admin)

**Form Title:** Create New Coupon  
**Form Description:** Allows administrators to create discount coupons for clients.

**Form Fields:**

**Basic Information Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Coupon Code | ✅ Required | Text | `WELCOME2024`, `SAVE50` |
| Coupon Name | ✅ Required | Text | `Welcome Discount` |
| Discount Type | ✅ Required | Select | `Percentage (%)`, `Fixed Amount (₱)` |
| Discount Value | ✅ Required | Number | `15` (for 15%) or `500` (for ₱500) |
| Maximum Discount Cap | ❌ Optional | Number | `5000.00` |
| Minimum Purchase Amount | ❌ Optional | Number | `1000.00` |
| Description | ❌ Optional | Textarea | `Welcome discount for new customers` |

**Validity Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Valid From | ❌ Optional | DateTime | `2024-01-01 00:00` |
| Valid Until | ❌ Optional | DateTime | `2024-12-31 23:59` |
| Usage Limit (Total) | ❌ Optional | Number | `100` |
| Usage Limit (Per User) | ❌ Optional | Number | `1` |

**Restrictions Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| First Order Only | ❌ Optional | Checkbox | `checked` |
| Specific Users | ❌ Optional | Multi-select | Select specific clients |
| Active Status | ✅ Required | Toggle | `Active` / `Inactive` |

---

### 9.3 Create Project Template Form (Admin)

**Form Title:** Create Project Template  
**Form Description:** Allows administrators to create reusable templates for faster project setup.

**Form Fields:**

**Basic Information Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Template Name | ✅ Required | Text | `E-commerce Website Standard` |
| Category | ✅ Required | Select | Dynamic categories |
| Description | ✅ Required | Textarea | `Standard e-commerce website package...` |

**Budget & Timeline Section:**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Budget Type | ✅ Required | Select | `Fixed Price`, `Hourly Rate` |
| Payment Type | ✅ Required | Select | `Milestone Payment`, `Full Payment`, `Down Payment` |
| Minimum Budget | ❌ Optional | Number | `50000.00` |
| Maximum Budget | ❌ Optional | Number | `150000.00` |
| Duration (Days) | ❌ Optional | Number | `30` |

**Default Tasks Section (Dynamic):**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Task Title | ✅ Required | Text | `Design Phase` |
| Task Description | ✅ Required | Textarea | `Create mockups and wireframes` |
| Priority | ✅ Required | Select | `Low`, `Medium`, `High` |
| Estimated Hours | ✅ Required | Number | `20` |

**Skills Required Section (Dynamic):**

| Field | Required | Type | Example Input |
|-------|----------|------|---------------|
| Skill | ❌ Optional | Text | `PHP`, `Laravel`, `React`, `UI/UX Design` |

---

## Form Relationship Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           CLIENT JOURNEY FLOW                                │
└─────────────────────────────────────────────────────────────────────────────┘

┌──────────────┐     ┌────────────────┐     ┌──────────────┐
│   Register   │────▶│  Get Started   │────▶│  Apply       │
│   (1.1)      │     │  Form (2.1)    │     │  Coupon (2.3)│
└──────────────┘     └───────┬────────┘     └──────────────┘
                             │
                             ▼
                    ┌────────────────┐
                    │ Admin Approval │
                    └───────┬────────┘
                            │
                            ▼
┌──────────────────────────────────────────────────────────────────────────────┐
│                              PROJECT PHASE                                    │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────┐    ┌──────────────────┐    ┌─────────────────────┐     │
│  │ Payment Upload  │───▶│   Project View   │───▶│  Schedule Meeting   │     │
│  │     (4.1)       │    │                  │    │       (6.2)         │     │
│  └─────────────────┘    └────────┬─────────┘    └─────────────────────┘     │
│                                  │                                           │
│                    ┌─────────────┼─────────────┐                            │
│                    ▼             ▼             ▼                            │
│            ┌────────────┐ ┌────────────┐ ┌───────────────┐                  │
│            │   Tasks    │ │  Messages  │ │  Revisions    │                  │
│            │            │ │   (6.1)    │ │  (2.4, 2.5)   │                  │
│            └────────────┘ └────────────┘ └───────────────┘                  │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
                            │
                            ▼
                    ┌────────────────┐
                    │   Completed    │
                    └───────┬────────┘
                            │
                            ▼
                    ┌────────────────┐
                    │   Feedback     │
                    │     (7.1)      │
                    └────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│                           ADIUTOR WORKFLOW                                   │
└─────────────────────────────────────────────────────────────────────────────┘

┌────────────────┐     ┌────────────────┐     ┌────────────────┐
│  Create Task   │────▶│ Upload Files   │────▶│ Update Progress│
│     (3.1)      │     │    (3.3)       │     │     (3.2)      │
└────────────────┘     └────────────────┘     └────────────────┘
        │
        ├──────────────┬──────────────────┐
        ▼              ▼                  ▼
┌────────────────┐ ┌────────────────┐ ┌────────────────┐
│ Budget Request │ │ Hour Request   │ │ Payout Request │
│     (4.4)      │ │    (4.5)       │ │     (4.3)      │
└────────────────┘ └────────────────┘ └────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│                           REFERRAL PROGRAM                                   │
└─────────────────────────────────────────────────────────────────────────────┘

┌────────────────┐     ┌────────────────┐     ┌────────────────┐
│ Share Referral │────▶│ Friend Signs   │────▶│ Friend Pays    │
│ Code/Email(5.1)│     │ Up with Code   │     │                │
└────────────────┘     └────────────────┘     └───────┬────────┘
                                                      │
                              ┌────────────────────────┘
                              ▼
                       ┌────────────────┐
                       │ Earn Credits   │
                       │                │
                       └───────┬────────┘
                               │
                               ▼
                       ┌────────────────┐
                       │ Withdraw (4.2) │
                       └────────────────┘
```

---

## Summary Statistics

| Category | Form Count |
|----------|------------|
| Authentication & Account | 5 |
| Service Request Flow | 5 |
| Project & Task Management | 4 |
| Payment & Financial | 5 |
| Referral & Loyalty | 1 |
| Communication | 2 |
| Feedback & Review | 1 |
| Adiutor Forms | 2 |
| Admin Management | 3 |
| **Total Forms** | **28** |

---

*Document generated on: December 5, 2025*  
*System: Treis Adiutor CMS*

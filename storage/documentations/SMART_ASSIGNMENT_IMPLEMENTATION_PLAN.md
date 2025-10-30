# Smart Project Assignment Logic Implementation Plan
## Integrated with Google Calendar

**Document Version:** 1.0  
**Created:** October 30, 2025  
**Project:** CMS - Content Management System  
**Repository:** mrkndrwslmn/CMS

---

## Table of Contents
1. [Executive Overview](#executive-overview)
2. [System Analysis](#system-analysis)
3. [Feature Specifications](#feature-specifications)
4. [Schedule & Task Management](#schedule--task-management)
5. [Implementation Plan](#implementation-plan)
6. [Previous System Enhancements](#previous-system-enhancements)
7. [Timeline & Milestones](#timeline--milestones)
8. [Risk Assessment](#risk-assessment)

---

## Executive Overview

### Current State
The CMS currently uses **manual assignment** where administrators manually select and assign adiutors (developers/freelancers) to projects and tasks. This approach:

- ❌ Lacks intelligent matching based on skills and availability
- ❌ Doesn't consider adiutor workload or capacity
- ❌ No integration with calendar systems for availability tracking
- ❌ Prone to human error and bias
- ❌ Time-consuming for administrators
- ❌ Doesn't optimize resource allocation

### Proposed Solution
Implement an **AI-powered Smart Assignment System** that automatically recommends and assigns adiutors based on:

- ✅ **Skill Proficiency**: Match required skills with adiutor expertise levels
- ✅ **Availability**: Real-time calendar integration via Google Calendar API
- ✅ **Workload Balance**: Current task load and project capacity
- ✅ **Performance Metrics**: Historical ratings, completion rates, and quality scores
- ✅ **Cost Optimization**: Hourly rates vs. budget constraints
- ✅ **Project Preferences**: Adiutor preferences for project types

### Business Impact
- 🎯 **60% reduction** in assignment time
- 🎯 **40% improvement** in project-adiutor matching accuracy
- 🎯 **25% increase** in on-time project delivery
- 🎯 **35% better** resource utilization
- 🎯 **Automated scheduling** with conflict prevention

---

## System Analysis

### Current Database Schema

#### 1. **Users Table**
```php
users {
  id: bigint (PK)
  fullName: string
  email: string
  role: enum ['admin', 'client', 'adiutor']
  status: enum ['active', 'inactive']
  phoneNumber: string
  profilePic: string
}
```

#### 2. **Adiutor Profiles Table**
```php
adiutor_profiles {
  id: bigint (PK)
  user_id: bigint (FK → users.id)
  bio: text
  title: string
  hourly_rate: decimal(8,2)
  availability: json // Current: manual input
  portfolio_url: string
  linkedin_url: string
  github_url: string
  experience: text
  languages: json
  location: string
  is_verified: boolean
  rating: decimal(3,2)
  total_projects: integer
  status: enum ['active', 'inactive', 'busy']
}
```

#### 3. **Skills Table**
```php
skills {
  id: bigint (PK)
  name: string (unique)
  description: text
  category: string // programming, design, marketing
  is_active: boolean
}
```

#### 4. **Adiutor Skills Pivot Table**
```php
adiutor_skills {
  id: bigint (PK)
  adiutor_id: bigint (FK → adiutor_profiles.id)
  skill_id: bigint (FK → skills.id)
  proficiency_level: enum ['beginner', 'intermediate', 'advanced', 'expert']
  years_experience: integer
}
```

#### 5. **Projects Table**
```php
projects {
  id: bigint (PK)
  service_request_id: bigint (FK → service_requests.id)
  client_id: bigint (FK → users.id)
  title: string
  description: text
  requirements: json
  skills_required: json // Array of skill IDs
  budget: decimal(10,2)
  budget_type: enum ['fixed', 'hourly']
  deadline: date
  priority: enum ['low', 'medium', 'high', 'urgent']
  status: enum ['active', 'in_progress', 'review', 'completed', 'cancelled']
}
```

#### 6. **Project Assignments Table** (Current)
```php
project_assignments {
  id: bigint (PK)
  project_id: bigint (FK → projects.id)
  adiutor_id: bigint (FK → users.id)
  agreed_rate: decimal(8,2)
  start_date: date
  expected_completion: date
  status: enum ['assigned', 'active', 'completed', 'removed']
  notes: text
  progress_percentage: integer
}
```

#### 7. **Tasks Table**
```php
tasks {
  taskID: bigint (PK)
  project_id: bigint (FK → projects.id)
  phase_id: bigint (FK → project_milestones.id)
  assignedTo: bigint (FK → users.id)
  taskTitle: string
  taskDescription: text
  status: enum ['pending', 'in_progress', 'completed', 'cancelled']
  priority: enum ['low', 'medium', 'high']
  deadline: datetime
  allocated_budget: decimal(8,2)
  actual_cost: decimal(8,2)
  progress_percentage: integer
}
```

#### 8. **Time Entries Table**
```php
time_entries {
  id: bigint (PK)
  adiutor_id: bigint (FK → users.id)
  task_id: bigint (FK → tasks.taskID)
  project_id: bigint (FK → projects.id)
  start_time: datetime
  end_time: datetime
  duration_minutes: integer
  is_approved: boolean
}
```

### Current Assignment Flow

```
┌─────────────────────────────────────────────────────────────┐
│                  CURRENT MANUAL ASSIGNMENT                   │
└─────────────────────────────────────────────────────────────┘

1. Admin views project details
2. Admin manually selects adiutor from dropdown
3. Admin enters agreed rate, notes
4. System creates project_assignment record
5. Email notification sent to adiutor
6. Adiutor accepts/declines manually

LIMITATIONS:
❌ No skill matching algorithm
❌ No availability checking
❌ No workload consideration
❌ No automated recommendations
❌ No calendar integration
```

---

## Feature Specifications

### 1. Smart Assignment Algorithm

#### 1.1 Scoring System
Each adiutor receives a **compatibility score (0-100)** based on:

| Factor | Weight | Description |
|--------|--------|-------------|
| **Skill Match** | 35% | Matches required skills with proficiency levels |
| **Availability** | 25% | Real-time calendar availability during project timeline |
| **Workload** | 20% | Current active projects and tasks |
| **Performance** | 10% | Historical ratings and completion rates |
| **Cost** | 5% | Hourly rate vs. project budget |
| **Experience** | 5% | Total projects and years in required skills |

#### 1.2 Scoring Formula

```php
Score = (
  (SkillMatchScore × 0.35) +
  (AvailabilityScore × 0.25) +
  (WorkloadScore × 0.20) +
  (PerformanceScore × 0.10) +
  (CostScore × 0.05) +
  (ExperienceScore × 0.05)
) × 100
```

##### Skill Match Score Calculation
```php
For each required skill:
  - Expert proficiency: 1.0
  - Advanced proficiency: 0.8
  - Intermediate proficiency: 0.5
  - Beginner proficiency: 0.3
  - No skill: 0.0
  
SkillMatchScore = Sum of matched skills / Total required skills
Bonus: +0.1 if all skills are Advanced or Expert
```

##### Availability Score Calculation
```php
RequiredHours = Project estimated hours
AvailableHours = Free hours in Google Calendar (project timeline)

AvailabilityScore = min(AvailableHours / RequiredHours, 1.0)

If calendar shows "Busy" or "Out of office": Score × 0.5
If no calendar connected: Default score = 0.6
```

##### Workload Score Calculation
```php
CurrentActiveProjects = Count of active projects
CurrentActiveTasks = Count of in_progress tasks
MaxCapacity = 3 projects OR 10 tasks (configurable)

WorkloadScore = 1.0 - (CurrentActiveProjects / MaxCapacity)

Penalties:
- Overdue tasks: -0.2 per overdue task (max -0.6)
- High-priority tasks: -0.1 per high-priority task
```

##### Performance Score Calculation
```php
AverageRating = AVG(project_feedback.rating) // 1-5 scale
CompletionRate = Completed projects / Total projects
OnTimeRate = On-time completions / Total completions

PerformanceScore = (
  (AverageRating / 5.0) × 0.5 +
  CompletionRate × 0.3 +
  OnTimeRate × 0.2
)
```

##### Cost Score Calculation
```php
ProjectMaxRate = Project budget / Estimated hours
AdiutorRate = Adiutor hourly_rate

If AdiutorRate <= ProjectMaxRate × 0.8: Score = 1.0
If AdiutorRate <= ProjectMaxRate: Score = 0.7
If AdiutorRate <= ProjectMaxRate × 1.2: Score = 0.4
If AdiutorRate > ProjectMaxRate × 1.2: Score = 0.1
```

##### Experience Score Calculation
```php
TotalProjects = adiutor_profile.total_projects
YearsInSkill = MAX(adiutor_skills.years_experience for required skills)

ExperienceScore = (
  min(TotalProjects / 20, 0.5) + // Max 0.5 for 20+ projects
  min(YearsInSkill / 10, 0.5)     // Max 0.5 for 10+ years
)
```

### 2. Google Calendar Integration

#### 2.1 Setup Requirements
- **Google Cloud Project** with Calendar API enabled
- **OAuth 2.0 credentials** for users to connect calendars
- **Service account** for server-to-server requests
- **Webhook subscriptions** for real-time updates

#### 2.2 Calendar Features

##### 2.2.1 Availability Tracking
```php
Features:
✓ Sync adiutor working hours
✓ Track busy/free time blocks
✓ Identify recurring meetings
✓ Detect out-of-office periods
✓ Calculate available hours per day/week
✓ Export available time slots
```

##### 2.2.2 Automatic Event Creation
```php
When project is assigned:
  → Create calendar event on adiutor's calendar
  → Event title: "Project: {project_title}"
  → Duration: start_date to expected_completion
  → Add project details in description
  → Include client name and priority
  → Add meeting link (if applicable)
  → Set reminders (1 day before, 1 hour before)
```

##### 2.2.3 Task Scheduling
```php
When task is created/assigned:
  → Check adiutor availability
  → Find optimal time slot
  → Create focused work block
  → Avoid conflicts with meetings
  → Respect working hours
  → Add task deadline as event
```

##### 2.2.4 Conflict Detection
```php
Before assignment:
  → Check calendar for overlapping events
  → Detect PTO/vacation periods
  → Flag double-booking risks
  → Suggest alternative dates
  → Show capacity warnings
```

#### 2.3 Database Schema Extensions

```php
// New table: adiutor_calendar_integrations
Schema::create('adiutor_calendar_integrations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('adiutor_id')->constrained('users')->onDelete('cascade');
    $table->enum('provider', ['google', 'outlook', 'apple'])->default('google');
    $table->string('calendar_id')->nullable(); // Primary calendar ID
    $table->text('access_token')->nullable(); // Encrypted OAuth token
    $table->text('refresh_token')->nullable(); // Encrypted refresh token
    $table->timestamp('token_expires_at')->nullable();
    $table->boolean('is_connected')->default(false);
    $table->boolean('auto_sync')->default(true);
    $table->timestamp('last_synced_at')->nullable();
    $table->json('sync_settings')->nullable(); // Working hours, timezone
    $table->json('webhook_config')->nullable(); // Calendar webhook data
    $table->timestamps();
    
    $table->index(['adiutor_id', 'is_connected']);
});

// New table: adiutor_availability_cache
Schema::create('adiutor_availability_cache', function (Blueprint $table) {
    $table->id();
    $table->foreignId('adiutor_id')->constrained('users')->onDelete('cascade');
    $table->date('date');
    $table->json('busy_slots')->nullable(); // [{start: '09:00', end: '10:00'}]
    $table->json('free_slots')->nullable(); // [{start: '10:00', end: '12:00'}]
    $table->integer('available_minutes')->default(0);
    $table->boolean('is_working_day')->default(true);
    $table->boolean('is_pto')->default(false); // Paid Time Off
    $table->timestamp('cached_at');
    $table->timestamps();
    
    $table->unique(['adiutor_id', 'date']);
    $table->index('date');
});

// New table: smart_assignment_recommendations
Schema::create('smart_assignment_recommendations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
    $table->foreignId('adiutor_id')->constrained('users')->onDelete('cascade');
    $table->decimal('total_score', 5, 2); // 0.00-100.00
    $table->decimal('skill_score', 5, 2);
    $table->decimal('availability_score', 5, 2);
    $table->decimal('workload_score', 5, 2);
    $table->decimal('performance_score', 5, 2);
    $table->decimal('cost_score', 5, 2);
    $table->decimal('experience_score', 5, 2);
    $table->integer('rank')->default(0); // 1 = top recommendation
    $table->json('matched_skills')->nullable(); // Skills that matched
    $table->json('missing_skills')->nullable(); // Required skills not present
    $table->json('availability_details')->nullable(); // Free hours breakdown
    $table->text('recommendation_reason')->nullable(); // AI-generated explanation
    $table->boolean('is_accepted')->default(false);
    $table->timestamp('recommended_at');
    $table->timestamp('accepted_at')->nullable();
    $table->timestamps();
    
    $table->index(['project_id', 'total_score']);
    $table->index(['adiutor_id', 'recommended_at']);
});

// Extend project_assignments table
Schema::table('project_assignments', function (Blueprint $table) {
    $table->foreignId('recommendation_id')->nullable()
          ->constrained('smart_assignment_recommendations')
          ->onDelete('set null')
          ->after('adiutor_id');
    $table->enum('assignment_type', ['manual', 'smart_auto', 'smart_recommended'])
          ->default('manual')
          ->after('recommendation_id');
    $table->string('google_calendar_event_id')->nullable()->after('notes');
    $table->timestamp('calendar_synced_at')->nullable()->after('google_calendar_event_id');
});
```

### 3. User Interface Components

#### 3.1 Smart Assignment Modal (Admin)

```
┌─────────────────────────────────────────────────────────┐
│  Smart Project Assignment                         [×]   │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Project: E-Commerce Website Development                │
│  Budget: $5,000 | Deadline: Dec 15, 2025                │
│  Required Skills: PHP, Laravel, Vue.js, MySQL           │
│                                                          │
│  ┌─────────────────────────────────────────────────┐   │
│  │  🤖 Smart Recommendations                       │   │
│  ├─────────────────────────────────────────────────┤   │
│  │                                                 │   │
│  │  #1  John Developer              Score: 94/100 │   │
│  │      ★★★★★ 4.8 | 23 projects | $45/hr        │   │
│  │      ✓ PHP (Expert) ✓ Laravel (Advanced)      │   │
│  │      ✓ Vue.js (Advanced) ✓ MySQL (Expert)     │   │
│  │      📅 Available 35hrs (next 2 weeks)         │   │
│  │      💼 Workload: 2/3 projects, Light          │   │
│  │      [View Calendar] [Assign Now]              │   │
│  │                                                 │   │
│  │  #2  Sarah Engineer              Score: 88/100 │   │
│  │      ★★★★☆ 4.5 | 18 projects | $55/hr        │   │
│  │      ✓ PHP (Expert) ✓ Laravel (Expert)        │   │
│  │      ⚠ Vue.js (Intermediate) ✓ MySQL (Adv)    │   │
│  │      📅 Available 28hrs (next 2 weeks)         │   │
│  │      💼 Workload: 3/3 projects, Heavy          │   │
│  │      [View Calendar] [Assign Now]              │   │
│  │                                                 │   │
│  │  #3  Mike Coder                  Score: 76/100 │   │
│  │      ★★★★☆ 4.2 | 12 projects | $38/hr        │   │
│  │      ✓ PHP (Advanced) ✓ Laravel (Intermediate)│   │
│  │      ✓ Vue.js (Advanced) ⚠ MySQL (Basic)      │   │
│  │      📅 Available 40hrs (next 2 weeks)         │   │
│  │      💼 Workload: 1/3 projects, Light          │   │
│  │      [View Calendar] [Assign Now]              │   │
│  │                                                 │   │
│  └─────────────────────────────────────────────────┘   │
│                                                          │
│  [◯ Auto-assign top match]  [◯ Manual selection]       │
│  [⚙ Adjust weights] [🔄 Refresh recommendations]       │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

#### 3.2 Calendar Integration Page (Adiutor)

```
┌─────────────────────────────────────────────────────────┐
│  Calendar Integration Settings                          │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  📅 Google Calendar                                     │
│  Status: ✓ Connected                                    │
│  Email: john@example.com                                │
│  Last Synced: 5 minutes ago                            │
│                                                          │
│  [Disconnect Calendar] [Refresh Now]                    │
│                                                          │
│  ─────────────────────────────────────────────────────  │
│                                                          │
│  Working Hours                                          │
│  Monday    - Friday:  09:00 AM - 05:00 PM              │
│  Saturday  - Sunday:  Unavailable                       │
│  Timezone: Asia/Manila (GMT+8)                          │
│                                                          │
│  [Edit Working Hours]                                   │
│                                                          │
│  ─────────────────────────────────────────────────────  │
│                                                          │
│  Auto-Scheduling Preferences                            │
│  ☑ Automatically block time for assigned projects       │
│  ☑ Create calendar events for tasks                     │
│  ☑ Send reminders for approaching deadlines             │
│  ☐ Allow double-booking for low-priority tasks          │
│                                                          │
│  ─────────────────────────────────────────────────────  │
│                                                          │
│  Availability Overview (Next 14 Days)                   │
│  ┌────────────────────────────────────────────────┐    │
│  │ Mon 11/1  ████░░░░  32 hrs available           │    │
│  │ Tue 11/2  ██████░░  24 hrs available           │    │
│  │ Wed 11/3  ████████  16 hrs available           │    │
│  │ Thu 11/4  ████░░░░  32 hrs available           │    │
│  │ Fri 11/5  ██░░░░░░  36 hrs available           │    │
│  │ Sat 11/6  ░░░░░░░░  Off Day                    │    │
│  │ Sun 11/7  ░░░░░░░░  Off Day                    │    │
│  └────────────────────────────────────────────────┘    │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

#### 3.3 Assignment Dashboard (Admin)

```
┌─────────────────────────────────────────────────────────┐
│  Smart Assignment Dashboard                             │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Metrics (Last 30 Days)                                 │
│  ┌──────────┬──────────┬──────────┬──────────┐         │
│  │  Total   │  Smart   │  Manual  │ Success  │         │
│  │  Assign  │  Assign  │  Assign  │  Rate    │         │
│  │   47     │   32     │   15     │  94%     │         │
│  └──────────┴──────────┴──────────┴──────────┘         │
│                                                          │
│  Pending Recommendations                                │
│  ┌────────────────────────────────────────────────┐    │
│  │ Project: Mobile App UI/UX                       │    │
│  │ 🤖 3 recommendations ready                      │    │
│  │ Top match: Jane Designer (Score: 96)            │    │
│  │ [View Details] [Auto-assign]                    │    │
│  ├────────────────────────────────────────────────┤    │
│  │ Project: API Integration                        │    │
│  │ 🤖 5 recommendations ready                      │    │
│  │ Top match: Tom Backend (Score: 91)              │    │
│  │ [View Details] [Auto-assign]                    │    │
│  └────────────────────────────────────────────────┘    │
│                                                          │
│  Adiutor Availability                                   │
│  ┌────────────────────────────────────────────────┐    │
│  │ 12 adiutors available (Light workload)          │    │
│  │  5 adiutors at capacity                         │    │
│  │  2 adiutors on PTO                              │    │
│  │ [View Full Calendar]                            │    │
│  └────────────────────────────────────────────────┘    │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

---

## Schedule & Task Management

### Overview
The Schedule & Task Management system bridges the existing task management functionality with Google Calendar integration, creating an intelligent scheduling system that automatically manages adiutor workload, prevents conflicts, and optimizes resource allocation.

### 1. Smart Task Scheduling

#### 1.1 Automatic Task Scheduling Algorithm

When a task is created or assigned, the system automatically:

```php
Task Scheduling Process:
┌──────────────────────────────────────────────────────┐
│ 1. Analyze Task Requirements                         │
│    ├── Estimated duration (hours)                    │
│    ├── Priority level (high/medium/low)              │
│    ├── Deadline constraints                          │
│    ├── Dependencies on other tasks                   │
│    └── Required skills/focus level                   │
│                                                       │
│ 2. Query Adiutor Availability                        │
│    ├── Fetch Google Calendar free/busy               │
│    ├── Check existing task schedule                  │
│    ├── Identify working hours                        │
│    ├── Detect PTO/vacation periods                   │
│    └── Calculate available time slots                │
│                                                       │
│ 3. Find Optimal Time Slot                            │
│    ├── Prioritize morning for high-focus tasks       │
│    ├── Group similar tasks together                  │
│    ├── Leave buffer time between tasks               │
│    ├── Respect deadline proximity                    │
│    └── Avoid meeting conflicts                       │
│                                                       │
│ 4. Create Calendar Event                             │
│    ├── Block time on Google Calendar                 │
│    ├── Add task details in description               │
│    ├── Set reminders (start, midpoint, deadline)     │
│    ├── Include project/client context                │
│    └── Generate meeting link if needed               │
│                                                       │
│ 5. Enable Smart Rescheduling                         │
│    ├── Monitor task progress                         │
│    ├── Detect delays or early completions            │
│    ├── Automatically adjust subsequent tasks         │
│    └── Notify affected stakeholders                  │
└──────────────────────────────────────────────────────┘
```

#### 1.2 Task Scheduling Rules Engine

```php
Priority-Based Scheduling Rules:

HIGH Priority Tasks:
✓ Schedule within next 24-48 hours
✓ Assign best available time slots (morning focused work)
✓ Minimize interruptions (3-4 hour blocks)
✓ Override low-priority tasks if needed
✓ Send immediate notifications

MEDIUM Priority Tasks:
✓ Schedule within next 3-5 days
✓ Assign regular working hours
✓ 2-3 hour focused blocks
✓ Balance with other tasks
✓ Standard notifications

LOW Priority Tasks:
✓ Schedule when capacity available
✓ Flexible time slots
✓ Can be interrupted/rescheduled
✓ Fill gaps in schedule
✓ Periodic reminders only

Deadline-Driven Scheduling:
- Deadline < 2 days: Auto-schedule ASAP, escalate priority
- Deadline 2-7 days: Schedule with urgency consideration
- Deadline 7-14 days: Normal scheduling flow
- Deadline > 14 days: Optimize for efficiency and grouping
```

#### 1.3 Task Time Estimation

```php
Automatic Duration Estimation:
┌─────────────────────────────────────────────────┐
│ Base Estimation Sources:                        │
├─────────────────────────────────────────────────┤
│ 1. Historical Data (Similar tasks)              │
│    → AVG(completed similar tasks duration)      │
│                                                  │
│ 2. Complexity Analysis                          │
│    → Simple: 1-2 hours                          │
│    → Moderate: 3-5 hours                        │
│    → Complex: 6-10 hours                        │
│    → Very Complex: 10+ hours                    │
│                                                  │
│ 3. Adiutor Skill Level Adjustment               │
│    → Expert: Base time × 0.7                    │
│    → Advanced: Base time × 0.9                  │
│    → Intermediate: Base time × 1.0              │
│    → Beginner: Base time × 1.3                  │
│                                                  │
│ 4. Project Phase Context                        │
│    → Planning/Research: +20% buffer             │
│    → Development: Standard estimation           │
│    → Testing/QA: +15% buffer                    │
│    → Revision/Fix: -10% (focused work)          │
│                                                  │
│ 5. Confidence Interval                          │
│    → Add 15-25% buffer for uncertainty          │
│    → Decrease buffer as historical data grows   │
└─────────────────────────────────────────────────┘
```

### 2. Database Schema Extensions for Scheduling

```php
// New table: task_schedules
Schema::create('task_schedules', function (Blueprint $table) {
    $table->id();
    $table->foreignId('task_id')->constrained('tasks', 'taskID')->onDelete('cascade');
    $table->foreignId('adiutor_id')->constrained('users')->onDelete('cascade');
    $table->datetime('scheduled_start');
    $table->datetime('scheduled_end');
    $table->integer('estimated_duration_minutes');
    $table->integer('actual_duration_minutes')->nullable();
    $table->enum('schedule_type', ['auto', 'manual', 'rescheduled'])->default('auto');
    $table->string('google_calendar_event_id')->nullable();
    $table->boolean('is_blocked_time')->default(true); // Block calendar
    $table->json('scheduling_metadata')->nullable(); // Algorithm details
    $table->text('reschedule_reason')->nullable();
    $table->integer('reschedule_count')->default(0);
    $table->timestamp('calendar_synced_at')->nullable();
    $table->timestamps();
    
    $table->index(['adiutor_id', 'scheduled_start']);
    $table->index(['task_id', 'schedule_type']);
});

// New table: task_dependencies
Schema::create('task_dependencies', function (Blueprint $table) {
    $table->id();
    $table->foreignId('task_id')->constrained('tasks', 'taskID')->onDelete('cascade');
    $table->foreignId('depends_on_task_id')->constrained('tasks', 'taskID')->onDelete('cascade');
    $table->enum('dependency_type', ['finish_to_start', 'start_to_start', 'finish_to_finish', 'start_to_finish'])
          ->default('finish_to_start');
    $table->integer('lag_time_hours')->default(0); // Buffer between tasks
    $table->boolean('is_blocking')->default(true); // Must complete before next
    $table->timestamps();
    
    $table->unique(['task_id', 'depends_on_task_id']);
    $table->index('dependency_type');
});

// New table: adiutor_work_schedules
Schema::create('adiutor_work_schedules', function (Blueprint $table) {
    $table->id();
    $table->foreignId('adiutor_id')->constrained('users')->onDelete('cascade');
    $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
    $table->time('start_time');
    $table->time('end_time');
    $table->boolean('is_working_day')->default(true);
    $table->integer('max_hours_per_day')->default(8);
    $table->integer('break_duration_minutes')->default(60); // Lunch/breaks
    $table->json('preferred_focus_hours')->nullable(); // Best productivity hours
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    
    $table->index(['adiutor_id', 'day_of_week']);
});

// New table: schedule_conflicts
Schema::create('schedule_conflicts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('task_schedule_id')->constrained('task_schedules')->onDelete('cascade');
    $table->foreignId('adiutor_id')->constrained('users')->onDelete('cascade');
    $table->enum('conflict_type', ['double_booking', 'over_capacity', 'calendar_event', 'pto', 'overdue_task']);
    $table->datetime('conflict_start');
    $table->datetime('conflict_end');
    $table->string('conflicting_event_id')->nullable(); // Google Calendar event ID
    $table->text('conflict_description');
    $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
    $table->enum('status', ['detected', 'notified', 'resolved', 'ignored'])->default('detected');
    $table->text('resolution_notes')->nullable();
    $table->timestamp('resolved_at')->nullable();
    $table->timestamps();
    
    $table->index(['adiutor_id', 'status', 'severity']);
    $table->index('conflict_type');
});

// Extend tasks table
Schema::table('tasks', function (Blueprint $table) {
    $table->integer('estimated_hours')->nullable()->after('allocated_budget');
    $table->datetime('auto_scheduled_at')->nullable()->after('dateAssigned');
    $table->datetime('actual_start_time')->nullable()->after('auto_scheduled_at');
    $table->datetime('actual_end_time')->nullable()->after('actual_start_time');
    $table->enum('scheduling_preference', ['asap', 'optimized', 'manual', 'deadline_driven'])
          ->default('optimized')
          ->after('priority');
    $table->boolean('allow_auto_reschedule')->default(true)->after('scheduling_preference');
    $table->json('time_tracking_summary')->nullable()->after('progress_percentage'); // Total time spent
});
```

### 3. Schedule Management Features

#### 3.1 Interactive Schedule Timeline (Admin & Adiutor)

```
┌─────────────────────────────────────────────────────────────────┐
│  Schedule Timeline - Week View                            [×]   │
├─────────────────────────────────────────────────────────────────┤
│  Adiutor: John Developer                   Week: Nov 1-7, 2025  │
│  [◄ Prev Week] [Today] [Next Week ►]       [Day|Week|Month]    │
├─────────────────────────────────────────────────────────────────┤
│         │  Mon 11/1 │  Tue 11/2 │  Wed 11/3 │  Thu 11/4 │ Fri  │
├─────────┼───────────┼───────────┼───────────┼───────────┼──────┤
│ 08:00   │           │ ┌───────┐ │           │           │      │
│         │           │ │Meeting│ │           │           │      │
│ 09:00   │ ┌───────────────────┐ │ ┌───────────────────┐ │      │
│         │ │ Task: API Setup   │ │ │ Task: Database    │ │      │
│ 10:00   │ │ High Priority     │ │ │ Design (Auto)     │ │      │
│         │ │ Project: E-comm   │ │ │ Medium Priority   │ │      │
│ 11:00   │ └───────────────────┘ │ │                   │ │      │
│ 12:00   │     Lunch Break       │ └───────────────────┘ │      │
│ 13:00   │ ┌───────────────────┐ │ ┌─────────────┐     │ │ ⚠️  │
│         │ │ Task: Frontend    │ │ │ Code Review │     │ │Conf- │
│ 14:00   │ │ Components        │ │ └─────────────┘     │ │lict! │
│         │ │ Medium Priority   │ │                     │ │      │
│ 15:00   │ └───────────────────┘ │ ┌───────────────────┐│      │
│ 16:00   │                       │ │ Task: Bug Fixes   ││      │
│ 17:00   │                       │ │ Low Priority      ││      │
│         │                       │ └───────────────────┘│      │
├─────────┴───────────┴───────────┴───────────┴───────────┴──────┤
│  Summary: 24 hrs scheduled | 6 hrs available | 2 conflicts      │
│  [🔄 Auto-Optimize] [➕ Add Task] [📅 Sync Calendar]           │
└─────────────────────────────────────────────────────────────────┘

Legend:
🟦 Auto-scheduled tasks
🟨 Manually scheduled tasks
🟥 Conflicts/Overbooked
🟩 Available time
⚫ Calendar events (meetings, PTO)
```

#### 3.2 Smart Rescheduling Engine

```php
Automatic Rescheduling Triggers:
┌────────────────────────────────────────────────────┐
│ 1. Task Completion Earlier Than Expected           │
│    → Move subsequent tasks earlier                 │
│    → Fill freed time with backlog tasks            │
│    → Update calendar events                        │
│                                                     │
│ 2. Task Taking Longer Than Estimated               │
│    → Extend current task block                     │
│    → Push subsequent tasks back                    │
│    → Check deadline impacts                        │
│    → Notify stakeholders if deadline at risk       │
│                                                     │
│ 3. New High-Priority Task Added                    │
│    → Find earliest available slot                  │
│    → Bump lower priority tasks if needed           │
│    → Preserve critical deadline tasks              │
│    → Get admin approval for major changes          │
│                                                     │
│ 4. Calendar Conflict Detected                      │
│    → Identify conflicting time blocks              │
│    → Suggest alternative times                     │
│    → Auto-reschedule if preference allows          │
│    → Notify adiutor of changes                     │
│                                                     │
│ 5. Adiutor Availability Changed (PTO, etc.)        │
│    → Detect blocked days from Google Calendar      │
│    → Redistribute tasks to available days          │
│    → Extend deadlines if necessary                 │
│    → Alert project manager                         │
└────────────────────────────────────────────────────┘

Rescheduling Algorithm:
1. Calculate impact score for each task
2. Sort tasks by: Priority × Deadline Urgency × Dependencies
3. Re-assign time slots starting with highest score
4. Maintain minimum buffer between tasks (30 min)
5. Respect working hours and break times
6. Update all affected calendar events
7. Log all changes for audit trail
8. Send bulk notification to affected parties
```

#### 3.3 Task Dependency Management

```
┌────────────────────────────────────────────────────┐
│  Task Dependencies - Project: E-Commerce Website   │
├────────────────────────────────────────────────────┤
│                                                     │
│  [Gantt Chart View]                                │
│                                                     │
│  Task 1: Database Design                           │
│  ████████ (Completed)                             │
│     ↓                                              │
│  Task 2: API Development                           │
│          ████████░░ (In Progress - 80%)           │
│     ↓         ↓                                    │
│  Task 3: Frontend  Task 4: Admin Panel            │
│  (Blocked)         (Blocked)                       │
│          ░░░░░░░░      ░░░░░░░░                  │
│     ↓                      ↓                       │
│  Task 5: Integration Testing                       │
│  (Waiting for Task 3 & 4)                         │
│          ░░░░░░░░░░░░░░                          │
│                                                     │
├────────────────────────────────────────────────────┤
│  Dependency Rules:                                 │
│  • Task 2 → Task 3: Finish-to-Start (No lag)     │
│  • Task 2 → Task 4: Finish-to-Start (No lag)     │
│  • Task 3 → Task 5: Finish-to-Start (2hr lag)    │
│  • Task 4 → Task 5: Finish-to-Start (2hr lag)    │
│                                                     │
│  [Edit Dependencies] [Auto-Schedule Chain]         │
└────────────────────────────────────────────────────┘
```

### 4. Google Calendar Integration for Schedule Management

#### 4.1 Two-Way Sync Features

```php
Calendar → CMS Sync:
✓ Import existing calendar events as busy time
✓ Detect PTO/Out of Office events
✓ Respect recurring meeting patterns
✓ Track external commitments
✓ Update availability cache in real-time

CMS → Calendar Sync:
✓ Create calendar events for scheduled tasks
✓ Update events when tasks rescheduled
✓ Delete events when tasks completed/cancelled
✓ Add task progress in event description
✓ Set color codes by priority/project

Sync Frequency:
- Real-time: Webhook-based updates (instant)
- Scheduled: Every 15 minutes for availability cache
- Manual: On-demand sync button
- Batch: Nightly reconciliation (03:00 AM)
```

#### 4.2 Calendar Event Structure for Tasks

```javascript
Google Calendar Event for Task:
{
  "summary": "[CMS] Task: API Development - E-Commerce",
  "description": `
    📋 Task Details:
    • Project: E-Commerce Website
    • Priority: High
    • Assigned to: John Developer
    • Estimated: 4 hours
    • Deadline: Nov 5, 2025
    • Progress: 0%
    
    🔗 Quick Links:
    • View Task: https://cms.app/tasks/123
    • Project Dashboard: https://cms.app/projects/45
    
    ⏱ Time Tracking:
    • Auto-tracked via CMS
    • Current session: Not started
    
    📝 Notes:
    Complete REST API endpoints for product catalog
  `,
  "start": {
    "dateTime": "2025-11-01T09:00:00+08:00",
    "timeZone": "Asia/Manila"
  },
  "end": {
    "dateTime": "2025-11-01T13:00:00+08:00",
    "timeZone": "Asia/Manila"
  },
  "reminders": {
    "useDefault": false,
    "overrides": [
      {"method": "popup", "minutes": 60},    // 1 hour before
      {"method": "popup", "minutes": 15},    // 15 min before
      {"method": "email", "minutes": 1440}   // 1 day before
    ]
  },
  "colorId": "11",  // Red for High priority
  "extendedProperties": {
    "private": {
      "cms_task_id": "123",
      "cms_project_id": "45",
      "cms_priority": "high",
      "cms_sync_version": "1.0"
    }
  },
  "attendees": [
    {"email": "admin@cms.app", "displayName": "Project Manager"}
  ]
}
```

#### 4.3 Conflict Resolution Workflow

```
┌────────────────────────────────────────────────────┐
│  ⚠️ Schedule Conflict Detected                    │
├────────────────────────────────────────────────────┤
│                                                     │
│  Conflict Type: Double Booking                     │
│  Affected Adiutor: John Developer                  │
│  Time: Wed, Nov 3, 2025 - 2:00 PM to 4:00 PM      │
│                                                     │
│  Conflicting Items:                                │
│  ┌──────────────────────────────────────────────┐ │
│  │ 1. CMS Task: Database Optimization            │ │
│  │    Project: E-Commerce                        │ │
│  │    Priority: High                             │ │
│  │    Estimated: 2 hours                         │ │
│  └──────────────────────────────────────────────┘ │
│                                                     │
│  ┌──────────────────────────────────────────────┐ │
│  │ 2. Google Calendar: Client Meeting            │ │
│  │    With: ABC Corporation                      │ │
│  │    Type: Video Conference                     │ │
│  │    Duration: 2 hours                          │ │
│  └──────────────────────────────────────────────┘ │
│                                                     │
│  Suggested Resolutions:                            │
│  ◉ Reschedule Task to Nov 3, 4:30 PM - 6:30 PM   │
│  ○ Reschedule Task to Nov 4, 9:00 AM - 11:00 AM  │
│  ○ Split Task into 2 sessions (1hr each)          │
│  ○ Assign Task to another adiutor                 │
│  ○ Mark conflict as accepted (manual override)    │
│                                                     │
│  [Apply Auto-Resolution] [Choose Manually]         │
│  [Notify Project Manager] [Ignore Once]            │
└────────────────────────────────────────────────────┘
```

### 5. Workload Balancing & Capacity Planning

#### 5.1 Capacity Visualization

```
┌────────────────────────────────────────────────────────┐
│  Adiutor Capacity Dashboard - Team Overview           │
├────────────────────────────────────────────────────────┤
│                                                         │
│  Week: Nov 1-7, 2025                                   │
│                                                         │
│  John Developer        ████████████░░  75% (30/40 hrs)│
│  ├─ E-Commerce (20hrs)  ██████████                    │
│  ├─ Mobile App (8hrs)   ████                          │
│  └─ Bug Fixes (2hrs)    █                             │
│  Available: 10 hrs  [View Schedule] [Assign Task]      │
│                                                         │
│  Sarah Engineer        ████████████████  100% (40/40)  │
│  ├─ CRM System (24hrs)  ████████████                  │
│  ├─ API Integration (12hrs) ██████                    │
│  └─ Code Review (4hrs)  ██                            │
│  ⚠️ At Capacity  [View Schedule] [Request Extension]  │
│                                                         │
│  Mike Coder            ██████░░░░░░░░  40% (16/40 hrs)│
│  ├─ Landing Page (12hrs) ██████                       │
│  └─ Testing (4hrs)      ██                            │
│  ✅ Available: 24 hrs  [View Schedule] [Assign Task]  │
│                                                         │
│  Team Summary:                                         │
│  Total Capacity: 120 hours                            │
│  Scheduled: 86 hours (72%)                            │
│  Available: 34 hours (28%)                            │
│                                                         │
│  [⚖️ Balance Workload] [📊 View Analytics]            │
└────────────────────────────────────────────────────────┘
```

#### 5.2 Intelligent Task Distribution

```php
Smart Task Distribution Algorithm:

When multiple adiutors match a task:
┌────────────────────────────────────────────────────┐
│ 1. Calculate Individual Capacity                   │
│    CurrentHours = Scheduled hours this week        │
│    MaxHours = Working hours per week               │
│    AvailableCapacity = MaxHours - CurrentHours     │
│                                                     │
│ 2. Workload Balance Score                          │
│    BalanceScore = AvailableCapacity / MaxHours     │
│    Bonus: +0.2 if least loaded in team             │
│    Penalty: -0.3 if overbooked                     │
│                                                     │
│ 3. Schedule Continuity                             │
│    If adiutor already working on same project:     │
│    → Continuity bonus: +0.15                       │
│    → Context switching cost reduced                │
│                                                     │
│ 4. Time Zone Consideration                         │
│    If task deadline within working hours:          │
│    → Timezone match bonus: +0.1                    │
│                                                     │
│ 5. Final Assignment Decision                       │
│    Combine: Skill Score + Balance Score +          │
│             Continuity + Timezone                  │
│    Select: Highest combined score                  │
│    Schedule: Earliest available optimal slot       │
└────────────────────────────────────────────────────┘
```

### 6. Service Classes for Schedule Management

```bash
app/Services/Schedule/
├── TaskScheduler.php              // Main scheduling orchestrator
├── ScheduleOptimizer.php          // Optimize task arrangements
├── DependencyResolver.php         // Handle task dependencies
├── ConflictDetector.php           // Identify scheduling conflicts
├── ConflictResolver.php           // Resolve conflicts automatically
├── CapacityAnalyzer.php           // Analyze adiutor capacity
├── WorkloadBalancer.php           // Balance tasks across team
├── TimeEstimator.php              // Estimate task durations
├── CalendarEventManager.php       // Manage Google Calendar events
└── ScheduleSyncService.php        // Sync schedules with calendar
```

### 7. API Routes for Schedule Management

```php
routes/web.php (Admin)

// Schedule Management
GET  /admin/schedules/timeline             // Team schedule timeline
GET  /admin/schedules/adiutor/{id}         // Individual adiutor schedule
POST /admin/schedules/task/{id}/schedule   // Schedule a task
POST /admin/schedules/task/{id}/reschedule // Reschedule a task
GET  /admin/schedules/conflicts            // View all conflicts
POST /admin/schedules/conflicts/{id}/resolve // Resolve conflict
GET  /admin/schedules/capacity             // Team capacity overview
POST /admin/schedules/optimize             // Auto-optimize schedules
GET  /admin/schedules/dependencies/{projectId} // View task dependencies

routes/web.php (Adiutor)

// Adiutor Schedule Views
GET  /adiutor/schedule/calendar            // Personal calendar view
GET  /adiutor/schedule/tasks               // Scheduled tasks list
POST /adiutor/schedule/working-hours       // Update working hours
GET  /adiutor/schedule/conflicts           // View personal conflicts
POST /adiutor/schedule/task/{id}/adjust    // Request time adjustment
POST /adiutor/schedule/break               // Schedule break time
GET  /adiutor/schedule/availability        // View availability

routes/api.php

// Schedule API
GET  /api/schedule/availability/{adiutorId}/{date} // Check availability
POST /api/schedule/find-slot               // Find optimal time slot
GET  /api/schedule/conflicts/check         // Check for conflicts
POST /api/schedule/sync                    // Manual calendar sync
```

### 8. Schedule Notifications & Alerts

```php
Schedule-Related Notifications:

1. Task Scheduled Notification
   → "Your task 'API Development' has been scheduled for Nov 1, 9:00 AM"
   → Include: Calendar event link, task details, prep materials

2. Schedule Change Notification
   → "Task 'Database Design' rescheduled from Nov 2 to Nov 3"
   → Reason: Conflict with client meeting
   → Action: Accept / Request alternative time

3. Upcoming Task Reminder
   → "Task starting in 1 hour: Frontend Components"
   → Quick actions: Start timer, View details, Postpone

4. Conflict Alert
   → "⚠️ Schedule conflict detected on Nov 3, 2:00 PM"
   → Suggest resolution, Request manual review

5. Deadline Proximity Warning
   → "Task 'Bug Fixes' due in 4 hours - Currently at 60% progress"
   → Escalate if at risk of missing deadline

6. Capacity Warning (Admin)
   → "Sarah Engineer at 100% capacity this week"
   → Suggest: Redistribute tasks, Extend deadlines, Add resources

7. Daily Schedule Digest (Morning)
   → "Today's Schedule: 3 tasks, 6 hours planned, 2 meetings"
   → Task list with time blocks, Focus time highlighted

8. Weekly Planning Summary (Sunday Evening)
   → "Week ahead: 5 projects, 15 tasks, 32 hours scheduled"
   → Capacity status, Upcoming deadlines, Preparation needed
```

---

## Implementation Plan

### Phase 1: Database & Models (Week 1-2)

#### 1.1 Migration Files
```bash
database/migrations/
├── 2025_11_01_000001_create_adiutor_calendar_integrations_table.php
├── 2025_11_01_000002_create_adiutor_availability_cache_table.php
├── 2025_11_01_000003_create_smart_assignment_recommendations_table.php
└── 2025_11_01_000004_extend_project_assignments_table.php
```

#### 1.2 Model Classes
```bash
app/Models/
├── AdiutorCalendarIntegration.php
├── AdiutorAvailabilityCache.php
└── SmartAssignmentRecommendation.php
```

#### 1.3 Configuration Files
```bash
config/
└── google_calendar.php  // API credentials, settings
```

### Phase 2: Google Calendar Integration & Schedule Management (Week 3-5)

#### 2.1 Service Classes
```bash
app/Services/
├── GoogleCalendarService.php      // OAuth, CRUD operations
├── CalendarSyncService.php        // Background sync jobs
├── AvailabilityCalculator.php     // Compute free/busy times
├── CalendarWebhookHandler.php     // Handle real-time updates
└── Schedule/
    ├── TaskScheduler.php          // Main scheduling orchestrator
    ├── ScheduleOptimizer.php      // Optimize task arrangements
    ├── DependencyResolver.php     // Handle task dependencies
    ├── ConflictDetector.php       // Identify scheduling conflicts
    ├── ConflictResolver.php       // Resolve conflicts automatically
    ├── CapacityAnalyzer.php       // Analyze adiutor capacity
    ├── WorkloadBalancer.php       // Balance tasks across team
    ├── TimeEstimator.php          // Estimate task durations
    ├── CalendarEventManager.php   // Manage Google Calendar events
    └── ScheduleSyncService.php    // Sync schedules with calendar
```

#### 2.2 Jobs (Queue)
```bash
app/Jobs/
├── SyncAdiutorCalendar.php           // Scheduled sync
├── CreateProjectCalendarEvent.php    // Auto-create events
├── CreateTaskCalendarEvent.php       // Auto-create task events
├── UpdateCalendarAvailability.php    // Update cache
├── ProcessCalendarWebhook.php        // Handle webhooks
├── AutoScheduleTask.php              // Schedule task automatically
├── RescheduleTaskChain.php           // Reschedule dependent tasks
├── DetectScheduleConflicts.php       // Background conflict detection
├── OptimizeTeamSchedule.php          // Nightly schedule optimization
└── SendScheduleDigest.php            // Daily/weekly schedule summaries
```

#### 2.3 API Routes
```php
routes/api.php

// Calendar OAuth
GET  /api/calendar/connect        // Initiate OAuth
GET  /api/calendar/callback       // OAuth callback
POST /api/calendar/disconnect     // Remove connection
GET  /api/calendar/status         // Connection status

// Availability
GET  /api/calendar/availability/{adiutorId}  // Get available slots
POST /api/calendar/sync                       // Manual sync
GET  /api/calendar/free-busy                  // Free/busy query

// Schedule Management
GET  /api/schedule/availability/{adiutorId}/{date} // Check availability
POST /api/schedule/find-slot               // Find optimal time slot
GET  /api/schedule/conflicts/check         // Check for conflicts
POST /api/schedule/task/schedule           // Schedule a task
POST /api/schedule/task/reschedule         // Reschedule a task
GET  /api/schedule/timeline/{adiutorId}    // Get adiutor timeline
POST /api/schedule/optimize                // Optimize schedule

// Webhooks
POST /api/calendar/webhook                    // Google Calendar webhooks
```

### Phase 3: Smart Assignment Algorithm (Week 6-7)

#### 3.1 Service Classes
```bash
app/Services/SmartAssignment/
├── AssignmentScoreCalculator.php  // Main scoring logic
├── SkillMatcher.php               // Skill matching algorithm
├── WorkloadAnalyzer.php           // Current workload analysis
├── PerformanceEvaluator.php       // Historical performance
├── CostOptimizer.php              // Budget optimization
└── RecommendationEngine.php       // Generate recommendations
```

#### 3.2 Controller Methods
```bash
app/Http/Controllers/Admin/
└── SmartAssignmentController.php
    ├── getRecommendations()       // Fetch top matches
    ├── autoAssign()               // Auto-assign top match
    ├── recalculate()              // Refresh scores
    ├── getScoreBreakdown()        // Detailed score info
    └── updateWeights()            // Adjust scoring weights
```

#### 3.3 API Routes
```php
routes/web.php (Admin)

// Smart Assignment
GET  /admin/projects/{id}/recommendations       // Get recommendations
POST /admin/projects/{id}/auto-assign           // Auto-assign
POST /admin/projects/{id}/assign-recommended    // Assign specific recommendation
GET  /admin/recommendations/dashboard           // Dashboard view
POST /admin/recommendations/recalculate         // Recalculate all
```

### Phase 4: User Interface (Week 8-9)

#### 4.1 Blade Views
```bash
resources/views/
├── admin/
│   ├── projects/
│   │   ├── smart-assignment-modal.blade.php
│   │   └── assignment-dashboard.blade.php
│   ├── recommendations/
│   │   ├── index.blade.php
│   │   └── score-breakdown.blade.php
│   └── schedules/
│       ├── timeline.blade.php              // Team schedule timeline
│       ├── capacity-dashboard.blade.php    // Capacity overview
│       ├── conflicts.blade.php             // Conflict management
│       └── task-dependencies.blade.php     // Dependency visualization
└── adiutor/
    ├── settings/
    │   ├── calendar-integration.blade.php
    │   ├── availability.blade.php
    │   └── working-hours.blade.php
    └── schedule/
        ├── calendar.blade.php              // Personal calendar view
        ├── tasks-timeline.blade.php        // Task schedule
        ├── conflicts.blade.php             // Personal conflicts
        └── time-preferences.blade.php      // Scheduling preferences
```

#### 4.2 Vue.js Components (Optional)
```bash
resources/js/components/
├── SmartAssignmentWidget.vue
├── CalendarSyncStatus.vue
├── AvailabilityTimeline.vue
├── ScoreVisualization.vue
├── AdiutorComparisonTable.vue
├── ScheduleTimeline.vue             // Interactive timeline
├── TaskScheduleCard.vue             // Individual task card
├── ConflictResolutionModal.vue      // Conflict resolver
├── CapacityGauge.vue                // Capacity visualization
├── DependencyGraph.vue              // Task dependency graph
├── WorkingHoursEditor.vue           // Working hours editor
└── ScheduleOptimizer.vue            // Optimization controls
```

#### 4.3 JavaScript Features
- Real-time score updates
- Drag-and-drop assignment
- Drag-and-drop task scheduling
- Calendar visualization
- Interactive timeline navigation
- Interactive filters
- Live availability checking
- Conflict visualization and resolution
- Gantt chart for dependencies
- Capacity heat maps
- Auto-refresh on calendar changes

### Phase 5: Testing & Optimization (Week 10-11)

#### 5.1 Unit Tests
```bash
tests/Unit/
├── SmartAssignment/
│   ├── AssignmentScoreCalculatorTest.php
│   ├── SkillMatcherTest.php
│   ├── WorkloadAnalyzerTest.php
│   └── PerformanceEvaluatorTest.php
├── Schedule/
│   ├── TaskSchedulerTest.php
│   ├── DependencyResolverTest.php
│   ├── ConflictDetectorTest.php
│   ├── ConflictResolverTest.php
│   ├── TimeEstimatorTest.php
│   └── WorkloadBalancerTest.php
└── Services/
    ├── GoogleCalendarServiceTest.php
    └── ScheduleSyncServiceTest.php
```

#### 5.2 Feature Tests
```bash
tests/Feature/
├── SmartAssignmentTest.php
├── CalendarIntegrationTest.php
├── AutoAssignmentTest.php
├── AvailabilityCalculationTest.php
├── TaskSchedulingTest.php
├── AutoReschedulingTest.php
├── ConflictManagementTest.php
├── DependencyHandlingTest.php
└── CapacityPlanningTest.php
```

#### 5.3 Performance Tests
- Load testing with 100+ adiutors
- Concurrent assignment scenarios
- Calendar API rate limit handling
- Cache optimization
- Schedule optimization with 1000+ tasks
- Real-time conflict detection performance
- Webhook processing under load
- Two-way sync stress testing

### Phase 6: Documentation & Training (Week 12)

#### 6.1 Documentation
```bash
docs/
├── smart-assignment-guide.md
├── calendar-integration-setup.md
├── schedule-management-guide.md
├── task-scheduling-workflow.md
├── conflict-resolution-guide.md
├── algorithm-explanation.md
└── troubleshooting.md
```

#### 6.2 Training Materials
- Admin training video (Assignment & Scheduling)
- Adiutor calendar setup guide
- Schedule management tutorial
- Conflict resolution walkthrough
- API documentation
- FAQ document

---

## Previous System Enhancements

### 1. Multi-Role Authentication System
- **Auth0 integration** for social login
- **OAuth 2.0** for third-party services
- **Role-based access control** (Admin, Client, Adiutor)
- **Profile management** per role type

### 2. Service Request → Project Workflow
- Clients submit service requests
- Admins review and approve
- Payment gateway integration (Maya)
- Automatic project creation upon payment
- **Milestone payment system** (Full, Milestone, Downpayment)

### 3. Task Management System
- Tasks belong to projects (not requests)
- **Phase-based organization** via project milestones
- Budget allocation per task
- Time tracking integration
- Progress percentage tracking

### 4. Document Management
- **Polymorphic relationships** (documentable)
- File uploads to cloud storage
- Access control based on payment status
- Version control support

### 5. Messaging & Notifications
- Real-time chat per project
- **Firebase Cloud Messaging** integration
- Email notifications
- In-app notification center
- Conversation threading

### 6. Feedback & Rating System
- Project-level feedback
- Adiutor-level feedback
- 5-star rating system
- Public/private feedback options
- Performance metrics tracking

### 7. Time Tracking
- Time entry logging
- Admin approval workflow
- Hourly rate calculations
- Billable hours tracking
- Project cost monitoring

### 8. Audit Logging
- Comprehensive activity tracking
- User action history
- Change detection
- Security monitoring

### 9. Advanced Reporting
- Budget vs. actual reports
- Performance analytics
- Client spending reports
- Adiutor productivity metrics
- Custom report templates

---

## Timeline & Milestones

### 📅 12-Week Implementation Schedule

| Week | Phase | Deliverables | Status |
|------|-------|--------------|--------|
| **1-2** | Database & Models | Migrations, Models, Relationships | 🔜 Pending |
| **3-5** | Calendar & Schedule | OAuth, Sync, Task Scheduling, Conflicts | 🔜 Pending |
| **6-7** | Smart Algorithm | Scoring Engine, Recommendations | 🔜 Pending |
| **8-9** | User Interface | Admin UI, Adiutor Settings, Timeline | 🔜 Pending |
| **10-11** | Testing | Unit/Feature/Performance Tests | 🔜 Pending |
| **12** | Documentation | Guides, Training, Deployment | 🔜 Pending |

### 🎯 Key Milestones

- ✅ **Milestone 1** (Week 2): Database schema complete (including schedule tables)
- ✅ **Milestone 2** (Week 5): Calendar integration & task scheduling working
- ✅ **Milestone 3** (Week 7): Smart assignment algorithm with workload balancing
- ✅ **Milestone 4** (Week 9): UI functional with timeline & conflict resolution
- ✅ **Milestone 5** (Week 11): System tested and optimized
- ✅ **Milestone 6** (Week 12): Production deployment ready

---

## Risk Assessment

### High-Risk Items

| Risk | Impact | Mitigation |
|------|--------|------------|
| **Google Calendar API Rate Limits** | High | Implement caching, batch requests, use webhooks |
| **Algorithm Accuracy** | High | A/B testing, machine learning refinement, feedback loop |
| **Schedule Optimization Complexity** | High | Incremental rollout, manual override, feedback collection |
| **Calendar OAuth Complexity** | Medium | Clear documentation, automated setup wizard |
| **Performance with Large Dataset** | Medium | Database indexing, Redis caching, queue optimization |
| **User Adoption Resistance** | Medium | Training programs, gradual rollout, manual override option |

### Medium-Risk Items

| Risk | Impact | Mitigation |
|------|--------|------------|
| **Calendar Sync Delays** | Medium | Background jobs, real-time webhooks, sync status indicator |
| **Task Dependency Deadlocks** | Medium | Circular dependency detection, validation, admin alerts |
| **Schedule Conflict Overload** | Medium | Prioritization rules, smart batching, manual review queue |
| **Skill Data Quality** | Medium | Admin review tools, adiutor self-reporting, validation |
| **Timezone Issues** | Low | UTC storage, proper timezone handling, user settings |
| **Third-Party API Changes** | Low | Version pinning, monitoring, fallback mechanisms |

---

## Success Metrics

### Quantitative KPIs
- ⏱️ **Assignment Time**: Target 60% reduction (from avg. 15 min to 6 min)
- 🎯 **Match Accuracy**: Target 85%+ adiutor-project compatibility
- 📅 **Scheduling Efficiency**: 90%+ of tasks auto-scheduled successfully
- ⚠️ **Conflict Rate**: < 5% schedule conflicts requiring manual resolution
- ⚡ **Response Time**: API response < 2 seconds for recommendations
- 📊 **Adoption Rate**: 70%+ of assignments using smart system (6 months)
- ⏰ **On-Time Delivery**: 25% improvement in deadline adherence
- 💯 **User Satisfaction**: 4.5/5 stars from admins and adiutors

### Qualitative KPIs
- ✅ Reduced administrative overhead
- ✅ Improved adiutor work-life balance
- ✅ Better project delivery rates
- ✅ Enhanced client satisfaction
- ✅ Data-driven decision making
- ✅ Reduced context switching for adiutors
- ✅ Improved team capacity utilization
- ✅ Better work schedule predictability

---

## Next Steps

### Immediate Actions (Week 1)
1. ✅ Review and approve implementation plan
2. ✅ Set up Google Cloud Project for Calendar API
3. ✅ Create development branch: `feature/smart-assignment`
4. ✅ Set up project board for task tracking
5. ✅ Schedule kickoff meeting with development team

### Pre-Development Checklist
- [ ] Google Cloud Console access granted
- [ ] OAuth credentials generated
- [ ] Development environment configured
- [ ] Testing accounts created
- [ ] Staging environment prepared
- [ ] Team roles assigned

---

## Appendix

### A. Technology Stack
- **Backend**: Laravel 11, PHP 8.2+
- **Database**: MySQL 8.0+
- **Queue**: Redis + Laravel Horizon
- **Cache**: Redis
- **External APIs**: Google Calendar API v3
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Real-time**: Laravel Echo, Pusher (optional)

### B. API Endpoints Reference
*(Full API documentation will be created in Phase 6)*

### C. Database ER Diagram
*(Will be generated after Phase 1 completion)*

### D. Glossary
- **Adiutor**: Developer/freelancer who executes projects
- **Smart Assignment**: AI-powered recommendation system
- **Proficiency Level**: Skill expertise (Beginner → Expert)
- **Workload Score**: Capacity calculation based on active work
- **Compatibility Score**: Overall match rating (0-100)
- **Task Schedule**: Planned time block for task execution
- **Schedule Conflict**: Overlapping time blocks or capacity issues
- **Task Dependency**: Prerequisite relationship between tasks
- **Auto-Scheduling**: Automatic task time allocation
- **Workload Balancing**: Even distribution of tasks across team
- **Capacity Planning**: Future resource allocation planning
- **Two-Way Sync**: Bidirectional calendar synchronization

---

**Document Owner**: Development Team  
**Last Updated**: October 30, 2025  
**Version**: 1.0  
**Status**: 📋 Ready for Review

---

## Approval Signatures

| Role | Name | Signature | Date |
|------|------|-----------|------|
| **Project Manager** | | | |
| **Lead Developer** | | | |
| **System Administrator** | | | |
| **Stakeholder** | | | |

---

*This document is a living document and will be updated as the project progresses.*

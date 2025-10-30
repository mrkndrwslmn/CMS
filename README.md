
# Treis Adiutor - Project Execution and Management System (PEMS)
A comprehensive project execution and management platform designed to facilitate end-to-end project management, from initial client requests through project execution and completion. The system enables seamless collaboration between Clients, Adiutors (service providers), and Administrators through workflow automation and project management capabilities.

## Tech Stack & API Integrations

### Core Framework & Infrastructure
- **Backend:** Laravel 12.33.0, PHP 8.2.12
- **Database:** SQLite (development), Microsoft Azure Database for MySQL (production)
- **Frontend:** Tailwind CSS, Alpine.js, Chart.js
- **Web Server:** Apache/Nginx with Windows Server deployment

### External API Integrations & Services

#### 🔐 Authentication & Security
- **Firebase Authentication API** - Single Sign-On (SSO) and multi-provider authentication
  - **Features:** User registration, login, password management, social logins
  - **Implementation:** Role-based access control, secure session management
  - **Endpoints:** `/auth/login`, `/auth/register`, `/auth/callback`

- **reCAPTCHA API (Google)** - Spam protection for public forms
  - **Features:** Bot detection, form security, human verification
  - **Implementation:** Service request forms, contact forms, user registration
  - **Integration:** JavaScript widget with server-side verification

#### 💳 Payment Processing
- **Maya Business API** - Complete payment gateway solution
  - **Features:** Credit/debit card processing, digital wallet support, webhooks
  - **Implementation:** Service request payments, project milestone billing
  - **Endpoints:** `/payment/create`, `/payment/webhook`, `/payment/status`
  - **Security:** PCI DSS compliance, secure tokenization

#### 🤖 AI & Machine Learning
- **Google Gemini AI API** - Intelligent chatbot and automation
  - **Features:** Natural language processing, context-aware responses, learning capabilities
  - **Implementation:** Customer support chatbot, automated FAQ responses
  - **Integration:** Floating widget on public pages, conversation persistence
  - **Caching:** 1-hour conversation memory with smart context retention

#### 📧 Email & Communication
- **Brevo SMTP API** (formerly Sendinblue) - Email service provider
  - **Features:** Transactional emails, bulk notifications, email templates
  - **Implementation:** User notifications, project updates, payment confirmations
  - **Templates:** Welcome emails, password resets, project status updates
  - **Tracking:** Delivery rates, open rates, click-through analytics

#### 🔔 Real-time Communication
- **Firebase Cloud Messaging (FCM)** - Push notifications and real-time updates
  - **Features:** Cross-platform push notifications, real-time messaging
  - **Implementation:** Project updates, task assignments, payment confirmations
  - **Integration:** Web push notifications, mobile app support (future)
  - **Targeting:** User role-based notifications, personalized messaging

#### 📁 Media & Storage Management
- **Cloudflare R2 API** - Enterprise cloud storage with CDN
  - **Features:** Scalable file storage, global CDN, cost optimization
  - **Implementation:** Document attachments, user avatars, project files
  - **Integration:** Seamless file upload, automatic backup, version control
  - **Performance:** Global content delivery, edge caching

#### 🗄️ Database Management
- **Microsoft Azure Database for MySQL** - Cloud database service
  - **Features:** High availability, automated backups, scaling capabilities
  - **Implementation:** Primary data storage, user management, transaction logging
  - **Security:** Encryption at rest, SSL connections, access controls
  - **Monitoring:** Performance insights, query optimization

#### 📅 Calendar & Scheduling
- **Google Calendar API** - Deadline tracking and availability management
  - **Features:** Event creation, deadline tracking, availability checking
  - **Implementation:** Project deadlines, milestone tracking, team availability
  - **Integration:** Automatic deadline creation, reminder notifications
  - **Sync:** Real-time calendar synchronization, conflict detection

#### 🎥 Video Conferencing
- **Zoom API** - Meeting scheduling and management
  - **Features:** Meeting creation, participant management, recording access
  - **Implementation:** Client-adiutor meetings, project review sessions
  - **Integration:** Automatic meeting links, calendar integration
  - **Automation:** Meeting scheduling based on project milestones

## Core Features Overview

### 🤖 AI-Powered Customer Support
- **Intelligent Chatbot:** Google Gemini AI-powered customer support with natural language processing
- **Smart Caching:** 1-hour conversation persistence with context awareness and learning capabilities
- **Universal Deployment:** Floating widget on all public pages with customizable branding
- **Instant Responses:** Automated answers about services, pricing, processes, and common questions
- **API Integration:** Google Gemini AI API with conversation tracking and response optimization
- **Setup:** [Quick Setup Guide](CHATBOT_QUICK_SETUP.md) | [Full Documentation](CHATBOT_DOCUMENTATION.md)

### 📋 Service Request Management
- **Public & Authenticated Submission:** Multiple request channels with reCAPTCHA spam protection
- **Rich File Attachments:** Support for all document and media types via Cloudflare R2 API
- **Intelligent Workflow:** Admin approval/rejection with detailed reasoning and automated notifications
- **Advanced Priority Management:** Urgent, high, medium, low prioritization with Google Calendar integration
- **Comprehensive Status Tracking:** 8-stage status progression with Firebase real-time updates
- **Budget Estimation:** Automated and manual budget approval processes with Maya Business integration
- **Payment Integration:** Seamless Maya Business gateway with webhook processing and automated workflows

### 🎯 Project Management
- **Automated Project Creation:** From approved and paid service requests via Maya Business webhook integration
- **Dynamic Team Assembly:** Multi-adiutor assignment with Google Calendar availability checking
- **Advanced Budget Tracking:** Real-time budget monitoring with Maya Business payment integration
- **Milestone Management:** Phase-based project progression with Google Calendar deadline tracking
- **Status Orchestration:** 6-tier project status management with Firebase real-time notifications
- **Completion Workflows:** Structured handoff and closure processes with Brevo email automation
- **Timeline Analytics:** Deadline tracking and performance metrics via Google Calendar API integration

### ✅ Task Management System
- **Dual Creation Modes:** Admin and adiutor task creation with automated Brevo email notifications
- **Smart Assignment Validation:** Automatic team membership verification with Google Calendar availability
- **Dynamic Progress Tracking:** Real-time status and percentage updates via Firebase notifications
- **Granular Budget Allocation:** Task-level budget management integrated with Maya Business tracking
- **Budget Change Requests:** Structured approval workflow with Brevo email notifications and Firebase alerts
- **Completion Verification:** Multi-stage task completion process with Zoom meeting integration for reviews
- **Performance Analytics:** Task efficiency and timeline analysis with Google Calendar integration

### 📁 Advanced Document Management
- **Cloud-Native Storage:** Integrated Cloudflare R2 API infrastructure with global CDN distribution
- **Intelligent Organization:** Automatic categorization by project and type with metadata indexing
- **Seamless Migration:** Automated transition from local to cloud storage via Cloudflare R2 API
- **Multi-Level Attachments:** Project, task, and request-level documents with role-based access control
- **Secure Access Control:** Firebase Authentication-integrated role-based document permissions and sharing controls
- **Version Management:** Document history and revision tracking with automated backup via Azure Database
- **CDN Distribution:** Global content delivery optimization through Cloudflare edge network

### ☁️ Cloud Infrastructure (Cloudflare R2 + Azure)
- **Scalable Storage:** Enterprise-grade file management via Cloudflare R2 API with unlimited scaling
- **Cost Optimization:** Efficient storage pricing with built-in CDN through Cloudflare edge network
- **Automatic Organization:** Smart bucket structure and file categorization with metadata tagging
- **Migration Tools:** Seamless transition utilities from local storage to Cloudflare R2
- **Global Distribution:** Worldwide content delivery network with edge caching optimization
- **Database Management:** Microsoft Azure Database for MySQL with automated backups and scaling
- **Setup:** [R2 Configuration Guide](CLOUDFLARE_R2_SETUP.md) | [Azure Database Setup](AZURE_DATABASE_SETUP.md)

### 💬 Communication & Feedback
- **Multi-Channel Feedback:** Client satisfaction tracking via Brevo email campaigns and Firebase notifications
- **Advanced Rating System:** 5-star ratings with detailed comments and automated Brevo follow-up emails
- **Admin Response Framework:** Structured feedback management with Brevo email templates and workflows
- **Communication History:** Complete interaction audit trails stored in Azure Database with full searchability
- **Notification System:** Real-time updates via Firebase FCM with email backup through Brevo SMTP
- **Feedback Analytics:** Satisfaction trends and insights with automated reporting via Brevo analytics integration
- **Video Meetings:** Zoom API integration for client feedback sessions and project reviews

### 👥 Advanced User Management
- **Role-Based Access Control:** Admin, Client, Adiutor with Firebase Authentication SSO integration and granular permissions
- **Profile Specialization:** Role-specific profile extensions with Google Calendar availability integration
- **Status Management:** Active/inactive controls with automated Brevo email workflows for status changes
- **Authentication Integration:** Firebase Authentication API for SSO, social logins, and multi-provider support
- **Security Framework:** Comprehensive permission and policy system with reCAPTCHA spam protection
- **User Analytics:** Engagement and performance tracking with Azure Database analytics and reporting

### 📊 Business Intelligence & Reporting
- **Real-Time Dashboards:** Live statistics and performance metrics
- **Custom Report Builder:** Flexible reporting with multiple export formats
- **Advanced Analytics:** User growth, task completion, revenue tracking
- **Interactive Charts:** Chart.js powered visualizations
- **Data Export:** CSV, Excel, PDF report generation
- **Performance Metrics:** KPI tracking and business insights

### 🔔 Notification & Alert System
- **Real-Time Notifications:** Instant updates via Firebase FCM across all user roles with customizable targeting
- **Multi-Channel Delivery:** Firebase push notifications, Brevo email delivery, and in-app notifications
- **Smart Prioritization:** Intelligent notification categorization with Firebase Analytics and user behavior tracking
- **Customizable Preferences:** User-controlled notification settings with Brevo subscription management integration
- **Activity Tracking:** Comprehensive audit and activity logs stored in Azure Database with real-time Firebase updates
- **Firebase Integration:** Complete push notification infrastructure with advanced targeting and analytics

### 💳 Financial Management
- **Payment Gateway Integration:** Maya Business API for complete payment processing with webhook automation
- **Budget Management:** Project and task-level budget controls with real-time Maya Business transaction tracking
- **Change Request System:** Structured budget modification workflow with Brevo email notifications and approvals
- **Financial Reporting:** Revenue tracking and financial analytics integrated with Maya Business transaction data
- **Payment History:** Complete transaction audit trails with Maya Business API integration and Azure Database storage
- **Billing Automation:** Automated invoicing via Brevo email templates and Maya Business payment link generation

### ⏱️ Time Tracking & Productivity
- **Integrated Time Tracking:** Built-in timer and manual entry systems
- **Productivity Analytics:** Efficiency metrics and performance tracking
- **Timesheet Management:** Automated timesheet generation and approval
- **Billing Integration:** Time-based billing and rate calculations
- **Project Time Allocation:** Resource planning and time budgeting
- **Performance Insights:** Individual and team productivity metrics

## User Roles & Access Levels

### 🛡️ Administrator (System Managers)
**Full system oversight and management capabilities**

**Core Responsibilities:**
- Complete service request lifecycle management
- Project creation, team assembly, and resource allocation
- Budget approval and financial oversight
- System configuration and user management
- Performance monitoring and business analytics

**Access Privileges:**
- **Dashboard:** Real-time business metrics with interactive charts and export capabilities
- **Request Management:** Complete CRUD operations with approval workflows
- **Project Management:** End-to-end project lifecycle with team assignment and budget tracking
- **Task Management:** Full task creation, assignment, and monitoring with budget change approvals
- **User Management:** Complete user administration with role management and status controls
- **Document Management:** Global document access with organization and security controls
- **Financial Management:** Budget oversight, payment tracking, and financial reporting
- **System Analytics:** Advanced reporting with custom report generation and data export
- **Notification Management:** System-wide notification control and preference management

### 👤 Client (Service Requesters)
**Service consumers with project visibility and feedback capabilities**

**Core Responsibilities:**
- Service request submission with detailed requirements
- Payment processing and financial compliance
- Project progress monitoring and milestone tracking
- Quality feedback and satisfaction reporting
- Document review and approval processes

**Access Privileges:**
- **Personal Dashboard:** Project overview with progress tracking and milestone visibility
- **Request Management:** Personal request submission, tracking, and history management
- **Project Monitoring:** Assigned project visibility with timeline and deliverable tracking
- **Payment Portal:** Secure payment processing with transaction history and receipt management
- **Document Access:** Project-related document viewing and download capabilities
- **Feedback System:** Project rating and detailed feedback submission with response tracking
- **Communication:** Direct messaging with project teams and administrators
- **Profile Management:** Personal information and preference management

### ⚡ Adiutor (Service Providers)
**Service delivery professionals with project execution capabilities**

**Core Responsibilities:**
- Project acceptance and timeline commitment
- Task execution and progress reporting
- Quality deliverable creation and submission
- Budget management and change request submission
- Client communication and relationship management

**Access Privileges:**
- **Professional Dashboard:** Project portfolio with performance metrics and earnings tracking
- **Project Management:** Assigned project details with task breakdown and milestone tracking
- **Task Execution:** Individual task management with progress reporting and completion workflows
- **Time Tracking:** Integrated time logging with productivity analytics and billing integration
- **Document Management:** Project document upload, organization, and client sharing
- **Budget Management:** Budget tracking with change request submission and approval monitoring
- **Client Collaboration:** Direct client communication within project context
- **Performance Analytics:** Personal productivity metrics and professional development insights
- **Portfolio Management:** Work showcase and professional profile enhancement

## System Architecture & Workflow

### 🔄 Complete Service Delivery Workflow

```
┌─────────────┐    ┌──────────────┐    ┌─────────────┐    ┌──────────────┐
│   Client    │───▶│   Service    │───▶│   Payment   │───▶│   Project    │
│   Request   │    │   Review     │    │ Processing  │    │   Creation   │
└─────────────┘    └──────────────┘    └─────────────┘    └──────────────┘
                            │                                       │
                            ▼                                       ▼
┌─────────────┐    ┌──────────────┐    ┌─────────────┐    ┌──────────────┐
│   Client    │◀───│   Feedback   │◀───│    Task     │◀───│   Team      │
│ Satisfaction│    │ Collection   │    │ Execution   │    │ Assignment  │
└─────────────┘    └──────────────┘    └─────────────┘    └──────────────┘
```

### 📋 Detailed Process Flow

#### Phase 1: Service Request Initiation
- **Client Submission:** Multi-channel request submission (public form/authenticated portal)
- **Requirement Gathering:** Detailed service specifications with file attachments
- **Initial Assessment:** Automated categorization and priority assignment
- **Queue Management:** Admin notification and request queue organization

#### Phase 2: Administrative Review & Approval
- **Expert Review:** Comprehensive requirement analysis by domain experts
- **Budget Estimation:** Accurate cost calculation based on scope and complexity
- **Approval Decision:** Structured approval/rejection with detailed reasoning
- **Client Communication:** Automated status updates and next steps

#### Phase 3: Payment Processing & Verification
- **Payment Request:** Secure Maya gateway integration for transaction processing
- **Verification:** Real-time payment confirmation and webhook processing
- **Financial Recording:** Automatic transaction logging and audit trail creation
- **Process Advancement:** Automated workflow progression upon payment confirmation

#### Phase 4: Project Creation & Team Assembly
- **Automatic Project Generation:** Seamless transition from paid request to active project
- **Team Selection:** Strategic adiutor assignment based on skills and availability
- **Resource Allocation:** Budget distribution and resource planning
- **Project Initialization:** Timeline establishment and milestone planning

#### Phase 5: Task Breakdown & Assignment
- **Work Decomposition:** Strategic breakdown of projects into manageable tasks
- **Smart Assignment:** Team member validation and workload balancing
- **Budget Allocation:** Granular budget distribution across individual tasks
- **Progress Framework:** Milestone setting and success criteria definition

#### Phase 6: Execution & Monitoring
- **Real-Time Tracking:** Continuous progress monitoring and status updates
- **Quality Assurance:** Regular deliverable review and quality checkpoints
- **Communication Management:** Structured client-team communication channels
- **Issue Resolution:** Proactive problem identification and resolution workflows

#### Phase 7: Completion & Satisfaction
- **Deliverable Review:** Comprehensive quality assessment and client approval
- **Feedback Collection:** Detailed satisfaction surveys and improvement insights
- **Knowledge Capture:** Process documentation and lessons learned recording
- **Relationship Management:** Client relationship nurturing and future opportunity identification

## Getting Started

### System Requirements

**Windows Server Requirements:**
- **Operating System:** Windows 10/11 or Windows Server 2019/2022
- **PHP:** 8.2+ with required extensions (BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML)
- **Composer:** Latest stable version for PHP dependency management (install via Windows installer)
- **Node.js:** 18+ with npm for frontend asset compilation (download Windows installer)
- **Database:** SQLite (development) / MySQL 8.0+ / PostgreSQL 13+ via Microsoft Azure (production)
- **Web Server:** IIS 10+ with URL Rewrite Module or Apache 2.4+ / Nginx 1.18+ via XAMPP/WAMP (Windows)
- **SSL Certificate:** Required for production deployment with payment processing (Let's Encrypt or commercial)

**Development Environment (Windows):**
- **Memory Limit:** 512MB minimum (1GB recommended) - configure in php.ini
- **Execution Time:** 300 seconds for large file uploads and migrations - set in php.ini
- **Storage:** 10GB minimum for development, scalable cloud storage for production
- **Cache:** Redis for Windows recommended for production (file cache acceptable for development)
- **PowerShell:** Windows PowerShell 5.1+ or PowerShell Core 7+ for command execution

### Quick Installation Guide (Windows)

#### 1. Repository Setup
```powershell
# Clone the repository using Git for Windows or GitHub Desktop
git clone https://github.com/mrkndrwslmn/CMS.git
cd cms

# Set appropriate permissions using Windows PowerShell (Run as Administrator)
icacls storage /grant Users:F /T
icacls bootstrap\cache /grant Users:F /T
```

#### 2. Dependency Installation
```powershell
# Install PHP dependencies using Composer for Windows
composer install --optimize-autoloader

# Install and compile frontend assets using Node.js for Windows
npm install
npm run build  # For production deployment
# OR
npm run dev    # For development with hot reloading and file watching
```

#### 3. Environment Configuration
```powershell
# Create environment configuration file
copy .env.example .env

# Generate Laravel application key
php artisan key:generate

# Edit .env file using Notepad++ or VS Code with required configurations:
# - Database credentials (Azure MySQL connection string)
# - Maya Business payment gateway API keys and settings
# - Cloudflare R2 storage credentials and bucket configuration
# - Firebase FCM server key and project configuration
# - Google Gemini AI API key for chatbot functionality
# - Brevo SMTP settings for email notifications
# - Firebase Authentication domain, client ID, and client secret for SSO
# - Google Calendar API credentials for scheduling
# - Zoom API credentials for meeting integration
# - reCAPTCHA site key and secret key for spam protection
```

#### 4. Database Setup (Windows)
```powershell
# For SQLite development database (Windows compatible)
New-Item -ItemType File -Path "database\database.sqlite" -Force

# Run Laravel migrations and seeders
php artisan migrate:fresh --seed

# For Azure MySQL production database, ensure proper connection string in .env:
# DB_CONNECTION=mysql
# DB_HOST=your-azure-mysql-server.mysql.database.azure.com
# DB_PORT=3306
# DB_DATABASE=your_database_name
# DB_USERNAME=your_username@your-server-name
# DB_PASSWORD=your_password
```

#### 5. Storage and Permissions (Windows)
```powershell
# Create symbolic link for public file access (Run PowerShell as Administrator)
php artisan storage:link

# Set up Cloudflare R2 cloud storage integration
php artisan r2:setup

# Configure Windows file permissions for Laravel storage
icacls storage /grant IIS_IUSRS:F /T
icacls bootstrap\cache /grant IIS_IUSRS:F /T

# Clear and optimize caches for Windows environment
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Development Server (Windows)

#### Standard Development Setup
```powershell
# Start Laravel development server using Windows PowerShell
php artisan serve

# In a separate PowerShell window, compile and watch assets
npm run dev

# Access the application at http://localhost:8000
# Ensure Windows Firewall allows PHP and Node.js if needed
```

#### Enhanced Development (Recommended for Windows)
```powershell
# Run complete development environment (Windows optimized)
composer run dev

# This Windows-optimized command starts:
# - Laravel development server (localhost:8000)
# - Queue worker for background job processing
# - Real-time log monitoring via PowerShell
# - Asset compilation with hot module replacement
# - File system watcher for automatic recompilation
```

### Production Deployment (Windows Server)

#### Windows Server Configuration
```powershell
# Optimize for production deployment on Windows Server
composer install --optimize-autoloader --no-dev
npm run build

# Cache configuration and routes for optimal Windows performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set up Windows Service for queue workers (recommended for production)
# Use NSSM (Non-Sucking Service Manager) to create Windows service:
# nssm install "Laravel Queue Worker" "C:\path\to\php.exe" "C:\path\to\your\project\artisan queue:work --daemon"
```

#### Required Windows Services Setup
1. **Azure Database:** Configure MySQL connection via Azure portal and update .env
2. **IIS Configuration:** Set up IIS with PHP FastCGI and URL Rewrite Module
3. **Cloudflare R2:** Configure cloud storage via dashboard (see [Windows setup guide](CLOUDFLARE_R2_SETUP_WINDOWS.md))
4. **Maya Business:** Configure payment gateway via Maya developer portal
5. **SSL Certificate:** Install certificate via IIS Manager or Let's Encrypt for Windows
6. **Windows Firewall:** Configure firewall rules for web traffic and API access

### Application Access Points

#### Public Interface
- **Homepage:** `http://localhost:8000` - Public landing page with service information
- **Service Requests:** `http://localhost:8000/get-started` - Public request submission
- **AI Chatbot:** Available on all public pages via floating widget

#### Administrative Access
- **Admin Login:** `http://localhost:8000/admin/login`
- **Dashboard:** Complete system overview with real-time analytics
- **Management Tools:** Full CRUD operations for all system entities

#### User Portals
- **User Login:** `http://localhost:8000/login`
- **Client Dashboard:** Project tracking and service management
- **Adiutor Dashboard:** Task management and productivity tools

### Default Login Credentials

#### System Administrators
```
Email: admin@treisadiutor.com
Password: admin123

Email: manager@treisadiutor.com  
Password: admin123
```

#### Test Client Account
```
Email: john.smith@techstartup.com
Password: client123
```

#### Test Adiutor Account
```
Email: alex@treisadiutor.com
Password: adiutor123
```

### Essential Artisan Commands (Windows PowerShell)

#### Database Management
```powershell
# Fresh installation with sample data (Windows compatible)
php artisan migrate:fresh --seed

# Run specific seeder for Windows environment
php artisan db:seed --class=UserSeeder

# Create new migration file
php artisan make:migration create_new_table

# Check database connection (useful for Azure MySQL troubleshooting)
php artisan tinker
# >> DB::connection()->getPdo();
```

#### Cache and Optimization (Windows)
```powershell
# Clear all caches (Windows file system compatible)
php artisan optimize:clear

# Cache for production (Windows Server optimization)
php artisan optimize

# Clear specific caches individually
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Rebuild Windows-optimized autoloader
composer dump-autoload --optimize
```

#### Queue and Jobs (Windows Service Integration)
```powershell
# Process queue jobs manually
php artisan queue:work

# List failed jobs with details
php artisan queue:failed

# Retry all failed jobs
php artisan queue:retry all

# For Windows Service setup, create batch file:
# @echo off
# cd /d "C:\path\to\your\project"
# php artisan queue:work --daemon --timeout=300
```

#### Custom Commands (Windows Environment)
```powershell
# Migrate files to Cloudflare R2 from Windows file system
php artisan r2:migrate

# Generate system performance report for Windows Server
php artisan system:report

# Update system caches for Windows optimization
php artisan system:refresh

# Clear and warm up Windows-specific caches
php artisan cache:forget config
php artisan config:cache
```

## API Integration Matrix & Implementation Guide

### 🔗 Complete API Integration Overview

The Treis Adiutor platform integrates with 10 major external APIs to provide comprehensive functionality. Each API serves specific features and enhances the overall user experience through automation and intelligent processing.

#### API Integration Summary Table

| API Service | Primary Purpose | Implementation Areas | Key Features | Setup Complexity |
|-------------|----------------|---------------------|--------------|------------------|
| **Firebase Authentication** | Authentication & User Management | Login, Registration, SSO | Multi-provider login, JWT tokens, user profiles | Medium |
| **Maya Business** | Payment Processing | Service payments, billing | Card processing, webhooks, transaction tracking | High |
| **Google Gemini AI** | Intelligent Chatbot | Customer support, FAQ automation | Natural language processing, context awareness | Medium |
| **Brevo (SMTP)** | Email Communications | Notifications, marketing | Transactional emails, templates, analytics | Low |
| **Firebase FCM** | Real-time Notifications | Push notifications, live updates | Cross-platform messaging, targeting | Medium |
| **Cloudflare R2** | Media & File Storage | Document management, file hosting | Global CDN, cost optimization, scalability | Medium |
| **Microsoft Azure** | Database Management | Data storage, backup, scaling | High availability, automated backups | High |
| **Google Calendar** | Scheduling & Deadlines | Timeline tracking, availability | Event creation, conflict detection, reminders | Medium |
| **Zoom** | Video Conferencing | Client meetings, project reviews | Meeting scheduling, recording, integration | Medium |
| **reCAPTCHA** | Spam Protection | Form security, bot prevention | Human verification, risk analysis | Low |

### 🛡️ Authentication & Security APIs

#### Firebase Authentication SSO Integration
**Implementation Areas:**
- User registration and login workflows
- Password reset and account management
- Social media login integration (Google, Facebook, LinkedIn)
- JWT token generation and validation
- Role-based access control integration

**Configuration (Windows .env):**
```env
Firebase Authentication_DOMAIN=your-domain.auth0.com
Firebase Authentication_CLIENT_ID=your_client_id
Firebase Authentication_CLIENT_SECRET=your_client_secret
Firebase Authentication_REDIRECT_URI=https://yourdomain.com/auth/callback
Firebase Authentication_LOGOUT_URI=https://yourdomain.com/logout
```

**Key Endpoints:**
- `POST /auth/login` - User authentication
- `GET /auth/callback` - OAuth callback handling
- `POST /auth/logout` - Session termination
- `GET /auth/user` - User profile retrieval

#### reCAPTCHA Spam Protection
**Implementation Areas:**
- Public service request forms
- User registration forms
- Contact and feedback forms
- Password reset requests

**Configuration (Windows .env):**
```env
RECAPTCHA_SITE_KEY=your_site_key
RECAPTCHA_SECRET_KEY=your_secret_key
RECAPTCHA_VERSION=v2  # or v3 for invisible reCAPTCHA
```

**Integration Points:**
- Form validation middleware
- AJAX form submission protection
- Server-side verification
- Score-based risk assessment (v3)

### 💳 Payment Processing API

#### Maya Business Gateway
**Implementation Areas:**
- Service request payment processing
- Project milestone billing
- Subscription management (future)
- Transaction history and reporting

**Configuration (Windows .env):**
```env
MAYA_PUBLIC_KEY=pk_test_your_public_key
MAYA_SECRET_KEY=sk_test_your_secret_key
MAYA_WEBHOOK_SECRET=whsec_your_webhook_secret
MAYA_ENVIRONMENT=sandbox  # or live for production
MAYA_RETURN_URL=https://yourdomain.com/payment/success
MAYA_CANCEL_URL=https://yourdomain.com/payment/cancel
```

**Key Features:**
- Credit/debit card processing
- Digital wallet integration (GCash, PayMaya)
- Real-time webhook notifications
- PCI DSS compliance
- Automated invoice generation

**API Endpoints:**
- `POST /payment/create` - Initialize payment
- `POST /payment/webhook` - Payment status updates
- `GET /payment/status/{id}` - Payment verification
- `GET /payment/history` - Transaction history

### 🤖 AI & Machine Learning APIs

#### Google Gemini AI Chatbot
**Implementation Areas:**
- Customer support automation
- FAQ response generation
- Service inquiry handling
- Contextual conversation management

**Configuration (Windows .env):**
```env
GEMINI_API_KEY=your_gemini_api_key
GEMINI_MODEL=gemini-pro
GEMINI_TEMPERATURE=0.7
GEMINI_MAX_TOKENS=1000
GEMINI_CACHE_DURATION=3600  # 1 hour conversation persistence
```

**Features:**
- Natural language understanding
- Context-aware responses
- Conversation memory (1-hour cache)
- Multi-language support
- Learning from interactions

**Integration Points:**
- Floating chat widget on public pages
- Customer service portal
- Automated email response suggestions
- FAQ knowledge base enhancement

### 📧 Communication APIs

#### Brevo SMTP Email Service
**Implementation Areas:**
- User registration confirmations
- Project status notifications
- Payment confirmations
- Marketing campaigns (future)

**Configuration (Windows .env):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_brevo_email
MAIL_PASSWORD=your_brevo_smtp_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Treis Adiutor"
```

**Email Templates:**
- Welcome and onboarding emails
- Project milestone notifications
- Payment confirmations and receipts
- Task assignment notifications
- Deadline reminders and alerts

#### Firebase Cloud Messaging (FCM)
**Implementation Areas:**
- Real-time push notifications
- Task assignment alerts
- Project update notifications
- System-wide announcements

**Configuration (Windows .env):**
```env
FIREBASE_SERVER_KEY=your_server_key
FIREBASE_PROJECT_ID=your_project_id
FIREBASE_MESSAGING_SENDER_ID=your_sender_id
FIREBASE_APP_ID=your_app_id
```

**Notification Types:**
- Task assignments and updates
- Project milestone completions
- Payment confirmations
- System maintenance alerts
- Custom user notifications

### 📁 Storage & Media APIs

#### Cloudflare R2 Cloud Storage
**Implementation Areas:**
- Document and file storage
- User avatar management
- Project deliverable hosting
- Backup and archival systems

**Configuration (Windows .env):**
```env
CLOUDFLARE_R2_ACCESS_KEY_ID=your_access_key
CLOUDFLARE_R2_SECRET_ACCESS_KEY=your_secret_key
CLOUDFLARE_R2_DEFAULT_REGION=auto
CLOUDFLARE_R2_BUCKET=your_bucket_name
CLOUDFLARE_R2_URL=https://your_account_id.r2.cloudflarestorage.com
CLOUDFLARE_R2_ENDPOINT=https://your_account_id.r2.cloudflarestorage.com
```

**Features:**
- Global CDN distribution
- Cost-effective storage pricing
- Automatic file organization
- Version control and backup
- Secure access controls

#### Microsoft Azure Database
**Implementation Areas:**
- Primary data storage
- User and project management
- Transaction logging
- Analytics and reporting

**Configuration (Windows .env):**
```env
DB_CONNECTION=mysql
DB_HOST=your_server.mysql.database.azure.com
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username@your_server
DB_PASSWORD=your_password
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
```

**Azure Features:**
- High availability and reliability
- Automated backups and point-in-time recovery
- Scalable performance tiers
- Built-in security and compliance
- Geographic redundancy options

### 📅 Scheduling & Meeting APIs

#### Google Calendar Integration
**Implementation Areas:**
- Project deadline tracking
- Team availability management
- Milestone scheduling
- Automated reminder creation

**Configuration (Windows .env):**
```env
GOOGLE_CALENDAR_ID=your_calendar_id@gmail.com
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback
```

**Calendar Features:**
- Automatic deadline creation
- Team availability checking
- Conflict detection and resolution
- Recurring milestone reminders
- Integration with project timelines

#### Zoom Meeting Integration
**Implementation Areas:**
- Client consultation meetings
- Project review sessions
- Team collaboration meetings
- Training and onboarding sessions

**Configuration (Windows .env):**
```env
ZOOM_API_KEY=your_api_key
ZOOM_API_SECRET=your_api_secret
ZOOM_JWT_TOKEN=your_jwt_token
ZOOM_ACCOUNT_ID=your_account_id
```

**Meeting Features:**
- Automated meeting scheduling
- Calendar integration
- Meeting recording access
- Participant management
- Custom meeting configurations

### 🔧 API Setup & Configuration Guides

#### Windows-Specific Setup Instructions

**1. API Key Management:**
Create a dedicated `.env` configuration file with all API credentials:

```powershell
# Copy environment template
copy .env.example .env

# Edit using Windows Notepad++ or VS Code
notepad++ .env
```

**2. SSL Certificate Requirements:**
Most APIs require SSL certificates for production:

```powershell
# For development, use Laravel's built-in SSL
php artisan serve --host=0.0.0.0 --port=8000

# For production, configure IIS with Let's Encrypt:
# Install Let's Encrypt for IIS via Web Platform Installer
```

**3. Windows Firewall Configuration:**
Ensure API access through Windows Firewall:

```powershell
# Allow HTTP/HTTPS traffic
netsh advfirewall firewall add rule name="Allow HTTP" dir=in action=allow protocol=TCP localport=80
netsh advfirewall firewall add rule name="Allow HTTPS" dir=in action=allow protocol=TCP localport=443

# Allow specific API endpoints if needed
netsh advfirewall firewall add rule name="Allow API Access" dir=out action=allow protocol=TCP remoteport=443
```

**4. Task Scheduler Integration:**
Set up Windows Task Scheduler for API maintenance:

```powershell
# Create scheduled task for queue processing
schtasks /create /sc minute /mo 1 /tn "Laravel Queue Worker" /tr "C:\path\to\php.exe C:\path\to\project\artisan queue:work --once"

# Create task for cache clearing
schtasks /create /sc daily /tn "Laravel Cache Clear" /tr "C:\path\to\php.exe C:\path\to\project\artisan cache:clear"
```

### 📊 API Performance & Monitoring

#### API Health Monitoring
The platform includes built-in API health checks:

```powershell
# Check all API connections
php artisan api:health-check

# Test specific API integration
php artisan api:test auth0
php artisan api:test maya
php artisan api:test firebase
```

#### Performance Metrics
Monitor API performance through the admin dashboard:

- **Response Time Tracking:** Average API response times
- **Error Rate Monitoring:** Failed API call tracking
- **Usage Analytics:** API call volume and patterns
- **Cost Tracking:** API usage costs and optimization

#### Troubleshooting Common Issues

**Windows-Specific API Issues:**
1. **SSL Certificate Problems:** Ensure proper certificate chain installation
2. **Firewall Blocking:** Configure Windows Firewall for API access
3. **PHP Extension Missing:** Install required PHP extensions via Windows PHP Manager
4. **Permission Issues:** Set proper IIS application pool permissions

**API Testing Tools:**
- **Postman:** API endpoint testing and documentation
- **Laravel Telescope:** Request/response debugging
- **Custom Health Checks:** Built-in API monitoring commands


## Configuration & Integration Guides (Windows)

### 🏢 Production Environment Setup (Windows Server)

#### Database Configuration (Windows Server)
- **Microsoft Azure MySQL:** See [Azure MySQL Setup Guide for Windows](storage/documentations/AZURE_MYSQL_SETUP_WINDOWS.md)
- **Connection Security:** Configure SSL connections and Windows Firewall rules
- **Local Development:** SQLite pre-configured for Windows development environment
- **Performance Tuning:** Windows-specific MySQL optimization and memory allocation

#### Cloud Storage Integration (Windows Compatible)
- **Cloudflare R2:** See [R2 Configuration Guide for Windows](storage/documentations/CLOUDFLARE_R2_SETUP_WINDOWS.md)
- **File Migration:** Automated Windows file system to cloud storage migration tools
- **CDN Integration:** Global content delivery optimized for Windows Server deployment
- **Backup Strategy:** Windows Task Scheduler automated backup to Cloudflare R2

#### Payment Gateway Setup (Windows Environment)
- **Maya Business Integration:** Configure payment processing via Windows IIS and PHP
- **Webhook Configuration:** Windows-compatible webhook handling with IIS URL Rewrite
- **Transaction Security:** PCI compliance setup for Windows Server environment
- **SSL Requirements:** Windows SSL certificate installation and configuration

#### Authentication & Security (Windows Integration)
- **Auth0 Integration:** Enterprise SSO configuration for Windows Active Directory
- **Role-Based Security:** Windows-compatible granular permission system
- **API Security:** Laravel Sanctum configuration for Windows Server deployment
- **SSL/TLS Setup:** Windows Certificate Manager integration and configuration

### 🤖 AI & Communication Features (Windows Setup)

#### Chatbot Configuration (Windows Compatible)
- **Google Gemini AI:** See [Chatbot Setup Guide for Windows](storage/documentations/CHATBOT_QUICK_SETUP_WINDOWS.md)
- **Conversation Persistence:** Windows-compatible caching with Redis for Windows
- **Widget Customization:** Windows-hosted branding and JavaScript deployment
- **Performance Optimization:** Windows Server memory management for AI processing

#### Notification System (Windows Integration)
- **Firebase FCM:** Push notification setup for Windows Server environment
- **Brevo SMTP:** Windows-compatible email configuration and template management
- **Real-time Updates:** WebSocket integration optimized for Windows IIS deployment
- **Queue Processing:** Windows Service configuration for background notification processing

#### Communication Framework (Windows Deployment)
- **Messaging System:** Windows-compatible internal communication infrastructure
- **Audit Logging:** Windows Event Log integration and compliance tracking
- **Feedback Management:** Windows-hosted customer satisfaction tracking and workflows
- **Video Integration:** Zoom API integration optimized for Windows Server deployment

### 📊 Analytics and Reporting (Windows Environment)

#### Business Intelligence (Windows Compatible)
- **Dashboard Customization:** Windows-hosted real-time metrics and KPI tracking
- **Custom Reports:** Windows-compatible report generation with Excel integration
- **Performance Analytics:** IIS log analysis and Windows Performance Monitor integration
- **Data Visualization:** Chart.js optimization for Windows browser compatibility

#### Data Management (Windows Server)
- **Export Capabilities:** Windows-native CSV, Excel, PDF generation with COM interop
- **Backup Strategies:** Windows Server Backup integration and Azure redundancy
- **Data Migration:** Windows PowerShell scripts for system migration and transfer
- **Compliance Reporting:** Windows-compatible audit trail generation and storage

### 🔧 Development & Maintenance (Windows Environment)

#### Code Quality & Testing (Windows Development)
- **PHPUnit Tests:** Windows PowerShell test execution and coverage reporting
- **Code Standards:** PSR-12 compliance with Windows-compatible formatting tools
- **Performance Monitoring:** Windows Performance Toolkit integration and optimization
- **Debugging Tools:** Xdebug configuration for Windows Visual Studio Code integration

#### Deployment & DevOps (Windows Infrastructure)
- **IIS Deployment:** Internet Information Services configuration and optimization
- **Windows Service Management:** Background queue processing and task scheduling
- **CI/CD Pipelines:** Azure DevOps integration for Windows-based deployment
- **Environment Management:** Windows-specific multi-stage deployment configuration
- **Monitoring Integration:** Windows Event Viewer and Azure Monitor integration

## Documentation Index

### 📚 Complete Documentation Library

#### System Architecture & Analysis
- **[Comprehensive System Analysis](storage/documentations/COMPREHENSIVE_SYSTEM_ANALYSIS.md)** - Complete system overview and architecture
- **[Updated System Analysis](storage/documentations/UPDATED_SYSTEM_ANALYSIS.md)** - Latest system improvements and status
- **[System Analysis](storage/documentations/SYSTEM_ANALYSIS.md)** - Core system documentation

#### Module-Specific Documentation
- **[Adiutor Comprehensive Analysis](storage/documentations/ADIUTOR_COMPREHENSIVE_ANALYSIS.md)** - Complete adiutor module documentation
- **[Adiutor Implementation Summary](storage/documentations/ADIUTOR_IMPLEMENTATION_SUMMARY.md)** - Implementation details and features
- **[Adiutor Module Analysis](storage/documentations/ADIUTOR_MODULE_ANALYSIS.md)** - Module structure and capabilities
- **[Adiutor Organization Complete](storage/documentations/ADIUTOR_ORGANIZATION_COMPLETE.md)** - Organization and workflow documentation
- **[Adiutor Enhancements Summary](storage/documentations/ADIUTOR_ENHANCEMENTS_SUMMARY.md)** - Recent improvements and features

#### Feature Implementation Guides
- **[Notification Implementation](storage/documentations/NOTIFICATION_IMPLEMENTATION_SUMMARY.md)** - Notification system setup and usage
- **[Revision System](storage/documentations/REVISION_SYSTEM_DOCUMENTATION.md)** - Document revision and version control
- **[Milestone System](storage/documentations/MILESTONE_SYSTEM.md)** - Project milestone management
- **[ReCAPTCHA Implementation](storage/documentations/RECAPTCHA_IMPLEMENTATION.md)** - Security and spam protection

#### Infrastructure & Deployment
- **[Docker Setup](storage/documentations/DOCKER_SETUP.md)** - Containerized deployment configuration
- **[Supabase Setup](storage/documentations/SUPABASE_SETUP.md)** - Cloud database configuration
- **[Cloudflare R2 Setup](CLOUDFLARE_R2_SETUP.md)** - Cloud storage configuration
- **[Chatbot Documentation](CHATBOT_DOCUMENTATION.md)** - AI chatbot implementation

## Performance & Scalability

### 🚀 Performance Optimization

#### Frontend Performance
- **Asset Optimization:** Vite-powered build system with code splitting
- **Lazy Loading:** Progressive content loading for optimal user experience
- **CDN Integration:** Global content delivery for static assets
- **Caching Strategy:** Smart browser and server-side caching

#### Backend Performance
- **Database Optimization:** Query optimization and indexing strategies
- **Redis Caching:** High-performance data caching for frequent operations
- **Queue Processing:** Background job processing for resource-intensive tasks
- **API Rate Limiting:** Intelligent rate limiting for API protection

#### Scalability Features
- **Horizontal Scaling:** Multi-server deployment capabilities
- **Load Balancing:** Traffic distribution for high availability
- **Microservices Ready:** Modular architecture for service separation
- **Cloud-Native Design:** Built for cloud deployment and scaling

### 📈 Monitoring & Analytics

#### Application Monitoring
- **Performance Metrics:** Real-time application performance tracking
- **Error Tracking:** Comprehensive error logging and notification
- **User Analytics:** Detailed user behavior and engagement metrics
- **System Health:** Infrastructure monitoring and alerting

#### Business Intelligence
- **Revenue Tracking:** Financial performance and revenue analytics
- **User Growth:** Registration and engagement trend analysis
- **Project Success:** Completion rates and client satisfaction metrics
- **Resource Utilization:** Team productivity and resource allocation insights

## Current Status & Development

### ✅ Fully Implemented Features

#### Core Platform Functionality
- **Service Request Lifecycle:** Complete end-to-end request processing
- **Project Management:** Advanced project creation, team assignment, and tracking
- **Task Management:** Comprehensive task breakdown, assignment, and completion workflows
- **User Management:** Multi-role system with granular permissions and profiles
- **Document Management:** Cloud-based storage with intelligent organization
- **Payment Processing:** Maya gateway integration with automated workflows
- **Feedback System:** Client satisfaction tracking with detailed analytics

#### Advanced Features
- **AI-Powered Chatbot:** Google Gemini integration with context awareness
- **Real-Time Dashboard:** Interactive charts and live performance metrics
- **Custom Report Builder:** Flexible reporting with multiple export formats
- **Notification System:** Multi-channel notification delivery
- **Time Tracking:** Integrated productivity and billing management
- **Budget Management:** Dynamic budget allocation with change request workflows
- **Cloud Storage:** Cloudflare R2 integration with CDN distribution

#### Technical Infrastructure
- **Authentication System:** Laravel Sanctum with Auth0 integration
- **Database Optimization:** Query optimization and intelligent indexing
- **API Framework:** RESTful API with comprehensive documentation
- **Security Framework:** Role-based access control with policy enforcement
- **Performance Monitoring:** Real-time application and infrastructure monitoring

### 🚧 Areas for Future Enhancement

#### Communication & Collaboration
- **Real-Time Messaging:** Direct chat system between clients and adiutors
- **Video Conferencing:** Integrated meeting and collaboration tools
- **Activity Feeds:** Real-time project activity and update streams
- **Team Collaboration:** Advanced collaboration features for project teams

#### Advanced Analytics & AI
- **Predictive Analytics:** AI-powered project success prediction
- **Resource Optimization:** Intelligent resource allocation recommendations
- **Performance Insights:** Advanced productivity and efficiency analytics
- **Market Intelligence:** Industry trend analysis and competitive insights

#### Mobile & API Expansion
- **Mobile Application:** Native iOS and Android applications
- **API Marketplace:** Third-party integration capabilities
- **Webhook Framework:** Advanced webhook system for external integrations
- **Export/Import Tools:** Advanced data migration and integration utilities

#### Enterprise Features
- **Multi-Tenant Architecture:** Support for multiple organizations
- **Advanced Permissions:** Fine-grained permission and policy management
- **Compliance Framework:** SOC 2, GDPR, and industry compliance tools
- **Audit & Governance:** Advanced audit trails and governance frameworks

### 🔄 Recent Improvements & Updates

#### Dashboard Enhancements
- **Real-Time Refresh:** Live data updates without page reload
- **CSV Export:** Comprehensive report generation and download
- **Interactive Charts:** Chart.js powered visualizations with data drilling
- **Performance Metrics:** Advanced KPI tracking and business intelligence

#### User Experience Improvements
- **Notification Bell:** Real-time notification display with smart categorization
- **Activity Tracking:** Comprehensive user activity monitoring
- **Mobile Optimization:** Responsive design improvements across all interfaces
- **Load Performance:** Optimized asset loading and caching strategies

#### System Reliability
- **Error Handling:** Comprehensive error tracking and user feedback
- **Data Validation:** Enhanced input validation and security measures
- **Backup Systems:** Automated backup and disaster recovery procedures
- **Monitoring Integration:** Advanced system health monitoring and alerting

## Support & Maintenance

### 🛠️ Development Support

#### Technical Support
- **Documentation:** Comprehensive technical and user documentation
- **Code Comments:** Extensive inline documentation for maintainability
- **API Documentation:** Complete API reference with examples
- **Troubleshooting Guides:** Common issue resolution and debugging help

#### Community & Resources
- **Developer Portal:** Resources for developers and contributors
- **Best Practices:** Development standards and coding guidelines
- **Training Materials:** User training and onboarding resources
- **Knowledge Base:** Searchable documentation and FAQ system

### 🔧 Maintenance & Updates

#### Regular Maintenance
- **Security Updates:** Regular security patches and vulnerability assessments
- **Performance Optimization:** Ongoing performance monitoring and improvements
- **Feature Updates:** Regular feature enhancements and user experience improvements
- **Bug Fixes:** Prompt issue resolution and quality assurance

#### Version Management
- **Release Notes:** Detailed changelog and feature documentation
- **Migration Guides:** Version upgrade instructions and compatibility notes
- **Backward Compatibility:** Ensuring smooth transitions between versions
- **Testing Procedures:** Comprehensive testing protocols for reliability

## License & Legal

### 📄 Licensing Information

This project is proprietary and confidential software owned by Treis Adiutor. All rights reserved.

**Important Legal Notice:**
- Unauthorized copying, distribution, or use is strictly prohibited
- Commercial use requires explicit written permission
- Reverse engineering or decompilation is forbidden
- All intellectual property rights are retained by the owner

### 🤝 Contributors & Acknowledgments

#### Core Development Team
- **Mark Andrew S.** - Lead Developer & System Architect

#### Technology Partners
- **Google Cloud** - AI and machine learning services
- **Cloudflare** - CDN and storage infrastructure
- **Maya Business** - Payment processing solutions
- **Laravel** - PHP framework and ecosystem

#### Special Thanks
- Laravel community for excellent documentation and support
- Open source contributors for various packages and tools
- Beta testers and early adopters for valuable feedback

### 📞 Contact Information

#### Business Inquiries
- **Email:** business@treisadiutor.com
- **Website:** https://treisadiutor.com
- **Support Portal:** https://support.treisadiutor.com

#### Technical Support
- **Developer Email:** dev@treisadiutor.com
- **Documentation:** Available in `/storage/documentations/`
- **API Documentation:** Available at `/api/documentation`

#### Emergency Support
- **Critical Issues:** emergency@treisadiutor.com
- **Security Issues:** security@treisadiutor.com
- **Response Time:** 24 hours for critical issues

---

**© 2025 Treis Adiutor. All rights reserved.**

*This Project Execution and Management System represents the next generation of professional service management, combining advanced technology with intuitive design to deliver exceptional client experiences and operational efficiency.*

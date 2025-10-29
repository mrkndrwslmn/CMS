# Treis Adiutor - Client Management System (CMS)
A client management system designed to facilitate service request handling, project management, task assignment, and collaboration between Clients, Adiutors (service providers), and Administrators.

## Tech Stack

- **Backend:** Laravel 12.33.0, PHP 8.2.12
- **Database:** SQLite (development), Supabase PostgreSQL (production)
- **Frontend:** Tailwind CSS, Alpine.js
- **Payment Gateway:** Maya Business

## Features

### AI-Powered Chatbot 🤖 (NEW!)
- Intelligent customer support powered by Google Gemini AI
- Instant answers about services, pricing, and processes
- Smart caching with 1-hour conversation persistence
- Beautiful floating widget on all public pages
- [Setup Guide](CHATBOT_QUICK_SETUP.md) | [Full Documentation](CHATBOT_DOCUMENTATION.md)

### Service Request Management
- Public and authenticated request submission
- File attachment support
- Admin approval/rejection
- Priority management
- Status tracking (pending, approved, rejected, pending_payment, paid, in_progress, completed)
- Budget estimation and approval

### Project Management
- Automatic project creation from paid requests
- Project-adiutor assignment
- Multiple adiutor support per project
- Budget tracking and overview
- Status management
- Project completion workflow
- Timeline tracking

### Task Management
- Task creation by admins and adiutors
- Task assignment validation (team members only)
- Task status tracking
- Budget allocation per task
- Progress percentage
- Task completion by adiutors
- Budget change requests

### Document Management
- File upload and storage with Cloudflare R2 integration
- Project-level documents
- Task-level documents
- Document download with direct R2 URLs
- Automatic migration from local storage
- Organized bucket structure for scalability

### Cloud Storage (Cloudflare R2) ☁️ (NEW!)
- Integrated Cloudflare R2 for all file storage
- Automatic file organization by type and project
- Seamless migration from local storage
- Cost-effective cloud storage with CDN support
- [Setup Guide](CLOUDFLARE_R2_SETUP.md)

### Feedback System
- Client project feedback
- Rating system (1-5 stars)
- Admin response capability
- Feedback history

### User Management
- Multi-role support (admin, client, adiutor)
- Profile management
- Status management (active/inactive)
- Client and adiutor profile extensions

## Login Credentials

### Administrator
- **Email:** admin@treisadiutor.com
- **Password:** admin123

or

- **Email:** manager@treisadiutor.com
- **Password:** admin123

### Client
- **Email:** john.smith@techstartup.com
- **Password:** client123

### Adiutor (Service Provider)
- **Email:** alex@treisadiutor.com
- **Password:** adiutor123

## System Workflow

### 1. Service Request Creation (Client)
- Client submits service request via public form or authenticated dashboard
- Request enters admin queue with status `pending`

### 2. Request Review & Approval (Admin)
- Admin reviews request details
- Admin approves or rejects the request
- If approved, payment request is sent to client
- Status changes: `pending` → `approved` → `pending_payment`

### 3. Payment Processing
- Client makes payment through Maya payment gateway
- System confirms payment (via webhook)
- Status changes: `pending_payment` → `paid`

### 4. Project Creation
- System automatically creates project when payment is confirmed
- Admin assigns adiutors to project
- Adiutors accept or decline project assignment
- Status changes: `assigned` → `accepted` (when adiutor accepts)

### 5. Task Management
- Tasks created for project by admin or assigned adiutors
- Tasks assigned to project team members
- Budget validated against project budget
- Adiutors work on assigned tasks and update progress
- Budget change requests can be submitted if needed

### 6. Project Completion
- All tasks marked as completed
- Admin marks project complete
- Client provides feedback and ratings
- Data stored for future reference

## Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- SQLite (for development)
- PostgreSQL support (for production with Supabase)

### Installation

1. Clone the repository:
```
git clone https://github.com/mrkndrwslmn/CMS.git
cd cms
```

2. Install PHP dependencies:
```
composer install
```

3. Install JavaScript dependencies:
```
npm install
```

4. Create and configure environment file:
```
copy .env.example .env
php artisan key:generate
```

5. Set up the database (SQLite for development):
```
touch database/database.sqlite
php artisan migrate
```

6. Seed the database with initial data:
```
php artisan db:seed
```

### Running the Application

1. Start the Laravel development server:
```
php artisan serve
```

2. Compile assets and watch for changes:
```
npm run dev
```

3. Access the application:
   - Public site: http://localhost:8000
   - Admin panel: http://localhost:8000/admin/login
   - Client dashboard: http://localhost:8000/login (then login as client)
   - Adiutor dashboard: http://localhost:8000/login (then login as adiutor)

### Additional Commands

- Run all migrations from scratch (caution: deletes all data):
```
php artisan migrate:fresh
```

- Run all migrations and seed the database:
```
php artisan migrate:fresh --seed
```

- Clear application cache:
```
php artisan optimize:clear
```

- Run the application in one command (server, queue, logs, Vite):
```
composer run dev
```

## Supabase Integration (Production)

For production environments, the system is designed to work with Supabase. See `SUPABASE_SETUP.md` for detailed configuration instructions.

## Payment Gateway Integration

The system integrates with Maya Payment Gateway for processing client payments. Configuration settings can be found in `config/maya.php`.

## Known Issues & Limitations

- Notification system database entries exist but UI display is incomplete
- Maya payment gateway integration needs webhook implementation
- Budget approval UI for budget change requests is missing
- No direct messaging system between clients and adiutors

## License

This project is proprietary and confidential. Unauthorized copying, distribution, or use is strictly prohibited.

## Contributors

- Mark Andrew S. (Lead Developer)

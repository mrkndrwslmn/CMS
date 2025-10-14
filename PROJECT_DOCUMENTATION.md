# Treis Adiutor Content Management System (TA-CMS)

## Project Overview

**Treis Adiutor** is a comprehensive task management and client service platform designed to facilitate professional support services. The system connects clients with service providers (called "Adiutors") through a web-based platform that manages task assignments, document sharing, communication, and feedback.

### Project Vision
To provide a seamless platform where clients can request professional services and get matched with qualified service providers, while maintaining transparency and quality through structured task management and feedback systems.

## Technology Stack

### Frontend Technologies
- **HTML5 & CSS3**: Semantic markup and modern styling
- **Tailwind CSS**: Utility-first CSS framework for responsive design
- **JavaScript (ES6+)**: Client-side interactivity and DOM manipulation
- **Font Awesome**: Icon library for UI elements
- **Lottie Player**: Animation library for interactive elements
- **SweetAlert2**: Modern alert and modal library
- **FullCalendar**: Calendar component for task visualization
- **Flatpickr**: Date picker component
- **Alpine.js**: Lightweight JavaScript framework for reactive components

### Backend Technologies
- **PHP 8.x**: Server-side scripting language
- **MySQL/MariaDB**: Relational database management system
- **PHPMailer**: Email sending library for notifications
- **Composer**: PHP dependency management

### Development Environment
- **XAMPP**: Local development server stack
- **Apache**: Web server
- **phpMyAdmin**: Database administration interface

## System Architecture

### Multi-Role Architecture
The system implements a role-based architecture with three distinct user types:

#### 1. **Client Role**
- Service requesters who submit tasks and requirements
- Can view task progress and provide feedback
- Access to personal dashboard and task history

#### 2. **Adiutor Role** (Service Providers)
- Professional service providers who execute tasks
- Task management and document upload capabilities
- Client relationship management and feedback reception

#### 3. **Admin Role**
- System administrators with full platform oversight
- User management, task assignment, and system monitoring
- Analytics and reporting capabilities

### Directory Structure

```
ta-cms/
├── admin/                      # Admin-specific functionality
│   ├── dashboard.php          # Admin dashboard with system metrics
│   ├── manage_users.php       # User management interface
│   ├── manage_clients.php     # Client management system
│   ├── requests.php           # Service request management
│   ├── assign_tasks.php       # Task assignment interface
│   ├── reports.php            # Analytics and reporting
│   ├── feedbacks.php          # Feedback management
│   ├── components/            # Admin-specific UI components
│   ├── functions/             # Admin utility functions
│   └── ajax/                  # AJAX endpoints for admin features
├── adiutor/                   # Service provider functionality
│   ├── dashboard.php          # Adiutor dashboard with task overview
│   ├── tasks.php              # Task management interface
│   ├── clients.php            # Client relationship management
│   ├── upload_documents.php   # Document upload system
│   ├── feedback.php           # Feedback viewing system
│   ├── reports.php            # Performance analytics
│   ├── components/            # Adiutor-specific UI components
│   ├── functions/             # Adiutor utility functions
│   └── documents/             # Document storage for adiutors
├── client/                    # Client-specific functionality
│   ├── dashboard.php          # Client dashboard with task status
│   ├── tasks.php              # Task viewing and tracking
│   ├── requests.php           # Service request history
│   ├── feedback.php           # Feedback submission system
│   ├── messages.php           # Communication interface
│   └── components/            # Client-specific UI components
├── auth/                      # Authentication system
│   └── auth.php               # Role-based access control
├── includes/                  # Shared system components
│   ├── db.php                 # Database connection and configuration
│   ├── functions.php          # Global utility functions
│   └── session.php            # Session management
├── components/                # Shared UI components
│   ├── navbar.php             # Navigation header
│   └── footer.php             # Site footer
├── assets/                    # Static resources
│   ├── css/                   # Custom stylesheets
│   ├── js/                    # JavaScript files
│   └── images/                # Image assets and logos
├── documents/                 # File storage system
│   └── user-uploads/          # User-uploaded documents
├── vendor/                    # Composer dependencies
│   └── phpmailer/             # Email library
├── uploads/                   # Legacy upload directory
├── form-fields/               # Form configuration
├── helpers/                   # Helper utilities
├── task-specific/             # Task-specific configurations
├── templates/                 # Email and document templates
├── index.php                  # Public landing page
├── login.php                  # User authentication
├── signup.php                 # User registration
├── get-started.php            # Service request form
├── about.php                  # Company information
├── composer.json              # PHP dependencies
└── README.md                  # Project documentation
```

## Database Schema

### Core Tables

#### **users**
Primary user management table supporting multi-role architecture.
```sql
CREATE TABLE users (
    userID INT PRIMARY KEY AUTO_INCREMENT,
    fullName VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'client', 'adiutor'),
    phoneNumber VARCHAR(20),
    profilePic VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    dateCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### **forms**
Service request submissions from clients.
```sql
CREATE TABLE forms (
    formID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    contact_method VARCHAR(100),
    contact_details VARCHAR(100),
    service_type VARCHAR(100),
    project_name VARCHAR(100),
    request_description TEXT,
    deadline DATE,
    expectations TEXT,
    additional_notes TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved') DEFAULT 'pending',
    FOREIGN KEY (userID) REFERENCES users(userID)
);
```

#### **tasks**
Task management and assignment system.
```sql
CREATE TABLE tasks (
    taskID INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    description TEXT,
    assignedBy INT,
    assignedTo INT,
    dueDate DATE,
    status ENUM('pending', 'in progress', 'completed') DEFAULT 'pending',
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    dateAssigned TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    taskType VARCHAR(50),
    formID INT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assignedBy) REFERENCES users(userID),
    FOREIGN KEY (assignedTo) REFERENCES users(userID),
    FOREIGN KEY (formID) REFERENCES forms(formID)
);
```

#### **documents**
File management and document storage.
```sql
CREATE TABLE documents (
    documentID INT PRIMARY KEY AUTO_INCREMENT,
    taskID INT,
    fileName VARCHAR(255),
    filePath VARCHAR(255),
    uploadDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (taskID) REFERENCES tasks(taskID)
);
```

#### **feedbacks**
Quality assurance and feedback system.
```sql
CREATE TABLE feedbacks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    task_id INT,
    giver_id INT,
    receiver_id INT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(taskID),
    FOREIGN KEY (giver_id) REFERENCES users(userID),
    FOREIGN KEY (receiver_id) REFERENCES users(userID)
);
```

### Supporting Tables

#### **form_files**
File attachments for service requests.
```sql
CREATE TABLE form_files (
    id INT PRIMARY KEY AUTO_INCREMENT,
    formid INT,
    filepath VARCHAR(255),
    date_uploaded DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (formid) REFERENCES forms(formID) ON DELETE CASCADE
);
```

#### **clients**
Extended client information and relationship management.
```sql
CREATE TABLE clients (
    clientID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    companyName VARCHAR(100),
    address TEXT,
    notes TEXT,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE
);
```

#### **notes**
Internal notes for client management.
```sql
CREATE TABLE notes (
    noteID INT PRIMARY KEY AUTO_INCREMENT,
    clientID INT,
    addedBy INT,
    content TEXT,
    noteDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (clientID) REFERENCES clients(clientID),
    FOREIGN KEY (addedBy) REFERENCES users(userID)
);
```

#### **password_resets**
Password recovery functionality.
```sql
CREATE TABLE password_resets (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255),
    expires_at DATETIME
);
```

## Core Features

### 1. **User Management System**
- **Multi-role authentication** with role-based access control
- **User registration and login** with secure password hashing
- **Profile management** with customizable user information
- **Account status management** (active/inactive)
- **Password recovery system** with email-based token verification

### 2. **Service Request Management**
- **Request submission form** with comprehensive project details
- **File upload capability** for project materials and references
- **Request status tracking** (pending, approved, rejected)
- **Deadline management** with date-based organization
- **Service type categorization** for better organization

### 3. **Task Management System**
- **Task creation and assignment** by administrators
- **Priority-based task organization** (low, medium, high)
- **Status tracking** (pending, in progress, completed)
- **Due date management** with overdue alerts
- **Task description and requirement management**
- **Calendar view** for visual task organization

### 4. **Document Management**
- **File upload system** for task deliverables
- **Document organization** by task and project
- **Secure file storage** with access control
- **File type validation** and size restrictions
- **Download functionality** for authorized users

### 5. **Communication System**
- **Email notifications** for task assignments and updates
- **Automated messaging** for status changes
- **Contact method flexibility** (email, messenger, phone)
- **Internal messaging system** between users

### 6. **Feedback and Rating System**
- **5-star rating system** for service quality
- **Comment-based feedback** for detailed reviews
- **Feedback aggregation** and average calculation
- **Quality assurance tracking** for service providers

### 7. **Analytics and Reporting**
- **Dashboard metrics** for all user roles
- **Task completion rates** and performance tracking
- **User activity monitoring** and engagement metrics
- **Service request analytics** and trend analysis

### 8. **Client Relationship Management**
- **Client portfolio management** for service providers
- **Client history tracking** and interaction logs
- **Performance metrics** per client relationship
- **Communication history** and contact management

## User Workflows

### Client Workflow
1. **Registration/Login** → Access client dashboard
2. **Service Request** → Fill out detailed request form with attachments
3. **Request Review** → Admin reviews and approves request
4. **Task Assignment** → Admin assigns task to appropriate adiutor
5. **Progress Tracking** → Monitor task status and updates
6. **Task Completion** → Review deliverables and provide feedback
7. **Feedback Submission** → Rate service and provide comments

### Adiutor Workflow
1. **Login** → Access adiutor dashboard with assigned tasks
2. **Task Review** → Review task requirements and client materials
3. **Task Execution** → Work on assigned tasks with status updates
4. **Document Upload** → Submit deliverables and work products
5. **Task Completion** → Mark tasks as completed
6. **Client Management** → Maintain client relationships and history
7. **Performance Review** → Monitor feedback and ratings

### Admin Workflow
1. **System Overview** → Monitor platform metrics and activity
2. **Request Management** → Review and approve service requests
3. **Task Assignment** → Match requests with appropriate adiutors
4. **User Management** → Create, edit, and manage user accounts
5. **Quality Control** → Monitor feedback and service quality
6. **System Administration** → Maintain platform functionality
7. **Analytics Review** → Generate reports and performance insights

## Security Features

### Authentication Security
- **Password hashing** using PHP's `password_hash()` with bcrypt
- **Session management** with secure session handling
- **Role-based access control** preventing unauthorized access
- **CSRF protection** through proper form handling

### Data Security
- **Prepared SQL statements** preventing SQL injection attacks
- **Input validation and sanitization** for all user inputs
- **File upload restrictions** with type and size validation
- **Secure file storage** with controlled access paths

### Access Control
- **Role-based permissions** limiting feature access by user type
- **Session validation** on all protected pages
- **Automatic logout** for inactive sessions
- **Secure password recovery** with time-limited tokens

## Email System

### PHPMailer Integration
- **SMTP configuration** for reliable email delivery
- **Email templates** for consistent messaging
- **Automated notifications** for system events
- **Multi-recipient support** for admin notifications

### Notification Types
- **Task assignment notifications** to adiutors
- **Status update notifications** to clients
- **Completion notifications** to all stakeholders
- **System alerts** to administrators

## File Management

### Upload System
- **Multi-file upload support** for comprehensive submissions
- **File type validation** ensuring appropriate content
- **Unique filename generation** preventing conflicts
- **Organized storage structure** by user and task

### Security Measures
- **Upload directory protection** preventing direct access
- **File size limitations** preventing system overload
- **Virus scanning capabilities** (implementation ready)
- **Access control** based on user roles and ownership

## Performance Optimization

### Database Optimization
- **Indexed foreign keys** for efficient joins
- **Optimized queries** with proper WHERE clauses
- **Connection management** with persistent connections
- **Query result caching** for frequently accessed data

### Frontend Optimization
- **CDN integration** for external libraries
- **Minified assets** for faster loading
- **Responsive design** for mobile compatibility
- **Progressive enhancement** for accessibility

## Development Standards

### Code Organization
- **MVC-inspired structure** with separated concerns
- **Reusable components** for consistent UI elements
- **Modular functionality** with dedicated directories
- **Clean code principles** with proper documentation

### Naming Conventions
- **Descriptive variable names** in camelCase
- **Consistent file naming** with underscores
- **Database naming** with clear table relationships
- **Function naming** indicating purpose and return type

### Error Handling
- **Exception handling** for database operations
- **User-friendly error messages** with system logging
- **Graceful degradation** for missing dependencies
- **Debug modes** for development environments

## Deployment Considerations

### Production Requirements
- **PHP 7.4+** with required extensions
- **MySQL 5.7+** or MariaDB 10.2+
- **Apache/Nginx** with mod_rewrite support
- **SSL certificate** for secure communications

### Configuration Management
- **Environment-specific settings** for database connections
- **Email configuration** for production SMTP servers
- **File permissions** for upload directories
- **Security headers** and HTTPS enforcement

### Backup Strategy
- **Database backups** with automated scheduling
- **File system backups** including uploaded documents
- **Configuration backups** for system settings
- **Recovery procedures** for disaster scenarios

## Future Enhancements

### Planned Features
- **Real-time messaging** system for instant communication
- **Advanced analytics** with detailed reporting dashboards
- **Mobile application** for iOS and Android platforms
- **API integration** for third-party service connections
- **Multi-language support** for international users
- **Advanced file preview** capabilities
- **Workflow automation** with rule-based task assignment
- **Integration capabilities** with external project management tools

### Scalability Improvements
- **Database sharding** for high-volume scenarios
- **Caching layers** for improved performance
- **Load balancing** for distributed deployments
- **Microservices architecture** for component independence

## Conclusion

The Treis Adiutor Content Management System represents a comprehensive solution for professional service management, combining robust task management, client relationship tools, and quality assurance systems. Built with modern web technologies and security best practices, the platform provides a scalable foundation for service-based businesses.

The multi-role architecture ensures that each user type has access to relevant functionality while maintaining system security and data integrity. The comprehensive feature set, from request management to feedback systems, creates a complete ecosystem for professional service delivery.

With its modular design and well-documented codebase, the system is positioned for future enhancements and can adapt to evolving business requirements while maintaining its core functionality and user experience standards.
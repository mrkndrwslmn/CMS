# 🚀 Quick Setup Guide - Treis Adiutor Project Execution and Management System (PEMS)

**Get up and running in 15 minutes or less!**

---

## ⚡ Prerequisites Check

Before starting, ensure you have:
- ✅ **PHP 8.2+** with required extensions
- ✅ **Composer** (latest version)
- ✅ **Node.js 18+** with npm
- ✅ **Git** for version control
- ✅ **Code Editor** (VS Code recommended)

**Quick PHP Extensions Check:**
```bash
php -m | grep -E "(bcmath|ctype|fileinfo|json|mbstring|openssl|pdo|tokenizer|xml)"
```

---

## 🎯 30-Second Installation

### 1. Clone & Navigate
```bash
git clone https://github.com/mrkndrwslmn/CMS.git
cd cms
```

### 2. Install Dependencies
```bash
# PHP dependencies
composer install --optimize-autoloader

# Frontend dependencies  
npm install
```

### 3. Environment Setup
```bash
# Create environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Set basic permissions (Linux/Mac)
chmod -R 755 storage bootstrap/cache
```

### 4. Database Setup
```bash
# Create SQLite database (development)
touch database/database.sqlite

# Run migrations with sample data
php artisan migrate:fresh --seed
```

### 5. Start Development
```bash
# Start everything in one command
composer run dev

# OR start manually:
# php artisan serve &
# npm run dev
```

### 6. Access Your Application
- **Homepage:** http://localhost:8000
- **Admin Panel:** http://localhost:8000/admin/dashboard (login via http://localhost:8000/login)
- **User Login:** http://localhost:8000/login

---

## 🔑 Default Login Credentials

### Admin Access
```
Email: admin@treisadiutor.com
Password: admin123
```

### Test Client
```
Email: john.smith@techstartup.com  
Password: client123
```

### Test Adiutor
```
Email: alex@treisadiutor.com
Password: adiutor123
```

---

## 🎯 Essential Commands

### Development
```bash
# Start complete development environment
composer run dev

# Clear all caches
php artisan optimize:clear

# Fresh database with sample data
php artisan migrate:fresh --seed

# Run background jobs
php artisan queue:work
```

### Asset Management
```bash
# Development with Docker (Hot Reload Enabled)
# Vite dev server runs automatically inside Docker container
# Just access http://localhost:8000 - changes auto-reload!

# For local development (without Docker)
npm run dev

# Production build
npm run build

# Watch for changes (local only)
npm run watch
```

### Database Operations
```bash
# Create migration
php artisan make:migration create_example_table

# Run specific seeder
php artisan db:seed --class=UserSeeder

# Rollback last migration
php artisan migrate:rollback
```

---

## 🏗️ Project Structure Overview

```
cms/
├── 📁 app/
│   ├── Http/Controllers/        # Request handling logic
│   │   ├── Admin/              # Admin-specific controllers
│   │   ├── Client/             # Client-specific controllers
│   │   └── Adiutor/            # Adiutor-specific controllers
│   ├── Models/                 # Database models & relationships
│   ├── Services/               # Business logic services
│   └── Traits/                 # Reusable model traits
├── 📁 database/
│   ├── migrations/             # Database schema changes
│   ├── seeders/                # Sample data generation
│   └── factories/              # Model factories for testing
├── 📁 resources/
│   ├── views/                  # Blade templates
│   │   ├── admin/              # Admin interface views
│   │   ├── client/             # Client dashboard views
│   │   └── adiutor/            # Adiutor workspace views
│   ├── css/                    # Stylesheets (Tailwind CSS)
│   └── js/                     # JavaScript (Alpine.js)
├── 📁 routes/
│   ├── web.php                 # Web routes (200+ routes)
│   └── console.php             # Artisan commands
├── 📁 storage/
│   ├── documentations/         # Complete system documentation
│   ├── app/public/             # File uploads (local development)
│   └── logs/                   # Application logs
└── 📁 public/
    ├── index.php               # Application entry point
    └── build/                  # Compiled assets
```

---

## 🔧 Common Development Tasks

### Adding New Features
```bash
# Create controller
php artisan make:controller Admin/ExampleController

# Create model with migration
php artisan make:model Example -m

# Create form request
php artisan make:request ExampleRequest

# Create notification
php artisan make:notification ExampleNotification
```

### Database Management
```bash
# Add new column to existing table
php artisan make:migration add_column_to_table --table=examples

# Create seeder for test data
php artisan make:seeder ExampleSeeder

# Create model factory
php artisan make:factory ExampleFactory
```

### Frontend Development
```bash
# Watch for file changes (development)
npm run dev

# Build for production
npm run build

# Analyze bundle size
npm run analyze
```

---

## 🐛 Troubleshooting

### Common Issues & Solutions

#### "Vite not recognized" Error
```bash
# Install Vite globally (if needed)
npm install -g vite

# Or use npx
npx vite
```

#### Permission Errors (Linux/Mac)
```bash
# Fix storage permissions
sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

#### Database Connection Issues
```bash
# Check .env database configuration
# For SQLite, ensure file exists:
touch database/database.sqlite

# For MySQL/PostgreSQL, verify credentials in .env
```

#### Cache Issues
```bash
# Clear all Laravel caches
php artisan optimize:clear

# Individual cache clearing
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

#### Asset Compilation Issues
```bash
# Clear node modules and reinstall
rm -rf node_modules package-lock.json
npm install

# Force npm cache clean
npm cache clean --force
```

### Getting Help

#### Check Logs
```bash
# Application logs
tail -f storage/logs/laravel.log

# Web server logs (varies by system)
# Apache: /var/log/apache2/error.log
# Nginx: /var/log/nginx/error.log
```

#### Debug Mode
```bash
# Enable debug mode in .env
APP_DEBUG=true

# View detailed error pages
# Check network tab in browser developer tools
```

---

## 📚 Next Steps

### 1. Explore the System
- Login as different user types to understand workflows
- Submit a test service request as a client
- Process it as an admin
- Work on tasks as an adiutor

### 2. Read Documentation
- Check `/storage/documentations/` for detailed guides
- Review API documentation for integration possibilities
- Understand the database schema in migration files

### 3. Development Setup
- Configure your IDE with Laravel extensions
- Set up debugging tools (Laravel Telescope recommended)
- Install Laravel Tinker for interactive testing

### 4. Optional Integrations
- Set up Cloudflare R2 for file storage (see CLOUDFLARE_R2_SETUP.md)
- Configure Maya payment gateway for testing
- Set up Google Gemini AI for chatbot functionality

---

## 💡 Pro Tips

### Development Efficiency
- Use `composer run dev` for complete development environment
- Keep `php artisan tinker` open for quick model testing
- Use Laravel debugbar for query optimization
- Set up IDE autocomplete with Laravel IDE Helper

### Code Quality
- Follow PSR-12 coding standards
- Write tests for new features
- Use meaningful commit messages
- Document complex business logic

### Performance
- Use eager loading for relationships
- Optimize database queries with indexes
- Cache frequently accessed data
- Monitor application performance regularly

---

## 🤝 Getting Support

### Developer Resources
- **Documentation:** `/storage/documentations/`
- **Code Comments:** Extensive inline documentation
- **Laravel Docs:** https://laravel.com/docs

### Community Support
- **Issues:** Create GitHub issues for bugs
- **Discussions:** Use GitHub discussions for questions
- **Code Review:** Submit pull requests for improvements

### Professional Support
- **Email:** dev@treisadiutor.com
- **Documentation:** Complete technical documentation available
- **Training:** Available for team onboarding

---

**🎉 Congratulations! You're ready to start developing with the Treis Adiutor Project Execution and Management System (PEMS).**

*For detailed feature documentation, check the comprehensive guides in `/storage/documentations/`*
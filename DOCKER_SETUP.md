# Treis Adiutor CMS - Docker Setup

This guide will help you set up the Treis Adiutor CMS using Docker instead of XAMPP for better development and deployment.

## Prerequisites

- Docker Desktop installed and running
- Git (for version control)
- At least 4GB of available RAM

## Quick Start

### 1. Update Your Existing Environment Configuration

I've already updated your `.env` file with the necessary Docker-specific settings:

✅ **Database**: Changed `DB_HOST` from `127.0.0.1` to `mysql` (Docker container name)  
✅ **Database Password**: Set to `secret` for Docker MySQL  
✅ **Redis**: Changed `REDIS_HOST` from `127.0.0.1` to `redis` (Docker container name)  
✅ **App URL**: Updated to `http://localhost:8000` for Docker port  

**All your existing configurations are preserved:**
- Auth0 settings
- Firebase configuration  
- Maya payment gateway
- Supabase settings
- Email configuration
- All API keys and secrets

The changes made to your `.env` file:
```bash
# Modified for Docker
DB_HOST=mysql                    # was: 127.0.0.1
DB_PASSWORD=secret              # was: (empty)
REDIS_HOST=redis                # was: 127.0.0.1
APP_URL=http://localhost:8000   # was: http://localhost
```

### 2. Run the Setup Script

**Make sure Docker Desktop is running first!**

**Windows (PowerShell):**
```cmd
.\docker-setup.bat
```

**Linux/Mac:**
```bash
chmod +x docker-setup.sh
./docker-setup.sh
```

The script will:
- ✅ Check Docker is running
- 🏗️ Build the containers
- 📦 Install dependencies
- 🗄️ Set up the database
- 🎨 Build frontend assets

### 3. Manual Setup (Alternative)

If you prefer manual setup:

```bash
# Build and start containers
docker-compose up -d --build

# Install dependencies
docker-compose exec app composer install

# Generate app key
docker-compose exec app php artisan key:generate

# Run migrations
docker-compose exec app php artisan migrate

# Install and build assets
docker-compose exec app npm install
docker-compose exec app npm run build

# Clear caches
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
```

## Services

Your application will be available at:

- **Laravel App**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080 (root/secret)
- **Redis**: localhost:6379

## Development

### Frontend Development

For frontend development, you can run Vite inside the container:

```bash
# Build assets for production
docker-compose exec app npm run build

# Or watch for changes during development
docker-compose exec app npm run dev
```

## Common Commands

### Laravel Artisan Commands
```bash
# Run any artisan command
docker-compose exec app php artisan [command]

# Examples:
docker-compose exec app php artisan migrate
docker-compose exec app php artisan make:controller UserController
docker-compose exec app php artisan queue:work
```

### Database Operations
```bash
# Access MySQL directly
docker-compose exec mysql mysql -u root -p

# Run specific migration
docker-compose exec app php artisan migrate:refresh --seed
```

### Container Management
```bash
# View logs
docker-compose logs -f app

# Access app container
docker-compose exec app bash

# Restart services
docker-compose restart

# Stop all services
docker-compose down

# Remove volumes (careful - this deletes data!)
docker-compose down -v
```

### Asset Building
```bash
# Install new NPM packages
docker-compose exec app npm install [package-name]

# Build for production
docker-compose exec app npm run build

# Watch for changes (development)
docker-compose exec app npm run dev
```

## Troubleshooting

### Permission Issues
```bash
# Fix permission issues
docker-compose exec app chown -R www-data:www-data /var/www/html
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Database Connection Issues
```bash
# Check if MySQL is running
docker-compose ps

# Restart MySQL
docker-compose restart mysql

# Check MySQL logs
docker-compose logs mysql
```

### Cache Issues
```bash
# Clear all caches
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

### Asset Issues
```bash
# Rebuild assets
docker-compose exec app npm ci
docker-compose exec app npm run build
```

## Production Deployment

The current setup is production-ready. For deployment:

```bash
# Build and start containers
docker-compose up -d --build

# Make sure to set production environment variables in your .env:
# APP_ENV=production
# APP_DEBUG=false
```

## File Structure

```
docker/
├── apache/
│   └── 000-default.conf    # Apache configuration
├── mysql/
│   └── my.cnf             # MySQL configuration
├── php/
│   └── local.ini          # PHP settings
└── supervisor/
    └── supervisord.conf   # Process management

Dockerfile                 # Production-ready container
docker-compose.yml         # All services
```

## Benefits of This Setup

1. **Consistent Environment**: Same setup across all machines
2. **Easy Database Management**: MySQL with phpMyAdmin included
3. **Redis Support**: Built-in caching and session storage
4. **Production Ready**: Optimized container with all necessary extensions
5. **Queue Processing**: Supervisor for background jobs
6. **Asset Building**: Node.js and NPM included for frontend builds

## Next Steps

1. ✅ Your `.env` file has been updated with Docker-specific settings
2. Run the setup script: `.\docker-setup.bat` (Windows) or `./docker-setup.sh` (Linux/Mac)
3. Start developing your application
4. Use the provided commands for daily development tasks

**Important:** All your existing configurations (Auth0, Firebase, Maya, Supabase) are preserved and will work with Docker!

For any issues, check the troubleshooting section or examine the logs using `docker-compose logs -f`.
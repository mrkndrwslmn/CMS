@echo off
REM Docker Setup Script for Treis Adiutor CMS (Windows)
echo 🚀 Setting up Treis Adiutor CMS with Docker...

REM Check if Docker is running
docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Docker is not running. Please start Docker first.
    exit /b 1
)

echo ✅ Docker is running

REM Check if .env exists
if not exist .env (
    echo ❌ No .env file found. Please make sure your .env file exists.
    exit /b 1
)

echo ✅ Using existing .env configuration

REM Build and start containers
echo 🏗️  Building Docker containers...
docker-compose down --remove-orphans
docker-compose build --no-cache

echo 🚀 Starting services...
docker-compose up -d

REM Wait for MySQL to be ready
echo ⏳ Waiting for MySQL to be ready...
:wait_mysql
docker-compose exec mysql mysqladmin ping -h"localhost" --silent >nul 2>&1
if %errorlevel% neq 0 (
    echo MySQL is unavailable - sleeping
    timeout /t 2 /nobreak >nul
    goto wait_mysql
)

echo ✅ MySQL is ready!

REM Install dependencies and set up Laravel
echo 📦 Installing Composer dependencies...
docker-compose exec app composer install

echo 🔑 Generating Laravel app key...
docker-compose exec app php artisan key:generate

echo 🗄️  Running database migrations...
docker-compose exec app php artisan migrate --force

echo 🌱 Seeding database (if seeders exist)...
docker-compose exec app php artisan db:seed --force 2>nul || echo No seeders found, skipping...

echo 🔗 Creating storage link...
docker-compose exec app php artisan storage:link

echo 🧹 Clearing caches...
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

echo 📦 Installing NPM dependencies...
docker-compose exec app npm install

echo 🎨 Building assets...
docker-compose exec app npm run build

echo ✅ Setup complete!
echo.
echo 🌐 Your application is now available at:
echo    - Laravel App: http://localhost:8000
echo    - phpMyAdmin: http://localhost:8080
echo.
echo 📋 Useful commands:
echo    - View logs: docker-compose logs -f
echo    - Stop services: docker-compose down
echo    - Restart services: docker-compose restart
echo    - Enter app container: docker-compose exec app bash
echo    - Run artisan commands: docker-compose exec app php artisan [command]

pause
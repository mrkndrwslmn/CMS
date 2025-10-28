#!/bin/bash

# Docker Setup Script for Treis Adiutor CMS
echo "🚀 Setting up Treis Adiutor CMS with Docker..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker first."
    exit 1
fi

echo "✅ Docker is running"

# Check if .env exists
if [ ! -f .env ]; then
    echo "❌ No .env file found. Please make sure your .env file exists."
    exit 1
fi

echo "✅ Using existing .env configuration"

# Build and start containers
echo "🏗️  Building Docker containers..."
docker-compose down --remove-orphans
docker-compose build --no-cache

echo "🚀 Starting services..."
docker-compose up -d

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
until docker-compose exec mysql mysqladmin ping -h"localhost" --silent; do
    echo "MySQL is unavailable - sleeping"
    sleep 2
done

echo "✅ MySQL is ready!"

# Install dependencies and set up Laravel
echo "📦 Installing Composer dependencies..."
docker-compose exec app composer install

echo "🔑 Generating Laravel app key..."
docker-compose exec app php artisan key:generate

echo "🗄️  Running database migrations..."
docker-compose exec app php artisan migrate --force

echo "🌱 Seeding database (if seeders exist)..."
docker-compose exec app php artisan db:seed --force || echo "No seeders found, skipping..."

echo "🔗 Creating storage link..."
docker-compose exec app php artisan storage:link

echo "🧹 Clearing caches..."
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

echo "📦 Installing NPM dependencies..."
docker-compose exec app npm install

echo "🎨 Building assets..."
docker-compose exec app npm run build

echo "✅ Setup complete!"
echo ""
echo "🌐 Your application is now available at:"
echo "   - Laravel App: http://localhost:8000"
echo "   - phpMyAdmin: http://localhost:8080"
echo ""
echo "📋 Useful commands:"
echo "   - View logs: docker-compose logs -f"
echo "   - Stop services: docker-compose down"
echo "   - Restart services: docker-compose restart"
echo "   - Enter app container: docker-compose exec app bash"
echo "   - Run artisan commands: docker-compose exec app php artisan [command]"
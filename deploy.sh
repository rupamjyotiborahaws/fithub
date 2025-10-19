#!/bin/bash

# FitHub Production Deployment Script
set -e

echo "🚀 Starting FitHub deployment..."

# Check if Docker and Docker Compose are installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Create necessary directories
echo "📁 Creating necessary directories..."
mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache
mkdir -p docker/ssl

# Set proper permissions
echo "🔐 Setting proper permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Generate application key if not exists
if [ ! -f .env ]; then
    echo "📝 Creating environment file..."
    cp .env.production .env
    
    echo "🔑 Generating application key..."
    docker run --rm -v "$(pwd)":/app -w /app php:8.2-cli php artisan key:generate --no-interaction
fi

# Build the Docker image
echo "🔨 Building Docker image..."
docker-compose build --no-cache

# Start the services
echo "🐳 Starting Docker containers..."
docker-compose up -d

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 10

# Run database migrations
echo "🗄️ Running database migrations..."
docker-compose exec -T app php artisan migrate --force

# Create storage link
echo "🔗 Creating storage link..."
docker-compose exec -T app php artisan storage:link

# Clear and cache config
echo "⚡ Optimizing application..."
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache

# Check if all services are running
echo "✅ Checking service status..."
docker-compose ps

echo "🎉 Deployment completed successfully!"
echo ""
echo "📋 Your application is now running at:"
echo "   - Web: http://localhost"
echo "   - MailHog: http://localhost:8025"
echo ""
echo "📚 To view logs, run: docker-compose logs -f"
echo "🛠️ To access the application container: docker-compose exec app sh"
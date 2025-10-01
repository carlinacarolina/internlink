#!/bin/bash

# InternLink Docker Setup Script
# This script sets up the Docker environment for InternLink

set -e

echo "🐳 Setting up InternLink Docker Environment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    print_error "Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null && ! docker compose version &> /dev/null; then
    print_error "Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Determine which compose command to use
if command -v docker-compose &> /dev/null; then
    COMPOSE_CMD="docker-compose"
else
    COMPOSE_CMD="docker compose"
fi

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    print_status "Creating .env file from template..."
    cp docker/env.example .env
    print_warning "Please update the .env file with your configuration before continuing."
    print_warning "Especially update APP_KEY, DB_PASSWORD, and other sensitive values."
    read -p "Press Enter to continue after updating .env file..."
fi

# Generate APP_KEY if not set
if ! grep -q "APP_KEY=base64:" .env; then
    print_status "Generating APP_KEY..."
    docker run --rm -v $(pwd):/app -w /app php:8.2-cli php -r "echo 'APP_KEY=base64:' . base64_encode(random_bytes(32)) . PHP_EOL;" >> .env.tmp
    # Update .env file with generated key
    sed -i '/^APP_KEY=/d' .env
    cat .env.tmp >> .env
    rm .env.tmp
    print_status "APP_KEY generated successfully!"
fi

# Create necessary directories
print_status "Creating necessary directories..."
mkdir -p storage/logs
mkdir -p storage/app/public
mkdir -p bootstrap/cache
mkdir -p docker/postgres/backup

# Set proper permissions
print_status "Setting proper permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Build and start containers
print_status "Building Docker images..."
$COMPOSE_CMD build

print_status "Starting containers..."
$COMPOSE_CMD up -d

# Wait for database to be ready
print_status "Waiting for database to be ready..."
sleep 10

# Run Laravel setup commands
print_status "Running Laravel setup commands..."
$COMPOSE_CMD exec app php artisan config:clear
$COMPOSE_CMD exec app php artisan cache:clear
$COMPOSE_CMD exec app php artisan migrate --force
$COMPOSE_CMD exec app php artisan db:seed --force
$COMPOSE_CMD exec app php artisan storage:link

print_status "✅ Docker environment setup complete!"
print_status "🌐 Application is available at: http://localhost:8000"
print_status "📊 Database is available at: localhost:5433"
print_status "🔴 Redis is available at: localhost:6379"

echo ""
print_status "Useful commands:"
echo "  $COMPOSE_CMD up -d          # Start all services"
echo "  $COMPOSE_CMD down            # Stop all services"
echo "  $COMPOSE_CMD logs -f app     # View application logs"
echo "  $COMPOSE_CMD exec app bash   # Access application container"
echo "  $COMPOSE_CMD exec postgres psql -U internlink -d internlink  # Access database"

#!/bin/bash

# InternLink Permission Fix Script
# This script fixes permission issues in the Docker container

set -e

echo "🔧 Fixing InternLink permissions..."

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

# Check if containers are running
if ! $COMPOSE_CMD ps | grep -q "internlink_app"; then
    print_error "InternLink containers are not running. Please start them first with: $COMPOSE_CMD up -d"
    exit 1
fi

print_status "Fixing storage directory permissions..."
$COMPOSE_CMD exec app chown -R www-data:www-data /var/www/html/storage
$COMPOSE_CMD exec app chmod -R 775 /var/www/html/storage

print_status "Fixing bootstrap cache directory permissions..."
$COMPOSE_CMD exec app chown -R www-data:www-data /var/www/html/bootstrap/cache
$COMPOSE_CMD exec app chmod -R 775 /var/www/html/bootstrap/cache

print_status "Clearing Laravel caches..."
$COMPOSE_CMD exec app php artisan config:clear
$COMPOSE_CMD exec app php artisan cache:clear
$COMPOSE_CMD exec app php artisan view:clear

print_status "✅ Permissions fixed successfully!"
print_status "The application should now work without permission errors."

echo ""
print_status "You can now access the application at: http://localhost:8000"


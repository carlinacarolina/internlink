#!/bin/bash

# InternLink Docker Deployment Script
# This script deploys the InternLink application using Docker

set -e

echo "🚀 Deploying InternLink Application..."

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

# Parse command line arguments
ENVIRONMENT="production"
BACKUP_DB=true
SKIP_TESTS=false

while [[ $# -gt 0 ]]; do
    case $1 in
        --staging|-s)
            ENVIRONMENT="staging"
            shift
            ;;
        --no-backup|-nb)
            BACKUP_DB=false
            shift
            ;;
        --skip-tests|-st)
            SKIP_TESTS=true
            shift
            ;;
        --help|-h)
            echo "Usage: $0 [OPTIONS]"
            echo "Options:"
            echo "  --staging, -s       Deploy to staging environment"
            echo "  --no-backup, -nb    Skip database backup"
            echo "  --skip-tests, -st   Skip running tests"
            echo "  --help, -h          Show this help message"
            exit 0
            ;;
        *)
            print_error "Unknown option: $1"
            exit 1
            ;;
    esac
done

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

# Check if .env file exists
if [ ! -f .env ]; then
    print_error ".env file not found. Please create it from docker/env.example"
    exit 1
fi

# Run tests if not skipped
if [ "$SKIP_TESTS" = false ]; then
    print_status "Running tests..."
    $COMPOSE_CMD exec app php artisan test
    if [ $? -ne 0 ]; then
        print_error "Tests failed. Deployment aborted."
        exit 1
    fi
    print_status "✅ Tests passed!"
fi

# Backup database if requested
if [ "$BACKUP_DB" = true ]; then
    print_status "Creating database backup..."
    BACKUP_FILE="backup_$(date +%Y%m%d_%H%M%S).sql"
    $COMPOSE_CMD exec postgres pg_dump -U internlink internlink > "docker/postgres/backup/$BACKUP_FILE"
    print_status "✅ Database backed up to: docker/postgres/backup/$BACKUP_FILE"
fi

# Build production images
print_status "Building production images..."
$COMPOSE_CMD -f docker-compose.prod.yml build --no-cache

# Stop existing containers
print_status "Stopping existing containers..."
$COMPOSE_CMD -f docker-compose.prod.yml down

# Start new containers
print_status "Starting new containers..."
$COMPOSE_CMD -f docker-compose.prod.yml up -d

# Wait for services to be ready
print_status "Waiting for services to be ready..."
sleep 15

# Run deployment commands
print_status "Running deployment commands..."
$COMPOSE_CMD -f docker-compose.prod.yml exec app php artisan config:cache
$COMPOSE_CMD -f docker-compose.prod.yml exec app php artisan route:cache
$COMPOSE_CMD -f docker-compose.prod.yml exec app php artisan view:cache
$COMPOSE_CMD -f docker-compose.prod.yml exec app php artisan migrate --force

# Clear and warm up cache
print_status "Warming up cache..."
$COMPOSE_CMD -f docker-compose.prod.yml exec app php artisan cache:clear
$COMPOSE_CMD -f docker-compose.prod.yml exec app php artisan config:cache

# Health check
print_status "Performing health check..."
sleep 5
if curl -f http://localhost/health > /dev/null 2>&1; then
    print_status "✅ Health check passed!"
else
    print_warning "⚠️  Health check failed. Please check the application logs."
fi

print_status "🎉 Deployment complete!"
print_status "🌐 Application is available at: http://localhost"
print_status "📊 Database is available at: localhost:5432"
print_status "🔴 Redis is available at: localhost:6379"

echo ""
print_status "Useful commands:"
echo "  $COMPOSE_CMD -f docker-compose.prod.yml logs -f app     # View application logs"
echo "  $COMPOSE_CMD -f docker-compose.prod.yml exec app bash   # Access application container"
echo "  $COMPOSE_CMD -f docker-compose.prod.yml ps              # View running containers"

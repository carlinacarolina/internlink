#!/bin/bash

# InternLink Docker Build Script
# This script builds the Docker images for InternLink

set -e

echo "🔨 Building InternLink Docker Images..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

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
BUILD_TYPE="development"
NO_CACHE=false

while [[ $# -gt 0 ]]; do
    case $1 in
        --production|-p)
            BUILD_TYPE="production"
            shift
            ;;
        --no-cache|-n)
            NO_CACHE=true
            shift
            ;;
        --help|-h)
            echo "Usage: $0 [OPTIONS]"
            echo "Options:"
            echo "  --production, -p    Build for production"
            echo "  --no-cache, -n     Build without cache"
            echo "  --help, -h         Show this help message"
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

# Build arguments
BUILD_ARGS=""
if [ "$NO_CACHE" = true ]; then
    BUILD_ARGS="--no-cache"
fi

if [ "$BUILD_TYPE" = "production" ]; then
    print_status "Building production images..."
    $COMPOSE_CMD -f docker-compose.prod.yml build $BUILD_ARGS
    print_status "✅ Production images built successfully!"
else
    print_status "Building development images..."
    $COMPOSE_CMD build $BUILD_ARGS
    print_status "✅ Development images built successfully!"
fi

print_status "🎉 Build complete!"
print_status "You can now start the containers with:"
if [ "$BUILD_TYPE" = "production" ]; then
    echo "  $COMPOSE_CMD -f docker-compose.prod.yml up -d"
else
    echo "  $COMPOSE_CMD up -d"
fi

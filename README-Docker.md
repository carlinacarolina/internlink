# 🐳 InternLink Docker Setup

This document provides comprehensive instructions for setting up and running InternLink using Docker with PostgreSQL.

## 📋 Prerequisites

- Docker Engine 20.10+
- Docker Compose 2.0+
- Git
- At least 4GB RAM available for containers

## 🚀 Quick Start

### 1. Clone and Setup

```bash
# Clone the repository
git clone <repository-url>
cd internlink

# Run the setup script
./docker-scripts/setup.sh
```

### 2. Access the Application

- **Application**: http://localhost:8000
- **Database**: localhost:5433
- **Redis**: localhost:6379

## 🏗️ Architecture

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Web Server    │    │   PostgreSQL    │    │   Queue Worker  │
│   (PHP/Laravel) │    │   Database      │    │   (PHP)         │
│   Port: 8000    │◄──►│   Port: 5433    │    │   Background    │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │
         ▼                       ▼
┌─────────────────┐    ┌─────────────────┐
│   Node.js       │    │   Redis         │
│   (Vite Build)  │    │   Cache         │
│   Port: 5173    │    │   Port: 6379   │
└─────────────────┘    └─────────────────┘
```

## 📁 File Structure

```
internlink/
├── docker/
│   ├── nginx/
│   │   ├── default.conf      # Production Nginx config
│   │   └── dev.conf          # Development Nginx config
│   ├── php/
│   │   └── Dockerfile        # PHP/Laravel container
│   ├── node/
│   │   └── Dockerfile        # Node.js/Vite container
│   ├── supervisor/
│   │   └── supervisord.conf  # Process management
│   ├── postgres/
│   │   ├── init/
│   │   │   └── 01-init.sql   # Database initialization
│   │   └── backup/           # Database backups
│   └── env.example           # Environment template
├── docker-scripts/
│   ├── setup.sh              # Initial setup
│   ├── build.sh              # Build images
│   └── deploy.sh             # Production deployment
├── docker-compose.yml         # Development environment
├── docker-compose.prod.yml    # Production environment
└── README-Docker.md          # This file
```

## 🔧 Configuration

### Environment Variables

Copy the environment template and customize:

```bash
cp docker/env.example .env
```

Key variables to update:

```env
# Application
APP_NAME="InternLink"
APP_KEY=base64:your-generated-key-here
APP_URL=http://localhost:8000

# Database (PostgreSQL)
DB_DATABASE=internlink
DB_USERNAME=internlink
DB_PASSWORD=your-secure-password

# Redis
REDIS_PASSWORD=your-redis-password

# Ports
APP_PORT=8000
VITE_PORT=5173
```

### Database Configuration

The application uses PostgreSQL with the following default settings:

- **Database**: internlink
- **Username**: internlink
- **Password**: password (change in production)
- **Port**: 5433
- **Extensions**: uuid-ossp, pg_trgm

## 🛠️ Development

### Starting Development Environment

```bash
# Start all services
docker compose up -d
# OR if you have docker-compose installed:
# docker-compose up -d

# View logs
docker compose logs -f app

# Access application container
docker compose exec app bash

# Run Laravel commands
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
```

### Asset Development

For frontend development with hot reload:

```bash
# Start with Node.js container
docker compose --profile development up -d
# OR if you have docker-compose installed:
# docker-compose --profile development up -d

# Access Vite dev server
# Available at: http://localhost:5173
```

### Database Operations

```bash
# Access PostgreSQL
docker compose exec postgres psql -U internlink -d internlink

# Create backup
docker compose exec postgres pg_dump -U internlink internlink > backup.sql

# Restore backup
docker compose exec -T postgres psql -U internlink -d internlink < backup.sql
```

## 🚀 Production Deployment

### Building Production Images

```bash
# Build production images
./docker-scripts/build.sh --production

# Deploy to production
./docker-scripts/deploy.sh
```

### Production Environment

The production setup includes:

- **Optimized PHP settings** with OPcache
- **Security headers** in Nginx
- **Resource limits** for containers
- **Health checks** for all services
- **Multiple queue workers** for scalability
- **Persistent volumes** for data

### Environment-Specific Commands

```bash
# Development
docker compose up -d
# OR: docker-compose up -d

# Production
docker compose -f docker-compose.prod.yml up -d
# OR: docker-compose -f docker-compose.prod.yml up -d

# Staging (if configured)
docker compose -f docker-compose.staging.yml up -d
# OR: docker-compose -f docker-compose.staging.yml up -d
```

## 🔍 Monitoring and Debugging

### Health Checks

All services include health checks:

```bash
# Check container health
docker compose ps
# OR: docker-compose ps

# Application health endpoint
curl http://localhost:8000/health
```

### Logs

```bash
# Application logs
docker compose logs -f app
# OR: docker-compose logs -f app

# Database logs
docker compose logs -f postgres
# OR: docker-compose logs -f postgres

# All services
docker compose logs -f
# OR: docker-compose logs -f
```

### Debugging

```bash
# Access application container
docker compose exec app bash
# OR: docker-compose exec app bash

# Access database
docker compose exec postgres psql -U internlink -d internlink
# OR: docker-compose exec postgres psql -U internlink -d internlink

# Check PHP configuration
docker compose exec app php -m
# OR: docker-compose exec app php -m

# Check Laravel configuration
docker compose exec app php artisan config:show
# OR: docker-compose exec app php artisan config:show
```

## 🛡️ Security

### Production Security Checklist

- [ ] Change default passwords
- [ ] Use strong APP_KEY
- [ ] Enable Redis authentication
- [ ] Configure SSL/TLS
- [ ] Set up firewall rules
- [ ] Regular security updates
- [ ] Database backups
- [ ] Monitor logs

### Security Headers

The Nginx configuration includes:

- X-Frame-Options
- X-XSS-Protection
- X-Content-Type-Options
- Referrer-Policy
- Content-Security-Policy

## 📊 Performance

### Resource Limits

Production containers have resource limits:

- **App Container**: 1GB RAM limit
- **Database**: 1GB RAM limit
- **Redis**: 512MB RAM limit
- **Queue Workers**: 512MB RAM limit each

### Optimization

- **OPcache** enabled for PHP
- **Gzip compression** enabled
- **Static file caching** configured
- **Database connection pooling**
- **Redis caching** for sessions and cache

## 🔄 Backup and Recovery

### Database Backup

```bash
# Manual backup
docker compose exec postgres pg_dump -U internlink internlink > backup_$(date +%Y%m%d_%H%M%S).sql
# OR: docker-compose exec postgres pg_dump -U internlink internlink > backup_$(date +%Y%m%d_%H%M%S).sql

# Automated backup (add to cron)
0 2 * * * docker compose exec postgres pg_dump -U internlink internlink > /backup/internlink_$(date +\%Y\%m\%d).sql
# OR: 0 2 * * * docker-compose exec postgres pg_dump -U internlink internlink > /backup/internlink_$(date +\%Y\%m\%d).sql
```

### Volume Backup

```bash
# Backup volumes
docker run --rm -v internlink_postgres_data:/data -v $(pwd):/backup alpine tar czf /backup/postgres_backup.tar.gz /data
```

## 🆘 Troubleshooting

### Common Issues

**Port conflicts:**
```bash
# Check port usage
netstat -tulpn | grep :8000
# Change ports in .env file
```

**Permission issues:**
```bash
# Fix storage permissions
docker compose exec app chown -R www-data:www-data storage
docker compose exec app chmod -R 755 storage
# OR: docker-compose exec app chown -R www-data:www-data storage
#     docker-compose exec app chmod -R 755 storage
```

**Database connection issues:**
```bash
# Check database status
docker compose exec postgres pg_isready -U internlink
# OR: docker-compose exec postgres pg_isready -U internlink

# Reset database
docker compose down -v
docker compose up -d
# OR: docker-compose down -v && docker-compose up -d
```

**Memory issues:**
```bash
# Check container memory usage
docker stats

# Increase Docker memory limit
# Docker Desktop: Settings > Resources > Memory
```

### Getting Help

1. Check the logs: `docker compose logs -f` (or `docker-compose logs -f`)
2. Verify environment variables: `docker compose exec app env` (or `docker-compose exec app env`)
3. Test database connection: `docker compose exec app php artisan tinker` (or `docker-compose exec app php artisan tinker`)
4. Check Laravel configuration: `docker compose exec app php artisan config:show` (or `docker-compose exec app php artisan config:show`)

## 📚 Additional Resources

- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [Laravel Documentation](https://laravel.com/docs)
- [PostgreSQL Documentation](https://www.postgresql.org/docs/)

## 🤝 Contributing

When contributing to the Docker setup:

1. Test changes in development environment
2. Update documentation
3. Ensure backward compatibility
4. Test production deployment
5. Update version tags appropriately

---

**Note**: This Docker setup is optimized for InternLink's specific requirements including PostgreSQL, Redis caching, queue workers, and PDF generation capabilities.

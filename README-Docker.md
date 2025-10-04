# InternLink Docker Guide

This guide explains how to bootstrap and run the InternLink development environment with Docker. The commands below assume you are working from the repository root.

## Prerequisites
- Docker Engine 20.10 or newer
- Docker Compose plugin 2.0 or newer (or the legacy `docker-compose` binary)
- Git
- At least 4 GB of available RAM for containers
- Access to the Docker daemon (Docker Desktop running or the service started, and your user in the `docker` group)

## One-Time Setup
```bash
# Clone the repository if you haven't already
git clone <repository-url>
cd internlink

# Provision and start all containers
./docker-scripts/linux/setup.sh
```

> If you see a permissions error about `/var/run/docker.sock`, ensure Docker is running and your user can access the daemon (e.g. `sudo usermod -aG docker $USER` then log out/in).

The setup script orchestrates the following steps:
- validates Docker and Docker Compose availability
- creates `.env` from `docker/env.example` if it is missing
- ensures storage-related directories exist locally and adjusts permissions
- builds each service image and starts the stack (app, postgres, redis, node, queue workers)
- prompts you to run `composer install` and `npm ci` inside the app container so host-mounted dependencies are ready
- waits for PostgreSQL to accept connections
- fixes runtime permissions inside the PHP container
- generates a Laravel `APP_KEY` when one is not present
- clears Laravel caches, runs database migrations and seeds, and recreates the storage symlink

After the script completes, the application is reachable at `http://localhost:8000`.

## Service Overview
| Service   | Compose name | Port | Purpose |
|-----------|--------------|------|---------|
| App       | `app`        | 8000 | Laravel application container (Nginx + PHP-FPM via Supervisor) |
| Postgres  | `postgres`   | 5433 | Primary relational database |
| Redis     | `redis`      | 6379 | Cache and queue backend |
| Node/Vite | `node`       | 5173 | Frontend asset builder with hot reload (development profile) |
| Queue     | `queue`      | —    | Runs Laravel queue workers |

_Exposed ports can be changed by editing the relevant entries in `.env`._

## Day-to-Day Commands
```bash
# Start the environment in the background
docker compose up -d

# Start the optional Node development profile
docker compose --profile development up -d node

# Stop the environment
docker compose down

# Follow application logs
docker compose logs -f app

# Run an Artisan command
docker compose exec app php artisan migrate

# Access the application container shell
docker compose exec app bash

# Access PostgreSQL using credentials from .env
DB_USER=$(grep -m1 '^DB_USERNAME=' .env | cut -d= -f2)
DB_PASS=$(grep -m1 '^DB_PASSWORD=' .env | cut -d= -f2)
DB_NAME=$(grep -m1 '^DB_DATABASE=' .env | cut -d= -f2)
PGPASSWORD="$DB_PASS" docker compose exec postgres psql -U "$DB_USER" -d "$DB_NAME"
```

If your system only provides the legacy `docker-compose` binary, substitute `docker-compose` for `docker compose` in the commands above.

## Environment Variables
Copy `docker/env.example` to `.env` when you first clone the project. The setup script will handle this automatically, but you can update values at any time. Key entries to review:

```env
APP_URL=http://localhost:8000
DB_DATABASE=internlink
DB_USERNAME=internlink
DB_PASSWORD=change-me
REDIS_PASSWORD=change-me
APP_PORT=8000
VITE_PORT=5173
```

Changes to `.env` require restarting the affected containers (`docker compose up -d` will recreate them).

## Troubleshooting
- **Port already in use** – adjust `APP_PORT`, `DB_PORT`, or `VITE_PORT` in `.env` and rerun `docker compose up -d`.
- **Permission denied** – rerun `./docker-scripts/linux/setup.sh` or execute `docker compose exec app bash` followed by `chown -R www-data:www-data storage bootstrap/cache`.
- **Database not reachable** – confirm containers are running (`docker compose ps`) and check logs (`docker compose logs postgres`).
- **Node service stops immediately** – start it with the development profile (`docker compose --profile development up -d node`).
- **Redis authentication failures** – ensure `REDIS_PASSWORD` in `.env` matches the password defined in `docker-compose.yml`.

## Production Notes
- Use the production compose definition: `docker compose -f docker-compose.prod.yml up -d`.
- Set strong secrets in `.env` (`APP_KEY`, database, Redis, and queue credentials`).
- Configure SSL/TLS at the edge or by extending the production build to include your reverse proxy.
- Schedule regular database backups with `pg_dump` (see `docker/postgres/backup`).
- Monitor container health with `docker compose ps` and forward logs to your observability stack.

For deeper context on individual services and security considerations, review the files under `docker/` and `agents/security.md`.

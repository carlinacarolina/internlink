# InternLink

InternLink is a web application that helps vocational schools manage every stage of their industrial internship programmes. It covers account provisioning for students and supervisors, school and department data management, internship applications and approvals, placement scheduling, monitoring logs, and document exports. The stack is Laravel 12, Vite with Tailwind CSS, and PostgreSQL (using dedicated schemas and database enums).

## Goals & Scope
- Deliver an end-to-end internship workflow for schools: account registration, student/supervisor profiling, partner institutions, period quotas, application submission and approval, placements, monitoring, and archival.
- Support multi-tenant access by scoping all school resources behind a URL prefix `/{school_code}`.
- Focus on a server-rendered experience (Blade) with Vite-managed assets; no standalone SPA or public API in this release.

Out of scope for the current milestone: SSO, full HRIS, and external company portals. Integrations can be addressed in future iterations.

## Preview
![Dashboard](https://drive.google.com/uc?export=download&id=1PrjVW7URgjm6c3neQIEE0X5EwLnIagss)
> Replace with an actual dashboard capture for your school instance.

## Installation & Setup

### Prerequisites
- PHP 8.2+ with extensions `pgsql`, `intl`, `bcmath`, `mbstring`, `openssl`, `pcntl`
- Composer 2.6+
- PostgreSQL 14+ (with the `citext` extension enabled)
- Node.js 20+ and npm 10+
- Redis (optional - the default queue driver uses the database)

### Quick Start (Local Development)
```bash
# 1) Clone and enter the project directory
git clone <REPLACE_WITH_REPOSITORY_URL> internlink
cd internlink

# 2) Install dependencies
composer install
npm ci || npm install

# 3) Configure the environment
cp .env.example .env
php artisan key:generate

# 4) Prepare PostgreSQL
# Ensure: CREATE EXTENSION IF NOT EXISTS citext;
# Update the DB_* values in .env, then migrate + seed
php artisan migrate --seed

# 5) Start the integrated dev processes
composer run dev
# Or run them individually:
# php artisan serve
# php artisan queue:listen
# php artisan pail
# npm run dev

# Bonus: one-command bootstrap
python setup.py
```

### Sample `.env` (PostgreSQL)
```dotenv
APP_NAME="InternLink"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=en

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=internlink
DB_USERNAME=postgres
DB_PASSWORD=<REPLACE_WITH_PASSWORD>

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@internlink.local"
MAIL_FROM_NAME="${APP_NAME}"

FILESYSTEM_DISK=local
```

> PostgreSQL connections use `search_path` `app,core,public` (see `config/database.php`).

### Optional: Headless PDF Generation
InternLink uses `spatie/browsershot` to render PDFs (for example, printable internship applications). Provide a Chromium binary and update these environment variables if the defaults do not work:
```dotenv
BROWSERSHOT_NODE_BINARY="node"
BROWSERSHOT_NPM_BINARY="npm"
PUPPETEER_EXECUTABLE_PATH=<REPLACE_WITH_CHROMIUM_PATH>
```

## Project Structure
```text
app/Http/Controllers/   # Application, authentication, and CRUD controllers
app/Http/Middleware/    # Custom middleware (session auth, role checks, school scoping)
app/Models/             # Core models: User, Student, Supervisor, Institution, etc.
bootstrap/, config/
database/migrations/    # PostgreSQL schemas (core/app), enums, triggers
public/, resources/     # Blade templates, layouts, forms, PDF views
routes/web.php          # All session-based web routes
tests/                  # PHPUnit test suite
vite.config.js, package.json, composer.json
```

## Architecture & System Design
- Laravel MVC with Blade-driven HTML; Vite handles asset bundling with Tailwind CSS.
- Tenancy is implemented via path prefixes (`/{school}`) and a `school` middleware that scopes all queries.
- Session-based authentication with custom middleware (no Sanctum/Passport).
- PostgreSQL hosts shared (`core`) and domain (`app`) schemas plus enums and triggers to enforce business rules.

### Architecture Diagram
```mermaid
flowchart LR
  U[User: developer/admin/supervisor/student]
  U -- HTTPS --> W[Web server (Nginx/Apache)] --> L[Laravel 12]
  L -- Blade --> V[Vite + Tailwind]
  L -- ORM --> P[(PostgreSQL: app/core schemas)]
  L -- Queue --> Q[(Database queue / Redis)]
  L -- Mail --> M[Mailer]
```

## Domain Overview
- **Schools**: tenants, departments/majors, period quotas, settings, staff assignments.
- **Students**: profiles, eligibility, internship history, required documents.
- **Supervisors**: school mentors, company mentors, access to monitoring forms.
- **Institutions**: partner companies, contacts, quota per period, internship slots.
- **Applications**: submission, approval workflow, PDF exports.
- **Internships**: placement records, schedules, evaluation outcomes.
- **Monitoring**: daily/weekly feedback, progress notes, attendance.
- **Meta Data**: lookup values (monitoring categories, supervisor roles) shared within a school context.
- **Developer Area**: global administration for onboarding new schools and managing system-wide settings.

### Core Entities
- `User`: authentication, password hashing via the `hashed` attribute cast.
- `Student`, `Supervisor`, `Institution`: domain objects tied to schools.
- `Application`: requested internship with status transitions.
- `Internship`: confirmed placement referencing applications and institutions.
- `MonitoringEntry`: logs created by supervisors or students.

### Request Lifecycle
1. User reaches `{school}` prefixed routes.
2. `auth.session` middleware ensures a valid session.
3. `school` middleware scopes models by the provided school code.
4. Controllers validate requests, use form requests or inline validation, then delegate to services/models.
5. Database triggers and enums enforce consistency before commit.

### Authentication & Authorization
- Roles: developer, school admin, supervisor, student.
- Gate/Policy checks on controllers; school admins manage departmental data, supervisors manage assigned students, students access their own records.
- Passwords are automatically hashed through the model cast declaration.

### Data Integrity & Validation
- Input validation handled via `$request->validate([...])` or form requests.
- PostgreSQL constraints, triggers, and enums guard referential and business consistency.
- File uploads (photos, documents) are stored in `storage/app` and served through controller endpoints.

```php
// Example controller validation snippet
$validated = $request->validate([
    'email' => ['required', 'email'],
    'password' => ['required', 'min:8'],
]);
```

### Environment Management
- Never commit `.env` or secrets.
- Confirm `DB_CONNECTION=pgsql` and enable `citext` in PostgreSQL.
- Review `APP_ENV`, `APP_DEBUG`, and all credentials before deploying.

### Privacy Notice (Placeholder)
Link a privacy notice (for example `<REPLACE_WITH_PRIVACY_POLICY_URL>`) that explains purpose, lawful basis, retention period, and data subject rights before launching in production.

## Usage
Key routes (see `routes/web.php`):
- Auth: `/login`, `/signup`, `/logout`
- School dashboard: `/{school}`
- School settings: `/{school}/settings/*`
- Students: `/{school}/students/*`
- Supervisors: `/{school}/supervisors/*`
- Institutions: `/{school}/institutions/*`
- Applications: `/{school}/applications/*` (PDF export via `/{id}/pdf`)
- Internships: `/{school}/internships/*`
- Monitoring: `/{school}/monitorings/*`
- Metadata helpers: `/{school}/meta/*`
- Developer area: `/developers/*`, `/schools/*`

## Contribution Guide
- Code style: PSR-12; run `laravel/pint` before opening a pull request.
- Commit messages: Conventional Commits (`feat:`, `fix:`, `refactor:`, `test:`, etc.).
- Typical workflow:
  1. Create migrations (use `DB::statement` for enums/triggers when necessary).
  2. Implement models, controllers, middleware/policies as needed.
  3. Build Blade views and validation logic.
  4. Add tests with PHPUnit and run `composer test` / `php artisan test`.
  5. Update documentation and changelog entries.

Snippet of nested routes:
```php
Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth.session')->group(function () {
    Route::prefix('{school}')->middleware('school')->group(function () {
        Route::prefix('applications')->group(function () {
            Route::post('/', [ApplicationController::class, 'store']);
            Route::get('{id}/pdf', [ApplicationController::class, 'pdf']);
        });
    });
});
```

## Testing & Deployment

### Testing
- Run the backend test suite:
  ```bash
  php artisan test
  # or
  composer test
  ```

### Deployment (Summary)
- Build assets: `npm run build`
- Cache configuration: `php artisan config:cache && php artisan route:cache && php artisan view:cache`
- Run database migrations: `php artisan migrate --force`
- Queue workers: `php artisan queue:work` (supervised by Systemd/Supervisor in production)
- Cron: execute `php artisan schedule:run` every minute

### GitHub Actions Example
```yaml
name: ci
on: [push]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - run: composer install --no-interaction --prefer-dist
      - run: cp .env.example .env && php artisan key:generate
      - run: php artisan test
```

## Changelog & Versioning
- Follow Semantic Versioning `MAJOR.MINOR.PATCH`.
- Log releases in `CHANGELOG.md`. Example:
  ```
  ## [1.0.0] - <REPLACE_WITH_RELEASE_DATE>
  ### Added
  - Student and supervisor registration flow with session-based authentication
  - School, department, student, supervisor, institution, and quota management
  - Internship application submission, approval, placement, and monitoring
  - Printable internship application PDFs
  ```

## License & Credits
- License: GNU GPLv3 (see `LICENSE`)
- Copyright: `Cerbera Foundation`
- Contact: `artrialazz@gmail.com`
- Early contributors: `Dendra`

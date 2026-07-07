# Module 01: Project Initialization

## Overview

Module 01 establishes the Cyra-Tech Enterprise Platform foundation using **Laravel 12**, **JavaScript**, **Tailwind CSS 4**, and **MySQL**.

## Core Stack (Strict)

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12 |
| Frontend | Blade templates + vanilla JavaScript |
| Styling | Tailwind CSS 4 |
| Database | MySQL |

**React is not used** in this project.

## Deliverables

### UI Implementation

- Blade initialization dashboard at `/`
- Reusable Blade UI components in `resources/views/components/ui/`:
  - `button`
  - `card`
  - `status-badge`
  - `metric-card`
- Base layout in `resources/views/layouts/app.blade.php`
- Cyra-Tech dark theme tokens in `resources/css/app.css`
- Vanilla JavaScript page module in `resources/js/pages/initialization.js`

### Backend Implementation

- Enterprise layering:
  - `app/Services/PlatformService.php`
  - `app/Repositories/PlatformModuleRepository.php`
  - `app/Contracts/RepositoryInterface.php`
  - `app/Enums/ModuleStatus.php`
- Web controller: `app/Http/Controllers/Web/InitializationController.php`
- API controllers under `app/Http/Controllers/Api/`
- Platform config: `config/cyra.php`

### API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/health` | Service health and dependency checks |
| GET | `/api/v1/platform/status` | Platform metadata and progress |
| GET | `/api/v1/platform/modules` | Full 25-module roadmap |

### Database Changes

- Migration: `2026_07_07_000001_create_platform_modules_table.php`
- Model: `App\Models\PlatformModule`
- Seeder: `Database\Seeders\PlatformModuleSeeder`

### Tests

- `tests/Feature/HealthCheckTest.php`
- `tests/Feature/PlatformApiTest.php`
- `tests/Feature/InitializationPageTest.php`

## Setup

1. Copy environment file:

```bash
cp .env.example .env
php artisan key:generate
```

2. Create the MySQL database:

```sql
CREATE DATABASE cyra_tech CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

3. Update `.env` database credentials if needed.

4. Install dependencies and migrate:

```bash
composer install
npm install
php artisan migrate --seed
npm run build
```

5. Start development:

```bash
composer dev
```

Or separately:

```bash
php artisan serve
npm run dev
```

## Architecture Notes

- **SOLID/DRY**: Repository and service layers are in place for all future modules.
- **API-first**: Versioned routes under `/api/v1`.
- **Module tracking**: `platform_modules` table and `config/cyra.php` define the 25-module roadmap.
- **No React**: All UI is Blade + JavaScript only.
- **No breaking changes**: Default Laravel health route `/up` remains available.

## Verification Checklist

- [ ] `/` renders the initialization dashboard
- [ ] `/api/v1/health` returns HTTP 200
- [ ] `/api/v1/platform/status` reports 1/25 modules complete
- [ ] `/api/v1/platform/modules` returns 25 modules
- [ ] `php artisan test` passes
- [ ] `npm run build` succeeds

## Git Commit Suggestion

```text
refactor(module-01): use Laravel, JavaScript, Tailwind CSS 4, and MySQL stack

Remove React/Inertia and rebuild initialization UI with Blade templates,
vanilla JavaScript, platform health APIs, module tracking, and tests.
```

## Next Module

Proceed with **Module 02: Authentication & RBAC** when ready.

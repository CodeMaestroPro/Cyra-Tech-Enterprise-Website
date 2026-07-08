# Module 25 — Testing & Optimization

## Overview

Module 25 delivers the Cyra-Tech Testing & Optimization command center at `/admin/optimization`, completing the 25-module enterprise platform roadmap. The workspace aggregates platform health checks, module QA coverage, performance indicators, SEO readiness validation, optimization recommendations, and safe maintenance actions.

## Stack

- **Blade** QA dashboard with health, performance, and SEO panels
- **TestingOptimizationService** with live checks and Artisan optimization actions
- **PlatformService integration** for health and module completion metrics
- **Laravel 12** service architecture (no additional database tables)

## Routes

| Route | Name | Auth | Description |
|-------|------|------|-------------|
| `/admin/optimization` | `admin.optimization.index` | Admin + `optimization.view` | QA and optimization dashboard |
| `/admin/optimization/actions` | `admin.optimization.actions` | Admin + `optimization.manage` | Run optimization action |

## RBAC

| Permission | Description |
|------------|-------------|
| `optimization.view` | View testing and optimization dashboard |
| `optimization.manage` | Run optimization maintenance actions |

Admin role receives all optimization permissions. Manager role receives `optimization.view` only.

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/optimization/dashboard` | Admin + `optimization.view` | Full QA and optimization payload |
| POST | `/api/v1/optimization/actions` | Admin + `optimization.manage` | Execute optimization action |

## Dashboard Panels

- **Summary KPIs** — Health score, modules complete (25/25), feature tests, SEO score
- **Platform Health Checks** — Application, database, storage, cache, assets
- **Performance Checks** — Config, route, view cache, compiled assets, API health
- **Module QA Matrix** — All 25 modules mapped to feature test coverage
- **SEO Readiness Checklist** — CMS pages, legal pages, core public routes
- **Optimization Recommendations** — Prioritized launch-readiness actions
- **Optimization Actions** — Safe Artisan commands (clear cache, cache config/routes/views, optimize)

## Configuration

Content is driven by `config/cyra.php` under the `testing_optimization` key:

- `health_checks` — Infrastructure readiness signals
- `performance_checks` — Production optimization indicators
- `seo_checks` — Public content and route validation
- `actions` — Allowed Artisan optimization commands
- `recommendations` — Default launch guidance
- `test_suites` — Module-to-test-file pattern mapping

## Navigation

- Admin sidebar **Reports** (Operations) → `/admin/optimization`
- Admin sidebar **Testing & Optimization** (System) → `/admin/optimization`
- Command Center quick action **QA & Optimization** added

## Platform Completion

Module 25 marks **25/25 modules complete** on the Cyra-Tech Enterprise Platform roadmap.

## Tests

- `tests/Feature/OptimizationAdminTest.php` — dashboard access and optimization actions
- `tests/Feature/OptimizationApiTest.php` — authenticated QA API and action validation

## Verification

```bash
php artisan test
npm run build
```

Sign in as `admin@cyratech.com` / `password` and visit `/admin/optimization`.

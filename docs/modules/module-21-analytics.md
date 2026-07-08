# Module 21 — Analytics

## Overview

Module 21 delivers Cyra Pulse Analytics with an admin executive dashboard, event ingestion API, seeded traffic data, and RBAC-protected reporting across page views, module engagement, lead conversion, and platform activity.

## Stack

- **Blade** admin analytics dashboard with Tailwind CSS bar visualizations
- **Event-driven metrics** stored in `analytics_events`
- **Laravel 12** service/repository architecture
- **Config-driven seed patterns** for demo reporting data

## Routes

| Route | Name | Auth | Description |
|-------|------|------|-------------|
| `/admin/analytics` | `admin.analytics.index` | Admin + `analytics.view` | Executive analytics dashboard |

## RBAC

| Slug | Purpose |
|------|---------|
| `analytics.view` | View analytics dashboard and admin API |

Role assignments:

- **admin** — analytics.view
- **manager** — analytics.view
- **super-admin** — all permissions via wildcard

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/analytics/dashboard` | Admin + `analytics.view` | Dashboard metrics payload |
| POST | `/api/v1/analytics/events` | No (throttled) | Record analytics events |

### Supported Event Types

- `page_view`
- `module_view`
- `form_submit`
- `portal_login`

## Database

### `analytics_events`

Stores event type, source, subject, optional metadata, session hash, user reference, and occurred timestamp for aggregation.

## Dashboard Panels

- Overview KPIs: page views, sessions, form submissions, conversion rate
- Traffic trend chart for selected range (7, 14, or 30 days)
- Top pages and top modules
- Lead source breakdown
- Platform snapshot (CMS pages, media assets, inquiries, users)
- Executive insight highlights

## Navigation Updates

- Admin sidebar **Analytics** item enabled → `/admin/analytics`

## Tests

- `tests/Feature/AnalyticsAdminTest.php` — admin access and RBAC
- `tests/Feature/AnalyticsApiTest.php` — dashboard API and event ingestion

## Verification

```bash
php artisan migrate
php artisan db:seed --class=AnalyticsSeeder
php artisan test
npm run build
```

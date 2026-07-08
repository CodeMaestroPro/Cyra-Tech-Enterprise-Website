# Module 22 — Dashboard (Command Center)

## Overview

Module 22 delivers the Cyra-Tech Command Center executive dashboard at `/admin`, replacing the Module 02 RBAC stub with a full operational brief aligned to the approved admin mockup. The dashboard aggregates KPIs, AI executive briefings, company pulse metrics, website analytics, project progress, quick actions, tasks, system status, and recent activities.

## Stack

- **Blade** Command Center layout with Tailwind CSS 4 visualizations
- **DashboardService** merging config-driven content with live analytics and engagement data
- **AnalyticsService integration** for traffic trends and conversion metrics
- **Laravel 12** service architecture

## Routes

| Route | Name | Auth | Description |
|-------|------|------|-------------|
| `/admin` | `admin.dashboard` | Admin + `dashboard.access` | Command Center executive dashboard |

## RBAC

Uses existing `dashboard.access` permission assigned to admin, manager, editor, and viewer roles.

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/dashboard` | Admin + `dashboard.access` | Full Command Center payload |

## Dashboard Panels

- **KPI Cards** — Company health, projects running, revenue growth, new leads, community reach
- **AI Executive Brief** — Daily summary with link to analytics report
- **Company Pulse** — Overall score with six health metrics
- **Website Analytics Overview** — Visitors, page views, bounce rate, session duration, traffic chart
- **Projects Overview** — Live client engagements or configured fallback projects
- **Quick Actions** — Links to CMS, media library, careers, analytics, and portfolio
- **Upcoming Events** — Scheduled meetings and launches
- **My Tasks** — Operational task checklist
- **System Status** — Website, API, database, and server health
- **Recent Activities** — Chronological activity feed

## Configuration

Command Center content is driven by `config/cyra.php` under the `command_center` key, with live overrides for:

- Active client engagement counts (Projects Running KPI)
- Contact and form submission counts (New Leads KPI)
- Analytics traffic data (Website Analytics panel)
- Database connectivity (System Status)

## Navigation & Layout Updates

- Admin header enhanced with search field and datetime display
- Admin sidebar footer **View Website** link added
- **Brief** sidebar item continues to route to `/admin`

## Tests

- `tests/Feature/AdminDashboardTest.php` — dashboard access and panel rendering
- `tests/Feature/DashboardApiTest.php` — authenticated Command Center API

## Verification

```bash
php artisan test
npm run build
```

Sign in as `admin@cyratech.com` / `password` and visit `/admin`.

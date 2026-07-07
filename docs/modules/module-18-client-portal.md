# Module 18 — Client Portal

## Overview

Module 18 delivers the Cyra-Tech Client Portal with a public landing page, authenticated client dashboard, engagement detail views, RBAC-scoped access, and API endpoints. Navigation links previously pointing to login now route to the portal landing page, while client users sign in and are redirected to their workspace.

## Stack

- **Blade** portal layout with sidebar navigation
- **Session authentication** with role-based redirects (Module 02 RBAC extended)
- **Tailwind CSS 4** aligned with the Cyra-Tech design system
- **Laravel 12** service/repository architecture

## Routes

| Route | Name | Auth | Description |
|-------|------|------|-------------|
| `/client-portal` | `client-portal` | No | Public portal landing page |
| `/client-portal/dashboard` | `client-portal.dashboard` | Client | Account dashboard with engagements |
| `/client-portal/engagements/{slug}` | `client-portal.engagements.show` | Client | Engagement detail page |

## Client Accounts & Engagements

Two client accounts seeded by default:

| Account | Industry | Engagements |
|---------|----------|-------------|
| `novabank` | Financial Services | Digital Core Modernization, Fraud Analytics Copilot, Regulatory Reporting Automation |
| `helix-health` | Healthcare | Connected Care Platform, Clinical Data Governance, Telehealth Scale Program |

## RBAC

New role and permissions:

| Slug | Purpose |
|------|---------|
| `client` | Client portal user role |
| `client-portal.access` | Access authenticated portal routes |
| `client-portal.view` | View assigned engagement data |

### Default Client User (Seeded)

- Email: `client@novabank.com`
- Password: `password`
- Role: `client`
- Account: `novabank`

Configure via `.env`:

```env
CYRA_CLIENT_NAME="Sarah Mitchell"
CYRA_CLIENT_EMAIL=client@novabank.com
CYRA_CLIENT_PASSWORD=password
```

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/client-portal` | No | Portal landing configuration |
| GET | `/api/v1/client-portal/dashboard` | Client | Dashboard payload for current user |
| GET | `/api/v1/client-portal/engagements/{slug}` | Client | Single engagement detail |

## Database

### `client_accounts`

Organization records scoped to portal users.

### `client_engagements`

Engagement programs linked to client accounts with milestones, deliverables, and team JSON fields.

### `users.client_account_id`

Links client users to their organization for data isolation.

## Navigation Updates

- Header and footer **Client Portal** links → `/client-portal`
- Homepage hero secondary CTA → `/client-portal`
- Admin **Executive → Client Portal** links to public landing

## Key Files

```
app/Services/ClientPortalService.php
app/Repositories/ClientAccountRepository.php
app/Repositories/ClientEngagementRepository.php
app/Models/ClientAccount.php
app/Models/ClientEngagement.php
app/Http/Controllers/Web/ClientPortalController.php
app/Http/Controllers/Api/ClientPortalController.php
resources/views/client-portal/
resources/views/layouts/portal.blade.php
resources/views/components/client-portal/
config/cyra.php (client_portal section, client role, client_user)
database/seeders/ClientPortalSeeder.php
database/seeders/ClientUserSeeder.php
```

## Verification

```bash
php artisan migrate --seed
php artisan test
npm run build
```

Visit `/client-portal`, sign in as `client@novabank.com`, and review NovaBank engagements on the dashboard.

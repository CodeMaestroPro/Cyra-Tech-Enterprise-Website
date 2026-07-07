# Module 17 — Partner Hub

## Overview

Module 17 delivers the Cyra-Tech Partner Hub with program filtering, individual partnership detail pages, ecosystem overview, and API access. Footer and admin navigation links previously pointing to Contact are updated to the full Partner Hub experience.

## Stack

- **Blade** components for program cards and detail pages
- **Vanilla JavaScript** for category filter tabs
- **Tailwind CSS 4** aligned with the Cyra-Tech design system
- **Laravel 12** service/repository architecture

## Routes

| Route | Name | Description |
|-------|------|-------------|
| `/partner-hub` | `partner-hub` | Partner programs catalog with category filters |
| `/partner-hub/{slug}` | `partner-hub.show` | Individual partnership program detail page |

## Partner Programs

Six programs seeded by default:

| Slug | Category | Title |
|------|----------|-------|
| `cloud-alliance-program` | cloud | Cloud Alliance Program |
| `technology-isv-partners` | technology | Technology ISV Partners |
| `system-integrator-network` | services | System Integrator Network |
| `consulting-co-sell-program` | services | Consulting Co-Sell Program |
| `channel-reseller-program` | channel | Channel & Reseller Program |
| `global-delivery-partners` | global | Global Delivery Partners |

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/partner-hub` | No | Full partner hub catalog payload |
| GET | `/api/v1/partner-hub/{slug}` | No | Single program detail |

## Database

### `partner_programs`

| Column | Purpose |
|--------|---------|
| `slug` | Unique program identifier |
| `category` | Filter category slug |
| `title` | Program title |
| `partner_type` | Partnership type label |
| `region` | Geographic coverage |
| `engagement_model` | Engagement model label |
| `tagline` | Short positioning line |
| `summary` | Card description |
| `description` | Full program overview |
| `benefits` | JSON list of partner benefits |
| `requirements` | JSON list of partner requirements |
| `enablement` | JSON list of enablement resources |
| `badge` | Program badge label |
| `icon` | Icon key for UI component |

Seeded from `config/cyra.php` via `PartnerHubSeeder`.

## Navigation Updates

- Footer **Partner Hub** links to `/partner-hub`
- Admin Command Center **Growth → Partners** links to `/partner-hub`
- Program CTAs link to `/contact?inquiry=partnership`

## Key Files

```
app/Services/PartnerHubService.php
app/Repositories/PartnerProgramRepository.php
app/Models/PartnerProgram.php
app/Http/Controllers/Web/PartnerHubController.php
app/Http/Controllers/Api/PartnerHubController.php
resources/views/partner-hub/
resources/views/components/partner-hub/
resources/js/pages/partner-hub.js
config/cyra.php (partner_hub section)
database/seeders/PartnerHubSeeder.php
```

## Verification

```bash
php artisan test
npm run build
```

Visit `/partner-hub` to browse partnership programs and filter by category.

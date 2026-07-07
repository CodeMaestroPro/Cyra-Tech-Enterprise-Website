# Module 10 — Industries

## Overview

Module 10 delivers the Cyra-Tech Industries catalog with category filtering, individual vertical detail pages, sector expertise overview, and API access. The Module 04 preview at `/industries` is replaced with full industry content.

## Stack

- **Blade** components for vertical cards and detail pages
- **Vanilla JavaScript** for category filter tabs
- **Tailwind CSS 4** aligned with the Cyra-Tech design system
- **Laravel 12** service/repository architecture

## Routes

| Route | Name | Description |
|-------|------|-------------|
| `/industries` | `industries` | Industry catalog with category filters |
| `/industries/{slug}` | `industries.show` | Individual vertical detail page |

## Industry Verticals

Six verticals seeded by default:

| Slug | Category | Title |
|------|----------|-------|
| `financial-services` | regulated | Financial Services |
| `healthcare` | regulated | Healthcare |
| `government` | public-sector | Government |
| `energy-utilities` | industrial | Energy & Utilities |
| `retail-commerce` | commercial | Retail & Commerce |
| `technology` | commercial | Technology |

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/industries` | No | Full industries catalog payload |
| GET | `/api/v1/industries/{slug}` | No | Single vertical detail |

## Database

### `industry_verticals`

| Column | Purpose |
|--------|---------|
| `slug` | Unique vertical identifier |
| `category` | Filter category slug |
| `title` | Industry name |
| `tagline` | Short positioning line |
| `summary` | Card description |
| `description` | Full industry overview |
| `challenges` | JSON list of sector challenges |
| `capabilities` | JSON list of Cyra capabilities |
| `compliance` | JSON list of compliance frameworks |
| `icon` | Icon key for UI component |

Seeded from `config/cyra.php` via `IndustrySeeder`.

## Navigation Updates

- Admin Command Center **Industries** item links to `/industries`

## Key Files

```
app/Services/IndustryService.php
app/Repositories/IndustryVerticalRepository.php
app/Models/IndustryVertical.php
app/Http/Controllers/Web/IndustriesController.php
app/Http/Controllers/Api/IndustriesController.php
resources/views/industries/
resources/views/components/industries/
resources/js/pages/industries.js
config/cyra.php (industries section)
database/seeders/IndustrySeeder.php
```

## Verification

```bash
php artisan test
npm run build
```

Visit `/industries` to browse verticals and filter by category.

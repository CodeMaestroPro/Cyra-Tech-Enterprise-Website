# Module 08 — Solutions

## Overview

Module 08 delivers the Cyra-Tech Solutions catalog with category filtering, individual offering detail pages, delivery process overview, and API access. The Module 04 preview at `/solutions` is replaced with full solution content.

## Stack

- **Blade** components for offering cards and detail pages
- **Vanilla JavaScript** for category filter tabs
- **Tailwind CSS 4** aligned with the Cyra-Tech design system
- **Laravel 12** service/repository architecture

## Routes

| Route | Name | Description |
|-------|------|-------------|
| `/solutions` | `solutions` | Solutions catalog with category filters |
| `/solutions/{slug}` | `solutions.show` | Individual offering detail page |

## Solution Offerings

Seven offerings seeded by default:

| Slug | Category | Title |
|------|----------|-------|
| `digital-transformation` | transformation | Digital Transformation |
| `cloud-infrastructure` | platform | Cloud & Infrastructure |
| `ai-intelligence` | intelligence | AI & Intelligence |
| `cybersecurity` | security | Cybersecurity |
| `data-analytics` | intelligence | Data & Analytics |
| `enterprise-integration` | platform | Enterprise Integration |
| `managed-services` | operations | Managed Services |

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/solutions` | No | Full solutions catalog payload |
| GET | `/api/v1/solutions/{slug}` | No | Single offering detail |

## Database

### `solution_offerings`

| Column | Purpose |
|--------|---------|
| `slug` | Unique offering identifier |
| `category` | Filter category slug |
| `title` | Offering name |
| `summary` | Short description |
| `description` | Full overview copy |
| `capabilities` | JSON list of capabilities |
| `outcomes` | JSON list of expected outcomes |
| `icon` | Icon key for UI component |

Seeded from `config/cyra.php` via `SolutionSeeder`.

## Navigation Updates

- Admin Command Center **Services** item links to `/solutions`
- Footer and header links continue to use the `solutions` route

## Key Files

```
app/Services/SolutionService.php
app/Repositories/SolutionOfferingRepository.php
app/Models/SolutionOffering.php
app/Http/Controllers/Web/SolutionsController.php
app/Http/Controllers/Api/SolutionsController.php
resources/views/solutions/
resources/views/components/solutions/
resources/js/pages/solutions.js
config/cyra.php (solutions section)
database/seeders/SolutionSeeder.php
```

## Verification

```bash
php artisan test
npm run build
```

Visit `/solutions` to browse offerings and filter by category.

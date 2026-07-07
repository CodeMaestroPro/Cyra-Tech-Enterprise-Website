# Module 11 — Portfolio

## Overview

Module 11 delivers the Cyra-Tech Portfolio with case study filtering, individual project detail pages, delivery impact overview, and API access. The Module 04 preview at `/portfolio` is replaced with full portfolio content.

## Stack

- **Blade** components for project cards and detail pages
- **Vanilla JavaScript** for category filter tabs
- **Tailwind CSS 4** aligned with the Cyra-Tech design system
- **Laravel 12** service/repository architecture

## Routes

| Route | Name | Description |
|-------|------|-------------|
| `/portfolio` | `portfolio` | Case study catalog with category filters |
| `/portfolio/{slug}` | `portfolio.show` | Individual project detail page |

## Portfolio Projects

Six projects seeded by default:

| Slug | Category | Title |
|------|----------|-------|
| `novabank-digital-core` | financial | NovaBank Digital Core |
| `helix-health-network` | healthcare | Helix Health Network |
| `astra-logistics-command` | commercial | Astra Logistics Command |
| `civic-one-portal` | public-sector | CivicOne Digital Services |
| `gridwise-energy-platform` | industrial | GridWise Operations Platform |
| `velocity-retail-commerce` | commercial | Velocity Retail Commerce |

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/portfolio` | No | Full portfolio catalog payload |
| GET | `/api/v1/portfolio/{slug}` | No | Single project detail |

## Database

### `portfolio_projects`

| Column | Purpose |
|--------|---------|
| `slug` | Unique project identifier |
| `category` | Filter category slug |
| `title` | Project name |
| `client_name` | Client organization |
| `tagline` | Short positioning line |
| `summary` | Card description |
| `description` | Full program overview |
| `services` | JSON list of services delivered |
| `outcomes` | JSON list of program outcomes |
| `metrics` | JSON list of impact metrics |
| `duration` | Program duration label |
| `icon` | Icon key for UI component |

Seeded from `config/cyra.php` via `PortfolioSeeder`.

## Navigation Updates

- Admin Command Center **Portfolio** and **Case Studies** items link to `/portfolio`

## Key Files

```
app/Services/PortfolioService.php
app/Repositories/PortfolioProjectRepository.php
app/Models/PortfolioProject.php
app/Http/Controllers/Web/PortfolioController.php
app/Http/Controllers/Api/PortfolioController.php
resources/views/portfolio/
resources/views/components/portfolio/
resources/js/pages/portfolio.js
config/cyra.php (portfolio section)
database/seeders/PortfolioSeeder.php
```

## Verification

```bash
php artisan test
npm run build
```

Visit `/portfolio` to browse case studies and filter by category.

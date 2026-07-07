# Module 12 — Innovation Lab

## Overview

Module 12 delivers the Cyra-Tech Innovation Lab with program filtering, individual initiative detail pages, lab methodology overview, and API access. The Module 04 preview at `/innovation-lab` is replaced with full Innovation Lab content.

## Stack

- **Blade** components for initiative cards and detail pages
- **Vanilla JavaScript** for category filter tabs
- **Tailwind CSS 4** aligned with the Cyra-Tech design system
- **Laravel 12** service/repository architecture

## Routes

| Route | Name | Description |
|-------|------|-------------|
| `/innovation-lab` | `innovation-lab` | Lab program catalog with category filters |
| `/innovation-lab/{slug}` | `innovation-lab.show` | Individual initiative detail page |

## Lab Initiatives

Six initiatives seeded by default:

| Slug | Category | Title |
|------|----------|-------|
| `ai-copilot-studio` | ai-automation | AI Copilot Studio |
| `intelligent-automation-lab` | ai-automation | Intelligent Automation Lab |
| `emerging-tech-poc` | emerging-tech | Emerging Tech PoC Lab |
| `spatial-edge-lab` | emerging-tech | Spatial & Edge Lab |
| `design-sprint-studio` | venture-design | Design Sprint Studio |
| `venture-partnership-hub` | venture-design | Venture Partnership Hub |

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/innovation-lab` | No | Full Innovation Lab catalog payload |
| GET | `/api/v1/innovation-lab/{slug}` | No | Single initiative detail |

## Database

### `innovation_initiatives`

| Column | Purpose |
|--------|---------|
| `slug` | Unique initiative identifier |
| `category` | Filter category slug |
| `title` | Initiative name |
| `tagline` | Short positioning line |
| `summary` | Card description |
| `description` | Full program overview |
| `focus_areas` | JSON list of focus areas |
| `deliverables` | JSON list of program deliverables |
| `timeline` | Typical program duration |
| `badge` | Program badge label |
| `icon` | Icon key for UI component |

Seeded from `config/cyra.php` via `InnovationLabSeeder`.

## Navigation Updates

- Admin Command Center **Innovation Lab** item links to `/innovation-lab`

## Key Files

```
app/Services/InnovationLabService.php
app/Repositories/InnovationInitiativeRepository.php
app/Models/InnovationInitiative.php
app/Http/Controllers/Web/InnovationLabController.php
app/Http/Controllers/Api/InnovationLabController.php
resources/views/innovation-lab/
resources/views/components/innovation-lab/
resources/js/pages/innovation-lab.js
config/cyra.php (innovation_lab section)
database/seeders/InnovationLabSeeder.php
```

## Verification

```bash
php artisan test
npm run build
```

Visit `/innovation-lab` to browse lab programs and filter by category.

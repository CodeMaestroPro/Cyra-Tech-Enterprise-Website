# Module 15 — Careers

## Overview

Module 15 delivers the Cyra-Tech Careers hub with role filtering, individual job detail pages, culture overview, and API access. The Module 04 preview at `/careers` is replaced with full careers content.

## Stack

- **Blade** components for opening cards and detail pages
- **Vanilla JavaScript** for category filter tabs
- **Tailwind CSS 4** aligned with the Cyra-Tech design system
- **Laravel 12** service/repository architecture

## Routes

| Route | Name | Description |
|-------|------|-------------|
| `/careers` | `careers` | Open roles catalog with category filters |
| `/careers/{slug}` | `careers.show` | Individual job detail page |

## Career Openings

Six roles seeded by default:

| Slug | Category | Title |
|------|----------|-------|
| `senior-cloud-architect` | engineering | Senior Cloud Architect |
| `lead-full-stack-engineer` | engineering | Lead Full-Stack Engineer |
| `ai-ml-engineer` | engineering | AI / ML Engineer |
| `enterprise-solutions-consultant` | consulting | Enterprise Solutions Consultant |
| `ux-product-designer` | design | UX / Product Designer |
| `program-delivery-manager` | operations | Program Delivery Manager |

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/careers` | No | Full careers catalog payload |
| GET | `/api/v1/careers/{slug}` | No | Single opening detail |

## Database

### `career_openings`

| Column | Purpose |
|--------|---------|
| `slug` | Unique role identifier |
| `category` | Filter category slug |
| `title` | Job title |
| `department` | Department name |
| `location` | Location label |
| `work_type` | Remote, Hybrid, or On-site |
| `tagline` | Short positioning line |
| `summary` | Card description |
| `description` | Full role overview |
| `responsibilities` | JSON list of responsibilities |
| `requirements` | JSON list of requirements |
| `experience_level` | Seniority level label |
| `badge` | Role badge label |
| `icon` | Icon key for UI component |

Seeded from `config/cyra.php` via `CareerSeeder`.

## Navigation Updates

- Admin Command Center **Careers** item links to `/careers`

## Key Files

```
app/Services/CareerService.php
app/Repositories/CareerOpeningRepository.php
app/Models/CareerOpening.php
app/Http/Controllers/Web/CareersController.php
app/Http/Controllers/Api/CareersController.php
resources/views/careers/
resources/views/components/careers/
resources/js/pages/careers.js
config/cyra.php (careers section)
database/seeders/CareerSeeder.php
```

## Verification

```bash
php artisan test
npm run build
```

Visit `/careers` to browse open roles and filter by category.

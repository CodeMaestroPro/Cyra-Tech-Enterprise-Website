# Module 14 — Insights

## Overview

Module 14 delivers the Cyra-Tech Insights hub with article filtering, individual article detail pages, editorial overview, and API access. The Module 04 preview at `/insights` is replaced with full thought leadership content.

## Stack

- **Blade** components for article cards and detail pages
- **Vanilla JavaScript** for category filter tabs
- **Tailwind CSS 4** aligned with the Cyra-Tech design system
- **Laravel 12** service/repository architecture

## Routes

| Route | Name | Description |
|-------|------|-------------|
| `/insights` | `insights` | Insights catalog with category filters |
| `/insights/{slug}` | `insights.show` | Individual article detail page |

## Insight Articles

Six articles seeded by default:

| Slug | Category | Title |
|------|----------|-------|
| `executive-guide-ai-readiness` | ai | The Executive Guide to AI Readiness |
| `cloud-finops-enterprise-scale` | cloud | Cloud FinOps for Enterprise Scale |
| `zero-trust-regulated-industries` | security | Zero Trust in Regulated Industries |
| `digital-transformation-playbook` | transformation | The Digital Transformation Playbook |
| `modern-data-platform-guide` | data | Modern Data Platform Guide |
| `responsible-ai-governance` | ai | Responsible AI Governance |

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/insights` | No | Full insights catalog payload |
| GET | `/api/v1/insights/{slug}` | No | Single article detail |

## Database

### `insight_articles`

| Column | Purpose |
|--------|---------|
| `slug` | Unique article identifier |
| `category` | Filter category slug |
| `title` | Article title |
| `tagline` | Short positioning line |
| `summary` | Card description |
| `description` | Full article body |
| `author` | Author attribution |
| `read_time` | Estimated reading time |
| `topics` | JSON list of key topics |
| `takeaways` | JSON list of executive takeaways |
| `published_label` | Publication date label |
| `badge` | Article badge label |
| `icon` | Icon key for UI component |

Seeded from `config/cyra.php` via `InsightSeeder`.

## Navigation Updates

- Admin Command Center **Insights** item links to `/insights`

## Key Files

```
app/Services/InsightService.php
app/Repositories/InsightArticleRepository.php
app/Models/InsightArticle.php
app/Http/Controllers/Web/InsightsController.php
app/Http/Controllers/Api/InsightsController.php
resources/views/insights/
resources/views/components/insights/
resources/js/pages/insights.js
config/cyra.php (insights section)
database/seeders/InsightSeeder.php
```

## Verification

```bash
php artisan test
npm run build
```

Visit `/insights` to browse articles and filter by category.

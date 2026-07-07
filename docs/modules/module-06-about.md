# Module 06 — About

## Overview

Module 06 delivers the Cyra-Tech About section as a multi-page experience with shared sub-navigation, CMS-ready content storage, and API access.

The Module 04 preview page at `/about` is replaced with five fully rendered About pages.

## Stack

- **Blade** page template with block components
- **Tailwind CSS 4** aligned with the Cyra-Tech design system
- **Laravel 12** service/repository architecture
- **Vanilla JavaScript** (no additional scripts required for this module)

## About Pages

| Route | Name | Content |
|-------|------|---------|
| `/about` | `about` | Company overview, story, stats, partnership approach |
| `/about/vision-mission` | `about.vision-mission` | Vision, mission, and purpose |
| `/about/values` | `about.values` | Six core values |
| `/about/history` | `about.history` | Company timeline milestones |
| `/about/why-choose-us` | `about.why-choose-us` | Differentiators, stats, contact CTA |

## Content Blocks

Block types supported in `about_pages.content`:

- `prose` — narrative paragraphs
- `stats-row` — metric highlights
- `feature-list` — titled differentiators
- `quote-cards` — vision/mission statements
- `value-grid` — core values grid
- `timeline` — historical milestones
- `cta` — conversion band with action button

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/about` | No | Full About catalog (navigation + all pages) |
| GET | `/api/v1/about/{slug}` | No | Single About page payload |

## Database

### `about_pages`

| Column | Purpose |
|--------|---------|
| `slug` | Unique page identifier |
| `route_name` | Laravel route name for navigation |
| `nav_label` | Sub-navigation label |
| `title` | Page heading |
| `description` | Intro copy |
| `content` | JSON block payload |
| `seo` | JSON SEO metadata |
| `sort_order` | Navigation order |

Seeded from `config/cyra.php` via `AboutSeeder`.

## Key Files

```
app/Services/AboutService.php
app/Repositories/AboutPageRepository.php
app/Models/AboutPage.php
app/Http/Controllers/Web/AboutController.php
app/Http/Controllers/Api/AboutController.php
resources/views/about/show.blade.php
resources/views/components/about/
config/cyra.php (about.pages)
database/seeders/AboutSeeder.php
```

## Verification

```bash
php artisan test
npm run build
```

Visit `/about` and use the sub-navigation to explore all About pages.

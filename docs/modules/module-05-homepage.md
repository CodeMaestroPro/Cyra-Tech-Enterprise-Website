# Module 05 — Homepage

## Overview

Module 05 delivers the public marketing homepage for Cyra-Tech at `/`. The Module 01 initialization dashboard remains available at `/platform/initialization`.

Homepage content is defined in `config/cyra.php`, persisted in `homepage_sections`, and rendered through Blade section components with vanilla JavaScript enhancements.

## Stack

- **Blade** section components (`x-homepage.*`)
- **Vanilla JavaScript** for animated statistics counters
- **Tailwind CSS 4** aligned with the Cyra-Tech design system
- **Laravel 12** service/repository/API architecture

## Homepage Sections

| Slug | Type | Purpose |
|------|------|---------|
| `hero` | hero | Primary headline, CTAs, platform highlights |
| `stats` | stats | Animated enterprise metrics |
| `partners` | logos | Trusted-by partner strip |
| `solutions` | feature-grid | Core solution capabilities |
| `products` | card-grid | Featured platform products |
| `industries` | card-grid | Industry expertise grid |
| `innovation-lab` | split-feature | Innovation Lab promo |
| `portfolio` | card-grid | Case study highlights |
| `insights` | card-grid | Thought leadership previews |
| `community` | cta-band | Community join CTA |
| `careers` | cta-band | Careers hiring CTA |
| `contact-cta` | cta-band | Final conversion band |

## SEO

The homepage pushes:

- Meta description and keywords
- Open Graph title/description
- JSON-LD `Organization` structured data

## API Endpoint

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/homepage` | No | Homepage SEO metadata and section payload |

## Database

### `homepage_sections`

| Column | Purpose |
|--------|---------|
| `slug` | Unique section identifier |
| `type` | Blade component type (`hero`, `stats`, `card-grid`, etc.) |
| `eyebrow` | Optional section label |
| `title` | Section heading |
| `description` | Supporting copy |
| `content` | JSON payload for cards, actions, stats, bullets |
| `sort_order` | Render order |

Seeded from `config/cyra.php` via `HomepageSeeder`.

## Key Files

```
app/Services/HomepageService.php
app/Repositories/HomepageSectionRepository.php
app/Models/HomepageSection.php
app/Http/Controllers/Web/HomepageController.php
app/Http/Controllers/Api/HomepageController.php
resources/views/home/index.blade.php
resources/views/components/homepage/
resources/js/pages/homepage.js
config/cyra.php (homepage section)
database/seeders/HomepageSeeder.php
```

## Routes

| Route | Name | Description |
|-------|------|-------------|
| `/` | `home` | Public homepage |
| `/platform/initialization` | `platform.initialization` | Module 01 platform dashboard |

## Verification

```bash
php artisan test
npm run build
```

Visit `/` for the homepage and `/platform/initialization` for the platform initialization dashboard.

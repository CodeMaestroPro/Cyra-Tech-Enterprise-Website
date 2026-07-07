# Module 09 — Products

## Overview

Module 09 delivers the Cyra-Tech product catalog with category filtering, individual product detail pages, ecosystem overview, and API access. The Module 04 preview at `/products` is replaced with full product content.

## Stack

- **Blade** components for product cards and detail pages
- **Vanilla JavaScript** for category filter tabs
- **Tailwind CSS 4** aligned with the Cyra-Tech design system
- **Laravel 12** service/repository architecture

## Routes

| Route | Name | Description |
|-------|------|-------------|
| `/products` | `products` | Product catalog with category filters |
| `/products/{slug}` | `products.show` | Individual product detail page |

## Platform Products

Six products seeded by default:

| Slug | Badge | Title |
|------|-------|-------|
| `cyra-command` | Flagship | Cyra Command |
| `cyra-pulse` | Analytics | Cyra Pulse |
| `cyra-shield` | Security | Cyra Shield |
| `cyra-connect` | Integration | Cyra Connect |
| `cyra-flow` | Automation | Cyra Flow |
| `cyra-studio` | Content | Cyra Studio |

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/products` | No | Full product catalog payload |
| GET | `/api/v1/products/{slug}` | No | Single product detail |

## Database

### `product_offerings`

| Column | Purpose |
|--------|---------|
| `slug` | Unique product identifier |
| `category` | Filter category slug |
| `title` | Product name |
| `tagline` | Short positioning line |
| `summary` | Card description |
| `description` | Full product overview |
| `badge` | Product badge label |
| `features` | JSON list of key features |
| `use_cases` | JSON list of use cases |
| `icon` | Icon key for UI component |

Seeded from `config/cyra.php` via `ProductSeeder`.

## Navigation Updates

- Admin Command Center **Products** item links to `/products`

## Key Files

```
app/Services/ProductService.php
app/Repositories/ProductOfferingRepository.php
app/Models/ProductOffering.php
app/Http/Controllers/Web/ProductsController.php
app/Http/Controllers/Api/ProductsController.php
resources/views/products/
resources/views/components/products/
resources/js/pages/products.js
config/cyra.php (products section)
database/seeders/ProductSeeder.php
```

## Verification

```bash
php artisan test
npm run build
```

Visit `/products` to browse the catalog and filter by category.

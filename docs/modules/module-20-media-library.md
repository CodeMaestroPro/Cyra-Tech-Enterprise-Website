# Module 20 — Media Library

## Overview

Module 20 delivers the Cyra-Tech Media Library with an admin workspace for uploading and organizing brand, marketing, portfolio, team, and document assets. Active assets are exposed through public read APIs for CMS and marketing integrations, while authenticated admin APIs support full asset management.

## Stack

- **Blade** admin grid and edit views
- **Laravel Storage** public disk under `storage/app/public/media`
- **Tailwind CSS 4** aligned with the Cyra-Tech design system
- **Laravel 12** service/repository architecture

## Routes

| Route | Name | Auth | Description |
|-------|------|------|-------------|
| `/admin/media` | `admin.media.index` | Admin + `media.view` | Media library grid and upload form |
| `/admin/media` (POST) | `admin.media.store` | Admin + `media.upload` | Upload new asset |
| `/admin/media/{uuid}/edit` | `admin.media.edit` | Admin + `media.view` | Edit asset metadata |
| `/admin/media/{uuid}` (PUT) | `admin.media.update` | Admin + `media.update` | Save metadata changes |
| `/admin/media/{uuid}` (DELETE) | `admin.media.destroy` | Admin + `media.delete` | Delete asset and file |

## RBAC

| Slug | Purpose |
|------|---------|
| `media.view` | View media library admin screens and admin API |
| `media.upload` | Upload new assets |
| `media.update` | Update asset metadata and availability |
| `media.delete` | Delete assets and stored files |

Role assignments:

- **admin** — all media permissions
- **editor** — view, upload, update, delete
- **manager** — view only
- **super-admin** — all permissions via wildcard

## Seeded Assets

Seven default assets seeded from fixtures:

| UUID suffix | Title | Category |
|-------------|-------|----------|
| `...0001` | Cyra-Tech Primary Logo | Brand |
| `...0002` | Cyra-Tech Logo Mark | Brand |
| `...0003` | Enterprise Hero Abstract | Marketing |
| `...0004` | Innovation Lab Banner | Marketing |
| `...0005` | Portfolio Case Study Cover | Portfolio |
| `...0006` | Leadership Headshot Placeholder | Team |
| `...0007` | Brand Usage Guidelines | Documents |

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/media/assets` | No | Public catalog of active assets |
| GET | `/api/v1/media/assets/{uuid}` | No | Single active asset |
| GET | `/api/v1/media` | Admin + `media.view` | Admin catalog with upload rules |
| GET | `/api/v1/media/{uuid}` | Admin + `media.view` | Admin asset detail |
| POST | `/api/v1/media` | Admin + `media.upload` | Upload asset |
| PUT | `/api/v1/media/{uuid}` | Admin + `media.update` | Update metadata |
| DELETE | `/api/v1/media/{uuid}` | Admin + `media.delete` | Delete asset |

## Database

### `media_assets`

Stores UUID, file metadata, category, accessibility fields, JSON metadata (dimensions), uploader, sort order, and active status.

## Navigation Updates

- Admin sidebar **Media Library** item enabled → `/admin/media`

## Supported Upload Types

- JPEG, PNG, WebP, SVG
- PDF
- Plain text documents

Maximum upload size defaults to 5120 KB and is configurable in `config/cyra.php`.

## Tests

- `tests/Feature/MediaLibraryPageTest.php` — public asset API behavior
- `tests/Feature/MediaLibraryAdminTest.php` — admin access, upload, update, delete
- `tests/Feature/MediaLibraryApiTest.php` — authenticated admin API endpoints

## Verification

```bash
php artisan migrate
php artisan db:seed --class=MediaLibrarySeeder
php artisan storage:link
php artisan test
npm run build
```

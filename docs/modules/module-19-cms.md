# Module 19 — CMS

## Overview

Module 19 delivers the Cyra-Tech Content Management System with an admin workspace for legal and editorial pages, a draft/publish workflow, public page rendering at `/pages/{slug}`, and public plus admin API endpoints. Footer legal links now resolve to published CMS pages instead of the About module.

## Stack

- **Blade** admin views and public page templates
- **About block components** reused for CMS content rendering
- **Tailwind CSS 4** aligned with the Cyra-Tech design system
- **Laravel 12** service/repository architecture

## Routes

| Route | Name | Auth | Description |
|-------|------|------|-------------|
| `/pages/{slug}` | `pages.show` | No | Public published CMS page |
| `/admin/cms` | `admin.cms.index` | Admin + `cms.view` | CMS page catalog |
| `/admin/cms/create` | `admin.cms.create` | Admin + `cms.create` | Create page form |
| `/admin/cms/{slug}/edit` | `admin.cms.edit` | Admin + `cms.view` | Edit page form |
| `/admin/cms/{slug}/publish` | `admin.cms.publish` | Admin + `cms.publish` | Publish draft page |
| `/admin/cms/{slug}/unpublish` | `admin.cms.unpublish` | Admin + `cms.publish` | Move page to draft |

## RBAC

| Slug | Purpose |
|------|---------|
| `cms.view` | View CMS admin catalog and edit screens |
| `cms.create` | Create new CMS pages |
| `cms.update` | Update page content and metadata |
| `cms.publish` | Publish or unpublish pages |

Role assignments:

- **admin** — all CMS permissions
- **editor** — view, create, update, publish
- **manager** — view only
- **super-admin** — all permissions via wildcard

## Seeded Pages

Six published legal/policy pages:

| Slug | Title |
|------|-------|
| `privacy-policy` | Privacy Policy |
| `terms-of-service` | Terms of Service |
| `cookie-policy` | Cookie Policy |
| `accessibility` | Accessibility Statement |
| `acceptable-use-policy` | Acceptable Use Policy |
| `data-processing-addendum` | Data Processing Addendum |

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/pages` | No | Public published page catalog |
| GET | `/api/v1/pages/{slug}` | No | Single published page |
| GET | `/api/v1/cms/pages` | Admin + `cms.view` | Admin page catalog with summary |
| GET | `/api/v1/cms/pages/{slug}` | Admin + `cms.view` | Admin page detail including body |

## Database

### `cms_pages`

Stores slug, title, template, status, SEO metadata, JSON content blocks, author, publish timestamp, and sort order.

## Navigation Updates

- Admin sidebar **Pages** item enabled → `/admin/cms`
- Footer legal links → `/pages/{slug}` for Privacy Policy, Terms, Cookie Policy, and Accessibility

## Content Rendering

CMS pages store structured JSON blocks. Seeded legal pages use `prose` blocks, rendered through the existing `<x-about.blocks.prose>` component on the public page template.

## Tests

- `tests/Feature/CmsPageTest.php` — public page rendering and draft protection
- `tests/Feature/CmsAdminTest.php` — admin access, create, publish workflow
- `tests/Feature/CmsApiTest.php` — public and admin API endpoints

## Verification

```bash
php artisan migrate:fresh --seed
php artisan test
npm run build
```

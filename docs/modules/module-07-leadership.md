# Module 07 — Leadership

## Overview

Module 07 delivers the Cyra-Tech Leadership page with executive profiles, extended leadership, governance pillars, and profile detail modals. Content is config-driven, persisted in `leadership_profiles`, and exposed via API.

## Stack

- **Blade** components for profile cards and page layout
- **Vanilla JavaScript** for accessible profile detail modals
- **Tailwind CSS 4** aligned with the Cyra-Tech design system
- **Laravel 12** service/repository architecture

## Page Sections

| Section | Description |
|---------|-------------|
| Hero | Leadership headline and intro copy |
| Executive Team | Four featured C-suite profiles |
| Extended Leadership | CFO and Chief People Officer |
| Governance | Three accountability pillars |
| CTA | Contact conversion band |

## Executive Profiles

Seeded leaders include:

- Collins Pever — Chief Executive Officer
- Dr. Amara Okonkwo — Chief Technology Officer
- James Whitfield — Chief Operating Officer
- Elena Vasquez — Chief Innovation Officer

Extended leadership includes CFO and Chief People Officer profiles.

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/leadership` | No | Full leadership page payload |
| GET | `/api/v1/leadership/{slug}` | No | Single leader profile |

## Database

### `leadership_profiles`

| Column | Purpose |
|--------|---------|
| `slug` | Unique profile identifier |
| `name` | Leader name |
| `title` | Role/title |
| `tier` | `executive` or `extended` |
| `bio` | Full biography |
| `focus_areas` | JSON list of focus tags |
| `linkedin_url` | Optional LinkedIn profile |
| `email` | Optional contact email |
| `is_featured` | Featured flag for future CMS use |

Seeded from `config/cyra.php` via `LeadershipSeeder`.

## Navigation Updates

- Footer **Leadership** link now routes to `/leadership`
- Admin Command Center **Leadership** item links to the public leadership page

## Key Files

```
app/Services/LeadershipService.php
app/Repositories/LeadershipProfileRepository.php
app/Models/LeadershipProfile.php
app/Http/Controllers/Web/LeadershipController.php
app/Http/Controllers/Api/LeadershipController.php
resources/views/leadership/index.blade.php
resources/views/components/leadership/
resources/js/pages/leadership.js
config/cyra.php (leadership section)
database/seeders/LeadershipSeeder.php
```

## Verification

```bash
php artisan test
npm run build
```

Visit `/leadership` to view executive profiles and open profile modals.

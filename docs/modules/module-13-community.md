# Module 13 — Community

## Overview

Module 13 delivers the Cyra-Tech Community hub with program filtering, individual program detail pages, community values overview, and API access. The Module 04 preview at `/community` is replaced with full community content.

## Stack

- **Blade** components for program cards and detail pages
- **Vanilla JavaScript** for category filter tabs
- **Tailwind CSS 4** aligned with the Cyra-Tech design system
- **Laravel 12** service/repository architecture

## Routes

| Route | Name | Description |
|-------|------|-------------|
| `/community` | `community` | Community program catalog with category filters |
| `/community/{slug}` | `community.show` | Individual program detail page |

## Community Programs

Six programs seeded by default:

| Slug | Category | Title |
|------|----------|-------|
| `cyra-connect-forum` | forums | Cyra Connect Forum |
| `executive-roundtable` | forums | Executive Roundtable |
| `innovation-summit` | events | Cyra Innovation Summit |
| `developer-guild` | learning | Developer Guild |
| `practitioner-guild` | learning | Practitioner Guild |
| `partner-network` | partners | Partner Network |

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/community` | No | Full community catalog payload |
| GET | `/api/v1/community/{slug}` | No | Single program detail |

## Database

### `community_programs`

| Column | Purpose |
|--------|---------|
| `slug` | Unique program identifier |
| `category` | Filter category slug |
| `title` | Program name |
| `tagline` | Short positioning line |
| `summary` | Card description |
| `description` | Full program overview |
| `benefits` | JSON list of member benefits |
| `activities` | JSON list of program activities |
| `membership` | Membership eligibility label |
| `schedule` | Program schedule label |
| `badge` | Program badge label |
| `icon` | Icon key for UI component |

Seeded from `config/cyra.php` via `CommunitySeeder`.

## Navigation Updates

- Admin Command Center **Community** item links to `/community`

## Key Files

```
app/Services/CommunityService.php
app/Repositories/CommunityProgramRepository.php
app/Models/CommunityProgram.php
app/Http/Controllers/Web/CommunityController.php
app/Http/Controllers/Api/CommunityController.php
resources/views/community/
resources/views/components/community/
resources/js/pages/community.js
config/cyra.php (community section)
database/seeders/CommunitySeeder.php
```

## Verification

```bash
php artisan test
npm run build
```

Visit `/community` to browse programs and filter by category.

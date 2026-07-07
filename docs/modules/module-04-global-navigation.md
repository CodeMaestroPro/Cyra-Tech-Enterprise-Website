# Module 04 — Global Navigation

## Overview

Module 04 delivers enterprise-grade navigation for the public website and admin Command Center. Navigation structure is defined in `config/cyra.php`, persisted in the `navigation_items` table, and resolved at runtime by `NavigationService`.

## Stack

- **Blade** components for header, footer, mobile menu, and admin sidebar
- **Vanilla JavaScript** for mobile menu toggle, scroll lock, and Escape-to-close
- **Tailwind CSS 4** styling aligned with the Cyra-Tech design system
- **Laravel 12** services, repositories, API endpoints, and seeders

## Public Navigation

### Header

- Brand lockup (`CYRA` + accent `TECH`)
- Primary links: Home, About, Solutions, Products, Industries, Portfolio, Innovation Lab, Community, Insights, Careers
- Utility placeholders: search and theme toggle (disabled until future modules)
- Actions: Client Portal (login) and Contact Us CTA
- Sticky header with backdrop blur
- Skip link for accessibility

### Mobile Menu

- Hamburger toggle on viewports below `lg`
- Slide-in panel with primary links and actions
- Backdrop click and Escape key close the menu
- Body scroll lock while open

### Footer

- Four link columns (Company, Solutions, Resources, Connect)
- Newsletter signup UI (disabled placeholder)
- Social links (external)
- Legal links and copyright

## Admin Navigation

The Command Center sidebar is grouped into seven sections:

1. Executive
2. Digital Headquarters
3. Solutions
4. Growth
5. People
6. Operations
7. System

Items with available routes link to admin pages. Future modules appear as muted labels until implemented. Permission checks filter admin items per user.

Public module routes (Modules 5–17) are fully implemented; navigation links resolve to their dedicated controllers and views rather than placeholder pages.

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/navigation/public` | No | Public header, actions, and footer structure |
| GET | `/api/v1/navigation/admin` | Yes + `dashboard.access` | Admin sidebar groups for current user |

## Database

### `navigation_items`

| Column | Purpose |
|--------|---------|
| `location` | `public_header`, `public_actions`, `footer_column`, `footer_social`, `footer_legal`, `admin_sidebar` |
| `group_label` | Footer column title or admin group name |
| `label` | Display text |
| `route_name` | Named Laravel route |
| `route_params` | JSON route parameters |
| `url` | External or fallback URL |
| `permission` | RBAC permission for admin items |
| `is_available` | Whether the item is clickable |

Seeded from `config/cyra.php` via `NavigationSeeder`.

## Key Files

```
app/Services/NavigationService.php
app/Repositories/NavigationItemRepository.php
app/Models/NavigationItem.php
app/Http/Middleware/ShareNavigationData.php
app/Http/Controllers/Api/NavigationController.php
resources/views/components/navigation/
resources/js/components/navigation.js
config/cyra.php (navigation section)
database/seeders/NavigationSeeder.php
```

## Verification

```bash
php artisan test
npm run build
```

Visit `/` for public navigation, `/about` for the About module, and `/admin` (authenticated) for the Command Center sidebar.

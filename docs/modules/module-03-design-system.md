# Module 03: Design System

## Overview

Module 03 establishes the Cyra-Tech design system using **Blade components**, **Tailwind CSS 4 tokens**, and **vanilla JavaScript** only. **React is not used.**

## Deliverables

### UI Implementation

- Admin design system showcase: `/admin/design-system`
- Expanded Blade component library in `resources/views/components/ui/`
- Typography, color, form, feedback, layout, and interactive patterns
- Vanilla JS behaviors for modal and tabs (`resources/js/components/ui.js`)

### New Components

| Component | Blade Tag |
|-----------|-----------|
| Badge | `<x-ui.badge>` |
| Textarea | `<x-ui.textarea>` |
| Select | `<x-ui.select>` |
| Spinner | `<x-ui.spinner>` |
| Section Heading | `<x-ui.section-heading>` |
| Breadcrumb | `<x-ui.breadcrumb>` |
| Table | `<x-ui.table>` |
| Empty State | `<x-ui.empty-state>` |
| Modal | `<x-ui.modal>` |
| Tabs | `<x-ui.tabs>` |

Existing components enhanced: `button` (success, danger, outline variants).

### Backend Implementation

- `App\Services\DesignSystemService`
- `App\Http\Controllers\Admin\DesignSystemController`
- `App\Http\Controllers\Api\DesignSystemController`
- Token catalog in `config/cyra.php` under `design_system`

### API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/design-system/tokens` | Color, typography, and radius tokens |
| GET | `/api/v1/design-system/catalog` | Full design system catalog |

### CSS Tokens

Extended utilities in `resources/css/app.css`:

- Typography: `.cyra-display`, `.cyra-heading-*`, `.cyra-body`, `.cyra-caption`
- Forms: `.cyra-input`, `.cyra-input-error`
- Tables: `.cyra-table`, `.cyra-table-wrap`
- Tabs: `.cyra-tab-active`, `.cyra-tab-inactive`
- Spinner animation: `.cyra-spinner`

### Tests

- `tests/Feature/DesignSystemPageTest.php`
- `tests/Feature/DesignSystemApiTest.php`

## Access

- Requires authentication and `modules.view` permission
- Default super admin can access immediately after seeding

## Verification

```bash
php artisan test
npm run build
```

Visit `/admin/design-system` after logging in.

## Git Commit Suggestion

```text
feat(module-03): add Cyra-Tech design system components and showcase

Expand Blade UI library, Tailwind tokens, vanilla JS modal/tabs behaviors,
design system API, admin showcase page, and feature tests without React.
```

## Next Module

Proceed with **Module 04: Global Navigation** when ready.

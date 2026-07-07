# Module 02: Authentication & RBAC

## Overview

Module 02 adds secure session authentication, role-based access control (RBAC), admin login, and a protected Command Center dashboard stub for the Cyra-Tech Enterprise Platform.

## Stack

- Laravel 12 session authentication
- Blade + vanilla JavaScript UI
- Tailwind CSS 4
- MySQL RBAC tables

## Deliverables

### UI Implementation

- Login page: `/login`
- Admin dashboard stub: `/admin`
- New Blade components: `input`, `label`, `alert`, `checkbox`
- Admin layout with Command Center sidebar/header styling
- Login loading state via `resources/js/pages/login.js`

### Backend Implementation

- RBAC models: `Role`, `Permission`, `HasRoles` trait on `User`
- Services: `AuthService`, `RoleService`
- Repositories: `UserRepository`, `RoleRepository`, `PermissionRepository`
- Middleware: `EnsureRole`, `EnsurePermission`
- Policies: `UserPolicy`, `RolePolicy`
- Form request: `LoginRequest`
- Seeders: `RolePermissionSeeder`, `AdminUserSeeder`

### API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/auth/me` | Required | Authenticated user profile |
| GET | `/api/v1/auth/permissions` | Required | User permission slugs |
| GET | `/api/v1/auth/roles` | Required + `roles.view` | Role listing |

### Database Changes

- `users`: added `is_active`, `last_login_at`
- `roles`, `permissions`, `role_user`, `permission_role`

### Default Roles

| Slug | Name |
|------|------|
| `super-admin` | Super Administrator |
| `admin` | Administrator |
| `manager` | Manager |
| `editor` | Editor |
| `viewer` | Viewer |

### Default Admin (Seeded)

- Email: `admin@cyratech.com`
- Password: `password` (change in production)
- Role: `super-admin`

Configure via `.env`:

```env
CYRA_ADMIN_NAME="Collins Pever"
CYRA_ADMIN_EMAIL=admin@cyratech.com
CYRA_ADMIN_PASSWORD=password
```

### Tests

- `tests/Feature/LoginTest.php`
- `tests/Feature/AdminDashboardTest.php`
- `tests/Feature/RbacTest.php`
- `tests/Feature/AuthApiTest.php`

## Verification

```bash
php artisan migrate --seed
php artisan test
npm run build
```

- Visit `/login` and sign in with seeded admin credentials
- Confirm redirect to `/admin`
- Confirm `/api/v1/auth/me` returns profile when authenticated

## Git Commit Suggestion

```text
feat(module-02): add authentication and RBAC foundation

Implement session login, role/permission schema, admin dashboard access,
auth APIs, policies, seeders, and feature tests for enterprise RBAC.
```

## Next Module

Proceed with **Module 03: Design System** when ready.

# Module 24 — Project Management

## Overview

Module 24 delivers the Cyra-Tech Project Management workspace at `/admin/projects`, providing a portfolio view of enterprise delivery programs, progress tracking, task management, and a cross-project task board.

## Stack

- **Blade** project portfolio cards with progress visualization
- **ProjectManagementService** with config-driven statuses, phases, priorities, and task states
- **Task board** at `/admin/projects/tasks` for operational visibility
- **Laravel 12** service/repository architecture

## Routes

| Route | Name | Auth | Description |
|-------|------|------|-------------|
| `/admin/projects` | `admin.projects.index` | Admin + `projects.view` | Project portfolio with filters |
| `/admin/projects/tasks` | `admin.projects.tasks` | Admin + `projects.view` | Cross-project task board |
| `/admin/projects/create` | `admin.projects.create` | Admin + `projects.create` | Create project form |
| `/admin/projects/{reference}/edit` | `admin.projects.edit` | Admin + `projects.view` | Edit project and manage tasks |

## RBAC

| Permission | Description |
|------------|-------------|
| `projects.view` | View project portfolio and task board |
| `projects.create` | Create new projects |
| `projects.update` | Update project records |
| `projects.manage` | Update progress and manage project tasks |

Admin role receives all project permissions. Manager role receives `projects.view` only.

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/projects` | Admin + `projects.view` | Full portfolio payload |
| GET | `/api/v1/projects/tasks` | Admin + `projects.view` | Cross-project task board |
| GET | `/api/v1/projects/{reference}` | Admin + `projects.view` | Single project with tasks |
| POST | `/api/v1/projects` | Admin + `projects.create` | Create project |
| PUT | `/api/v1/projects/{reference}` | Admin + `projects.update` | Update project |
| PATCH | `/api/v1/projects/{reference}/progress` | Admin + `projects.manage` | Update progress |
| POST | `/api/v1/projects/{reference}/tasks` | Admin + `projects.manage` | Create task |
| PUT | `/api/v1/projects/{reference}/tasks/{taskReference}` | Admin + `projects.manage` | Update task |

## Configuration

Project management content is driven by `config/cyra.php` under the `project_management` key:

- `statuses` — planning, in-progress, on-hold, completed, cancelled
- `phases` — discovery through support
- `priorities` — low through critical
- `task_statuses` — pending through blocked
- `seed_projects` — five sample programs with nested tasks via `ProjectManagementSeeder`

## Database

- `projects` — delivery program records with optional links to client engagements and CRM leads
- `project_tasks` — milestone and operational tasks linked to projects

## Navigation

Admin sidebar **Projects** and **Tasks** enabled under Operations.

Command Center quick actions updated:

- **Add New Project** → `/admin/projects/create`
- **Manage Projects** → `/admin/projects`

## Tests

- `tests/Feature/ProjectManagementAdminTest.php` — portfolio access, task board, create project, add task
- `tests/Feature/ProjectManagementApiTest.php` — authenticated project and task APIs

## Verification

```bash
php artisan test
npm run build
```

Sign in as `admin@cyratech.com` / `password` and visit `/admin/projects`.

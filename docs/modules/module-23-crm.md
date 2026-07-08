# Module 23 — CRM

## Overview

Module 23 delivers the Cyra-Tech Leads & CRM workspace at `/admin/crm`, providing a pipeline board for enterprise sales opportunities, lead CRUD, stage management, and conversion of inbound contact inquiries into tracked CRM leads.

## Stack

- **Blade** pipeline board with stage columns and lead cards
- **CrmService** with config-driven stages, sources, and priorities
- **Contact inquiry integration** via `contact_inquiry_id` linkage
- **Laravel 12** service/repository architecture

## Routes

| Route | Name | Auth | Description |
|-------|------|------|-------------|
| `/admin/crm` | `admin.crm.index` | Admin + `crm.view` | Pipeline board and inbound inquiries |
| `/admin/crm/create` | `admin.crm.create` | Admin + `crm.create` | Create lead form |
| `/admin/crm/{reference}/edit` | `admin.crm.edit` | Admin + `crm.view` | Edit lead form |
| `/admin/crm/inquiries/{id}/convert` | `admin.crm.inquiries.convert` | Admin + `crm.create` | Convert contact inquiry to lead |

## RBAC

| Permission | Description |
|------------|-------------|
| `crm.view` | View CRM pipeline and lead details |
| `crm.create` | Create leads and convert inquiries |
| `crm.update` | Update lead records |
| `crm.manage` | Move leads between pipeline stages |

Admin role receives all CRM permissions. Manager role receives `crm.view` only.

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/crm` | Admin + `crm.view` | Full pipeline payload |
| GET | `/api/v1/crm/{reference}` | Admin + `crm.view` | Single lead details |
| POST | `/api/v1/crm` | Admin + `crm.create` | Create lead |
| PUT | `/api/v1/crm/{reference}` | Admin + `crm.update` | Update lead |
| PATCH | `/api/v1/crm/{reference}/stage` | Admin + `crm.manage` | Update pipeline stage |
| POST | `/api/v1/crm/inquiries/{id}/convert` | Admin + `crm.create` | Convert inquiry to lead |

## Pipeline Stages

- New
- Qualified
- Proposal
- Negotiation
- Won
- Lost

## Configuration

CRM content is driven by `config/cyra.php` under the `crm` key:

- `pipeline_stages` — stage slugs, labels, and colors
- `sources` — lead acquisition channels
- `priorities` — low, medium, high
- `seed_leads` — six sample enterprise leads seeded via `CrmSeeder`

## Database

`crm_leads` table stores lead records with optional linkage to `contact_inquiries`.

## Navigation

Admin sidebar **Leads & CRM** enabled under Growth section, routing to `/admin/crm`.

Command Center quick actions include **Manage Leads**.

## Tests

- `tests/Feature/CrmAdminTest.php` — admin pipeline access, create, and stage updates
- `tests/Feature/CrmApiTest.php` — authenticated CRM API and inquiry conversion

## Verification

```bash
php artisan test
npm run build
```

Sign in as `admin@cyratech.com` / `password` and visit `/admin/crm`.

# Module 16 — Contact

## Overview

Module 16 delivers the Cyra-Tech Contact page with inquiry form submission, office and channel details, and API access. The Module 04 preview at `/contact` is replaced with the full contact experience.

## Stack

- **Blade** components for form fields, alerts, and layout sections
- **Vanilla JavaScript** to preselect inquiry type from query parameters
- **Tailwind CSS 4** aligned with the Cyra-Tech design system
- **Laravel 12** service/repository architecture

## Routes

| Route | Name | Description |
|-------|------|-------------|
| `/contact` | `contact` | Contact page with inquiry form |
| `POST /contact` | `contact.store` | Submit a contact inquiry |

## Inquiry Types

Configured in `config/cyra.php`:

| Slug | Label |
|------|-------|
| `sales` | Sales & Solutions |
| `support` | Client Support |
| `partnership` | Partnerships |
| `careers` | Careers & Talent |
| `media` | Media & Press |
| `general` | General Inquiry |

Query parameters `?inquiry=` or `?type=` preselect the inquiry type in the form.

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/v1/contact` | No | Contact page configuration payload |
| POST | `/api/v1/contact` | No | Submit a contact inquiry (201) |

## Database

### `contact_inquiries`

| Column | Purpose |
|--------|---------|
| `reference` | Unique inquiry reference (e.g. `CYRA-20260707-ABC123`) |
| `name` | Submitter name |
| `email` | Submitter email |
| `company` | Optional organization |
| `phone` | Optional phone number |
| `inquiry_type` | Selected inquiry slug |
| `message` | Inquiry message body |
| `status` | Processing status (default: `pending`) |
| `ip_address` | Optional request IP |

## Navigation Updates

- Admin Command Center **Growth → Contact** links to `/contact`
- Public header **Contact Us** action continues to route to `/contact`

## Key Files

```
app/Services/ContactService.php
app/Repositories/ContactInquiryRepository.php
app/Models/ContactInquiry.php
app/Http/Controllers/Web/ContactController.php
app/Http/Controllers/Api/ContactController.php
app/Http/Requests/ContactInquiryRequest.php
resources/views/contact/index.blade.php
resources/js/pages/contact.js
config/cyra.php (contact section)
database/migrations/2026_07_07_100015_create_contact_inquiries_table.php
```

## Verification

```bash
php artisan test
npm run build
```

Visit `/contact` to submit an inquiry or use `/contact?inquiry=partnership` to preselect a type.

# agents/settings.md

Account settings area for InternLink users.

> Read **AGENTS.md** first to understand navigation and realm rules.

---

## Access Rights

- `/{school_code}/settings/profile` and `/{school_code}/settings/security` are available to every authenticated role while inside a school realm.
- `/{school_code}/settings/environments` is restricted to admin and developer roles; other roles receive a 403 response.

---

## Navigation

- The Settings button in the header routes to `/{school_code}/settings/profile` when a realm is active.
- Each settings page renders a local sidebar with links to Profile, Security, and (role-gated) Environments for quick switching.

---

## Profile — `/{school_code}/settings/profile`

### Purpose

- Provides editable inputs so users can maintain their personal information without leaving the settings area.
- Displays a read-only “Profile Overview” card summarising the latest stored values.

### Form Inputs

| Role | Inputs |
| --- | --- |
| Student | Name, Email, Phone, Student Number, National Student Number, Major, Class, Batch, Notes, Photo URL |
| Supervisor | Name, Email, Phone, Supervisor Number, Department, Notes, Photo URL |
| Admin / Developer | Name, Email, Phone |

### Validation & Behaviour

- Name and Email are required for every role.
- Email uniqueness follows existing CRUD rules: scoped to the current school for non-developers, global for developers.
- Student & Supervisor identifiers (`student_number`, `national_sn`, `supervisor_number`) stay unique inside the school realm; updates occur in a transaction alongside the base user record.
- No password field appears on this page (password changes live in Security).
- Submitting the form saves immediately and redirects back with a success flash message.

---

## Security — `/{school_code}/settings/security`

### Form Inputs

- Old Password (required)
- New Password (required, min 8 characters)
- Confirm Password (must match new password)

### Behaviour

- Old password must match the current credential; mismatch raises a validation error.
- On success the password rotates instantly and the user is returned to the Security page with confirmation.
- A “Security Overview” card still surfaces role, verification status, and account timestamps for reference.

---

## Environments — `/{school_code}/settings/environments`

- Visible only to admin and developer roles.
- Currently a placeholder card for upcoming environment management tooling.
- Non-authorised roles are blocked with HTTP 403 to prevent accidental discovery.

---

## Notes

- Developers must enter a realm (via the Schools list) before the Settings button appears; settings are realm-scoped.
- All read-only details leverage the existing reporting views (`student_details_view`, `supervisor_details_view`) so diagnostics remain consistent with CRUD modules.

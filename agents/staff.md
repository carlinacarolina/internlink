# agents/staff.md

Major Staff Contacts module manages the assignment between school majors and supervisors who act as the designated staff contact for internship correspondence.

> Read **AGENTS.md** first to understand realm rules and navigation.

---

## Access Rights
* **Create / Read / Update / Delete**: Developer and Admin roles only. Other roles must be blocked with HTTP 403.

---

## List – `/{school_code}/major-contacts/`
1. Page title: **Major Contacts**.  
2. Table columns: Major, Staff Name, Email, Phone, Department, Actions.  
   * Actions: **Update**, **Delete**.  
3. Show the total number of contacts and pagination info `Page X out of N` at the bottom.  
4. Display **10 records per page** with **Back**/**Next** buttons. Disable controls when not available.  
5. Add a **Create Contact** button in the header that links to the create form.

---

## Create – `/{school_code}/major-contacts/create/`
1. Page title: **Create Major Contact**.  
2. Inputs:  
   * Major (Text, max 150 chars) — use an HTML datalist seeded from existing student majors for convenience.  
   * Staff (Dropdown, Tom Select) listing supervisors from the active school.  
   * Cancel (Button)  
   * Save (Button)  
3. Notes:  
   * ID is not an input field.  
   * Autofill should trim values; majors are case-insensitive but stored as given.  
   * Prevent duplicate majors within the same school (enforce before submit).  
   * The dropdown should show supervisor name and optional department.

---

## Read
* No dedicated read page; information is surfaced on the list table and on related modules (Applications/PDF).

---

## Update – `/{school_code}/major-contacts/[id]/update/`
1. Page title: **Update Major Contact**.  
2. Inputs mirror the Create form with defaults prefilled.  
3. Notes:  
   * Changing the major re-validates the unique-per-school constraint.  
   * Switching the supervisor is allowed as long as the supervisor belongs to the same school.  
4. **Cancel** returns to the list.  
5. **Save** stores the changes.  

---

## Delete
* Triggered via the list. Show a confirmation dialog (`Delete this contact?`).
* Removing a contact should not cascade to students, but Applications will be blocked from targeting majors without a contact.

---

## Validation Summary
* **Major**: required, string, max 150, trimmed; must be unique per school after slugification.  
* **Supervisor**: required; must reference an existing supervisor within the active school.  
* Auto-generate / update `major_slug` via `Str::slug` for comparisons.  

---

## Database Notes
* Table: `app.major_staff_assignments` (`id`, `school_id`, `supervisor_id`, `major`, `major_slug`, timestamps).  
* Unique index: `(school_id, major_slug)` enforces one contact per major per school.  
* Trigger `trg_major_staff_assignments_updated_at` maintains `updated_at`.  
* View: `major_staff_details_view` joins supervisors for list/read operations.  

---

Module ensures every major used in Applications has an associated staff contact, enabling dynamic letter generation.

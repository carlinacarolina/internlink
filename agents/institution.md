# agents/institution.md

CRUD Institution is used to perform operations on the **institution** table, **institution_contact**, and **institution_quotas**.

> Before reading this document, make sure you have already read **AGENTS.md** to understand the context.

---

## Access Rights
* **Create**: Only the student role cannot do this.
* **Read**: Only students and the students themselves can view their own internship institution data, provided that the students have the application data; Roles above Student can perform full Read operations.
* **Update**: Only the student role cannot do this.
* **Delete**: Only the student role cannot do this.

---

## List – `/{school_code}/institutions/`

1. Page title: **Institutions**.  

2. **Button filter**
   * This button only appears when applying filters.
   * The number of buttons corresponds to how many filters are applied.
   * Button format: “{filter name}: {filter value}”

3. **Search Input**  
   * Search records based on all columns displayed in the table (no 10-record limit).  
   * Search executes only after the user submits the form via the **Search** button or pressing Enter.  
   * Changing the input alone does not trigger a search.

3. **Filter** (sidebar opens from the right after clicking the filter button):  
   * Title: **Filter Inbstitutions**  
   * **X** button to close the sidebar  
   * Inputs:  
     * Name (text)
     * Address (textArea)
     * City (Dropdown) (Tom Select) 
     * Province (Dropdown) (Tom Select)
     * Website (Text)
     * Industry (Dropdown) (Tom Select)
     * Have Notes? (radio: True / False / Any)  
     * Have Photo? (radio: True / False / Any)  
     * Contact Name (text)
     * Contact E-Mail (email)  
     * Contact Phone (number)  
     * Contact Position (text) 
     * Contact Is Primary? (radio: True / False / Any)
     * Period Year (date) (Year only)  
     * Period Term (number)  
     * Quota (number)  
     * Quota Used (number)  
   * **Reset** button to clear filters  
   * **Apply** button to apply filters  
   * Note: Filters can be combined for more specific search results.  

4. **Table** with columns: Name, City, Province, Industry, Contact Name, Contact E-Mail, Contact Phone, Contact Position, Period Year, Period Term, Quota, Quota Used.

Notes: Anticipate if the table width exceeds the screen width due to its content. By adding a horizontal scroll bar below the table if it exceeds the screen width. Don't force the table to be long and wide explicitly, but adjust it to the content.

5. Display **10 records per page**, with **Next** and **Back** navigation.  

6. Display the total number of Institutions.  

7. Display page information in the format: `Page X out of N` (X = current page, N = total pages).  

---

## Create – `/{school_code}/institutions/create/`

1. Page title: **Create Institution**.  

2. Inputs:  
     * Name (text)
     * Address (text)
     * City (Dropdown) (Tom Select) 
     * Province (Dropdown) (Tom Select)
     * Website (Text)
     * Industry (Dropdown) (Tom Select)
     * Notes (TextArea)  
     * Photo (text)  
     * Contact Name (text)
     * Contact E-Mail (email)  
     * Contact Phone (number)  
     * Contact Position (text) 
     * Contact Is Primary? (radio: True / False / Any)
     * Period (Dropdown) (Tom Select) (format: "{year}: {term}") with a **Create new period** button beside it. Clicking the button hides the dropdown and reveals the inputs below.
     * New Period Year (number) (Displayed only after pressing **Create new period**)
     * New Period Term (number) (Displayed only after pressing **Create new period**)
     * Quota (number)

3. Notes:  
   * ID is not an input field.  
   * Institution_ID is not an input field.
   * If the Year + Term Period combination does not yet exist in the Period table → create a new record, then link with its ID. If it already exists → just link to the existing ID.  
   * Used is assigned automatically.  

4. **Cancel** button to go back.  

5. **Save** button to store the new data.  

---

## Read – `/{school_code}/institutions/[id]/read/`

Institution details are displayed as:  
* Photo: {value}  
* Name: {value}  
* Address: {value}  
* City: {value}  
* Province: {value}
* Website: {value}  
* Industry: {value}  
* Notes: {value}  
* Contact Name: {value}  
* Contact E-Mail: {value}  
* Contact Phone: {value}
* Contact Position: {value}
* Contact Is Primary? {value}
* Period Year {value}
* Period Term {value}  
* Quota {value}
* Used {value}
---

## Update – `/{school_code}/institutions/[id]/update/`

1. Page title: **Update Institution**.  

2. Inputs:  
     * Name (text) (Disabled)
     * Address (text)
     * City (Dropdown) (Tom Select) 
     * Province (Dropdown) (Tom Select)
     * Website (Text)
     * Industry (Dropdown) (Tom Select)
     * Notes (TextArea)  
     * Photo (text)  
     * Contact Name (text)
     * Contact E-Mail (email)  
     * Contact Phone (number)  
     * Contact Position (text) 
     * Contact Is Primary? (radio: True / False / Any)
     * Period (Dropdown) (Tom Select) (format: "{year}: {term}") with a **Create new period** button beside it. Clicking the button hides the dropdown and reveals the inputs below.
     * New Period Year (number) (Displayed only after pressing **Create new period**)
     * New Period Term (number) (Displayed only after pressing **Create new period**)
     * Quota (number)

3. Notes:
   * All inputs have default values from the database, except Password.  
   * ID is not an input field.  
   * Institution_ID is not an input field.
   * If the Year + Term Period combination does not yet exist in the Period table → create a new record, then link with its ID. If it already exists → just link to the existing ID.
   * Used is assigned automatically.  

4. **Cancel** button to go back.  

5. **Save** button to store the changes.  

---

## Delete

Delete records using the **Delete** button in the table at the `/{school_code}/institutions/` endpoint.  

---

## Validation Summary

- Institution `name` is required and must be unique within the school (`uq_institutions_school_name`).  
- `industry` is required; `address`, `city`, `province`, `website`, `notes`, and `photo` remain optional text fields (photo capped at 255 characters).  
- Each primary contact entry requires `name`; `email` is optional but if present it must be unique per institution (`institution_id`, `email`).  
- Quota creation requires selecting a valid period; `quota` must be an integer ≥ 0 and each `(institution, period)` pair can exist only once.  
- When defining a new period inline, enforce `year` between 2000–2100 and `term` between 1–4 (see `app.periods` checks).  
- All institution-related records must stay scoped to the active school (`school_id` enforced by foreign keys and application routing).

---

## Database Notes

- Institutions live in `app.institutions` with a mandatory `school_id` referencing `app.schools(id)` after the realm migration (`0001_01_01_000001_create_my_desain.php`, `2024_08_20_000001_add_school_scope.php`).  
- Unique index `uq_institutions_school_name` preserves per-school uniqueness for institution names (`2024_08_20_000001_add_school_scope.php`).  
- Contacts are stored in `app.institution_contacts`; the FK to `app.institutions(id)` cascades deletes, and a unique constraint on `(institution_id, email)` prevents duplicate contact emails (`0001_01_01_000001_create_my_desain.php`).  
- Quotas reside in `app.institution_quotas` with foreign keys to `app.institutions`, `app.periods`, and `app.schools`; trigger `app.validate_quota_not_over()` prevents `used` from exceeding `quota` (`0001_01_01_000001_create_my_desain.php`, `2024_08_20_000001_add_school_scope.php`).  
- Period metadata lives in `app.periods`, now school-scoped via `fk_periods_school` and the unique index `uq_periods_school_year_term` (`2024_08_20_000001_add_school_scope.php`).  
- `institution_details_view` denormalises institution, contact, and latest quota data per school for read endpoints (`2024_08_21_000000_refresh_views_for_school_scope.php`).

---

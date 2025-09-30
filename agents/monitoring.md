# agents/application.md

The CRUD Application is used to perform operations on the **application** table.

> Before reading this document, make sure you have read **AGENTS.md** to understand the context.

---

## Access Rights
* **Create**: The Student role can only create an application for themselves if and only if he does not have other application data. Higher roles can perform full Create operations.
* **Read**: The Student role can only view their own application. Higher roles can perform full Read operations.  
* **Update**: The Student role can only update if the *Student Access* column is set to **True**. Higher roles can perform full Update operations.  
* **Delete**: The Student role can only delete if the *Student Access* column is set to **True**.. Higher roles can perform full Delete operations.  

---

## List – `/{school_code}/applications/`
1. Page title: **Applications**

2. **Search Input**  
   * Search across all displayed table columns (no 10-record limit).  
   * Search executes only after the user submits the form via the **Search** button or pressing Enter.  
   * Changing the input alone does not trigger a search.

3. **Button filter**
   * This button only appears when applying filters.
   * The number of buttons corresponds to how many filters are applied.
   * Button format: “{filter name}: {filter value}”

4. **Filter** (sidebar opens from the right when the filter button is clicked)  
   * Title: **Filter Applications**  
   * **X** button to close the sidebar  
   * Inputs:  
     * Student Name (Text)  
     * Institution Name (Text)  
     * Period (Dropdown) (Tom Select) (format: "{year}: {term}")
     * Status Application (Dropdown, no Tom Select)  
     * Student Access (Radio: True / False / Any)  
     * Submitted At (Date)  
     * Have Notes? (Radio: True / False / Any)  
   * **Reset** button to clear filters  
   * **Apply** button to apply filters  
   * Note: Filters can be combined for more specific searches.  

5. **Table** columns: Student Name, Institution Name, Year, Term, Status Application, Student Access, Submitted At.  
   * Anticipate table width exceeding screen size → add horizontal scroll.  
   * Do not force table to stretch; adjust only as needed to fit content.  

6. Show **10 records per page** with **Next** and **Back** navigation.

7. Show the total number of applications.

8. Show page info in the format: `Page X out of N` (X = current page, N = total pages).  

---

## Create – `/{school_code}/applications/create/`
1. Page title: **Create Application**  
2. Inputs:  
   * Student Name (Dropdown, Tom Select)
   * "+" (Button) to add a new Student Name
   * Additional Student Name (Dropdown, Tom Select, appears after pressing +)
   * Apply to all students who do not yet have the application (Checkbox)
   * Institution Name (Dropdown, Tom Select)  
   * Period (Dropdown) (Tom Select) (format: "{year}: {term}" – show only after Institution is chosen and list periods linked to that institution)
   * Status Application (Dropdown, no Tom Select)
   * Student Access (Radio: True / False / Any) (Display the input only if the role is not a student)
   * Submitted At (Date)
   * Notes (Textarea)
   * Cancel (Button)
   * Save (Button)

3. Notes:  
   * Student Name input shows the name, not the ID. Database still stores the ID.  
   * Period shows the year and term, not the ID, Database still stores the ID.
   * Additional Student Name dropdown, make sure that the selected name does not appear again in the dropdown menu.
   * Institution Name works the same way.
   * Period dropdown remains hidden until Institution is selected and must only list periods that belong to the chosen institution (based on linked quotas).

4. **Cancel** button navigates back.  
5. **Save** button stores the new data.  

---

## Read – `/{school_code}/applications/[id]/read/`
Application details displayed as:  
* Student Photo
* Student Name (click → `/{school_code}/students/[id]/read/`)  
* Student Email
* Student Phone  
* Student Number  
* National Student Number  
* Student Major  
* Student Class  
* Student Batch  
* Student Notes  
* Institution Photo  
* Institution Name (click → `/{school_code}/institutions/[id]/read/`)  
* Institution Address  
* Institution City  
* Institution Province  
* Institution Website  
* Institution Industry  
* Institution Notes  
* Institution Contact Name  
* Institution Contact Email  
* Institution Contact Phone  
* Institution Contact Position  
* Institution Contact Primary  
* Institution Quota  
* Institution Quota Used  
* Institution Quota Period Year  
* Institution Quota Period Term  
* Institution Quota Notes  
* Period Year  
* Period Term  
* Status Application  
* Student Access  
* Submitted At  
* Notes  

### PDF Utilities
* Detail page provides a `Download PDF` button that performs a direct file download via `/{school_code}/applications/{id}/pdf/print`.
* The printable layout is generated from the shared Tailwind template used by both the inline PDF endpoint and the download, ensuring visual parity.
* Access `/{school_code}/applications/{id}/pdf` to inspect the generated PDF in the browser viewer.

---

## Update – `/{school_code}/applications/[id]/update/`
1. Page title: **Update Application**  
2. Inputs:  
   * Student Name (Dropdown, Tom Select – disabled, default from database)
   * "+" (Button) to add a new Student Name
   * Additional Student Name (Dropdown, Tom Select, appears after pressing +)
   * Apply to all applications with the same institution (Checkbox)
   * Institution Name (Dropdown, Tom Select)
   * Period (Dropdown) (Tom Select) (format: "{year}: {term}" – show only after Institution is chosen and list periods linked to that institution)
   * Status Application (Dropdown, no Tom Select)
   * Student Access (Radio: True / False / Any) (Display the input only if the role is not a student)
   * Submitted At (Date)
   * Notes (Textarea)
   * Cancel (Button)
   * Save (Button)

3. Notes:  
   * Student Name & Institution Name are displayed as names; the database still stores IDs.
   * All inputs load default values from the database.
   * Additional Student Name dropdown only shows students from the same institution as the first Student Name and make sure that the selected name does not appear again in the dropdown menu.
   * The checkbox automatically adds all Student Names from the same institution.
   * Do not display Student Names already selected in other inputs.
   * If all Student Names from the institution are already selected → the + button becomes disabled.
   * Period shows the year and term, not the ID, Database still stores the ID.
   * Period dropdown stays hidden until an institution is selected and must only list periods available for that institution.

4. **Cancel** button navigates back.  
5. **Save** button stores changes.  

---

## Delete
Delete records through the **Delete** button in the table at `/{school_code}/applications/`.  

---

## Validation Summary

- `internship_id` is required and must reference an internship that belongs to the active realm; align the submitted `school_id` with the internship or the FK will reject the insert.  
- `supervisor_id` is optional, but if provided it must point to a supervisor inside the same school to avoid cross-realm joins in the views.  
- `title` is optional yet capped at 150 characters; trim or otherwise guard long inputs before hitting the database.  
- `log_date` defaults to `CURRENT_DATE` but remains mandatory; block empty values and prevent future dates if the UX disallows them.  
- `content` cannot be blank because the column is `NOT NULL`; run presence validation and strip accidental whitespace-only payloads.  
- `type` must be one of `weekly`, `issue`, `final`, or `other` as defined by `monitor_type_enum`; any other value fails before the insert/update completes.

---

## Database Notes

- Monitoring logs live in `app.monitoring_logs` with foreign keys to `app.internships`, `app.supervisors`, and `app.schools`; deleting an internship cascades to its logs, while supervisors are nullified on delete (`0001_01_01_000001_create_my_desain.php`, `2024_08_20_000001_add_school_scope.php`).  
- `log_date` carries a `CURRENT_DATE` default and combined indexes on `(internship_id, log_date)` and `(supervisor_id, log_date)` keep list filters fast (`0001_01_01_000001_create_my_desain.php`).  
- `trg_monitoring_logs_updated_at` uses `app.set_updated_at()` to maintain the audit timestamp on every update (`0001_01_01_000001_create_my_desain.php`).  
- Reads should pull from `v_monitoring_log_summary` or `v_monitoring_log_detail`, both of which join student, institution, and application context while enforcing `school_id` alignment (`2024_08_21_000000_refresh_views_for_school_scope.php`).  
- Log types come from the shared `public.monitor_type_enum`, so any enum changes require migrating both the base type and dependent code paths (`0001_01_01_000000_initial_schema.php`).

---

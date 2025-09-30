# agents/supervisor.md 

CRUD Supervisor is used to perform operations on the **user** table with the **Supervisor** role, along with the **supervisor** table. The **supervisor** table is therefore highly dependent on the **user** table.

> Before reading this document, make sure you have already read **AGENTS.md** to understand the context.

---

## Access Rights
* **Create**: Only roles above Supervisor can perform full Create operations.  
* **Read**: Only the Supervisor role and the supervisor themselves can view their own data; they cannot view other Supervisors. Roles above Supervisor can perform full Read operations.  
* **Update**: Only the Supervisor role and the supervisor themselves can update their own data; they cannot update other Supervisors. Roles above Supervisor can perform full Update operations.  
* **Delete**: Only the Supervisor role and the supervisor themselves can delete their own data; they cannot delete other Supervisors. Roles above Supervisor can perform full Delete operations.  

---

## List – `/{school_code}/supervisors/`

1. Page title: **Supervisors**.  

2. **Search Input**  
   * Search across all displayed table columns (no 10-record limit).  
   * Search executes only after the user submits the form via the **Search** button or pressing Enter.  
   * Changing the input alone does not trigger a search.

3. **Button filter**
   * This button only appears when applying filters.
   * The number of buttons corresponds to how many filters are applied.
   * Button format: “{filter name}: {filter value}”

4. **Filter** (sidebar opens from the right after clicking the filter button):  
   * Title: **Filter Supervisors**  
   * **X** button to close the sidebar  
   * Inputs:  
     * Name (text)  
     * Email (text)  
     * Phone (text)  
     * Is Email Verified? (radio: True / False / Any)  
     * Email Verified At (date)  
     * Department (text)  
     * Have notes? (radio: True / False / Any)  
     * Have photo? (radio: True / False / Any)  
   * **Reset** button to clear filters  
   * **Apply** button to apply filters  
   * Note: Filters can be combined for more specific search results.  

5. **Table** with columns: Name, Email, Phone, Department.  
Notes: Anticipate if the table width exceeds the screen width due to its content. By adding a horizontal scroll bar below the table if it exceeds the screen width. Don't force the table to be long and wide explicitly, but adjust it to the content.

6. Display **10 records per page**, with **Next** and **Back** navigation.  

7. Display the total number of supervisors.  

8. Display page information in the format: `Page X out of N` (X = current page, N = total pages).  

---

## Create – `/{school_code}/supervisors/create/`

1. Page title: **Create Supervisor**.  

2. Inputs:  
   * Name (Text)  
   * Email (Email)  
   * Phone (Number)  
   * Password (Password)  
   * Supervisor Number (Number)  
   * Department (Text)  
   * Notes (TextArea)  
   * Photo (Text)  

3. Notes:  
   * ID is not an input field.  
   * User ID is not an input field.  
   * Role is assigned automatically.  
   * `email_verified_at` is still TBD.  

4. **Cancel** button to go back.  

5. **Save** button to store the new data.  

---

## Read – `/{school_code}/supervisors/[id]/read/`

Supervisor details are displayed as:  
* Photo: {value}  
* Name: {value}  
* Email: {value}  
* Phone: {value}  
* Email Verified At: {value} (if empty, display **False**)  
* Supervisor Number: {value}  
* Department: {value}  
* Notes: {value}  

---

## Update – `/{school_code}/supervisors/[id]/update/`

1. Page title: **Update Supervisor**.  

2. Inputs:  
   * Name (Text)  
   * Email (Email)  
   * Phone (Number)  
   * Password (Password)  
   * Supervisor Number (Number)  
   * Department (Text)  
   * Notes (TextArea)  
   * Photo (Text)  

3. Notes:  
   * ID is not an input field.  
   * User ID is not an input field.  
   * Role is assigned automatically.  
   * `email_verified_at` is still TBD.  
   * All inputs have default values from the database, except Password.  
   * If Password is not changed, the old value is not overwritten.  

4. **Cancel** button to go back.  

5. **Save** button to store the changes.  

---

## Delete

Delete records using the **Delete** button in the table at the `/{school_code}/supervisors/` endpoint.  

---

## Validation Summary

- `name` is required (stored as `varchar(255)` in `core.users`).  
- `email` is required and must stay unique within the same school because the column uses `citext` combined with `uq_users_school_email`.  
- `password` is required on create and may remain unchanged on update when the field is empty.  
- `phone` is optional but limited to 15 characters.  
- `supervisor_number` is required and must stay unique per school (`uq_supervisors_school_number`).  
- `department` is required (`varchar(100)`), while `notes` and `photo` are optional text fields.  
- Supervisor accounts must always be tied to the active school realm; the role is locked to `supervisor` by database trigger.

---

## Database Notes

- Identity data lives in `core.users` with `role = 'supervisor'` and non-null `school_id` referencing `app.schools(id)` (`0001_01_01_000000_initial_schema.php`, `2024_08_20_000001_add_school_scope.php`).  
- Profile data is stored in `app.supervisors` with foreign keys to both `core.users(id)` and `app.schools(id)`; `school_id` became mandatory in the school scope migration (`2024_08_20_000001_add_school_scope.php`).  
- Unique index `uq_supervisors_school_number` prevents duplicate supervisor numbers within a school (`2024_08_20_000001_add_school_scope.php`).  
- Trigger `trg_supervisors_role` calls `app.enforce_role_supervisor()` so the linked user must stay in the supervisor role, and `trg_supervisors_updated_at` keeps timestamps fresh (`0001_01_01_000001_create_my_desain.php`).  
- `supervisor_details_view` surfaces school, user, and profile fields for list/read actions with the already-joined data (`2024_08_21_000000_refresh_views_for_school_scope.php`).

---
 

# agents/developer.md

CRUD Developer is used to perform operations on the **user** table with the **Developer** role.

> Before reading this document, make sure you have already read **AGENTS.md** to understand the context.

---

## Access Rights

* **Create**: No role can create.  
* **Read**: A developer can only view their own data.  
* **Update**: A developer can only update their own data.  
* **Delete**: A developer can only delete their own data.  

---

## List – `/developers/`

1. Page title: **Developers**.

2. **Search Input**  
   * Search across all displayed table columns (no 10-record limit).  
   * Search executes only after the user submits the form via the **Search** button or pressing Enter.  
   * Changing the input alone does not trigger a search.

3. **Button filter**
   * This button only appears when applying filters.
   * The number of buttons corresponds to how many filters are applied.
   * Button format: “{filter name}: {filter value}”

4. **Filter** (sidebar opens from the right after clicking the filter button):  
   * Title: **Filter Developer**  
   * **X** button to close the sidebar  
   * Inputs:  
     * Name (text)  
     * Email (text)  
     * Phone (text)  
     * Is Email Verified? (radio: True / False / Any)  
     * Email Verified At (date)  
   * **Reset** button to clear filters  
   * **Apply** button to apply filters  
   * Note: Filters can be combined for more specific search results.  

5. **Table** with columns: Name, Email, Phone.  
Notes: Anticipate if the table width exceeds the screen width due to its content. By adding a horizontal scroll bar below the table if it exceeds the screen width. Don't force the table to be long and wide explicitly, but adjust it to the content.

6. Display **10 records per page**, with **Next** and **Back** navigation.  

7. Display the total number of developers.  

8. Display page information in the format: `Page X out of N` (X = current page, N = total pages).  

---

## Create – `/developers/create/`

1. Page title: **Create Developer**.  

2. Inputs:  
   * Name (Text)  
   * Email (Email)  
   * Phone (Number)  
   * Password (Password)  

3. Notes:  
   * ID is not an input field.  
   * Role is assigned automatically.  
   * `email_verified_at` is still TBD.  

4. **Cancel** button to go back.  

5. **Save** button to store the new data.  

---

## Read – `/developers/[id]/read/`

Developer details are displayed as:  
* Name: {value}  
* Email: {value}  
* Phone: {value}  
* Email Verified At: {value} (if empty, display **False**)  

---

## Update – `/developers/[id]/update/`

1. Page title: **Update Developer**.  

2. Inputs:  
   * Name (Text)  
   * Email (Email)  
   * Phone (Number)  
   * Password (Password)  

3. Notes:  
   * ID is not an input field.  
   * Role is assigned automatically.  
   * `email_verified_at` is still TBD.  
   * All inputs have default values from the database, except Password.  
   * If Password is not changed, the old value is not overwritten.  

4. **Cancel** button to go back.  

5. **Save** button to store the changes.  

---

## Delete

Delete records using the **Delete** button in the table at the `/developers/` endpoint.  

---

## Validation Summary

- `name` is required and stored in a `varchar(255)` column; reject empty values.  
- `email` is required, must be unique among developers, and uses case-insensitive comparisons because the column type is `citext`.  
- `password` is required on create but may stay unchanged on update when the field is left blank.  
- `phone` is optional; when present it must fit within 15 characters.  
- The role is auto-forced to developer and developers must not be linked to any school (`school_id` is enforced to stay null).

---

## Database Notes

- Rows live in `core.users` with `role = 'developer'`; the `chk_users_school_presence` check constraint keeps `school_id` null for this role (`0001_01_01_000000_initial_schema.php`).  
- Unique index `uq_users_email_developer` guarantees developer emails are unique regardless of casing (`0001_01_01_000000_initial_schema.php`).  
- Trigger `trg_core_users_updated_at` refreshes `updated_at` timestamps on every change (`0001_01_01_000000_initial_schema.php`).  
- `developer_details_view` exposes the id, name, contact info, verification timestamp, and audit fields for reads (`2024_07_18_120000_refresh_developer_details_view.php`).

---

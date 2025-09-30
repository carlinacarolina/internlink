# agents/student.md

CRUD Student is used to perform operations on the **user** table with the **Student** role, along with the **student** table. The **student** table is therefore highly dependent on the **user** table.

> Before reading this document, make sure you have already read **AGENTS.md** to understand the context.

---

## Access Rights
* **Create**: Only roles above Student can perform full Create operations.  
* **Read**: Only the Student role and the student themselves can view their own data; they cannot view other Students. Roles above Student can perform full Read operations.  
* **Update**: Only the Student role and the student themselves can update their own data; they cannot update other Students. Roles above Student can perform full Update operations.  
* **Delete**: Only the Student role and the student themselves can delete their own data; they cannot delete other Students. Roles above Student can perform full Delete operations.  

---

## List – `/{school_code}/students/`

1. Page title: **Students**.  

2. **Button filter**
   * This button only appears when applying filters.
   * The number of buttons corresponds to how many filters are applied.
   * Button format: “{filter name}: {filter value}”

3. **Search Input**  
   * Search records based on all columns displayed in the table (no 10-record limit).  
   * Search executes only after the user submits the form via the **Search** button or pressing Enter.  
   * Changing the input alone does not trigger a search.

3. **Filter** (sidebar opens from the right after clicking the filter button):  
   * Title: **Filter Students**  
   * **X** button to close the sidebar  
   * Inputs:  
     * Name (text)  
     * Email (text)  
     * Phone (text)  
     * Is Email Verified? (radio: True / False / Any)  
     * Email Verified At (date)  
     * Student Number (number)  
     * National Student Number (number)  
     * Major (text)  
     * Class (text)  
     * Batch (date) (Year only)  
     * Have notes? (radio: True / False / Any)  
     * Have photo? (radio: True / False / Any)  
   * **Reset** button to clear filters  
   * **Apply** button to apply filters  
   * Note: Filters can be combined for more specific search results.  

4. **Table** with columns: Name, Email, Phone, NIS, NISN, Major, Class, Batch.  
Notes: Anticipate if the table width exceeds the screen width due to its content. By adding a horizontal scroll bar below the table if it exceeds the screen width. Don't force the table to be long and wide explicitly, but adjust it to the content.

5. Display **10 records per page**, with **Next** and **Back** navigation.  

6. Display the total number of students.  

7. Display page information in the format: `Page X out of N` (X = current page, N = total pages).  

---

## Create – `/{school_code}/students/create/`

1. Page title: **Create Student**.  

2. Inputs:  
   * Name (Text)  
   * Email (Email)  
   * Phone (Number)  
   * Password (Password)  
   * Student Number (Number)  
   * National Student Number (Number)  
   * Major (Text)  
   * Class (Text)  
   * Batch (Date) (Year only)  
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

## Read – `/{school_code}/students/[id]/read/`

Student details are displayed as:  
* Photo: {value}  
* Name: {value}  
* Email: {value}  
* Phone: {value}  
* Email Verified At: {value} (if empty, display **False**)  
* Student Number: {value}  
* National Student Number: {value}  
* Major: {value}  
* Class: {value}  
* Batch: {value}  
* Notes: {value}  

---

## Update – `/{school_code}/students/[id]/update/`

1. Page title: **Update Student**.  

2. Inputs:  
   * Name (Text)  
   * Email (Email)  
   * Phone (Number)  
   * Password (Password)  
   * Student Number (Number)  
   * National Student Number (Number)  
   * Major (Text)  
   * Class (Text)  
   * Batch (Date) (Year only)  
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

Delete records using the **Delete** button in the table at the `/{school_code}/students/` endpoint.  

---

## Validation Summary

- `name` is required (`varchar(255)` in `core.users`).  
- `email` is required, case-insensitively unique per school, and must stay within email formatting rules.  
- `password` is required on create; on update it can remain unchanged if the field is empty.  
- `phone` is optional but capped at 15 characters.  
- `student_number` (NIS) and `national_sn` (NISN) are required and each must remain unique inside the school (`uq_students_school_student_number`, `uq_students_school_national_sn`).  
- `major`, `class`, and `batch` are required profile fields (`varchar(100)`, `varchar(100)`, and `varchar(9)` respectively).  
- `notes` and `photo` are optional text fields.  
- Student accounts must stay attached to the active school realm, and the linked user role must always be `student`.

---

## Database Notes

- Identity data lives in `core.users` with `role = 'student'` and non-null `school_id` pointing to `app.schools(id)` (`0001_01_01_000000_initial_schema.php`).  
- Profile data is stored in `app.students`, referencing both `core.users(id)` and `app.schools(id)`; the school scope migration added the `school_id` column and per-school unique indexes (`2024_08_20_000001_add_school_scope.php`).  
- Trigger `trg_students_role` enforces that the linked user keeps the student role, while `trg_students_updated_at` maintains timestamps (`0001_01_01_000001_create_my_desain.php`).  
- `student_details_view` combines user and student columns (including `school_id`) for read/list operations (`2024_08_21_000000_refresh_views_for_school_scope.php`).

---

# agents/school.md

CRUD School is used to manage rows in the **schools** table and the **school_details_view** view. Use this document to keep the UI and behaviours aligned with InternLink standards.

> Read **AGENTS.md** first to understand the overall context and global guidelines.

---

## Access Rights
* **Create**: Developer only.  
* **Read**: Developer only.  
* **Update**: Developer only.  
* **Delete**: Developer only.  

---

## List – `/schools/`

1. Page title: **Schools**.  

2. **Search Input**  
   * Searches across Name, Address, Phone, and Email (no 10-record limit).  
   * Search executes only after the user submits the form via the **Search** button or pressing Enter.  
   * Changing the input alone does not trigger a search.  

3. **Create button**  
   * Label: **Create School**.  
   * Positioned beside the search box.  
   * Redirects to `/schools/create`.  

4. **Filter** (sidebar opens from the right after clicking the **Filter** button):  
   * Title: **Filter Schools**.  
   * **X** button to close the sidebar.  
   * Inputs:  
     * Name (text)  
     * Email (text)  
     * Phone (text)  
     * Has Website? (radio: True / False / Any)  
     * Sort By (dropdown) with options:  
       * Newest (`created_at:desc`)  
       * Oldest (`created_at:asc`)  
       * Name A-Z (`name:asc`)  
       * Name Z-A (`name:desc`)  
       * Recently Updated (`updated_at:desc`)  
       * Least Recently Updated (`updated_at:asc`)  
   * **Reset** button to clear filters.  
   * **Apply** button to apply filters.  
   * Applied filters appear as removable chips above the table using the format `{filter name}: {filter value}`.  

5. **Table** with columns: Name, Phone, Email, Website, Updated At, Actions.  
   * Website column shows **Visit** link when available, otherwise `—`.  
   * The table supports horizontal scrolling when content exceeds the viewport width.  
   * Actions include **Realm** to jump into the selected school realm at `/{id}` alongside Read / Update / Delete controls.  

6. Display **10 records per page**, with **Next** and **Back** navigation.  

7. Display the total number of schools and the page indicator in the format `Page X out of N`.  

---

## Create – `/schools/create/`

1. Page title: **Create School**.  

2. Inputs:  
   * School Name (text, max 150 chars)  
   * Address (textarea, max 1000 chars)  
   * Phone (text, accepts digits, spaces, `+`, `(`, `)`, `-`, max 30 chars)  
   * Email (email)  
   * Website (url, optional)
   * Invite Code

3. Notes:  
   * ID is not an input field.  
   * All fields except Website are required.  
   * Phone and Email values must be unique.  
   * School code is generated automatically when the record is saved.  

4. **Cancel** button to go back to the list.  

5. **Save** button to store the new data.  

---

## Read – `/schools/[id]/read/`

School details are displayed as:  
* Name: {value}  
* Invite Code: {value}  
* Address: {value}  
* Phone: {value}  
* Email: {value}  
* Website: {value or `—`}  
* Created At: {value}  
* Updated At: {value}  

Action buttons on this page: **Update** and **Delete**.

---

## Update – `/schools/[id]/update/`

1. Page title: **Update School**.  

2. Inputs are identical to the Create form with default values prefilled from the database.  

3. Notes:  
   * Phone and Email must remain unique (excluding the current record).  
   * ID is not an input field.  
   * School code remains read-only and is not part of the form.  

4. **Cancel** button returns to `/schools/[id]/read`.  

5. **Save** button stores the changes.  

---

## Delete

* Delete through the **Delete** button on the list or detail pages.  
* A confirmation dialog must appear before deletion proceeds.  

---

## Validation Summary

* **Name**: required, string, max 150.  
* **Address**: required, string, max 1000.  
* **Phone**: required, unique, string, max 30, accepts numbers, spaces, `+`, `(`, `)`, `-`.  
* **Email**: required, unique, valid email, max 255.  
* **Website**: optional, valid URL, max 255.  
* **Code**: generated automatically; must remain unique per school.  

---

## Database Notes

* Table: `app.schools` with columns `id`, `code`, `name`, `address`, `phone`, `email`, `website`, `created_at`, `updated_at`.  
* Trigger: `trg_schools_updated_at` keeps `updated_at` in sync.  
* View: `school_details_view` used for all read/list operations and now includes the `code` column.  
* Code, Phone, Email, and Name columns are unique to keep the table in 3NF and avoid duplicate records.  

---

Keep the UI consistent with existing CRUD pages (button styles, spacing, messaging) and follow **agents/security.md** for validation, authorization, and CSRF requirements.

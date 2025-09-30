# agents/auth.md

Auth agents handle the **foundation** flows for Register, Login, and Logout. These processes are global (not realm-prefixed) and keep user sessions aligned with each school's realm routing.

> Read **AGENTS.md** first to understand overall navigation, realm usage, and security expectations.

---

## Access Rights
* **Register (`/signup`)**: Available to prospective Students and Supervisors only. Existing accounts should not re-use this form.
* **Login (`/login`)**: Available to all roles. Developers may access any realm after login via the Schools area; non-developers are routed automatically to their school realm.
* **Logout (`/logout`)**: Available to all authenticated users via POST.

---

## Register – `/signup`

1. Multi-step form with session-backed progress. Step 1 collects:
   * Name (text)
   * Email (email)
   * Password (password, min 8)
   * Phone (numeric)
   * Role (radio/select limited to `student` or `supervisor`)
   * School Code (text, required and matched case-insensitively to `schools.code`)

2. Successful Step 1 validation resolves the school by code and locks the account to that school for the remainder of the flow.

3. Step 2 fields depend on the selected role:
   * **Student**: Student Number, National Student Number, Major, Batch (year), optional Photo URL.
   * **Supervisor**: Supervisor Number, Department, Photo URL.

4. Registration writes both the `users` record and the related `students` or `supervisors` record with the resolved `school_id`. Password hashing is handled via the model cast.

5. After completion the user is automatically logged in and redirected according to their realm (see Session Handling below).

---

## Login – `/login`

1. Form fields:
   * Email (email)
   * Password (password)

2. Login no longer prompts for a School Code. The system reads the account's linked school and stores its `code` in session for realm routing. Developers skip the school lookup and stay in the global area until they choose a realm.

3. Validation failures respond with standard error messaging. Accounts missing a school link surface a descriptive error.

---

## Logout – `/logout`

1. Triggered via POST (CSRF protected) and always redirects to `/login` with a status flash message.

2. Session is invalidated and the token regenerated to satisfy security requirements.

---

## Session Handling & Realm Routing
* Non-developer logins store `school_id` and `school_code` in session, ensuring redirects to `/{school_code}` dashboards and that shared helpers (e.g., `schoolRoute()`) resolve correctly.
* Developers keep their global access and may select any school realm from the Schools list; no `school_code` is forced into their session until they enter a realm.
* Registration immediately seeds the same session keys so first-time users land in their school realm without an extra login.

---

## Validation and Security Notes
* Follow the constraints defined in the controllers (e.g., regex for supervisor numbers, numeric checks for student data).
* Ensure passwords are never exposed in responses or logs.
* Consult `agents/security.md` before altering auth flows—changes must maintain CSRF protection, secure hashing, and proper session regeneration on login.


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW student_details_view AS
            SELECT s.id,
                   s.user_id,
                   s.school_id,
                   u.name,
                   u.email,
                   u.phone,
                   u.email_verified_at,
                   u.role,
                   s.student_number,
                   s.national_sn,
                   s.major_id,
                   sm.name AS major,
                   s.class,
                   s.batch,
                   s.notes,
                   s.photo,
                   s.created_at,
                   s.updated_at
            FROM app.students s
            JOIN core.users u ON u.id = s.user_id
            LEFT JOIN app.school_majors sm ON sm.id = s.major_id;
        SQL);

        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW supervisor_details_view AS
            SELECT s.id,
                   s.user_id,
                   s.school_id,
                   u.name,
                   u.email,
                   u.email_verified_at,
                   u.phone,
                   u.role,
                   s.supervisor_number,
                   s.department_id,
                   sm.name AS department,
                   s.notes,
                   s.photo,
                   s.created_at,
                   s.updated_at
            FROM app.supervisors s
            JOIN core.users u ON s.user_id = u.id
            LEFT JOIN app.school_majors sm ON sm.id = s.department_id;
        SQL);

        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW developer_details_view AS
            SELECT u.id,
                   u.name,
                   u.email,
                   u.phone,
                   u.email_verified_at,
                   u.created_at,
                   u.updated_at
            FROM core.users u
            WHERE u.role = 'developer';
        SQL);

        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW school_details_view AS
            SELECT s.id,
                   s.code,
                   s.name,
                   s.address,
                   s.city,
                   s.postal_code,
                   s.phone,
                   s.email,
                   s.website,
                   s.principal_name,
                   s.principal_nip,
                   s.created_at,
                   s.updated_at
            FROM app.schools s;
        SQL);

        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW institution_details_view AS
            WITH primary_contact AS (
                SELECT DISTINCT ON (institution_id) id, institution_id, name, email, phone, position, is_primary
                FROM app.institution_contacts
                ORDER BY institution_id, is_primary DESC, id
            ),
            latest_quota AS (
                SELECT DISTINCT ON (institution_id)
                       iq.id,
                       iq.institution_id,
                       iq.school_id,
                       iq.quota,
                       iq.used,
                       p.year,
                       p.term
                FROM app.institution_quotas iq
                JOIN app.periods p ON p.id = iq.period_id AND p.school_id = iq.school_id
                ORDER BY iq.institution_id, p.year DESC, p.term DESC, iq.id DESC
            )
            SELECT i.id,
                   i.school_id,
                   i.name,
                   i.address,
                   i.city,
                   i.province,
                   i.website,
                   i.industry_for,
                   sm.name AS industry_for_name,
                   i.notes,
                   i.photo,
                   pc.name AS contact_name,
                   pc.email AS contact_email,
                   pc.phone AS contact_phone,
                   pc.position AS contact_position,
                   pc.is_primary AS contact_primary,
                   lq.year AS period_year,
                   lq.term AS period_term,
                   lq.quota,
                   lq.used
            FROM app.institutions i
            LEFT JOIN app.school_majors sm ON sm.id = i.industry_for
            LEFT JOIN primary_contact pc ON pc.institution_id = i.id
            LEFT JOIN latest_quota lq ON lq.institution_id = i.id AND lq.school_id = i.school_id;
        SQL);

        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW major_staff_details_view AS
            SELECT msa.id,
                   msa.school_id,
                   msa.supervisor_id,
                   msa.major,
                   msa.major_id,
                   sdv.name,
                   sdv.email,
                   sdv.phone,
                   sdv.department_id,
                   sdv.department,
                   sdv.supervisor_number,
                   sdv.created_at,
                   sdv.updated_at
            FROM app.major_staff_assignments msa
            JOIN supervisor_details_view sdv ON sdv.id = msa.supervisor_id;
        SQL);

        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW application_details_view AS
            WITH student_major AS (
                SELECT sdv.id,
                       sdv.user_id,
                       sdv.school_id,
                       sdv.name,
                       sdv.email,
                       sdv.phone,
                       sdv.student_number,
                       sdv.national_sn,
                       sdv.major_id,
                       sdv.major,
                       sdv.class,
                       sdv.batch,
                       sdv.notes,
                       sdv.photo
                FROM student_details_view sdv
            )
            SELECT a.id,
                   a.school_id,
                   a.student_id,
                   sm.user_id                  AS student_user_id,
                   sm.name                     AS student_name,
                   sm.email                    AS student_email,
                   sm.phone                    AS student_phone,
                   sm.student_number,
                   sm.national_sn,
                   sm.major_id                 AS student_major_id,
                   sm.major                    AS student_major,
                   sm.class                    AS student_class,
                   sm.batch                    AS student_batch,
                   sm.notes                    AS student_notes,
                   sm.photo                    AS student_photo,
                   a.institution_id,
                   idv.name                    AS institution_name,
                   idv.address                 AS institution_address,
                   idv.city                    AS institution_city,
                   idv.province                AS institution_province,
                   idv.website                 AS institution_website,
                   idv.industry_for_name        AS institution_industry_for_name,
                   idv.notes                   AS institution_notes,
                   idv.photo                   AS institution_photo,
                   idv.contact_name            AS institution_contact_name,
                   idv.contact_email           AS institution_contact_email,
                   idv.contact_phone           AS institution_contact_phone,
                   idv.contact_position        AS institution_contact_position,
                   idv.contact_primary         AS institution_contact_primary,
                   idv.quota                   AS institution_quota,
                   idv.used                    AS institution_quota_used,
                   idv.period_year             AS institution_quota_period_year,
                   idv.period_term             AS institution_quota_period_term,
                   a.period_id,
                   p.year                      AS period_year,
                   p.term                      AS period_term,
                   a.status,
                   a.student_access,
                   a.submitted_at,
                   a.planned_start_date,
                   a.planned_end_date,
                   a.notes                     AS application_notes,
                   msa.supervisor_id           AS staff_supervisor_id,
                   sdv_staff.name              AS staff_name,
                   sdv_staff.email             AS staff_email,
                   sdv_staff.phone             AS staff_phone,
                   sdv_staff.department        AS staff_department,
                   sdv_staff.supervisor_number AS staff_supervisor_number,
                   a.created_at,
                   a.updated_at
            FROM app.applications a
            JOIN student_major sm ON sm.id = a.student_id AND sm.school_id = a.school_id
            JOIN institution_details_view idv  ON idv.id = a.institution_id AND idv.school_id = a.school_id
            JOIN app.periods p                 ON p.id = a.period_id AND p.school_id = a.school_id
            LEFT JOIN app.major_staff_assignments msa ON msa.school_id = a.school_id AND msa.major_id = sm.major_id
            LEFT JOIN supervisor_details_view sdv_staff ON sdv_staff.id = msa.supervisor_id AND sdv_staff.school_id = a.school_id;
        SQL);

        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW internship_details_view AS
            SELECT it.id,
                   it.school_id,
                   it.application_id,
                   it.student_id,
                   u.name AS student_name,
                   it.institution_id,
                   i.name AS institution_name,
                   it.period_id,
                   p.year AS period_year,
                   p.term AS period_term,
                   it.start_date,
                   it.end_date,
                   it.status,
                   it.created_at,
                   it.updated_at
            FROM app.internships it
            JOIN app.students s ON s.id = it.student_id AND s.school_id = it.school_id
            JOIN core.users u ON u.id = s.user_id
            JOIN app.institutions i ON i.id = it.institution_id AND i.school_id = it.school_id
            JOIN app.periods p ON p.id = it.period_id AND p.school_id = it.school_id;
        SQL);

        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW v_monitoring_log_summary AS
            SELECT
              ml.id                    AS monitoring_log_id,
              ml.school_id,
              ml.log_date,
              ml.type                  AS log_type,
              COALESCE(ml.title, NULL) AS title,
              ml.content,
              ml.supervisor_id,
              it.id                    AS internship_id,
              it.student_id,
              inst.id                  AS institution_id,
              sd.name                  AS student_name,
              inst.name                AS institution_name,
              usv.name                 AS supervisor_name,
              p.year                   AS period_year,
              p.term                   AS period_term,
              it.status                AS internship_status,
              ml.created_at,
              ml.updated_at
            FROM app.monitoring_logs ml
            JOIN app.internships it       ON it.id = ml.internship_id AND it.school_id = ml.school_id
            JOIN student_details_view sd   ON sd.id = it.student_id AND sd.school_id = ml.school_id
            JOIN app.institutions inst    ON inst.id = it.institution_id AND inst.school_id = ml.school_id
            JOIN app.periods p            ON p.id = it.period_id AND p.school_id = ml.school_id
            LEFT JOIN app.supervisors sv  ON sv.id = ml.supervisor_id AND sv.school_id = ml.school_id
            LEFT JOIN core.users usv       ON usv.id = sv.user_id;
        SQL);

        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW v_monitoring_log_detail AS
            WITH primary_contact AS (
                SELECT DISTINCT ON (institution_id) id, institution_id, name, email, phone, position, is_primary
                FROM app.institution_contacts
                ORDER BY institution_id, is_primary DESC, id
            ),
            latest_quota AS (
                SELECT DISTINCT ON (institution_id)
                       iq.institution_id,
                       iq.school_id,
                       iq.quota,
                       iq.used,
                       NULL::text AS notes,
                       p.year,
                       p.term
                FROM app.institution_quotas iq
                JOIN app.periods p ON p.id = iq.period_id AND p.school_id = iq.school_id
                ORDER BY iq.institution_id, p.year DESC, p.term DESC, iq.id DESC
            ),
            application_data AS (
                SELECT a.id,
                       a.school_id,
                       a.student_id,
                       a.institution_id,
                       a.period_id,
                       a.status,
                       a.student_access,
                       a.submitted_at,
                       a.notes,
                       p.year AS period_year,
                       p.term AS period_term
                FROM app.applications a
                LEFT JOIN app.periods p ON p.id = a.period_id AND p.school_id = a.school_id
            )
            SELECT
              ml.id            AS monitoring_log_id,
              ml.school_id,
              ml.log_date,
              ml.type          AS log_type,
              ml.title,
              ml.content,
              ml.supervisor_id,
              it.id            AS internship_id,
              it.status        AS internship_status,
              it.start_date    AS internship_start_date,
              it.end_date      AS internship_end_date,
              p.year           AS internship_period_year,
              p.term           AS internship_period_term,
              sd.id            AS student_id,
              sd.name          AS student_name,
              sd.email         AS student_email,
              sd.phone         AS student_phone,
              sd.student_number,
              sd.national_sn,
              sd.major         AS student_major,
              sd.class         AS student_class,
              sd.batch         AS student_batch,
              sd.notes         AS student_notes,
              sd.photo         AS student_photo,
              inst.id          AS institution_id,
              inst.name        AS institution_name,
              inst.address     AS institution_address,
              inst.city        AS institution_city,
              inst.province    AS institution_province,
              inst.website     AS institution_website,
              sm.name AS institution_industry_for_name,
              inst.notes       AS institution_notes,
              inst.photo       AS institution_photo,
              pc.name          AS institution_contact_name,
              pc.email         AS institution_contact_email,
              pc.phone         AS institution_contact_phone,
              pc.position      AS institution_contact_position,
              pc.is_primary    AS institution_contact_primary,
              lq.quota         AS institution_quota,
              lq.used          AS institution_quota_used,
              lq.year          AS institution_quota_period_year,
              lq.term          AS institution_quota_period_term,
              lq.notes         AS institution_quota_notes,
              app.period_year  AS application_period_year,
              app.period_term  AS application_period_term,
              app.status       AS application_status,
              app.student_access AS application_student_access,
              app.submitted_at AS application_submitted_at,
              app.notes        AS application_notes
            FROM app.monitoring_logs ml
            JOIN app.internships it       ON it.id = ml.internship_id AND it.school_id = ml.school_id
            JOIN student_details_view sd   ON sd.id = it.student_id AND sd.school_id = ml.school_id
            JOIN app.institutions inst    ON inst.id = it.institution_id AND inst.school_id = ml.school_id
            LEFT JOIN app.school_majors sm ON sm.id = inst.industry_for
            LEFT JOIN primary_contact pc  ON pc.institution_id = inst.id
            LEFT JOIN latest_quota lq     ON lq.institution_id = inst.id AND lq.school_id = ml.school_id
            LEFT JOIN app.periods p       ON p.id = it.period_id AND p.school_id = ml.school_id
            LEFT JOIN application_data app ON app.id = it.application_id AND app.school_id = ml.school_id;
        SQL);

        DB::statement(<<<'SQL'
        CREATE OR REPLACE VIEW v_application_summary AS
        SELECT a.id AS application_id,
               a.school_id,
               s.id AS student_id,
               u.name AS student_name,
               i.name AS institution_name,
               p.year AS period_year,
               p.term AS period_term,
               a.status,
               a.submitted_at
        FROM app.applications a
        JOIN app.students s   ON s.id = a.student_id AND s.school_id = a.school_id
        JOIN core.users u      ON u.id = s.user_id
        JOIN app.institutions i ON i.id = a.institution_id AND i.school_id = a.school_id
        JOIN app.periods p    ON p.id = a.period_id AND p.school_id = a.school_id;
        SQL);

        DB::statement(<<<'SQL'
        CREATE OR REPLACE VIEW v_internship_detail AS
        SELECT it.id AS internship_id,
               it.school_id,
               it.status AS internship_status,
               it.start_date,
               it.end_date,
               u.name AS student_name,
               i.name AS institution_name,
               p.year AS period_year,
               p.term AS period_term,
               usv.name AS primary_supervisor_name
        FROM app.internships it
        JOIN app.students s       ON s.id = it.student_id AND s.school_id = it.school_id
        JOIN core.users u          ON u.id = s.user_id
        JOIN app.institutions i   ON i.id = it.institution_id AND i.school_id = it.school_id
        JOIN app.periods p        ON p.id = it.period_id AND p.school_id = it.school_id
        LEFT JOIN app.internship_supervisors its ON its.internship_id = it.id AND its.is_primary = TRUE
        LEFT JOIN app.supervisors sv     ON sv.id = its.supervisor_id AND sv.school_id = it.school_id
        LEFT JOIN core.users usv          ON usv.id = sv.user_id;
        SQL);
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS v_internship_detail;');
        DB::statement('DROP VIEW IF EXISTS v_application_summary;');
        DB::statement('DROP VIEW IF EXISTS v_monitoring_log_detail;');
        DB::statement('DROP VIEW IF EXISTS v_monitoring_log_summary;');
        DB::statement('DROP VIEW IF EXISTS internship_details_view;');
        DB::statement('DROP VIEW IF EXISTS application_details_view;');
        DB::statement('DROP VIEW IF EXISTS institution_details_view;');
        DB::statement('DROP VIEW IF EXISTS supervisor_details_view;');
        DB::statement('DROP VIEW IF EXISTS student_details_view;');
        DB::statement('DROP VIEW IF EXISTS developer_details_view;');
        DB::statement('DROP VIEW IF EXISTS school_details_view;');
        DB::statement('DROP VIEW IF EXISTS major_staff_details_view;');
    }
};

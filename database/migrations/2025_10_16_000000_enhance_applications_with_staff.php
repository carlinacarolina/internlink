<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET search_path TO app, core, public');

        DB::unprepared(<<<'SQL'
            ALTER TABLE app.schools
                ADD COLUMN city varchar(100),
                ADD COLUMN postal_code varchar(20),
                ADD COLUMN principal_name varchar(150),
                ADD COLUMN principal_nip varchar(50);
        SQL);

        DB::statement('DROP VIEW IF EXISTS school_details_view');

        DB::unprepared(<<<'SQL'
            CREATE VIEW school_details_view AS
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

        DB::unprepared(<<<'SQL'
            ALTER TABLE app.applications
                ADD COLUMN planned_start_date date,
                ADD COLUMN planned_end_date date;
        SQL);

        DB::unprepared(<<<'SQL'
            CREATE TABLE IF NOT EXISTS app.major_staff_assignments (
                id bigserial PRIMARY KEY,
                school_id bigint NOT NULL REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE CASCADE,
                supervisor_id bigint NOT NULL REFERENCES app.supervisors(id) ON UPDATE CASCADE ON DELETE CASCADE,
                major varchar(150) NOT NULL,
                major_slug varchar(160) NOT NULL,
                created_at timestamptz NOT NULL DEFAULT now(),
                updated_at timestamptz NOT NULL DEFAULT now()
            );
        SQL);

        DB::unprepared(<<<'SQL'
            DO $$
            BEGIN
              IF NOT EXISTS (
                SELECT 1 FROM pg_trigger
                WHERE tgname = 'trg_major_staff_assignments_updated_at'
              ) THEN
                CREATE TRIGGER trg_major_staff_assignments_updated_at
                BEFORE UPDATE ON app.major_staff_assignments
                FOR EACH ROW EXECUTE FUNCTION app.set_updated_at();
              END IF;
            END $$;
        SQL);

        DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS uq_major_staff_school_major ON app.major_staff_assignments (school_id, major_slug)');

        DB::statement('DROP VIEW IF EXISTS major_staff_details_view');

        DB::unprepared(<<<'SQL'
            CREATE VIEW major_staff_details_view AS
            SELECT msa.id,
                   msa.school_id,
                   msa.supervisor_id,
                   msa.major,
                   msa.major_slug,
                   sdv.name,
                   sdv.email,
                   sdv.phone,
                   sdv.department,
                   sdv.supervisor_number,
                   sdv.created_at,
                   sdv.updated_at
            FROM app.major_staff_assignments msa
            JOIN supervisor_details_view sdv ON sdv.id = msa.supervisor_id;
        SQL);

        DB::statement('DROP VIEW IF EXISTS application_details_view');

        DB::unprepared(<<<'SQL'
            CREATE VIEW application_details_view AS
            WITH student_major AS (
                SELECT sdv.id,
                       sdv.user_id,
                       sdv.school_id,
                       sdv.name,
                       sdv.email,
                       sdv.phone,
                       sdv.student_number,
                       sdv.national_sn,
                       sdv.major,
                       sdv.class,
                       sdv.batch,
                       sdv.notes,
                       sdv.photo,
                       LOWER(REGEXP_REPLACE(COALESCE(sdv.major, ''), '[^a-z0-9]+', '-', 'g')) AS major_slug
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
                   idv.industry                AS institution_industry,
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
            LEFT JOIN app.major_staff_assignments msa ON msa.school_id = a.school_id AND msa.major_slug = sm.major_slug
            LEFT JOIN supervisor_details_view sdv_staff ON sdv_staff.id = msa.supervisor_id AND sdv_staff.school_id = a.school_id;
        SQL);
    }

    public function down(): void
    {
        DB::statement('SET search_path TO app, core, public');

        DB::statement('DROP VIEW IF EXISTS application_details_view');

        DB::unprepared(<<<'SQL'
            CREATE VIEW application_details_view AS
            SELECT a.id,
                   a.school_id,
                   a.student_id,
                   sdv.user_id                  AS student_user_id,
                   sdv.name                     AS student_name,
                   sdv.email                    AS student_email,
                   sdv.phone                    AS student_phone,
                   sdv.student_number,
                   sdv.national_sn,
                   sdv.major                    AS student_major,
                   sdv.class                    AS student_class,
                   sdv.batch                    AS student_batch,
                   sdv.notes                    AS student_notes,
                   sdv.photo                    AS student_photo,
                   a.institution_id,
                   idv.name                    AS institution_name,
                   idv.address                 AS institution_address,
                   idv.city                    AS institution_city,
                   idv.province                AS institution_province,
                   idv.website                 AS institution_website,
                   idv.industry                AS institution_industry,
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
                   a.notes                     AS application_notes,
                   a.created_at,
                   a.updated_at
            FROM app.applications a
            JOIN student_details_view sdv       ON sdv.id = a.student_id AND sdv.school_id = a.school_id
            JOIN institution_details_view idv  ON idv.id = a.institution_id AND idv.school_id = a.school_id
            JOIN app.periods p                 ON p.id = a.period_id AND p.school_id = a.school_id;
        SQL);

        DB::statement('DROP VIEW IF EXISTS major_staff_details_view');
        DB::statement('DROP TABLE IF EXISTS app.major_staff_assignments');

        DB::unprepared(<<<'SQL'
            ALTER TABLE app.applications
                DROP COLUMN IF EXISTS planned_start_date,
                DROP COLUMN IF EXISTS planned_end_date;
        SQL);

        DB::statement('DROP VIEW IF EXISTS school_details_view');

        DB::unprepared(<<<'SQL'
            ALTER TABLE app.schools
                DROP COLUMN IF EXISTS city,
                DROP COLUMN IF EXISTS postal_code,
                DROP COLUMN IF EXISTS principal_name,
                DROP COLUMN IF EXISTS principal_nip;
        SQL);

        DB::unprepared(<<<'SQL'
            CREATE VIEW school_details_view AS
            SELECT s.id,
                   s.code,
                   s.name,
                   s.address,
                   s.phone,
                   s.email,
                   s.website,
                   s.created_at,
                   s.updated_at
            FROM app.schools s;
        SQL);
    }
};

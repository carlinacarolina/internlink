<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET search_path TO app, core, public');

        // app.students
        DB::statement('ALTER TABLE app.students ADD COLUMN school_id bigint');
        DB::statement('ALTER TABLE app.students ALTER COLUMN school_id SET NOT NULL');
        DB::statement('ALTER TABLE app.students ADD CONSTRAINT fk_students_school FOREIGN KEY (school_id) REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE RESTRICT');
        DB::statement('ALTER TABLE app.students DROP CONSTRAINT IF EXISTS students_student_number_key');
        DB::statement('ALTER TABLE app.students DROP CONSTRAINT IF EXISTS students_national_sn_key');
        DB::statement('CREATE UNIQUE INDEX uq_students_school_student_number ON app.students (school_id, student_number)');
        DB::statement('CREATE UNIQUE INDEX uq_students_school_national_sn ON app.students (school_id, national_sn)');

        // app.supervisors
        DB::statement('ALTER TABLE app.supervisors ADD COLUMN school_id bigint');
        DB::statement('ALTER TABLE app.supervisors ALTER COLUMN school_id SET NOT NULL');
        DB::statement('ALTER TABLE app.supervisors ADD CONSTRAINT fk_supervisors_school FOREIGN KEY (school_id) REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE RESTRICT');
        DB::statement('ALTER TABLE app.supervisors DROP CONSTRAINT IF EXISTS supervisors_supervisor_number_key');
        DB::statement('CREATE UNIQUE INDEX uq_supervisors_school_number ON app.supervisors (school_id, supervisor_number)');

        // app.periods
        DB::statement('ALTER TABLE app.periods ADD COLUMN school_id bigint');
        DB::statement('ALTER TABLE app.periods ALTER COLUMN school_id SET NOT NULL');
        DB::statement('ALTER TABLE app.periods ADD CONSTRAINT fk_periods_school FOREIGN KEY (school_id) REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE RESTRICT');
        DB::statement('ALTER TABLE app.periods DROP CONSTRAINT IF EXISTS uq_periods_year_term');
        DB::statement('CREATE UNIQUE INDEX uq_periods_school_year_term ON app.periods (school_id, year, term)');

        // app.institutions
        DB::statement('ALTER TABLE app.institutions ADD COLUMN school_id bigint');
        DB::statement('ALTER TABLE app.institutions ALTER COLUMN school_id SET NOT NULL');
        DB::statement('ALTER TABLE app.institutions ADD CONSTRAINT fk_institutions_school FOREIGN KEY (school_id) REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE RESTRICT');
        DB::statement('ALTER TABLE app.institutions DROP CONSTRAINT IF EXISTS institutions_name_key');
        DB::statement('CREATE UNIQUE INDEX uq_institutions_school_name ON app.institutions (school_id, name)');

        // app.institution_quotas
        DB::statement('ALTER TABLE app.institution_quotas ADD COLUMN school_id bigint');
        DB::statement('ALTER TABLE app.institution_quotas ALTER COLUMN school_id SET NOT NULL');
        DB::statement('ALTER TABLE app.institution_quotas ADD CONSTRAINT fk_inst_quotas_school FOREIGN KEY (school_id) REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE RESTRICT');

        // app.applications
        DB::statement('ALTER TABLE app.applications ADD COLUMN school_id bigint');
        DB::statement('ALTER TABLE app.applications ALTER COLUMN school_id SET NOT NULL');
        DB::statement('ALTER TABLE app.applications ADD CONSTRAINT fk_applications_school FOREIGN KEY (school_id) REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE RESTRICT');

        // app.application_status_history
        DB::statement('ALTER TABLE app.application_status_history ADD COLUMN school_id bigint');
        DB::statement('ALTER TABLE app.application_status_history ALTER COLUMN school_id SET NOT NULL');
        DB::statement('ALTER TABLE app.application_status_history ADD CONSTRAINT fk_app_history_school FOREIGN KEY (school_id) REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE RESTRICT');
        DB::statement(<<<'SQL'
            CREATE OR REPLACE FUNCTION app.log_app_status_on_insert() RETURNS trigger
            LANGUAGE plpgsql AS $$
            DECLARE actor bigint;
            BEGIN
              actor := app.app_current_actor();
              PERFORM app.assert_not_student(actor);
              INSERT INTO app.application_status_history(application_id, school_id, from_status, to_status, changed_by)
              VALUES (NEW.id, NEW.school_id, NULL, NEW.status, actor);
              RETURN NEW;
            END $$;
        SQL);
        DB::statement(<<<'SQL'
            CREATE OR REPLACE FUNCTION app.log_app_status_change() RETURNS trigger
            LANGUAGE plpgsql AS $$
            DECLARE actor bigint;
            BEGIN
              IF TG_OP='UPDATE' AND NEW.status IS DISTINCT FROM OLD.status THEN
                actor := app.app_current_actor();
                PERFORM app.assert_not_student(actor);
                INSERT INTO app.application_status_history(application_id, school_id, from_status, to_status, changed_by)
                VALUES (NEW.id, NEW.school_id, OLD.status, NEW.status, actor);
              END IF;
              RETURN NEW;
            END $$;
        SQL);

        DB::statement(<<<'SQL'
        CREATE OR REPLACE FUNCTION app.ensure_quota_exists_on_active() RETURNS trigger AS $$
        DECLARE v_exists int;
        BEGIN
          IF (TG_OP='INSERT' AND app.is_active_status(NEW.status))
             OR (TG_OP='UPDATE' AND app.is_active_status(NEW.status) AND NOT app.is_active_status(OLD.status)) THEN
            SELECT 1 INTO v_exists FROM app.institution_quotas
             WHERE institution_id = NEW.institution_id
               AND period_id      = NEW.period_id
               AND school_id      = NEW.school_id
             FOR UPDATE;
            IF v_exists IS NULL THEN
              RAISE EXCEPTION 'Quota not set for institution_id=% period_id=% school_id=%', NEW.institution_id, NEW.period_id, NEW.school_id;
            END IF;
          END IF;
          RETURN NEW;
        END $$ LANGUAGE plpgsql;
        SQL);

        DB::statement(<<<'SQL'
        CREATE OR REPLACE FUNCTION app.enforce_max_active_per_student()
        RETURNS trigger LANGUAGE plpgsql AS $$
        DECLARE cnt int;
        BEGIN
          IF (TG_OP='INSERT' AND app.is_active_status(NEW.status))
             OR (TG_OP='UPDATE' AND app.is_active_status(NEW.status) AND NOT app.is_active_status(OLD.status)) THEN
            SELECT COUNT(*) INTO cnt
              FROM app.applications
              WHERE student_id = NEW.student_id
                AND school_id  = NEW.school_id
                AND period_id  = NEW.period_id
                AND app.is_active_status(status);
            IF cnt >= 3 THEN
              RAISE EXCEPTION 'Active application limit reached (3) for student_id=% period_id=% school_id=%', NEW.student_id, NEW.period_id, NEW.school_id;
            END IF;
          END IF;
          RETURN NEW;
        END $$;
        SQL);

        DB::statement(<<<'SQL'
        CREATE OR REPLACE FUNCTION app.bump_quota_used_active()
        RETURNS trigger LANGUAGE plpgsql AS $$
        BEGIN
          IF TG_OP='INSERT' THEN
            IF app.is_active_status(NEW.status) THEN
              UPDATE app.institution_quotas
                SET used = used + 1
              WHERE institution_id = NEW.institution_id
                AND period_id      = NEW.period_id
                AND school_id      = NEW.school_id;
            END IF;

          ELSIF TG_OP='UPDATE' THEN
            IF app.is_active_status(OLD.status) AND app.is_active_status(NEW.status)
               AND (OLD.institution_id <> NEW.institution_id OR OLD.period_id <> NEW.period_id OR OLD.school_id <> NEW.school_id) THEN

              UPDATE app.institution_quotas
                SET used = GREATEST(0, used - 1)
              WHERE institution_id = OLD.institution_id
                AND period_id      = OLD.period_id
                AND school_id      = OLD.school_id;

              UPDATE app.institution_quotas
                SET used = used + 1
              WHERE institution_id = NEW.institution_id
                AND period_id      = NEW.period_id
                AND school_id      = NEW.school_id;

            ELSIF NOT app.is_active_status(OLD.status) AND app.is_active_status(NEW.status) THEN
              UPDATE app.institution_quotas
                SET used = used + 1
              WHERE institution_id = NEW.institution_id
                AND period_id      = NEW.period_id
                AND school_id      = NEW.school_id;

            ELSIF app.is_active_status(OLD.status) AND NOT app.is_active_status(NEW.status) THEN
              UPDATE app.institution_quotas
                SET used = GREATEST(0, used - 1)
              WHERE institution_id = OLD.institution_id
                AND period_id      = OLD.period_id
                AND school_id      = OLD.school_id;
            END IF;

          ELSIF TG_OP='DELETE' THEN
            IF app.is_active_status(OLD.status) THEN
              UPDATE app.institution_quotas
                SET used = GREATEST(0, used - 1)
              WHERE institution_id = OLD.institution_id
                AND period_id      = OLD.period_id
                AND school_id      = OLD.school_id;
            END IF;
            RETURN OLD;
          END IF;

          RETURN NEW;
        END $$;
        SQL);

        // app.internships
        DB::statement('ALTER TABLE app.internships ADD COLUMN school_id bigint');
        DB::statement('ALTER TABLE app.internships ALTER COLUMN school_id SET NOT NULL');
        DB::statement('ALTER TABLE app.internships ADD CONSTRAINT fk_internships_school FOREIGN KEY (school_id) REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE RESTRICT');
        DB::statement(<<<'SQL'
        CREATE OR REPLACE FUNCTION app.enforce_internship_from_accepted_application()
        RETURNS trigger LANGUAGE plpgsql AS $$
        DECLARE apprec RECORD;
        BEGIN
          SELECT * INTO apprec FROM app.applications WHERE id = NEW.application_id FOR UPDATE;
          IF apprec IS NULL THEN RAISE EXCEPTION 'Application % not found', NEW.application_id; END IF;
          IF apprec.status <> 'accepted' THEN RAISE EXCEPTION 'Application % is not accepted', NEW.application_id; END IF;
          IF NEW.school_id IS NOT NULL AND NEW.school_id <> apprec.school_id THEN
            RAISE EXCEPTION 'Internship school mismatch for application %', NEW.application_id;
          END IF;
          NEW.school_id := apprec.school_id;
          NEW.student_id := apprec.student_id;
          NEW.institution_id := apprec.institution_id;
          NEW.period_id := apprec.period_id;
          RETURN NEW;
        END $$;
        SQL);

        // app.monitoring_logs
        DB::statement('ALTER TABLE app.monitoring_logs ADD COLUMN school_id bigint');
        DB::statement('ALTER TABLE app.monitoring_logs ALTER COLUMN school_id SET NOT NULL');
        DB::statement('ALTER TABLE app.monitoring_logs ADD CONSTRAINT fk_monitoring_logs_school FOREIGN KEY (school_id) REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE RESTRICT');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO app, core, public');

        DB::statement('ALTER TABLE app.monitoring_logs DROP CONSTRAINT IF EXISTS fk_monitoring_logs_school');
        DB::statement('ALTER TABLE app.monitoring_logs DROP COLUMN IF EXISTS school_id');

        DB::statement('ALTER TABLE app.internships DROP CONSTRAINT IF EXISTS fk_internships_school');
        DB::statement('ALTER TABLE app.internships DROP COLUMN IF EXISTS school_id');

        DB::statement('ALTER TABLE app.application_status_history DROP CONSTRAINT IF EXISTS fk_app_history_school');
        DB::statement('ALTER TABLE app.application_status_history DROP COLUMN IF EXISTS school_id');

        DB::statement('ALTER TABLE app.applications DROP CONSTRAINT IF EXISTS fk_applications_school');
        DB::statement('ALTER TABLE app.applications DROP COLUMN IF EXISTS school_id');

        DB::statement('ALTER TABLE app.institution_quotas DROP CONSTRAINT IF EXISTS fk_inst_quotas_school');
        DB::statement('ALTER TABLE app.institution_quotas DROP COLUMN IF EXISTS school_id');

        DB::statement('DROP INDEX IF EXISTS uq_institutions_school_name');
        DB::statement('ALTER TABLE app.institutions DROP CONSTRAINT IF EXISTS fk_institutions_school');
        DB::statement('ALTER TABLE app.institutions DROP COLUMN IF EXISTS school_id');
        DB::statement('ALTER TABLE app.institutions ADD CONSTRAINT institutions_name_key UNIQUE (name)');

        DB::statement('DROP INDEX IF EXISTS uq_periods_school_year_term');
        DB::statement('ALTER TABLE app.periods DROP CONSTRAINT IF EXISTS fk_periods_school');
        DB::statement('ALTER TABLE app.periods DROP COLUMN IF EXISTS school_id');
        DB::statement('ALTER TABLE app.periods ADD CONSTRAINT uq_periods_year_term UNIQUE (year, term)');

        DB::statement('DROP INDEX IF EXISTS uq_supervisors_school_number');
        DB::statement('ALTER TABLE app.supervisors DROP CONSTRAINT IF EXISTS fk_supervisors_school');
        DB::statement('ALTER TABLE app.supervisors DROP COLUMN IF EXISTS school_id');
        DB::statement('ALTER TABLE app.supervisors ADD CONSTRAINT supervisors_supervisor_number_key UNIQUE (supervisor_number)');

        DB::statement('DROP INDEX IF EXISTS uq_students_school_student_number');
        DB::statement('DROP INDEX IF EXISTS uq_students_school_national_sn');
        DB::statement('ALTER TABLE app.students DROP CONSTRAINT IF EXISTS fk_students_school');
        DB::statement('ALTER TABLE app.students DROP COLUMN IF EXISTS school_id');
        DB::statement('ALTER TABLE app.students ADD CONSTRAINT students_student_number_key UNIQUE (student_number)');
        DB::statement('ALTER TABLE app.students ADD CONSTRAINT students_national_sn_key UNIQUE (national_sn)');

    }
};

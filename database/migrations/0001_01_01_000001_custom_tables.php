<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE EXTENSION IF NOT EXISTS citext;");
        DB::statement("CREATE SCHEMA IF NOT EXISTS core;");
        DB::statement("CREATE SCHEMA IF NOT EXISTS app;");

        // Create periods table
        DB::statement(<<<'SQL'
        CREATE TABLE IF NOT EXISTS app.periods (
          id           bigserial PRIMARY KEY,
          year         integer NOT NULL,
          term         smallint NOT NULL,
          school_id    bigint NOT NULL REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE RESTRICT,
          CONSTRAINT uq_periods_school_year_term UNIQUE (school_id, year, term),
          CONSTRAINT chk_period_year CHECK (year BETWEEN 2000 AND 2100),
          CONSTRAINT chk_period_term CHECK (term BETWEEN 1 AND 4)
        );
        SQL);

        // Create school_majors table
        DB::unprepared(<<<'SQL'
            CREATE TABLE IF NOT EXISTS app.school_majors (
                id bigserial PRIMARY KEY,
                school_id bigint NOT NULL REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE CASCADE,
                name varchar(150) NOT NULL,
                slug varchar(160) NOT NULL,
                is_active boolean NOT NULL DEFAULT TRUE,
                created_at timestamptz NOT NULL DEFAULT now(),
                updated_at timestamptz NOT NULL DEFAULT now(),
                CONSTRAINT uq_school_majors_school_slug UNIQUE (school_id, slug)
            );
        SQL);

        // Add updated_at trigger for school_majors
        DB::unprepared(<<<'SQL'
            DO $$
            BEGIN
              IF NOT EXISTS (
                SELECT 1 FROM pg_trigger
                WHERE tgname = 'trg_school_majors_updated_at'
              ) THEN
                CREATE TRIGGER trg_school_majors_updated_at
                BEFORE UPDATE ON app.school_majors
                FOR EACH ROW EXECUTE FUNCTION app.set_updated_at();
              END IF;
            END $$;
        SQL);

        // Create indexes for school_majors
        DB::statement('CREATE INDEX IF NOT EXISTS idx_school_majors_school_id ON app.school_majors (school_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_school_majors_active ON app.school_majors (school_id, is_active) WHERE is_active = TRUE');

        // Create students table
        DB::statement(<<<'SQL'
        CREATE TABLE IF NOT EXISTS app.students (
          id              bigserial PRIMARY KEY,
          user_id         bigint NOT NULL REFERENCES core.users(id) ON DELETE CASCADE,
          student_number  varchar(50) NOT NULL,
          national_sn     varchar(50) NOT NULL,
          major_id        bigint REFERENCES app.school_majors(id) ON UPDATE CASCADE ON DELETE RESTRICT,
          "class"         varchar(100) NOT NULL,
          batch           varchar(9) NOT NULL,
          school_id       bigint NOT NULL REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE RESTRICT,
          notes           text NULL,
          photo           text NULL,
          created_at      timestamptz NOT NULL DEFAULT now(),
          updated_at      timestamptz NOT NULL DEFAULT now(),
          CONSTRAINT uq_students_user UNIQUE (user_id),
          CONSTRAINT uq_students_school_student_number UNIQUE (school_id, student_number),
          CONSTRAINT uq_students_school_national_sn UNIQUE (school_id, national_sn)
        );
        SQL);

        DB::statement("CREATE INDEX IF NOT EXISTS idx_students_class ON app.students (\"class\");");
        DB::statement(<<<'SQL'
        DO $$
        BEGIN
          IF NOT EXISTS (
            SELECT 1 FROM pg_trigger
            WHERE tgname = 'trg_students_updated_at'
          ) THEN
            CREATE TRIGGER trg_students_updated_at
            BEFORE UPDATE ON app.students
            FOR EACH ROW EXECUTE FUNCTION app.set_updated_at();
          END IF;
        END $$;
        SQL);

        // Create supervisors table
        DB::statement(<<<'SQL'
        CREATE TABLE IF NOT EXISTS app.supervisors (
          id                  bigserial PRIMARY KEY,
          user_id             bigint NOT NULL REFERENCES core.users(id) ON DELETE CASCADE,
          supervisor_number   varchar(50) NOT NULL,
          department_id       bigint REFERENCES app.school_majors(id) ON UPDATE CASCADE ON DELETE RESTRICT,
          school_id           bigint NOT NULL REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE RESTRICT,
          notes               text NULL,
          photo               text NULL,
          created_at          timestamptz NOT NULL DEFAULT now(),
          updated_at          timestamptz NOT NULL DEFAULT now(),
          CONSTRAINT uq_supervisors_user UNIQUE (user_id),
          CONSTRAINT uq_supervisors_school_number UNIQUE (school_id, supervisor_number)
        );
        SQL);

        DB::statement(<<<'SQL'
        DO $$
        BEGIN
          IF NOT EXISTS (
            SELECT 1 FROM pg_trigger
            WHERE tgname = 'trg_supervisors_updated_at'
          ) THEN
            CREATE TRIGGER trg_supervisors_updated_at
            BEFORE UPDATE ON app.supervisors
            FOR EACH ROW EXECUTE FUNCTION app.set_updated_at();
          END IF;
        END $$;
        SQL);

        // Create institutions table
        DB::statement(<<<'SQL'
        CREATE TABLE IF NOT EXISTS app.institutions (
          id         bigserial PRIMARY KEY,
          name       varchar(150) NOT NULL,
          address    text NULL,
          city       varchar(100) NULL,
          province   varchar(100) NULL,
          website    text NULL,
          industry_for bigint REFERENCES app.school_majors(id) ON UPDATE CASCADE ON DELETE RESTRICT,
          school_id  bigint NOT NULL REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE RESTRICT,
          notes      text NULL,
          photo      varchar(255) NULL,
          created_at timestamptz NOT NULL DEFAULT now(),
          updated_at timestamptz NOT NULL DEFAULT now(),
          CONSTRAINT uq_institutions_school_name UNIQUE (school_id, name)
        );
        SQL);

        DB::statement("CREATE INDEX IF NOT EXISTS idx_institutions_industry_for ON app.institutions (industry_for);");
        DB::statement(<<<'SQL'
        DO $$
        BEGIN
          IF NOT EXISTS (
            SELECT 1 FROM pg_trigger
            WHERE tgname = 'trg_institutions_updated_at'
          ) THEN
            CREATE TRIGGER trg_institutions_updated_at
            BEFORE UPDATE ON app.institutions
            FOR EACH ROW EXECUTE FUNCTION app.set_updated_at();
          END IF;
        END $$;
        SQL);

        // Create institution_contacts table
        DB::statement(<<<'SQL'
        CREATE TABLE IF NOT EXISTS app.institution_contacts (
          id              bigserial PRIMARY KEY,
          institution_id  bigint NOT NULL REFERENCES app.institutions(id) ON DELETE CASCADE,
          name            varchar(150) NOT NULL,
          email           varchar(255) NULL,
          phone           varchar(50) NULL,
          position        varchar(100) NULL,
          is_primary      boolean NOT NULL DEFAULT FALSE,
          created_at      timestamptz NOT NULL DEFAULT now(),
          updated_at      timestamptz NOT NULL DEFAULT now(),
          CONSTRAINT uq_inst_contacts UNIQUE (institution_id, email)
        );
        SQL);

        DB::statement(<<<'SQL'
        DO $$
        BEGIN
          IF NOT EXISTS (
            SELECT 1 FROM pg_trigger
            WHERE tgname = 'trg_institution_contacts_updated_at'
          ) THEN
            CREATE TRIGGER trg_institution_contacts_updated_at
            BEFORE UPDATE ON app.institution_contacts
            FOR EACH ROW EXECUTE FUNCTION app.set_updated_at();
          END IF;
        END $$;
        SQL);

        // Create institution_quotas table
        DB::statement(<<<'SQL'
        CREATE TABLE IF NOT EXISTS app.institution_quotas (
          id              bigserial PRIMARY KEY,
          institution_id  bigint NOT NULL REFERENCES app.institutions(id) ON DELETE CASCADE,
          period_id       bigint NOT NULL REFERENCES app.periods(id) ON DELETE RESTRICT,
          school_id       bigint NOT NULL REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE RESTRICT,
          quota           integer NOT NULL,
          used            integer NOT NULL DEFAULT 0,
          created_at      timestamptz NOT NULL DEFAULT now(),
          updated_at      timestamptz NOT NULL DEFAULT now(),
          CONSTRAINT uq_inst_quota UNIQUE (institution_id, period_id),
          CONSTRAINT chk_quota_nonneg CHECK (quota >= 0)
        );
        SQL);

        DB::statement("CREATE INDEX IF NOT EXISTS idx_inst_quota_period ON app.institution_quotas (institution_id, period_id);");
        DB::statement(<<<'SQL'
        DO $$
        BEGIN
          IF NOT EXISTS (
            SELECT 1 FROM pg_trigger
            WHERE tgname = 'trg_inst_quota_validate'
          ) THEN
            CREATE TRIGGER trg_inst_quota_validate
            BEFORE INSERT OR UPDATE ON app.institution_quotas
            FOR EACH ROW EXECUTE FUNCTION app.validate_quota_not_over();
          END IF;
        END $$;
        SQL);

        DB::statement(<<<'SQL'
        DO $$
        BEGIN
          IF NOT EXISTS (
            SELECT 1 FROM pg_trigger
            WHERE tgname = 'trg_institution_quotas_updated_at'
          ) THEN
            CREATE TRIGGER trg_institution_quotas_updated_at
            BEFORE UPDATE ON app.institution_quotas
            FOR EACH ROW EXECUTE FUNCTION app.set_updated_at();
          END IF;
        END $$;
        SQL);

        // Create applications table
        DB::statement(<<<'SQL'
        CREATE TABLE IF NOT EXISTS app.applications (
          id               bigserial PRIMARY KEY,
          student_id       bigint NOT NULL REFERENCES app.students(id) ON DELETE CASCADE,
          institution_id   bigint NOT NULL REFERENCES app.institutions(id) ON DELETE RESTRICT,
          period_id        bigint NOT NULL REFERENCES app.periods(id) ON DELETE RESTRICT,
          school_id        bigint NOT NULL REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE RESTRICT,
          status           public.application_status_enum NOT NULL DEFAULT 'submitted',
          student_access   boolean NOT NULL DEFAULT FALSE,
          submitted_at     timestamptz NOT NULL DEFAULT now(),
          planned_start_date date,
          planned_end_date date,
          notes            text NULL,
          created_at       timestamptz NOT NULL DEFAULT now(),
          updated_at       timestamptz NOT NULL DEFAULT now(),
          CONSTRAINT uq_application_unique_per_period UNIQUE (student_id, institution_id, period_id)
        );
        SQL);

        DB::statement("CREATE INDEX IF NOT EXISTS idx_applications_student ON app.applications (student_id, period_id);");
        DB::statement("CREATE INDEX IF NOT EXISTS idx_applications_institution ON app.applications (institution_id, period_id);");
        DB::statement("CREATE INDEX IF NOT EXISTS idx_applications_status ON app.applications (status);");
        DB::statement("CREATE UNIQUE INDEX IF NOT EXISTS uq_app_id_period ON app.applications (id, period_id);");
        DB::statement(<<<'SQL'
        DO $$
        BEGIN
          IF NOT EXISTS (
            SELECT 1 FROM pg_trigger
            WHERE tgname = 'trg_applications_updated_at'
          ) THEN
            CREATE TRIGGER trg_applications_updated_at
            BEFORE UPDATE ON app.applications
            FOR EACH ROW EXECUTE FUNCTION app.set_updated_at();
          END IF;
        END $$;
        SQL);

        // Create application_status_history table
        DB::statement(<<<'SQL'
        CREATE TABLE IF NOT EXISTS app.application_status_history (
          id             bigserial PRIMARY KEY,
          application_id bigint NOT NULL REFERENCES app.applications(id) ON DELETE CASCADE,
          school_id      bigint NOT NULL REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE RESTRICT,
          from_status    public.application_status_enum NULL,
          to_status      public.application_status_enum NOT NULL,
          changed_at     timestamptz NOT NULL DEFAULT now(),
          changed_by     bigint NULL REFERENCES core.users(id)
        );
        SQL);

        DB::statement("CREATE INDEX IF NOT EXISTS idx_ash_app ON app.application_status_history (application_id, changed_at);");
        DB::statement("CREATE INDEX IF NOT EXISTS idx_ash_changed_by ON app.application_status_history (changed_by);");

        // Create major_staff_assignments table
        DB::unprepared(<<<'SQL'
            CREATE TABLE IF NOT EXISTS app.major_staff_assignments (
                id bigserial PRIMARY KEY,
                school_id bigint NOT NULL REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE CASCADE,
                supervisor_id bigint NOT NULL REFERENCES app.supervisors(id) ON UPDATE CASCADE ON DELETE CASCADE,
                major varchar(150) NOT NULL,
                major_id bigint NOT NULL REFERENCES app.school_majors(id) ON UPDATE CASCADE ON DELETE RESTRICT,
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

        DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS uq_major_staff_school_major ON app.major_staff_assignments (school_id, major_id)');

        // Create internships table
        DB::statement(<<<'SQL'
        CREATE TABLE IF NOT EXISTS app.internships (
          id              bigserial PRIMARY KEY,
          application_id  bigint NOT NULL,
          student_id      bigint NOT NULL REFERENCES app.students(id) ON DELETE RESTRICT,
          institution_id  bigint NOT NULL REFERENCES app.institutions(id) ON DELETE RESTRICT,
          period_id       bigint NOT NULL REFERENCES app.periods(id) ON DELETE RESTRICT,
          school_id       bigint NOT NULL REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE RESTRICT,
          start_date      date NULL,
          end_date        date NULL,
          status          public.internship_status_enum NOT NULL DEFAULT 'planned',
          created_at      timestamptz NOT NULL DEFAULT now(),
          updated_at      timestamptz NOT NULL DEFAULT now(),
          CONSTRAINT uq_internships_application UNIQUE (application_id),
          CONSTRAINT chk_dates_valid CHECK (end_date IS NULL OR start_date IS NULL OR end_date >= start_date),
          CONSTRAINT fk_internships_app_period
            FOREIGN KEY (application_id, period_id)
            REFERENCES app.applications (id, period_id)
            ON UPDATE CASCADE
            ON DELETE CASCADE
        );
        SQL);

        DB::statement("CREATE INDEX IF NOT EXISTS idx_internships_student ON app.internships (student_id, period_id);");
        DB::statement("CREATE INDEX IF NOT EXISTS idx_internships_institution ON app.internships (institution_id, period_id);");
        DB::statement("CREATE UNIQUE INDEX IF NOT EXISTS uq_internships_student_period ON app.internships (student_id, period_id);");
        DB::statement(<<<'SQL'
        DO $$
        BEGIN
          IF NOT EXISTS (
            SELECT 1 FROM pg_trigger
            WHERE tgname = 'trg_internships_updated_at'
          ) THEN
            CREATE TRIGGER trg_internships_updated_at
            BEFORE UPDATE ON app.internships
            FOR EACH ROW EXECUTE FUNCTION app.set_updated_at();
          END IF;
        END $$;
        SQL);

        // Create internship_supervisors table
        DB::statement(<<<'SQL'
        CREATE TABLE IF NOT EXISTS app.internship_supervisors (
          internship_id bigint NOT NULL REFERENCES app.internships(id) ON DELETE CASCADE,
          supervisor_id bigint NOT NULL REFERENCES app.supervisors(id) ON DELETE CASCADE,
          is_primary    boolean NOT NULL DEFAULT FALSE,
          PRIMARY KEY (internship_id, supervisor_id)
        );
        SQL);

        DB::statement(<<<'SQL'
        CREATE INDEX IF NOT EXISTS idx_internship_supervisors_primary
        ON app.internship_supervisors (internship_id) WHERE is_primary;
        SQL);

        // Create monitoring_logs table
        DB::statement(<<<'SQL'
        CREATE TABLE IF NOT EXISTS app.monitoring_logs (
          id             bigserial PRIMARY KEY,
          internship_id  bigint NOT NULL REFERENCES app.internships(id) ON DELETE CASCADE,
          supervisor_id  bigint NULL REFERENCES app.supervisors(id) ON DELETE SET NULL,
          school_id      bigint NOT NULL REFERENCES app.schools(id) ON UPDATE CASCADE ON DELETE RESTRICT,
          title          varchar(150) NULL,
          log_date       date NOT NULL DEFAULT CURRENT_DATE,
          content        text NOT NULL,
          type           public.monitor_type_enum NOT NULL DEFAULT 'weekly',
          created_at     timestamptz NOT NULL DEFAULT now(),
          updated_at     timestamptz NOT NULL DEFAULT now()
        );
        SQL);

        DB::statement("CREATE INDEX IF NOT EXISTS idx_monitoring_logs_internship ON app.monitoring_logs (internship_id, log_date);");
        DB::statement("CREATE INDEX IF NOT EXISTS idx_monitoring_logs_supervisor ON app.monitoring_logs (supervisor_id, log_date);");
        DB::statement(<<<'SQL'
        DO $$
        BEGIN
          IF NOT EXISTS (
            SELECT 1 FROM pg_trigger
            WHERE tgname = 'trg_monitoring_logs_updated_at'
          ) THEN
            CREATE TRIGGER trg_monitoring_logs_updated_at
            BEFORE UPDATE ON app.monitoring_logs
            FOR EACH ROW EXECUTE FUNCTION app.set_updated_at();
          END IF;
        END $$;
        SQL);

        // Create all the application-related functions
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
        CREATE OR REPLACE FUNCTION app.enforce_history_changed_by_not_student()
        RETURNS trigger LANGUAGE plpgsql AS $$
        BEGIN
          PERFORM app.assert_not_student(NEW.changed_by);
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
        CREATE OR REPLACE FUNCTION app.set_default_hold_ttl()
        RETURNS trigger LANGUAGE plpgsql AS $$
        BEGIN
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

        DB::statement(<<<'SQL'
        CREATE OR REPLACE FUNCTION app.cancel_expired_holds()
        RETURNS integer LANGUAGE plpgsql AS $$
        BEGIN
          RETURN 0;
        END $$;
        SQL);

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

        DB::statement(<<<'SQL'
        CREATE OR REPLACE FUNCTION app.enforce_role_student() RETURNS trigger AS $$
        DECLARE r public.user_role_enum;
        BEGIN
          SELECT role INTO r FROM core.users WHERE id = NEW.user_id FOR UPDATE;
          IF r <> 'student' THEN RAISE EXCEPTION 'User % is not role=student', NEW.user_id; END IF;
          RETURN NEW;
        END $$ LANGUAGE plpgsql;
        SQL);

        DB::statement(<<<'SQL'
        CREATE OR REPLACE FUNCTION app.enforce_role_supervisor() RETURNS trigger AS $$
        DECLARE r public.user_role_enum;
        BEGIN
          SELECT role INTO r FROM core.users WHERE id = NEW.user_id FOR UPDATE;
          IF r <> 'supervisor' THEN RAISE EXCEPTION 'User % is not role=supervisor', NEW.user_id; END IF;
          RETURN NEW;
        END $$ LANGUAGE plpgsql;
        SQL);

        // Create all triggers
        DB::statement("DROP TRIGGER IF EXISTS trg_app_log_status_insert ON app.applications;");
        DB::statement(<<<'SQL'
        CREATE TRIGGER trg_app_log_status_insert
        AFTER INSERT ON app.applications
        FOR EACH ROW EXECUTE FUNCTION app.log_app_status_on_insert();
        SQL);

        DB::statement("DROP TRIGGER IF EXISTS trg_app_log_status ON app.applications;");
        DB::statement(<<<'SQL'
        CREATE TRIGGER trg_app_log_status
        AFTER UPDATE OF status ON app.applications
        FOR EACH ROW EXECUTE FUNCTION app.log_app_status_change();
        SQL);

        DB::statement("DROP TRIGGER IF EXISTS trg_ash_guard_ins ON app.application_status_history;");
        DB::statement(<<<'SQL'
        CREATE TRIGGER trg_ash_guard_ins
        BEFORE INSERT ON app.application_status_history
        FOR EACH ROW EXECUTE FUNCTION app.enforce_history_changed_by_not_student();
        SQL);

        DB::statement("DROP TRIGGER IF EXISTS trg_app_check_quota_exists ON app.applications;");
        DB::statement(<<<'SQL'
        CREATE TRIGGER trg_app_check_quota_exists
        BEFORE INSERT OR UPDATE OF status ON app.applications
        FOR EACH ROW EXECUTE FUNCTION app.ensure_quota_exists_on_active();
        SQL);

        DB::statement("DROP TRIGGER IF EXISTS trg_app_enforce_max_active ON app.applications;");
        DB::statement(<<<'SQL'
        CREATE TRIGGER trg_app_enforce_max_active
        BEFORE INSERT OR UPDATE OF status ON app.applications
        FOR EACH ROW EXECUTE FUNCTION app.enforce_max_active_per_student();
        SQL);

        DB::statement("DROP TRIGGER IF EXISTS trg_app_set_hold_ttl ON app.applications;");
        DB::statement(<<<'SQL'
        CREATE TRIGGER trg_app_set_hold_ttl
        BEFORE INSERT OR UPDATE OF status ON app.applications
        FOR EACH ROW EXECUTE FUNCTION app.set_default_hold_ttl();
        SQL);

        DB::statement("DROP TRIGGER IF EXISTS trg_app_bump_quota_ins ON app.applications;");
        DB::statement(<<<'SQL'
        CREATE TRIGGER trg_app_bump_quota_ins
        AFTER INSERT ON app.applications
        FOR EACH ROW EXECUTE FUNCTION app.bump_quota_used_active();
        SQL);

        DB::statement("DROP TRIGGER IF EXISTS trg_app_bump_quota_upd ON app.applications;");
        DB::statement(<<<'SQL'
        CREATE TRIGGER trg_app_bump_quota_upd
        AFTER UPDATE OF status, institution_id, period_id ON app.applications
        FOR EACH ROW EXECUTE FUNCTION app.bump_quota_used_active();
        SQL);

        DB::statement("DROP TRIGGER IF EXISTS trg_app_bump_quota_del ON app.applications;");
        DB::statement(<<<'SQL'
        CREATE TRIGGER trg_app_bump_quota_del
        AFTER DELETE ON app.applications
        FOR EACH ROW EXECUTE FUNCTION app.bump_quota_used_active();
        SQL);

        DB::statement("DROP TRIGGER IF EXISTS trg_internship_enforce_app ON app.internships;");
        DB::statement(<<<'SQL'
        CREATE TRIGGER trg_internship_enforce_app
        BEFORE INSERT OR UPDATE OF application_id ON app.internships
        FOR EACH ROW EXECUTE FUNCTION app.enforce_internship_from_accepted_application();
        SQL);

        DB::statement("DROP TRIGGER IF EXISTS trg_students_role ON app.students;");
        DB::statement(<<<'SQL'
        CREATE TRIGGER trg_students_role
        BEFORE INSERT OR UPDATE OF user_id ON app.students
        FOR EACH ROW EXECUTE FUNCTION app.enforce_role_student();
        SQL);

        DB::statement("DROP TRIGGER IF EXISTS trg_supervisors_role ON app.supervisors;");
        DB::statement(<<<'SQL'
        CREATE TRIGGER trg_supervisors_role
        BEFORE INSERT OR UPDATE OF user_id ON app.supervisors
        FOR EACH ROW EXECUTE FUNCTION app.enforce_role_supervisor();
        SQL);
    }

    public function down(): void
    {
        DB::statement("DROP TRIGGER IF EXISTS trg_students_updated_at ON app.students;");
        DB::statement("DROP TRIGGER IF EXISTS trg_supervisors_updated_at ON app.supervisors;");
        DB::statement("DROP TRIGGER IF EXISTS trg_institutions_updated_at ON app.institutions;");
        DB::statement("DROP TRIGGER IF EXISTS trg_institution_contacts_updated_at ON app.institution_contacts;");
        DB::statement("DROP TRIGGER IF EXISTS trg_inst_quota_validate ON app.institution_quotas;");
        DB::statement("DROP TRIGGER IF EXISTS trg_institution_quotas_updated_at ON app.institution_quotas;");
        DB::statement("DROP TRIGGER IF EXISTS trg_applications_updated_at ON app.applications;");
        DB::statement("DROP TRIGGER IF EXISTS trg_app_log_status_insert ON app.applications;");
        DB::statement("DROP TRIGGER IF EXISTS trg_app_log_status ON app.applications;");
        DB::statement("DROP TRIGGER IF EXISTS trg_ash_guard_ins ON app.application_status_history;");
        DB::statement("DROP TRIGGER IF EXISTS trg_app_check_quota_exists ON app.applications;");
        DB::statement("DROP TRIGGER IF EXISTS trg_app_enforce_max_active ON app.applications;");
        DB::statement("DROP TRIGGER IF EXISTS trg_app_set_hold_ttl ON app.applications;");
        DB::statement("DROP TRIGGER IF EXISTS trg_app_bump_quota_ins ON app.applications;");
        DB::statement("DROP TRIGGER IF EXISTS trg_app_bump_quota_upd ON app.applications;");
        DB::statement("DROP TRIGGER IF EXISTS trg_app_bump_quota_del ON app.applications;");
        DB::statement("DROP TRIGGER IF EXISTS trg_internships_updated_at ON app.internships;");
        DB::statement("DROP TRIGGER IF EXISTS trg_internship_enforce_app ON app.internships;");
        DB::statement("DROP TRIGGER IF EXISTS trg_monitoring_logs_updated_at ON app.monitoring_logs;");
        DB::statement("DROP TRIGGER IF EXISTS idx_internship_supervisors_primary ON app.internship_supervisors;");
        DB::statement("DROP TRIGGER IF EXISTS trg_students_role ON app.students;");
        DB::statement("DROP TRIGGER IF EXISTS trg_supervisors_role ON app.supervisors;");
        DB::statement("DROP TRIGGER IF EXISTS trg_school_majors_updated_at ON app.school_majors;");
        DB::statement("DROP TRIGGER IF EXISTS trg_major_staff_assignments_updated_at ON app.major_staff_assignments;");

        DB::statement("DROP FUNCTION IF EXISTS app.log_app_status_on_insert() CASCADE;");
        DB::statement("DROP FUNCTION IF EXISTS app.log_app_status_change() CASCADE;");
        DB::statement("DROP FUNCTION IF EXISTS app.enforce_history_changed_by_not_student() CASCADE;");
        DB::statement("DROP FUNCTION IF EXISTS app.ensure_quota_exists_on_active() CASCADE;");
        DB::statement("DROP FUNCTION IF EXISTS app.enforce_max_active_per_student() CASCADE;");
        DB::statement("DROP FUNCTION IF EXISTS app.set_default_hold_ttl() CASCADE;");
        DB::statement("DROP FUNCTION IF EXISTS app.bump_quota_used_active() CASCADE;");
        DB::statement("DROP FUNCTION IF EXISTS app.cancel_expired_holds() CASCADE;");
        DB::statement("DROP FUNCTION IF EXISTS app.enforce_internship_from_accepted_application() CASCADE;");
        DB::statement("DROP FUNCTION IF EXISTS app.enforce_role_student() CASCADE;");
        DB::statement("DROP FUNCTION IF EXISTS app.enforce_role_supervisor() CASCADE;");

        DB::statement("DROP TABLE IF EXISTS app.monitoring_logs CASCADE;");
        DB::statement("DROP TABLE IF EXISTS app.internship_supervisors CASCADE;");
        DB::statement("DROP TABLE IF EXISTS app.internships CASCADE;");
        DB::statement("DROP TABLE IF EXISTS app.application_status_history CASCADE;");
        DB::statement("DROP TABLE IF EXISTS app.applications CASCADE;");
        DB::statement("DROP TABLE IF EXISTS app.institution_quotas CASCADE;");
        DB::statement("DROP TABLE IF EXISTS app.institution_contacts CASCADE;");
        DB::statement("DROP TABLE IF EXISTS app.institutions CASCADE;");
        DB::statement("DROP TABLE IF EXISTS app.supervisors CASCADE;");
        DB::statement("DROP TABLE IF EXISTS app.students CASCADE;");
        DB::statement("DROP TABLE IF EXISTS app.periods CASCADE;");
        DB::statement("DROP TABLE IF EXISTS app.school_majors CASCADE;");
        DB::statement("DROP TABLE IF EXISTS app.major_staff_assignments CASCADE;");
    }
};

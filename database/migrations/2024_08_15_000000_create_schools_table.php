<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE TABLE IF NOT EXISTS app.schools (
                id          bigserial PRIMARY KEY,
                name        varchar(150) NOT NULL UNIQUE,
                address     text NOT NULL,
                phone       varchar(30) NOT NULL UNIQUE,
                email       citext NOT NULL UNIQUE,
                website     text NULL,
                created_at  timestamptz NOT NULL DEFAULT now(),
                updated_at  timestamptz NOT NULL DEFAULT now()
            );
        SQL);

        DB::statement(<<<'SQL'
            DO $$
            BEGIN
              IF NOT EXISTS (
                SELECT 1 FROM pg_trigger
                WHERE tgname = 'trg_schools_updated_at'
              ) THEN
                CREATE TRIGGER trg_schools_updated_at
                BEFORE UPDATE ON app.schools
                FOR EACH ROW EXECUTE FUNCTION app.set_updated_at();
              END IF;
            END $$;
        SQL);

        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW school_details_view AS
            SELECT s.id,
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

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS school_details_view;");

        DB::statement(<<<'SQL'
            DO $$
            BEGIN
              IF EXISTS (
                SELECT 1 FROM pg_trigger
                WHERE tgname = 'trg_schools_updated_at'
              ) THEN
                DROP TRIGGER trg_schools_updated_at ON app.schools;
              END IF;
            END $$;
        SQL);

        DB::statement('DROP TABLE IF EXISTS app.schools;');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE app.schools ADD COLUMN code varchar(20)');

        DB::statement(<<<'SQL'
            UPDATE app.schools
            SET code = CONCAT('SCH', LPAD(id::text, 4, '0'))
            WHERE code IS NULL;
        SQL);

        DB::statement('ALTER TABLE app.schools ADD CONSTRAINT uq_schools_code UNIQUE (code)');
        DB::statement('ALTER TABLE app.schools ALTER COLUMN code SET NOT NULL');

        DB::statement('DROP VIEW IF EXISTS school_details_view');

        DB::statement(<<<'SQL'
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

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS school_details_view');

        DB::statement(<<<'SQL'
            CREATE VIEW school_details_view AS
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

        DB::statement('ALTER TABLE app.schools DROP CONSTRAINT IF EXISTS uq_schools_code;');
        DB::statement('ALTER TABLE app.schools DROP COLUMN IF EXISTS code;');
    }
};

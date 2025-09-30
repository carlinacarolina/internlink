<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('DROP VIEW IF EXISTS supervisor_details_view;');

        DB::unprepared(<<<'SQL'
            CREATE VIEW supervisor_details_view AS
            SELECT s.id,
                   COALESCE(s.school_id, u.school_id) AS school_id,
                   u.id AS user_id,
                   u.name,
                   u.email,
                   u.email_verified_at,
                   u.phone,
                   u.role,
                   s.supervisor_number,
                   s.department,
                   s.notes,
                   s.photo,
                   s.created_at,
                   s.updated_at
            FROM app.supervisors s
            JOIN core.users u ON s.user_id = u.id;
        SQL);
    }

    public function down(): void
    {
        DB::unprepared('DROP VIEW IF EXISTS supervisor_details_view;');

        DB::unprepared(<<<'SQL'
            CREATE VIEW supervisor_details_view AS
            SELECT s.id,
                   COALESCE(s.school_id, u.school_id) AS school_id,
                   u.id AS user_id,
                   u.name,
                   u.email,
                   u.phone,
                   u.role,
                   s.supervisor_number,
                   s.department,
                   s.notes,
                   s.photo,
                   s.created_at,
                   s.updated_at
            FROM app.supervisors s
            JOIN core.users u ON s.user_id = u.id;
        SQL);
    }
};

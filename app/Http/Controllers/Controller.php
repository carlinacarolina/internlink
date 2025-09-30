<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

abstract class Controller
{
    protected function currentStudentId(): ?int
    {
        if (session('role') !== 'student') {
            return null;
        }
        return DB::table('students')->where('user_id', session('user_id'))->value('id');
    }

    protected function currentSupervisorId(): ?int
    {
        if (session('role') !== 'supervisor') {
            return null;
        }
        return DB::table('supervisors')->where('user_id', session('user_id'))->value('id');
    }

    protected function currentSchool(): ?School
    {
        return App::bound('currentSchool') ? App::get('currentSchool') : null;
    }

    protected function currentSchoolId(): ?int
    {
        return optional($this->currentSchool())->id;
    }

    protected function schoolRoute(string $path = '', ?School $school = null): string
    {
        $school ??= $this->currentSchool();
        if (!$school) {
            return '/' . ltrim($path, '/');
        }

        $prefix = '/' . rawurlencode($school->code);
        return $prefix . ($path !== '' ? '/' . ltrim($path, '/') : '');
    }
}

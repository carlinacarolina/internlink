<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentSelf
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session('role') === 'student') {
            $student = DB::table('students')
                ->select('id', 'school_id')
                ->where('user_id', session('user_id'))
                ->first();

            if (!$student) {
                abort(403);
            }

            $routeId = (int) $request->route('id');
            if ($routeId !== (int) $student->id) {
                abort(403);
            }

            $currentSchoolId = app()->bound('currentSchool') ? app('currentSchool')->id : null;
            if ($currentSchoolId && (int) $student->school_id !== (int) $currentSchoolId) {
                abort(403);
            }
        }
        return $next($request);
    }
}

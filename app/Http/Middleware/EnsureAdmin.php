<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $role = session('role');
        if ($role === 'developer') {
            return $next($request);
        }

        if ($role !== 'admin') {
            abort(403);
        }

        $currentSchoolId = app()->bound('currentSchool') ? app('currentSchool')->id : null;
        $sessionSchoolId = session('school_id');

        if (!$currentSchoolId || (int) $sessionSchoolId !== (int) $currentSchoolId) {
            abort(403);
        }
        return $next($request);
    }
}

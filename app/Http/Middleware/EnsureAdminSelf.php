<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminSelf
{
    public function handle(Request $request, Closure $next): Response
    {
        $role = session('role');
        if ($role === 'admin') {
            $routeId = (int) $request->route('id');
            if ($routeId !== (int) session('user_id')) {
                abort(403);
            }

            $currentSchoolId = app()->bound('currentSchool') ? app('currentSchool')->id : null;
            if ($currentSchoolId && (int) session('school_id') !== (int) $currentSchoolId) {
                abort(403);
            }
        } elseif ($role === 'developer') {
            // Developers can manage any admin account
        } else {
            abort(403);
        }
        return $next($request);
    }
}

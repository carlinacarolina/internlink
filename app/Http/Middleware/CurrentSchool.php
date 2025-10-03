<?php

namespace App\Http\Middleware;

use App\Models\School;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class CurrentSchool
{
    public function handle(Request $request, Closure $next): Response
    {
        $identifier = trim((string) $request->route('school'));
        if ($identifier === '') {
            abort(404);
        }

        $school = null;

        if (ctype_digit($identifier)) {
            $school = School::find((int) $identifier);
        }

        if (!$school) {
            $school = School::whereRaw('LOWER(code) = ?', [strtolower($identifier)])->first();
        }

        if (!$school) {
            abort(404);
        }

        $role = session('role');
        if ($role !== 'developer') {
            $userSchoolId = session('school_id');
            if (!$userSchoolId || (int) $userSchoolId !== (int) $school->id) {
                abort(403);
            }
        }

        app()->instance('currentSchool', $school);
        View::share('currentSchool', $school);
        $prefix = '/' . rawurlencode($school->code);
        View::share('schoolRoutePrefix', $prefix);
        View::share('schoolRoute', fn (string $path = '') => $prefix . ($path !== '' ? '/' . ltrim($path, '/') : ''));

        return $next($request);
    }
}

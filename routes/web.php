<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\MajorStaffAssignmentController;
use App\Http\Controllers\MetaController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\MonitoringLogController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SupervisorController;
use App\Models\School;
use Illuminate\Support\Facades\Route;

Route::view('/introduction', 'introduction');

Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
Route::match(['get', 'post'], '/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth.session')->group(function () {
    Route::get('/', function () {
        if (session('role') === 'developer') {
            return view('dashboard');
        }

        $schoolCode = session('school_code');
        if (!$schoolCode) {
            $schoolId = session('school_id');
            abort_if(!$schoolId, 403, 'School assignment missing.');
            $school = School::find($schoolId);
            abort_if(!$school, 403, 'School assignment invalid.');
            $schoolCode = $school->code;
            session(['school_code' => $schoolCode]);
        }

        return redirect('/' . rawurlencode($schoolCode));
    });

    Route::middleware('developer')->group(function () {
        Route::prefix('developers')->group(function () {
            Route::get('/', [DeveloperController::class, 'index']);
            Route::get('/create', [DeveloperController::class, 'create']);
            Route::post('/', [DeveloperController::class, 'store']);
            Route::middleware('developer.self')->group(function () {
                Route::get('{id}/read', [DeveloperController::class, 'show']);
                Route::get('{id}/update', [DeveloperController::class, 'edit'])->name('developers.edit');
                Route::put('{id}', [DeveloperController::class, 'update']);
                Route::delete('{id}', [DeveloperController::class, 'destroy']);
            });
        });

        Route::prefix('schools')->group(function () {
            Route::get('/', [SchoolController::class, 'index']);
            Route::get('/create', [SchoolController::class, 'create']);
            Route::post('/', [SchoolController::class, 'store']);
            Route::get('{id}/read', [SchoolController::class, 'show']);
            Route::get('{id}/update', [SchoolController::class, 'edit']);
            Route::put('{id}', [SchoolController::class, 'update']);
            Route::delete('{id}', [SchoolController::class, 'destroy']);
        });
    });

    Route::prefix('{school}')->middleware('school')->group(function () {
        Route::get('/', function () {
            return view('dashboard');
        })->name('school.dashboard');

        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'redirect'])->name('index');
            Route::get('/profile', [SettingController::class, 'editProfile'])->name('profile');
            Route::put('/profile', [SettingController::class, 'updateProfile'])->name('profile.update');
            Route::get('/security', [SettingController::class, 'editSecurity'])->name('security');
            Route::put('/security', [SettingController::class, 'updateSecurity'])->name('security.update');
            Route::get('/environments', [SettingController::class, 'environments'])->name('environments');
        });

        Route::prefix('students')->group(function () {
            Route::get('/', [StudentController::class, 'index']);
            Route::get('/create', [StudentController::class, 'create']);
            Route::post('/', [StudentController::class, 'store']);
            Route::middleware('student.self')->group(function () {
                Route::get('{id}/read', [StudentController::class, 'show']);
                Route::get('{id}/update', [StudentController::class, 'edit'])->name('students.edit');
                Route::put('{id}', [StudentController::class, 'update']);
                Route::delete('{id}', [StudentController::class, 'destroy']);
            });
        });

        Route::prefix('supervisors')->group(function () {
            Route::get('/', [SupervisorController::class, 'index']);
            Route::get('/create', [SupervisorController::class, 'create']);
            Route::post('/', [SupervisorController::class, 'store']);
            Route::middleware('supervisor.self')->group(function () {
                Route::get('{id}/read', [SupervisorController::class, 'show']);
                Route::get('{id}/update', [SupervisorController::class, 'edit'])->name('supervisors.edit');
                Route::put('{id}', [SupervisorController::class, 'update']);
                Route::delete('{id}', [SupervisorController::class, 'destroy']);
            });
        });

        Route::prefix('major-contacts')->group(function () {
            Route::get('/', [MajorStaffAssignmentController::class, 'index']);
            Route::get('/create', [MajorStaffAssignmentController::class, 'create']);
            Route::post('/', [MajorStaffAssignmentController::class, 'store']);
            Route::get('{id}/update', [MajorStaffAssignmentController::class, 'edit']);
            Route::put('{id}', [MajorStaffAssignmentController::class, 'update']);
            Route::delete('{id}', [MajorStaffAssignmentController::class, 'destroy']);
        });

        Route::prefix('admins')->group(function () {
            Route::get('/', [AdminUserController::class, 'index']);
            Route::get('/create', [AdminUserController::class, 'create']);
            Route::post('/', [AdminUserController::class, 'store']);
            Route::middleware('admin.self')->group(function () {
                Route::get('{id}/read', [AdminUserController::class, 'show']);
                Route::get('{id}/update', [AdminUserController::class, 'edit'])->name('admins.edit');
                Route::put('{id}', [AdminUserController::class, 'update']);
                Route::delete('{id}', [AdminUserController::class, 'destroy']);
            });
        });

        Route::prefix('institutions')->group(function () {
            Route::get('/', [InstitutionController::class, 'index']);
            Route::get('/create', [InstitutionController::class, 'create']);
            Route::post('/', [InstitutionController::class, 'store']);
            Route::get('{id}/read', [InstitutionController::class, 'show']);
            Route::get('{id}/update', [InstitutionController::class, 'edit']);
            Route::put('{id}', [InstitutionController::class, 'update']);
            Route::delete('{id}', [InstitutionController::class, 'destroy']);
        });

        Route::prefix('applications')->group(function () {
            Route::get('/', [ApplicationController::class, 'index']);
            Route::get('/create', [ApplicationController::class, 'create']);
            Route::post('/', [ApplicationController::class, 'store']);
            Route::get('{id}/read', [ApplicationController::class, 'show']);
            Route::get('{id}/pdf', [ApplicationController::class, 'pdf']);
            Route::get('{id}/pdf/print', [ApplicationController::class, 'pdfPrint']);
            Route::get('{id}/update', [ApplicationController::class, 'edit']);
            Route::put('{id}', [ApplicationController::class, 'update']);
            Route::delete('{id}', [ApplicationController::class, 'destroy']);
        });

        Route::prefix('internships')->group(function () {
            Route::get('/', [InternshipController::class, 'index']);
            Route::get('/create', [InternshipController::class, 'create']);
            Route::post('/', [InternshipController::class, 'store']);
            Route::get('{id}/read', [InternshipController::class, 'show']);
            Route::get('{id}/update', [InternshipController::class, 'edit']);
            Route::put('{id}', [InternshipController::class, 'update']);
            Route::delete('{id}', [InternshipController::class, 'destroy']);
        });

        Route::prefix('monitorings')->group(function () {
            Route::get('/', [MonitoringLogController::class, 'index']);
            Route::get('/create', [MonitoringLogController::class, 'create']);
            Route::post('/', [MonitoringLogController::class, 'store']);
            Route::get('{id}/read', [MonitoringLogController::class, 'show']);
            Route::get('{id}/update', [MonitoringLogController::class, 'edit']);
            Route::put('{id}', [MonitoringLogController::class, 'update']);
            Route::delete('{id}', [MonitoringLogController::class, 'destroy']);
        });

        Route::prefix('meta')->group(function () {
            Route::get('/monitor-types', [MetaController::class, 'monitorTypes']);
            Route::get('/supervisors', [MetaController::class, 'supervisors']);
        });
    });
});

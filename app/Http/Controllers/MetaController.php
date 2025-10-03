<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MetaController extends Controller
{
    public function monitorTypes()
    {
        $driver = DB::getDriverName();
        if ($driver === 'pgsql') {
            $types = collect(DB::select("SELECT unnest(enum_range(NULL::monitor_type_enum)) AS type"))->pluck('type');
        } else {
            $types = collect(['weekly','issue','final','other']);
        }
        return response()->json($types);
    }

    public function supervisors(Request $request)
    {
        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 404, 'School context missing.');

        if (session('role') === 'student') {
            $query = DB::table('supervisor_details_view as sv')
                ->join('monitoring_logs as ml', 'sv.id', '=', 'ml.supervisor_id')
                ->join('internships as it', 'ml.internship_id', '=', 'it.id')
                ->where('it.student_id', $this->currentStudentId())
                ->where('sv.school_id', $schoolId)
                ->where('ml.school_id', $schoolId)
                ->where('it.school_id', $schoolId)
                ->select('sv.id', 'sv.name')
                ->distinct()
                ->orderBy('sv.name');
            if ($request->filled('internship_id')) {
                $query->where('ml.internship_id', $request->internship_id);
            }
            return response()->json($query->get());
        }

        $query = DB::table('supervisor_details_view')
            ->select('id','name')
            ->where('school_id', $schoolId)
            ->orderBy('name');
        if ($request->filled('internship_id')) {
            $query->join('internship_supervisors','supervisor_details_view.id','=','internship_supervisors.supervisor_id')
                  ->join('internships', 'internships.id', '=', 'internship_supervisors.internship_id')
                  ->where('internship_supervisors.internship_id', $request->internship_id)
                  ->where('internships.school_id', $schoolId);
        }
        $supervisors = $query->get();
        if ($supervisors->isEmpty()) {
            $supervisors = DB::table('supervisor_details_view')
                ->select('id','name')
                ->where('school_id', $schoolId)
                ->orderBy('name')
                ->get();
        }
        return response()->json($supervisors);
    }
}

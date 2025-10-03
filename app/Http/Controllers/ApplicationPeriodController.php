<?php

namespace App\Http\Controllers;

use App\Models\InstitutionQuota;
use App\Models\Period;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApplicationPeriodController extends Controller
{
    private function schoolIdOrFail(): int
    {
        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 404, 'School context missing.');

        return $schoolId;
    }

    public function store(Request $request): JsonResponse
    {
        abort_if(session('role') === 'student', 403);

        $schoolId = $this->schoolIdOrFail();

        $data = $request->validate([
            'institution_id' => [
                'required',
                Rule::exists('institutions', 'id')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'planned_start_date' => 'required|date',
            'quota' => 'required|integer|min:0',
        ]);

        $start = Carbon::parse($data['planned_start_date']);
        $term = $start->month >= 7 ? 2 : 1;

        $period = Period::firstOrCreate([
            'school_id' => $schoolId,
            'year' => $start->year,
            'term' => $term,
        ]);

        $quota = InstitutionQuota::firstOrNew([
            'institution_id' => (int) $data['institution_id'],
            'period_id' => $period->id,
            'school_id' => $schoolId,
        ]);

        if (!$quota->exists) {
            $quota->used = 0;
        }
        $quota->quota = (int) $data['quota'];
        $quota->save();

        return response()->json([
            'status' => 'ok',
            'period' => [
                'id' => (int) $period->id,
                'year' => (int) $period->year,
                'term' => (int) $period->term,
            ],
            'quota' => [
                'id' => (int) $quota->id,
                'quota' => (int) $quota->quota,
                'used' => (int) $quota->used,
            ],
        ], $quota->wasRecentlyCreated ? 201 : 200);
    }
}

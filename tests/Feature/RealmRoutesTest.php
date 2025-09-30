<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RealmRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_developer_can_access_student_update_in_other_realm(): void
    {
        $developer = User::where('role', 'developer')->firstOrFail();
        $student = Student::where('school_id', 2)->with('school')->firstOrFail();

        $this
            ->withSession([
                'user_id' => $developer->id,
                'role' => 'developer',
                'school_id' => null,
            ])
            ->get('/' . rawurlencode($student->school->code) . "/students/{$student->id}/update")
            ->assertOk();
    }
}

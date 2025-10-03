<?php

namespace Tests\Feature;

use App\Models\School;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SchoolRealmAccessTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('school')->get('/realm-probe/{school}', function () {
            return response()->json([
                'school_id' => app()->bound('currentSchool') ? app('currentSchool')->id : null,
            ]);
        });

        $this->alpha = School::factory()->create();
        $this->beta = School::factory()->create();
    }

    public function test_admin_without_school_session_is_blocked(): void
    {
        $this->withSession(['role' => 'admin'])
            ->get('/realm-probe/' . $this->alpha->id)
            ->assertStatus(403);
    }

    public function test_student_cannot_enter_other_school_realm(): void
    {
        $this->withSession(['role' => 'student', 'school_id' => $this->beta->id])
            ->get('/realm-probe/' . $this->alpha->id)
            ->assertStatus(403);
    }

    public function test_developer_can_access_any_school_realm(): void
    {
        $this->withSession(['role' => 'developer'])
            ->get('/realm-probe/' . $this->beta->id)
            ->assertOk()
            ->assertJson(['school_id' => $this->beta->id]);
    }

    public function test_matching_school_context_allows_access(): void
    {
        $this->withSession(['role' => 'admin', 'school_id' => $this->alpha->id])
            ->get('/realm-probe/' . $this->alpha->id)
            ->assertOk()
            ->assertJson(['school_id' => $this->alpha->id]);
    }
}

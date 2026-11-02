<?php

namespace Tests\Feature;

use App\Models\FreeTrainingRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FreeTrainingRegistrationAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_free_training_registrations(): void
    {
        $this->get(route('admin.free-training.index'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_view_free_training_registrations(): void
    {
        $user = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        FreeTrainingRegistration::query()->create([
            'reference' => 'FTR-CT-001',
            'name' => 'Ada Student',
            'email' => 'ada.student@example.com',
            'phone' => '+234 800 111 2222',
            'date_of_birth' => '2002-04-18',
            'gender' => 'female',
            'state' => 'FCT',
            'city' => 'Abuja',
            'program' => 'ai-automation',
            'skill_level' => 'beginner',
            'has_tech_experience' => false,
            'status' => 'registered',
        ]);

        $this->actingAs($user)
            ->get(route('admin.free-training.index'))
            ->assertOk()
            ->assertViewIs('admin.free-training.index')
            ->assertSee('Free Training')
            ->assertSee('FTR-CT-001')
            ->assertSee('Ada Student')
            ->assertSee('AI Automation');

        $this->actingAs($user)
            ->get(route('admin.free-training.show', 'FTR-CT-001'))
            ->assertOk()
            ->assertSee('Ada Student')
            ->assertSee('AI Automation')
            ->assertSee('Abuja');
    }
}

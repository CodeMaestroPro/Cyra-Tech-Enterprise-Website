<?php

namespace Tests\Feature;

use App\Models\ItStudentRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItStudentRegistrationAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_it_registrations(): void
    {
        $this->get(route('admin.it.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_it_registrations(): void
    {
        ItStudentRegistration::query()->create([
            'reference' => 'ITS-CT-001',
            'name' => 'Ada Student',
            'email' => 'ada.student@example.com',
            'phone' => '+234 800 111 2222',
            'institution' => 'University of Abuja',
            'course_of_study' => 'Computer Science',
            'academic_level' => '300l',
            'interest_area' => 'web-development',
            'availability' => 'internship',
            'motivation' => 'I want to learn real-world software development.',
            'status' => 'registered',
        ]);

        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.it.index'))
            ->assertOk()
            ->assertViewIs('admin.it.index')
            ->assertSee('IT Registrations')
            ->assertSee('Ada Student')
            ->assertSee('ITS-CT-001')
            ->assertSee('Web Development');

        $this->actingAs($admin)
            ->get(route('admin.it.show', 'ITS-CT-001'))
            ->assertOk()
            ->assertSee('University of Abuja')
            ->assertSee('I want to learn real-world software development.');
    }
}

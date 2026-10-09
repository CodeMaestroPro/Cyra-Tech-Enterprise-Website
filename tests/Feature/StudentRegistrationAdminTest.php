<?php

namespace Tests\Feature;

use App\Models\StudentRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentRegistrationAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_student_registrations(): void
    {
        $this->get(route('admin.students.index'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_view_student_registrations(): void
    {
        $user = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        StudentRegistration::query()->create([
            'reference' => 'STR-CT-001',
            'name' => 'Ada Student',
            'email' => 'ada.student@example.com',
            'phone' => '+234 800 111 2222',
            'date_of_birth' => '2002-04-18',
            'gender' => 'female',
            'state' => 'FCT',
            'city' => 'Abuja',
            'education_level' => 'bachelors',
            'field_of_study' => 'Computer Science',
            'institution' => 'Abuja, FCT',
            'course_of_study' => 'Computer Science',
            'academic_level' => 'bachelors',
            'skill_level' => 'beginner',
            'has_tech_experience' => false,
            'program' => 'frontend-development',
            'amount' => 150000,
            'payment_plan' => 'full',
            'amount_paid' => 150000,
            'amount_remaining' => 0,
            'currency' => 'NGN',
            'motivation' => 'I want to become a professional frontend developer.',
            'status' => 'registered',
            'payment_status' => 'paid',
            'paystack_reference' => 'STR-CT-001-TESTREF1',
            'paid_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('admin.students.index'))
            ->assertOk()
            ->assertSee('Student Registrations')
            ->assertSee('STR-CT-001')
            ->assertSee('Ada Student')
            ->assertSee('Frontend Development');

        $this->actingAs($user)
            ->get(route('admin.students.show', 'STR-CT-001'))
            ->assertOk()
            ->assertSee('Ada Student')
            ->assertSee('₦150,000')
            ->assertSee('Paid');
    }
}

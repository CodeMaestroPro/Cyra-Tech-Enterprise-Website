<?php

namespace Tests\Feature;

use App\Mail\ItStudentRegistrationSubmitted;
use App\Models\ItStudentRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ItStudentRegistrationPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        Mail::fake();
    }

    public function test_it_page_renders_registration_form(): void
    {
        $response = $this->get(route('it'));

        $response
            ->assertOk()
            ->assertViewIs('it.index')
            ->assertSee('Start your journey with Cyra-Tech')
            ->assertSee('Register in 3 easy steps')
            ->assertSee('Complete Registration')
            ->assertSee('Personal')
            ->assertSee('Education')
            ->assertSee('Interest');
    }

    public function test_it_registration_creates_record_and_shows_acknowledgement(): void
    {
        $response = $this->post(route('it.store'), [
            'name' => 'Ada Student',
            'email' => 'ada.student@example.com',
            'phone' => '+234 800 111 2222',
            'institution' => 'University of Abuja',
            'course_of_study' => 'Computer Science',
            'academic_level' => '300l',
            'interest_area' => 'web-development',
            'availability' => 'internship',
            'motivation' => 'I want to learn real-world software development with Cyra-Tech.',
        ]);

        $response
            ->assertRedirect(route('it'))
            ->assertSessionHas('success')
            ->assertSessionHas('registration_reference');

        $this->assertDatabaseCount('it_student_registrations', 1);

        $registration = ItStudentRegistration::query()->first();

        $this->assertSame('Ada Student', $registration->name);
        $this->assertSame('registered', $registration->status);
        $this->assertSame('ITS-CT-001', $registration->reference);

        $this->post(route('it.store'), [
            'name' => 'Bola Student',
            'email' => 'bola.student@example.com',
            'phone' => '+234 800 111 3333',
            'institution' => 'University of Abuja',
            'course_of_study' => 'Computer Science',
            'academic_level' => '200l',
            'interest_area' => 'ui-ux-design',
            'availability' => 'siwes',
            'motivation' => 'I want to grow my design skills with Cyra-Tech.',
        ])->assertRedirect(route('it'));

        $this->assertSame('ITS-CT-002', ItStudentRegistration::query()->where('email', 'bola.student@example.com')->value('reference'));

        Mail::assertSent(ItStudentRegistrationSubmitted::class);

        $followUp = $this
            ->withSession([
                'success' => 'Congratulations! Your IT student registration was successful.',
                'registration_reference' => $registration->reference,
            ])
            ->get(route('it'));

        $followUp
            ->assertOk()
            ->assertSee('Acknowledgement Slip')
            ->assertSee($registration->reference)
            ->assertSee('Ada Student')
            ->assertSee('Send acknowledgement on WhatsApp');
    }

    public function test_it_registration_validation_errors_are_returned(): void
    {
        $response = $this->from(route('it'))->post(route('it.store'), [
            'name' => '',
            'email' => 'bad-email',
            'phone' => '',
            'institution' => '',
            'course_of_study' => '',
            'academic_level' => 'invalid',
            'interest_area' => 'invalid',
            'availability' => 'invalid',
            'motivation' => '',
        ]);

        $response
            ->assertRedirect(route('it'))
            ->assertSessionHasErrors([
                'name',
                'email',
                'phone',
                'institution',
                'course_of_study',
                'academic_level',
                'interest_area',
                'availability',
                'motivation',
            ]);

        $this->assertDatabaseCount('it_student_registrations', 0);
    }
}

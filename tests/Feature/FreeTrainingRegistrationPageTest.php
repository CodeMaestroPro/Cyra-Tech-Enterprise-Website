<?php

namespace Tests\Feature;

use App\Mail\FreeTrainingRegistrationSubmitted;
use App\Models\FreeTrainingRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class FreeTrainingRegistrationPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        Mail::fake();
    }

    public function test_free_training_page_renders_onboarding_form(): void
    {
        $response = $this->get(route('free-training'));

        $response
            ->assertOk()
            ->assertViewIs('free-training.index')
            ->assertSee('Learn in-demand tech skills at no cost')
            ->assertSee('Onboard in 2 steps')
            ->assertSee('Complete free enrollment')
            ->assertSee('Software Development')
            ->assertSee('AI Automation')
            ->assertSee('Data Analysis')
            ->assertSee('Cyber Security')
            ->assertDontSee('Highest level of education')
            ->assertDontSee('Field of study')
            ->assertDontSee('Proceed to Paystack');
    }

    public function test_free_training_registration_creates_record_and_shows_confirmation(): void
    {
        $response = $this->post(route('free-training.store'), $this->validPayload());

        $response
            ->assertRedirect(route('free-training'))
            ->assertSessionHas('success')
            ->assertSessionHas('free_training_reference');

        $registration = FreeTrainingRegistration::query()->first();

        $this->assertNotNull($registration);
        $this->assertSame('Ada Student', $registration->name);
        $this->assertSame('software-development', $registration->program);
        $this->assertSame('beginner', $registration->skill_level);
        $this->assertFalse($registration->has_tech_experience);
        $this->assertSame('FTR-CT-001', $registration->reference);

        Mail::assertSent(FreeTrainingRegistrationSubmitted::class);

        $followUp = $this
            ->withSession([
                'success' => 'You are enrolled. Print or save your confirmation on this page.',
                'free_training_reference' => $registration->reference,
            ])
            ->get(route('free-training'));

        $followUp
            ->assertOk()
            ->assertSee('Enrollment Confirmation')
            ->assertSee('FTR-CT-001')
            ->assertSee('Ada Student')
            ->assertSee('Software Development')
            ->assertSee('Print / save confirmation')
            ->assertDontSee('Send receipt on WhatsApp');
    }

    public function test_enrollment_verification_confirms_authentic_registration(): void
    {
        FreeTrainingRegistration::query()->create($this->modelAttributes());

        $token = hash_hmac('sha256', 'FTR-CT-001', (string) config('app.key'));

        $this->get(route('free-training.verify', [
            'reference' => 'FTR-CT-001',
            'token' => $token,
        ]))
            ->assertOk()
            ->assertSee('This Cyra-Tech enrollment is verified')
            ->assertSee('Ada Student')
            ->assertSee('FTR-CT-001');
    }

    public function test_enrollment_verification_rejects_invalid_token(): void
    {
        FreeTrainingRegistration::query()->create($this->modelAttributes());

        $this->get(route('free-training.verify', [
            'reference' => 'FTR-CT-001',
            'token' => str_repeat('a', 64),
        ]))
            ->assertOk()
            ->assertSee('This enrollment could not be verified')
            ->assertDontSee('Ada Student');
    }

    public function test_free_training_validation_errors_are_returned(): void
    {
        $response = $this->from(route('free-training'))->post(route('free-training.store'), [
            'name' => '',
            'email' => 'bad-email',
            'phone' => '',
            'date_of_birth' => '',
            'gender' => 'invalid',
            'state' => 'invalid',
            'city' => '',
            'program' => 'invalid',
            'skill_level' => 'invalid',
            'has_tech_experience' => '',
        ]);

        $response
            ->assertRedirect(route('free-training'))
            ->assertSessionHasErrors([
                'name',
                'email',
                'phone',
                'date_of_birth',
                'gender',
                'state',
                'city',
                'program',
                'skill_level',
                'has_tech_experience',
            ]);

        $this->assertDatabaseCount('free_training_registrations', 0);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Ada Student',
            'email' => 'ada.student@example.com',
            'phone' => '+234 800 111 2222',
            'date_of_birth' => '2002-04-18',
            'gender' => 'female',
            'state' => 'FCT',
            'city' => 'Abuja',
            'program' => 'software-development',
            'skill_level' => 'beginner',
            'has_tech_experience' => 'no',
        ], $overrides);
    }

    /**
     * @return array<string, mixed>
     */
    private function modelAttributes(): array
    {
        return [
            'reference' => 'FTR-CT-001',
            'name' => 'Ada Student',
            'email' => 'ada.student@example.com',
            'phone' => '+234 800 111 2222',
            'date_of_birth' => '2002-04-18',
            'gender' => 'female',
            'state' => 'FCT',
            'city' => 'Abuja',
            'program' => 'software-development',
            'skill_level' => 'beginner',
            'has_tech_experience' => false,
            'status' => 'registered',
        ];
    }
}

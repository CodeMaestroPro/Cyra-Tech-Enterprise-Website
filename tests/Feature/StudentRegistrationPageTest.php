<?php

namespace Tests\Feature;

use App\Mail\StudentRegistrationSubmitted;
use App\Models\StudentRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StudentRegistrationPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        Mail::fake();

        config([
            'services.paystack.public_key' => 'pk_test_example',
            'services.paystack.secret_key' => 'sk_test_example',
        ]);
    }

    public function test_students_page_renders_registration_form(): void
    {
        $response = $this->get(route('students'));

        $response
            ->assertOk()
            ->assertViewIs('students.index')
            ->assertSee('Enroll in a Cyra-Tech training program')
            ->assertSee('Register and pay in 3 steps')
            ->assertSee('Proceed to Paystack')
            ->assertSee('Frontend Development')
            ->assertSee('Date of birth')
            ->assertSee('Highest level of education')
            ->assertSee('Current tech skill level')
            ->assertSee('Pay in full')
            ->assertSee('Pay in installments')
            ->assertSee('Pay remaining balance')
            ->assertDontSee('Available programs');
    }

    public function test_students_registration_initiates_full_paystack_payment(): void
    {
        Http::fake([
            'api.paystack.co/transaction/initialize' => Http::response([
                'status' => true,
                'message' => 'Authorization URL created',
                'data' => [
                    'authorization_url' => 'https://checkout.paystack.com/test-session',
                    'access_code' => 'access_code_123',
                    'reference' => 'STR-CT-001-ABCD1234',
                ],
            ], 200),
        ]);

        $response = $this->post(route('students.store'), $this->validPayload());

        $response->assertRedirect('https://checkout.paystack.com/test-session');

        $registration = StudentRegistration::query()->first();

        $this->assertSame('full', $registration->payment_plan);
        $this->assertSame($this->programAmount('frontend-development'), $registration->amount);
        $this->assertSame(0, $registration->amount_remaining);
        $this->assertSame('beginner', $registration->skill_level);
        $this->assertFalse($registration->has_tech_experience);

        Mail::assertNothingSent();
    }

    public function test_students_registration_initiates_installment_payment_for_sixty_percent(): void
    {
        Http::fake([
            'api.paystack.co/transaction/initialize' => Http::response([
                'status' => true,
                'message' => 'Authorization URL created',
                'data' => [
                    'authorization_url' => 'https://checkout.paystack.com/installment-session',
                    'access_code' => 'access_code_456',
                    'reference' => 'STR-CT-001-INST1234',
                ],
            ], 200),
        ]);

        $response = $this->post(route('students.store'), $this->validPayload([
            'name' => 'Bola Student',
            'email' => 'bola.student@example.com',
            'phone' => '+234 800 111 3333',
            'payment_plan' => 'installment',
        ]));

        $response->assertRedirect('https://checkout.paystack.com/installment-session');

        $registration = StudentRegistration::query()->first();

        $expectedAmount = $this->programAmount('frontend-development');
        $expectedBalance = (int) round($expectedAmount * 0.4);

        $this->assertSame('installment', $registration->payment_plan);
        $this->assertSame($expectedAmount, $registration->amount);
        $this->assertSame($expectedBalance, $registration->amount_remaining);
    }

    public function test_payment_callback_verifies_and_shows_receipt(): void
    {
        $registration = StudentRegistration::query()->create([
            'reference' => 'STR-CT-001',
            'name' => 'Ada Student',
            'email' => 'ada.student@example.com',
            'phone' => '+234 800 111 2222',
            'institution' => 'University of Abuja',
            'course_of_study' => 'Computer Science',
            'academic_level' => '300l',
            'program' => 'frontend-development',
            'amount' => 150000,
            'payment_plan' => 'full',
            'amount_paid' => 0,
            'amount_remaining' => 0,
            'currency' => 'NGN',
            'motivation' => 'I want to become a professional frontend developer.',
            'status' => 'pending',
            'payment_status' => 'pending',
            'paystack_reference' => 'STR-CT-001-TESTREF1',
        ]);

        Http::fake([
            'api.paystack.co/transaction/verify/*' => Http::response([
                'status' => true,
                'message' => 'Verification successful',
                'data' => [
                    'status' => 'success',
                    'amount' => 15000000,
                    'paid_at' => now()->toIso8601String(),
                ],
            ], 200),
        ]);

        $response = $this->get(route('students.payment.callback', [
            'reference' => $registration->paystack_reference,
        ]));

        $response
            ->assertRedirect(route('students'))
            ->assertSessionHas('success')
            ->assertSessionHas('registration_reference', 'STR-CT-001');

        $registration->refresh();

        $this->assertSame('paid', $registration->payment_status);
        $this->assertSame(150000, $registration->amount_paid);

        Mail::assertSent(StudentRegistrationSubmitted::class);

        $followUp = $this
            ->withSession([
                'success' => 'Payment successful! Your training registration is complete.',
                'registration_reference' => $registration->reference,
            ])
            ->get(route('students'));

        $followUp
            ->assertOk()
            ->assertSee('Official Receipt')
            ->assertSee('Scan to verify this receipt')
            ->assertSee('Print / save receipt')
            ->assertSee('STR-CT-001')
            ->assertSee('Ada Student')
            ->assertSee('₦150,000')
            ->assertDontSee('Send receipt on WhatsApp')
            ->assertDontSee('wa.me');
    }

    public function test_receipt_verification_page_confirms_authentic_payment(): void
    {
        StudentRegistration::query()->create([
            'reference' => 'STR-CT-001',
            'name' => 'Ada Student',
            'email' => 'ada.student@example.com',
            'phone' => '+234 800 111 2222',
            'institution' => 'University of Abuja',
            'course_of_study' => 'Computer Science',
            'academic_level' => '300l',
            'program' => 'frontend-development',
            'amount' => 150000,
            'payment_plan' => 'full',
            'amount_paid' => 150000,
            'amount_remaining' => 0,
            'currency' => 'NGN',
            'motivation' => '',
            'status' => 'registered',
            'payment_status' => 'paid',
            'paystack_reference' => 'STR-CT-001-TESTREF1',
            'paid_at' => now(),
        ]);

        $token = hash_hmac('sha256', 'STR-CT-001', (string) config('app.key'));

        $this->get(route('students.verify', [
            'reference' => 'STR-CT-001',
            'token' => $token,
        ]))
            ->assertOk()
            ->assertSee('This Cyra-Tech receipt is verified')
            ->assertSee('Ada Student')
            ->assertSee('STR-CT-001')
            ->assertSee('Official Receipt');
    }

    public function test_receipt_verification_rejects_invalid_token(): void
    {
        StudentRegistration::query()->create([
            'reference' => 'STR-CT-001',
            'name' => 'Ada Student',
            'email' => 'ada.student@example.com',
            'phone' => '+234 800 111 2222',
            'institution' => 'University of Abuja',
            'course_of_study' => 'Computer Science',
            'academic_level' => '300l',
            'program' => 'frontend-development',
            'amount' => 150000,
            'payment_plan' => 'full',
            'amount_paid' => 150000,
            'amount_remaining' => 0,
            'currency' => 'NGN',
            'motivation' => '',
            'status' => 'registered',
            'payment_status' => 'paid',
            'paystack_reference' => 'STR-CT-001-TESTREF1',
            'paid_at' => now(),
        ]);

        $this->get(route('students.verify', [
            'reference' => 'STR-CT-001',
            'token' => str_repeat('a', 64),
        ]))
            ->assertOk()
            ->assertSee('This receipt could not be verified')
            ->assertDontSee('Ada Student');
    }

    public function test_installment_callback_marks_registration_as_partial(): void
    {
        $registration = StudentRegistration::query()->create([
            'reference' => 'STR-CT-002',
            'name' => 'Bola Student',
            'email' => 'bola.student@example.com',
            'phone' => '+234 800 111 3333',
            'institution' => 'University of Abuja',
            'course_of_study' => 'Computer Science',
            'academic_level' => '200l',
            'program' => 'frontend-development',
            'amount' => 150000,
            'payment_plan' => 'installment',
            'amount_paid' => 0,
            'amount_remaining' => 60000,
            'currency' => 'NGN',
            'motivation' => 'I want to grow my frontend skills.',
            'status' => 'pending',
            'payment_status' => 'pending',
            'paystack_reference' => 'STR-CT-002-TESTREF1',
        ]);

        Http::fake([
            'api.paystack.co/transaction/verify/*' => Http::response([
                'status' => true,
                'message' => 'Verification successful',
                'data' => [
                    'status' => 'success',
                    'amount' => 9000000,
                    'paid_at' => now()->toIso8601String(),
                ],
            ], 200),
        ]);

        $this->get(route('students.payment.callback', [
            'reference' => $registration->paystack_reference,
        ]))->assertRedirect(route('students'));

        $registration->refresh();

        $this->assertSame('partial', $registration->payment_status);
        $this->assertSame(90000, $registration->amount_paid);
        $this->assertSame(60000, $registration->amount_remaining);
    }

    public function test_students_registration_validation_errors_are_returned(): void
    {
        $response = $this->from(route('students'))->post(route('students.store'), [
            'name' => '',
            'email' => 'bad-email',
            'phone' => '',
            'date_of_birth' => '',
            'gender' => 'invalid',
            'state' => 'invalid',
            'city' => '',
            'education_level' => 'invalid',
            'field_of_study' => '',
            'program' => 'invalid',
            'skill_level' => 'invalid',
            'has_tech_experience' => '',
            'payment_plan' => 'invalid',
        ]);

        $response
            ->assertRedirect(route('students'))
            ->assertSessionHasErrors([
                'name',
                'email',
                'phone',
                'date_of_birth',
                'gender',
                'state',
                'city',
                'education_level',
                'field_of_study',
                'program',
                'skill_level',
                'has_tech_experience',
                'payment_plan',
            ]);

        $this->assertDatabaseCount('student_registrations', 0);
    }

    public function test_student_can_look_up_registration_and_see_remaining_balance(): void
    {
        StudentRegistration::query()->create([
            'reference' => 'STR-CT-009',
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
            'program' => 'frontend-development',
            'skill_level' => 'beginner',
            'has_tech_experience' => false,
            'amount' => 150000,
            'payment_plan' => 'installment',
            'amount_paid' => 90000,
            'amount_remaining' => 60000,
            'currency' => 'NGN',
            'motivation' => '',
            'status' => 'registered',
            'payment_status' => 'partial',
            'paystack_reference' => 'STR-CT-009-TESTREF1',
        ]);

        $this->from(route('students'))
            ->followingRedirects()
            ->post(route('students.lookup'), [
                'lookup_reference' => 'str-ct-009',
                'lookup_email' => 'ada.student@example.com',
            ])
            ->assertOk()
            ->assertSee('Pay remaining ₦60,000')
            ->assertSee('STR-CT-009');
    }

    public function test_lookup_rejects_unknown_reference_or_email(): void
    {
        $this->from(route('students'))
            ->post(route('students.lookup'), [
                'lookup_reference' => 'STR-CT-999',
                'lookup_email' => 'nobody@example.com',
            ])
            ->assertRedirect(route('students'))
            ->assertSessionHas('error');
    }

    private function programAmount(string $slug): int
    {
        $program = collect(config('cyra.student_registrations.programs', []))->firstWhere('slug', $slug);

        return (int) ($program['amount'] ?? 0);
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
            'education_level' => 'bachelors',
            'field_of_study' => 'Computer Science',
            'program' => 'frontend-development',
            'skill_level' => 'beginner',
            'has_tech_experience' => 'no',
            'payment_plan' => 'full',
        ], $overrides);
    }
}

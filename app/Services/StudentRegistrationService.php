<?php

namespace App\Services;

use App\Mail\StudentRegistrationSubmitted;
use App\Models\StudentRegistration;
use App\Repositories\StudentRegistrationRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StudentRegistrationService extends BaseService
{
    public function __construct(
        private readonly StudentRegistrationRepository $studentRegistrationRepository,
        private readonly PaystackService $paystackService,
        private readonly QrCodeService $qrCodeService,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getPage(): array
    {
        return [
            'seo' => $this->getSeoMeta(),
            'hero' => config('cyra.student_registrations.hero', []),
            'form' => config('cyra.student_registrations.form', []),
            'highlights' => config('cyra.student_registrations.highlights', []),
            'programs' => $this->getPrograms(),
            'education_levels' => config('cyra.student_registrations.education_levels', []),
            'genders' => config('cyra.student_registrations.genders', []),
            'states' => config('cyra.student_registrations.states', []),
            'skill_levels' => config('cyra.student_registrations.skill_levels', []),
            'tech_experience' => config('cyra.student_registrations.tech_experience', []),
            'payment' => config('cyra.student_registrations.payment', []),
            'acknowledgement' => config('cyra.student_registrations.acknowledgement', []),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getPrograms(): array
    {
        return collect(config('cyra.student_registrations.programs', []))
            ->map(function (array $program): array {
                $amount = (int) ($program['amount'] ?? 0);

                return [
                    'slug' => $program['slug'],
                    'label' => $program['label'],
                    'description' => $program['description'] ?? '',
                    'amount' => $amount,
                    'amount_formatted' => $this->formatAmount($amount),
                    'amount_kobo' => $amount * 100,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public function getProgramOptions(): array
    {
        return collect($this->getPrograms())
            ->pluck('label', 'slug')
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public function getEducationLevelOptions(): array
    {
        return collect(config('cyra.student_registrations.education_levels', []))
            ->pluck('label', 'slug')
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public function getGenderOptions(): array
    {
        return collect(config('cyra.student_registrations.genders', []))
            ->pluck('label', 'slug')
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public function getStateOptions(): array
    {
        return collect(config('cyra.student_registrations.states', []))
            ->mapWithKeys(fn (string $state) => [$state => $state])
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public function getSkillLevelOptions(): array
    {
        return collect(config('cyra.student_registrations.skill_levels', []))
            ->pluck('label', 'slug')
            ->all();
    }

    public function getProgramAmount(string $slug): ?int
    {
        $program = collect($this->getPrograms())->firstWhere('slug', $slug);

        return $program ? (int) $program['amount'] : null;
    }

    /**
     * @return array{due_now: int, balance: int}
     */
    public function calculatePaymentBreakdown(int $totalAmount, string $paymentPlan): array
    {
        if ($paymentPlan === 'installment') {
            $initialPercent = (int) config('cyra.student_registrations.payment.installment_initial_percent', 60);
            $dueNow = (int) round($totalAmount * ($initialPercent / 100));

            return [
                'due_now' => $dueNow,
                'balance' => max(0, $totalAmount - $dueNow),
            ];
        }

        return [
            'due_now' => $totalAmount,
            'balance' => 0,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{success: bool, authorization_url?: string, reference?: string, message?: string}
     */
    public function initiateRegistration(array $data, ?string $ipAddress = null): array
    {
        $amount = $this->getProgramAmount($data['program']);

        if ($amount === null) {
            return [
                'success' => false,
                'message' => 'Please select a valid training program.',
            ];
        }

        $paymentPlan = (string) ($data['payment_plan'] ?? 'full');
        $breakdown = $this->calculatePaymentBreakdown($amount, $paymentPlan);
        $reference = $this->generateReference();
        $paystackReference = $this->generatePaystackReference($reference);

        $hasTechExperience = ($data['has_tech_experience'] ?? 'no') === 'yes';

        $registration = $this->studentRegistrationRepository->createRegistration([
            'reference' => $reference,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'date_of_birth' => $data['date_of_birth'],
            'gender' => $data['gender'],
            'state' => $data['state'],
            'city' => $data['city'],
            'education_level' => $data['education_level'],
            'field_of_study' => $data['field_of_study'],
            'institution' => trim($data['city'].', '.$data['state']),
            'course_of_study' => $data['field_of_study'],
            'academic_level' => $data['education_level'],
            'program' => $data['program'],
            'skill_level' => $data['skill_level'],
            'has_tech_experience' => $hasTechExperience,
            'amount' => $amount,
            'payment_plan' => $paymentPlan,
            'amount_paid' => 0,
            'amount_remaining' => $breakdown['balance'],
            'currency' => config('cyra.student_registrations.currency', 'NGN'),
            'motivation' => '',
            'status' => 'pending',
            'payment_status' => 'pending',
            'paystack_reference' => $paystackReference,
            'ip_address' => $ipAddress,
        ]);

        $payment = $this->paystackService->initializeTransaction(
            email: $registration->email,
            amountInKobo: $breakdown['due_now'] * 100,
            reference: $paystackReference,
            callbackUrl: route('students.payment.callback'),
            metadata: [
                'registration_reference' => $registration->reference,
                'program' => $registration->program,
                'student_name' => $registration->name,
                'payment_plan' => $paymentPlan,
                'payment_phase' => 'initial',
            ],
        );

        if (! $payment['success'] || empty($payment['authorization_url'])) {
            $registration->update(['payment_status' => 'failed']);

            return [
                'success' => false,
                'message' => $payment['message'] ?? 'Unable to start payment. Please try again.',
            ];
        }

        if (! empty($payment['reference']) && $payment['reference'] !== $paystackReference) {
            $this->studentRegistrationRepository->updateRegistration($registration, [
                'paystack_reference' => $payment['reference'],
            ]);
        }

        return [
            'success' => true,
            'authorization_url' => $payment['authorization_url'],
            'reference' => $registration->reference,
        ];
    }

    /**
     * @return array{success: bool, authorization_url?: string, message?: string}
     */
    public function initiateBalancePayment(string $reference): array
    {
        $registration = $this->studentRegistrationRepository->findByReference($reference);

        if ($registration === null) {
            return [
                'success' => false,
                'message' => 'Registration not found.',
            ];
        }

        if ($registration->payment_status === 'paid' || $registration->amount_remaining <= 0) {
            return [
                'success' => false,
                'message' => 'This registration has no outstanding balance.',
            ];
        }

        if ($registration->payment_status === 'pending') {
            return [
                'success' => false,
                'message' => 'Please complete your initial payment first.',
            ];
        }

        $balanceReference = $this->generatePaystackReference($registration->reference.'-BAL');

        $this->studentRegistrationRepository->updateRegistration($registration, [
            'balance_paystack_reference' => $balanceReference,
        ]);

        $payment = $this->paystackService->initializeTransaction(
            email: $registration->email,
            amountInKobo: $registration->amount_remaining * 100,
            reference: $balanceReference,
            callbackUrl: route('students.payment.callback'),
            metadata: [
                'registration_reference' => $registration->reference,
                'program' => $registration->program,
                'student_name' => $registration->name,
                'payment_plan' => $registration->payment_plan,
                'payment_phase' => 'balance',
            ],
        );

        if (! $payment['success'] || empty($payment['authorization_url'])) {
            return [
                'success' => false,
                'message' => $payment['message'] ?? 'Unable to start balance payment. Please try again.',
            ];
        }

        if (! empty($payment['reference']) && $payment['reference'] !== $balanceReference) {
            $this->studentRegistrationRepository->updateRegistration($registration, [
                'balance_paystack_reference' => $payment['reference'],
            ]);
        }

        return [
            'success' => true,
            'authorization_url' => $payment['authorization_url'],
        ];
    }

    public function verifyAndCompletePayment(string $paystackReference): ?array
    {
        $registration = $this->studentRegistrationRepository->findByPaystackReference($paystackReference);

        if ($registration === null) {
            return null;
        }

        if ($registration->payment_status === 'paid') {
            return $this->formatRegistration($registration);
        }

        $isBalancePayment = $registration->balance_paystack_reference === $paystackReference;

        if ($isBalancePayment && $registration->payment_status !== 'partial') {
            return null;
        }

        if (! $isBalancePayment && $registration->payment_status === 'partial') {
            return null;
        }

        $verification = $this->paystackService->verifyTransaction($paystackReference);

        if (! $verification['success']) {
            if (! $isBalancePayment) {
                $this->studentRegistrationRepository->updateRegistration($registration, [
                    'payment_status' => 'failed',
                ]);
            }

            return null;
        }

        $expectedAmount = $isBalancePayment
            ? $registration->amount_remaining * 100
            : $this->calculatePaymentBreakdown((int) $registration->amount, $registration->payment_plan)['due_now'] * 100;

        if (isset($verification['amount']) && (int) $verification['amount'] !== $expectedAmount) {
            Log::warning('Paystack amount mismatch.', [
                'reference' => $registration->reference,
                'expected' => $expectedAmount,
                'received' => $verification['amount'],
            ]);

            return null;
        }

        $paidAt = filled($verification['paid_at'] ?? null)
            ? now()->parse($verification['paid_at'])
            : now();

        if ($isBalancePayment) {
            $this->studentRegistrationRepository->updateRegistration($registration, [
                'amount_paid' => (int) $registration->amount,
                'amount_remaining' => 0,
                'payment_status' => 'paid',
                'status' => 'registered',
                'paid_at' => $paidAt,
            ]);
        } else {
            $paidAmount = (int) round($expectedAmount / 100);
            $isPartial = $registration->payment_plan === 'installment' && $registration->amount_remaining > 0;

            $this->studentRegistrationRepository->updateRegistration($registration, [
                'amount_paid' => $paidAmount,
                'payment_status' => $isPartial ? 'partial' : 'paid',
                'status' => 'registered',
                'paid_at' => $paidAt,
            ]);
        }

        $registration->refresh();
        $this->sendRegistrationNotification($registration);

        return $this->formatRegistration($registration);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function handleWebhookEvent(array $payload): bool
    {
        $event = $payload['event'] ?? null;

        if ($event !== 'charge.success') {
            return true;
        }

        $data = $payload['data'] ?? [];
        $reference = $data['reference'] ?? null;

        if (! filled($reference)) {
            return false;
        }

        return $this->verifyAndCompletePayment((string) $reference) !== null;
    }

    public function findRegistrationByReference(string $reference): ?array
    {
        $registration = $this->studentRegistrationRepository->findByReference($reference);

        if ($registration === null) {
            return null;
        }

        return $this->formatRegistration($registration, true);
    }

    public function findRegistrationForLookup(string $reference, string $email): ?array
    {
        $registration = $this->studentRegistrationRepository->findByReferenceAndEmail($reference, $email);

        if ($registration === null) {
            return null;
        }

        return $this->formatRegistration($registration, true);
    }

    public function findVerifiedRegistration(string $reference, string $token): ?array
    {
        $registration = $this->studentRegistrationRepository->findByReference($reference);

        if ($registration === null) {
            return null;
        }

        if (! hash_equals($this->verificationToken($registration->reference), $token)) {
            return null;
        }

        if (! in_array($registration->payment_status, ['paid', 'partial'], true)) {
            return null;
        }

        return $this->formatRegistration($registration, true);
    }

    /**
     * @return array<string, mixed>
     */
    public function getAdminWorkspace(): array
    {
        $registrations = $this->studentRegistrationRepository
            ->getAllOrdered()
            ->map(fn (StudentRegistration $registration) => array_merge(
                $this->formatRegistration($registration),
                [
                    'submitted_ago' => $registration->created_at?->diffForHumans(),
                    'show_url' => route('admin.students.show', $registration->reference),
                ],
            ))
            ->values()
            ->all();

        return [
            'description' => 'Review paid student training registrations submitted from the public intake form.',
            'summary' => [
                'total' => count($registrations),
                'paid' => collect($registrations)->where('payment_status', 'paid')->count(),
                'partial' => collect($registrations)->where('payment_status', 'partial')->count(),
                'pending' => collect($registrations)->where('payment_status', 'pending')->count(),
            ],
            'registrations' => $registrations,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getSeoMeta(): array
    {
        $seo = config('cyra.student_registrations.seo', []);

        return [
            'title' => $seo['title'] ?? 'Student Registration | '.config('cyra.name'),
            'description' => $seo['description'] ?? 'Register for Cyra-Tech training programs and pay securely with Paystack.',
            'keywords' => $seo['keywords'] ?? [],
        ];
    }

    public function formatAmount(int $amount): string
    {
        $symbol = config('cyra.student_registrations.currency_symbol', '₦');

        return $symbol.number_format($amount);
    }

    /**
     * @return array<string, mixed>
     */
    private function formatRegistration(StudentRegistration $registration, bool $includeQr = false): array
    {
        $levels = $this->getEducationLevelOptions();
        $genders = $this->getGenderOptions();
        $skills = $this->getSkillLevelOptions();
        $programs = $this->getProgramOptions();
        $verificationUrl = $this->verificationUrl($registration->reference);

        return [
            'reference' => $registration->reference,
            'name' => $registration->name,
            'email' => $registration->email,
            'phone' => $registration->phone,
            'date_of_birth' => $registration->date_of_birth?->format('F j, Y'),
            'age' => $registration->date_of_birth?->age,
            'gender' => $registration->gender,
            'gender_label' => $genders[$registration->gender] ?? ucfirst((string) $registration->gender),
            'state' => $registration->state,
            'city' => $registration->city,
            'location' => trim(collect([$registration->city, $registration->state])->filter()->implode(', ')),
            'institution' => $registration->institution,
            'education_level' => $registration->education_level ?: $registration->academic_level,
            'education_level_label' => $levels[$registration->education_level ?: $registration->academic_level] ?? ucfirst((string) ($registration->education_level ?: $registration->academic_level)),
            'field_of_study' => $registration->field_of_study ?: $registration->course_of_study,
            'course_of_study' => $registration->field_of_study ?: $registration->course_of_study,
            'academic_level' => $registration->education_level ?: $registration->academic_level,
            'academic_level_label' => $levels[$registration->education_level ?: $registration->academic_level] ?? ucfirst((string) ($registration->education_level ?: $registration->academic_level)),
            'skill_level' => $registration->skill_level,
            'skill_level_label' => $skills[$registration->skill_level] ?? ucfirst((string) $registration->skill_level),
            'has_tech_experience' => (bool) $registration->has_tech_experience,
            'has_tech_experience_label' => $registration->has_tech_experience ? 'Yes' : 'No',
            'program' => $registration->program,
            'program_label' => $programs[$registration->program] ?? ucfirst(str_replace('-', ' ', $registration->program)),
            'amount' => $registration->amount,
            'amount_formatted' => $this->formatAmount((int) $registration->amount),
            'amount_paid' => (int) $registration->amount_paid,
            'amount_paid_formatted' => $this->formatAmount((int) $registration->amount_paid),
            'amount_remaining' => (int) $registration->amount_remaining,
            'amount_remaining_formatted' => $this->formatAmount((int) $registration->amount_remaining),
            'payment_plan' => $registration->payment_plan,
            'payment_plan_label' => $registration->payment_plan === 'installment' ? '60/40 Installment' : 'Full payment',
            'currency' => $registration->currency,
            'motivation' => $registration->motivation,
            'status' => $registration->status,
            'payment_status' => $registration->payment_status,
            'payment_status_label' => match ($registration->payment_status) {
                'paid' => 'Paid in full',
                'partial' => 'Partially paid',
                'pending' => 'Pending',
                'failed' => 'Failed',
                default => ucfirst($registration->payment_status),
            },
            'can_pay_balance' => $registration->payment_status === 'partial' && $registration->amount_remaining > 0,
            'balance_payment_url' => route('students.balance.pay', $registration->reference),
            'paystack_reference' => $registration->paystack_reference,
            'balance_paystack_reference' => $registration->balance_paystack_reference,
            'registered_at' => $registration->created_at?->format('M j, Y g:i A'),
            'registered_date' => $registration->paid_at?->format('F j, Y') ?? $registration->created_at?->format('F j, Y'),
            'paid_at' => $registration->paid_at?->format('M j, Y g:i A'),
            'verification_url' => $verificationUrl,
            'verification_qr' => $includeQr ? $this->verificationQrSvg($verificationUrl) : '',
        ];
    }

    private function verificationToken(string $reference): string
    {
        return hash_hmac('sha256', Str::upper($reference), (string) config('app.key'));
    }

    private function verificationUrl(string $reference): string
    {
        return route('students.verify', [
            'reference' => $reference,
            'token' => $this->verificationToken($reference),
        ]);
    }

    private function verificationQrSvg(string $url): string
    {
        try {
            return $this->qrCodeService->svg($url, 168);
        } catch (\Throwable $exception) {
            Log::warning('Failed to generate student receipt QR code.', [
                'error' => $exception->getMessage(),
            ]);

            return '';
        }
    }

    private function sendRegistrationNotification(StudentRegistration $registration): void
    {
        $recipient = config('cyra.student_registrations.notification_email')
            ?: config('cyra.contact.notification_email');

        if (! filled($recipient)) {
            return;
        }

        $levels = $this->getEducationLevelOptions();
        $programs = $this->getProgramOptions();

        try {
            Mail::to($recipient)->send(new StudentRegistrationSubmitted(
                $registration,
                $programs[$registration->program] ?? ucfirst(str_replace('-', ' ', $registration->program)),
                $levels[$registration->education_level ?: $registration->academic_level] ?? ucfirst((string) ($registration->education_level ?: $registration->academic_level)),
                $this->formatAmount((int) $registration->amount),
            ));
        } catch (\Throwable $exception) {
            Log::error('Failed to send student registration notification email.', [
                'reference' => $registration->reference,
                'recipient' => $recipient,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function generateReference(): string
    {
        $next = 1;

        $references = StudentRegistration::query()
            ->where('reference', 'like', 'STR-CT-%')
            ->pluck('reference');

        foreach ($references as $reference) {
            if (preg_match('/^STR-CT-(\d+)$/', (string) $reference, $matches) === 1) {
                $next = max($next, ((int) $matches[1]) + 1);
            }
        }

        return 'STR-CT-'.str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }

    private function generatePaystackReference(string $registrationReference): string
    {
        return $registrationReference.'-'.Str::upper(Str::random(8));
    }
}

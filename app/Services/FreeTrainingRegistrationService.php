<?php

namespace App\Services;

use App\Mail\FreeTrainingRegistrationSubmitted;
use App\Models\FreeTrainingRegistration;
use App\Repositories\FreeTrainingRegistrationRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class FreeTrainingRegistrationService extends BaseService
{
    public function __construct(
        private readonly FreeTrainingRegistrationRepository $freeTrainingRegistrationRepository,
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
            'hero' => config('cyra.free_training.hero', []),
            'form' => config('cyra.free_training.form', []),
            'highlights' => config('cyra.free_training.highlights', []),
            'programs' => $this->getPrograms(),
            'genders' => config('cyra.student_registrations.genders', []),
            'states' => config('cyra.student_registrations.states', []),
            'skill_levels' => config('cyra.student_registrations.skill_levels', []),
            'acknowledgement' => config('cyra.free_training.acknowledgement', []),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getPrograms(): array
    {
        return collect(config('cyra.free_training.programs', []))
            ->map(fn (array $program): array => [
                'slug' => $program['slug'],
                'label' => $program['label'],
                'description' => $program['description'] ?? '',
            ])
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

    /**
     * @param  array<string, mixed>  $data
     * @return array{reference: string, message: string}
     */
    public function submitRegistration(array $data, ?string $ipAddress = null): array
    {
        $registration = $this->freeTrainingRegistrationRepository->createRegistration([
            'reference' => $this->generateReference(),
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'date_of_birth' => $data['date_of_birth'],
            'gender' => $data['gender'],
            'state' => $data['state'],
            'city' => $data['city'],
            'program' => $data['program'],
            'skill_level' => $data['skill_level'],
            'has_tech_experience' => ($data['has_tech_experience'] ?? 'no') === 'yes',
            'status' => 'registered',
            'ip_address' => $ipAddress,
        ]);

        $this->sendRegistrationNotification($registration);

        return [
            'reference' => $registration->reference,
            'message' => config(
                'cyra.free_training.form.success_message',
                'Registration successful! Print or save your enrollment confirmation.'
            ),
        ];
    }

    public function findRegistrationByReference(string $reference): ?array
    {
        $registration = $this->freeTrainingRegistrationRepository->findByReference($reference);

        if ($registration === null) {
            return null;
        }

        return $this->formatRegistration($registration, true);
    }

    public function findVerifiedRegistration(string $reference, string $token): ?array
    {
        $registration = $this->freeTrainingRegistrationRepository->findByReference($reference);

        if ($registration === null) {
            return null;
        }

        if (! hash_equals($this->verificationToken($registration->reference), $token)) {
            return null;
        }

        return $this->formatRegistration($registration, true);
    }

    /**
     * @return array<string, mixed>
     */
    public function getAdminWorkspace(): array
    {
        $registrations = $this->freeTrainingRegistrationRepository
            ->getAllOrdered()
            ->map(fn (FreeTrainingRegistration $registration) => array_merge(
                $this->formatRegistration($registration),
                [
                    'submitted_ago' => $registration->created_at?->diffForHumans(),
                    'show_url' => route('admin.free-training.show', $registration->reference),
                ],
            ))
            ->values()
            ->all();

        $byProgram = collect($this->getPrograms())
            ->mapWithKeys(fn (array $program) => [
                $program['slug'] => collect($registrations)->where('program', $program['slug'])->count(),
            ])
            ->all();

        return [
            'description' => 'Review free training onboardings submitted from the public academy form.',
            'summary' => [
                'total' => count($registrations),
                'registered' => collect($registrations)->where('status', 'registered')->count(),
                'by_program' => $byProgram,
            ],
            'registrations' => $registrations,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getSeoMeta(): array
    {
        $seo = config('cyra.free_training.seo', []);

        return [
            'title' => $seo['title'] ?? 'Free Training | '.config('cyra.name'),
            'description' => $seo['description'] ?? 'Join Cyra-Tech free training and onboard for Software Development, AI Automation, Data Analysis, or Cyber Security.',
            'keywords' => $seo['keywords'] ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatRegistration(FreeTrainingRegistration $registration, bool $includeQr = false): array
    {
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
            'program' => $registration->program,
            'program_label' => $programs[$registration->program] ?? ucfirst(str_replace('-', ' ', $registration->program)),
            'skill_level' => $registration->skill_level,
            'skill_level_label' => $skills[$registration->skill_level] ?? ucfirst((string) $registration->skill_level),
            'has_tech_experience' => (bool) $registration->has_tech_experience,
            'has_tech_experience_label' => $registration->has_tech_experience ? 'Yes' : 'No',
            'status' => $registration->status,
            'registered_at' => $registration->created_at?->format('M j, Y g:i A'),
            'registered_date' => $registration->created_at?->format('F j, Y'),
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
        return route('free-training.verify', [
            'reference' => $reference,
            'token' => $this->verificationToken($reference),
        ]);
    }

    private function verificationQrSvg(string $url): string
    {
        try {
            return $this->qrCodeService->svg($url, 168);
        } catch (\Throwable $exception) {
            Log::warning('Failed to generate free training confirmation QR code.', [
                'error' => $exception->getMessage(),
            ]);

            return '';
        }
    }

    private function sendRegistrationNotification(FreeTrainingRegistration $registration): void
    {
        $recipient = config('cyra.free_training.notification_email')
            ?: config('cyra.contact.notification_email');

        if (! filled($recipient)) {
            return;
        }

        $programs = $this->getProgramOptions();

        try {
            Mail::to($recipient)->send(new FreeTrainingRegistrationSubmitted(
                $registration,
                $programs[$registration->program] ?? ucfirst(str_replace('-', ' ', $registration->program)),
            ));
        } catch (\Throwable $exception) {
            Log::error('Failed to send free training registration notification email.', [
                'reference' => $registration->reference,
                'recipient' => $recipient,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function generateReference(): string
    {
        $next = 1;

        $references = FreeTrainingRegistration::query()
            ->where('reference', 'like', 'FTR-CT-%')
            ->pluck('reference');

        foreach ($references as $reference) {
            if (preg_match('/^FTR-CT-(\d+)$/', (string) $reference, $matches) === 1) {
                $next = max($next, ((int) $matches[1]) + 1);
            }
        }

        return 'FTR-CT-'.str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }
}

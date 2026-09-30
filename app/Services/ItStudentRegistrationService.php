<?php

namespace App\Services;

use App\Mail\ItStudentRegistrationSubmitted;
use App\Models\ItStudentRegistration;
use App\Repositories\ItStudentRegistrationRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ItStudentRegistrationService extends BaseService
{
    public function __construct(
        private readonly ItStudentRegistrationRepository $itStudentRegistrationRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getPage(): array
    {
        return [
            'seo' => $this->getSeoMeta(),
            'hero' => config('cyra.it_students.hero', []),
            'form' => config('cyra.it_students.form', []),
            'highlights' => config('cyra.it_students.highlights', []),
            'academic_levels' => config('cyra.it_students.academic_levels', []),
            'interest_areas' => config('cyra.it_students.interest_areas', []),
            'availability_options' => config('cyra.it_students.availability_options', []),
            'whatsapp' => $this->getWhatsAppConfig(),
            'acknowledgement' => config('cyra.it_students.acknowledgement', []),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function getAcademicLevelOptions(): array
    {
        return collect(config('cyra.it_students.academic_levels', []))
            ->pluck('label', 'slug')
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public function getInterestAreaOptions(): array
    {
        return collect(config('cyra.it_students.interest_areas', []))
            ->pluck('label', 'slug')
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public function getAvailabilityOptions(): array
    {
        return collect(config('cyra.it_students.availability_options', []))
            ->pluck('label', 'slug')
            ->all();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function submitRegistration(array $data, ?string $ipAddress = null): array
    {
        $registration = $this->itStudentRegistrationRepository->createRegistration([
            'reference' => $this->generateReference(),
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'institution' => $data['institution'],
            'course_of_study' => $data['course_of_study'],
            'academic_level' => $data['academic_level'],
            'interest_area' => $data['interest_area'],
            'availability' => $data['availability'],
            'motivation' => $data['motivation'],
            'status' => 'registered',
            'ip_address' => $ipAddress,
        ]);

        $this->sendRegistrationNotification($registration);

        return [
            'reference' => $registration->reference,
            'message' => config(
                'cyra.it_students.form.success_message',
                'Welcome aboard! Your IT student registration was successful.'
            ),
            'registration' => $this->formatRegistration($registration),
        ];
    }

    public function findRegistrationByReference(string $reference): ?array
    {
        $registration = $this->itStudentRegistrationRepository->findByReference($reference);

        if ($registration === null) {
            return null;
        }

        return $this->formatRegistration($registration);
    }

    /**
     * @return array<string, mixed>
     */
    public function getAdminWorkspace(): array
    {
        $registrations = $this->itStudentRegistrationRepository
            ->getAllOrdered()
            ->map(fn (ItStudentRegistration $registration) => array_merge(
                $this->formatRegistration($registration),
                [
                    'submitted_ago' => $registration->created_at?->diffForHumans(),
                    'show_url' => route('admin.it.show', $registration->reference),
                ],
            ))
            ->values()
            ->all();

        return [
            'description' => 'Review IT student registrations submitted from the public intake form.',
            'summary' => [
                'total' => count($registrations),
                'registered' => collect($registrations)->where('status', 'registered')->count(),
            ],
            'registrations' => $registrations,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getSeoMeta(): array
    {
        $seo = config('cyra.it_students.seo', []);

        return [
            'title' => $seo['title'] ?? 'IT Student Registration | '.config('cyra.name'),
            'description' => $seo['description'] ?? 'Register as an IT student with Cyra-Tech.',
            'keywords' => $seo['keywords'] ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getWhatsAppConfig(): array
    {
        $raw = (string) config('cyra.it_students.whatsapp_number', '');
        $numbers = collect(preg_split('/[,\s]+/', $raw) ?: [])
            ->map(fn (string $number) => preg_replace('/\D+/', '', $number) ?: '')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $config = config('cyra.it_students.whatsapp', []);

        return [
            'numbers' => $numbers,
            'number' => $numbers[0] ?? '',
            'label' => $config['label'] ?? 'Send acknowledgement via WhatsApp',
            'hint' => $config['hint'] ?? 'Tap WhatsApp to open your acknowledgement message — then tap Send.',
            'enabled' => $numbers !== [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatRegistration(ItStudentRegistration $registration): array
    {
        $levels = $this->getAcademicLevelOptions();
        $interests = $this->getInterestAreaOptions();
        $availability = $this->getAvailabilityOptions();
        $whatsapp = $this->getWhatsAppConfig();

        $message = $this->buildWhatsAppMessage($registration, $levels, $interests, $availability);

        $whatsappLinks = collect($whatsapp['numbers'])
            ->map(fn (string $number, int $index) => [
                'number' => $number,
                'label' => count($whatsapp['numbers']) > 1
                    ? 'WhatsApp '.($index + 1)
                    : ($whatsapp['label'] ?? 'Send on WhatsApp'),
                'url' => 'https://wa.me/'.$number.'?text='.rawurlencode($message),
            ])
            ->values()
            ->all();

        return [
            'reference' => $registration->reference,
            'name' => $registration->name,
            'email' => $registration->email,
            'phone' => $registration->phone,
            'institution' => $registration->institution,
            'course_of_study' => $registration->course_of_study,
            'academic_level' => $registration->academic_level,
            'academic_level_label' => $levels[$registration->academic_level] ?? ucfirst($registration->academic_level),
            'interest_area' => $registration->interest_area,
            'interest_area_label' => $interests[$registration->interest_area] ?? ucfirst(str_replace('-', ' ', $registration->interest_area)),
            'availability' => $registration->availability,
            'availability_label' => $availability[$registration->availability] ?? ucfirst($registration->availability),
            'motivation' => $registration->motivation,
            'status' => $registration->status,
            'registered_at' => $registration->created_at?->format('M j, Y g:i A'),
            'registered_date' => $registration->created_at?->format('F j, Y'),
            'whatsapp_url' => $whatsappLinks[0]['url'] ?? null,
            'whatsapp_links' => $whatsappLinks,
            'whatsapp_message' => $message,
        ];
    }

    /**
     * @param  array<string, string>  $levels
     * @param  array<string, string>  $interests
     * @param  array<string, string>  $availability
     */
    private function buildWhatsAppMessage(
        ItStudentRegistration $registration,
        array $levels,
        array $interests,
        array $availability,
    ): string {
        $template = (string) config(
            'cyra.it_students.whatsapp.message_template',
            "Hello Cyra-Tech,\n\nI have successfully registered as an IT student.\n\nName: :name\nReference: :reference\nInstitution: :institution\nCourse: :course\nLevel: :level\nInterest: :interest\nAvailability: :availability\n\nPlease find my acknowledgement slip details above. Thank you."
        );

        return strtr($template, [
            ':name' => $registration->name,
            ':reference' => $registration->reference,
            ':date' => $registration->created_at?->format('F j, Y') ?? now()->format('F j, Y'),
            ':institution' => $registration->institution,
            ':course' => $registration->course_of_study,
            ':level' => $levels[$registration->academic_level] ?? $registration->academic_level,
            ':interest' => $interests[$registration->interest_area] ?? $registration->interest_area,
            ':availability' => $availability[$registration->availability] ?? $registration->availability,
            ':email' => $registration->email,
            ':phone' => $registration->phone,
        ]);
    }

    private function sendRegistrationNotification(ItStudentRegistration $registration): void
    {
        $recipient = config('cyra.it_students.notification_email')
            ?: config('cyra.contact.notification_email');

        if (! filled($recipient)) {
            return;
        }

        $levels = $this->getAcademicLevelOptions();
        $interests = $this->getInterestAreaOptions();
        $availability = $this->getAvailabilityOptions();

        try {
            Mail::to($recipient)->send(new ItStudentRegistrationSubmitted(
                $registration,
                $interests[$registration->interest_area] ?? ucfirst(str_replace('-', ' ', $registration->interest_area)),
                $levels[$registration->academic_level] ?? ucfirst($registration->academic_level),
                $availability[$registration->availability] ?? ucfirst($registration->availability),
            ));
        } catch (\Throwable $exception) {
            Log::error('Failed to send IT student registration notification email.', [
                'reference' => $registration->reference,
                'recipient' => $recipient,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function generateReference(): string
    {
        $next = 1;

        $references = ItStudentRegistration::query()
            ->where('reference', 'like', 'ITS-CT-%')
            ->pluck('reference');

        foreach ($references as $reference) {
            if (preg_match('/^ITS-CT-(\d+)$/', (string) $reference, $matches) === 1) {
                $next = max($next, ((int) $matches[1]) + 1);
            }
        }

        return 'ITS-CT-'.str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }
}

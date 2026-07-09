<?php

namespace App\Services;

use App\Models\ContactInquiry;
use App\Repositories\ContactInquiryRepository;
use Illuminate\Support\Str;

class ContactService extends BaseService
{
    public function __construct(
        private readonly ContactInquiryRepository $contactInquiryRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getContact(): array
    {
        return [
            'seo' => $this->getSeoMeta(),
            'hero' => config('cyra.contact.hero', []),
            'inquiry_types' => config('cyra.contact.inquiry_types', []),
            'offices' => config('cyra.contact.offices', []),
            'channels' => config('cyra.contact.channels', []),
            'form' => config('cyra.contact.form', []),
            'support' => config('cyra.contact.support', []),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function submitInquiry(array $data, ?string $ipAddress = null): array
    {
        $inquiryType = $this->resolveInquiryType($data['inquiry_type'] ?? '');

        $inquiry = $this->contactInquiryRepository->createInquiry([
            'reference' => $this->generateReference(),
            'name' => $data['name'],
            'email' => $data['email'],
            'company' => $data['company'] ?? null,
            'phone' => $data['phone'] ?? null,
            'inquiry_type' => $inquiryType['slug'],
            'message' => $data['message'],
            'status' => 'pending',
            'ip_address' => $ipAddress,
        ]);

        return [
            'reference' => $inquiry->reference,
            'message' => config('cyra.contact.form.success_message', 'Thank you. Our team will respond shortly.'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getSeoMeta(): array
    {
        $seo = config('cyra.contact.seo', []);

        return [
            'title' => $seo['title'] ?? 'Contact | '.config('cyra.name'),
            'description' => $seo['description'] ?? 'Contact Cyra-Tech for sales, support, partnerships, and careers inquiries.',
            'keywords' => $seo['keywords'] ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getInquiryTypeOptions(): array
    {
        return collect(config('cyra.contact.inquiry_types', []))
            ->pluck('label', 'slug')
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function getAdminWorkspace(): array
    {
        $typeLabels = $this->getInquiryTypeOptions();

        $inquiries = $this->contactInquiryRepository
            ->getAllOrdered()
            ->map(fn (ContactInquiry $inquiry) => [
                'reference' => $inquiry->reference,
                'name' => $inquiry->name,
                'email' => $inquiry->email,
                'company' => $inquiry->company,
                'phone' => $inquiry->phone,
                'inquiry_type' => $inquiry->inquiry_type,
                'inquiry_type_label' => $typeLabels[$inquiry->inquiry_type] ?? ucfirst(str_replace('-', ' ', $inquiry->inquiry_type)),
                'message' => $inquiry->message,
                'status' => $inquiry->status,
                'submitted_at' => $inquiry->created_at?->format('M j, Y g:i A'),
                'submitted_ago' => $inquiry->created_at?->diffForHumans(),
                'edit_url' => route('admin.contact.edit', $inquiry->reference),
            ])
            ->values()
            ->all();

        return [
            'description' => 'Review inbound contact form submissions and route inquiries to CRM.',
            'summary' => [
                'total' => count($inquiries),
                'pending' => collect($inquiries)->where('status', 'pending')->count(),
                'converted' => collect($inquiries)->where('status', 'converted')->count(),
            ],
            'inquiries' => $inquiries,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAdminInquiry(string $reference): ?array
    {
        $inquiry = $this->contactInquiryRepository->findByReference($reference);

        if ($inquiry === null) {
            return null;
        }

        $typeLabels = $this->getInquiryTypeOptions();

        return [
            'reference' => $inquiry->reference,
            'name' => $inquiry->name,
            'email' => $inquiry->email,
            'company' => $inquiry->company,
            'phone' => $inquiry->phone,
            'inquiry_type' => $inquiry->inquiry_type,
            'inquiry_type_label' => $typeLabels[$inquiry->inquiry_type] ?? ucfirst(str_replace('-', ' ', $inquiry->inquiry_type)),
            'message' => $inquiry->message,
            'status' => $inquiry->status,
            'submitted_at' => $inquiry->created_at?->format('M j, Y g:i A'),
        ];
    }

    /**
     * @return list<string>
     */
    public function getStatusOptions(): array
    {
        return ['pending', 'reviewed', 'converted', 'closed'];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateInquiry(string $reference, array $data): ?array
    {
        $inquiry = $this->contactInquiryRepository->findByReference($reference);

        if ($inquiry === null) {
            return null;
        }

        $inquiry = $this->contactInquiryRepository->updateInquiry($inquiry, [
            'status' => $data['status'],
        ]);

        return $this->getAdminInquiry($inquiry->reference);
    }

    public function deleteInquiry(string $reference): bool
    {
        $inquiry = $this->contactInquiryRepository->findByReference($reference);

        if ($inquiry === null) {
            return false;
        }

        $this->contactInquiryRepository->deleteInquiry($inquiry);

        return true;
    }

    /**
     * @return array<string, mixed>
     */
    private function resolveInquiryType(string $slug): array
    {
        $types = collect(config('cyra.contact.inquiry_types', []));

        $match = $types->firstWhere('slug', $slug);

        if ($match !== null) {
            return $match;
        }

        return $types->first() ?? ['slug' => 'general', 'label' => 'General Inquiry'];
    }

    private function generateReference(): string
    {
        do {
            $reference = 'CYRA-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));
        } while (ContactInquiry::query()->where('reference', $reference)->exists());

        return $reference;
    }
}

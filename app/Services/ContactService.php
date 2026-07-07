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

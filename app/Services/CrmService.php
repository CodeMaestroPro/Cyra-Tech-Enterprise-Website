<?php

namespace App\Services;

use App\Models\ContactInquiry;
use App\Models\CrmLead;
use App\Models\User;
use App\Repositories\CrmLeadRepository;
use Illuminate\Support\Str;

class CrmService extends BaseService
{
    public function __construct(
        private readonly CrmLeadRepository $crmLeadRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getPipeline(?string $stage = null): array
    {
        $leads = $this->crmLeadRepository
            ->getActiveLeads($stage)
            ->map(fn (CrmLead $lead) => $this->formatLead($lead))
            ->values()
            ->all();

        $stages = collect(config('cyra.crm.pipeline_stages', []))->map(function (array $stageConfig) use ($leads) {
            $stageLeads = collect($leads)->where('pipeline_stage', $stageConfig['slug'])->values()->all();

            return [
                ...$stageConfig,
                'count' => count($stageLeads),
                'value' => collect($stageLeads)->sum('estimated_value'),
                'leads' => $stageLeads,
            ];
        })->values()->all();

        return [
            'summary' => $this->buildSummary($leads),
            'pipeline_stages' => config('cyra.crm.pipeline_stages', []),
            'sources' => config('cyra.crm.sources', []),
            'priorities' => config('cyra.crm.priorities', []),
            'stages' => $stages,
            'inbound_inquiries' => $this->getInboundInquiries(),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getLead(string $reference): ?array
    {
        $lead = $this->crmLeadRepository->findByReference($reference);

        if ($lead === null) {
            return null;
        }

        return $this->formatLead($lead, detailed: true);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createLead(array $data, User $user): array
    {
        $lead = $this->crmLeadRepository->createLead([
            'reference' => $this->generateReference(),
            'name' => $data['name'],
            'email' => $data['email'],
            'company' => $data['company'] ?? null,
            'phone' => $data['phone'] ?? null,
            'job_title' => $data['job_title'] ?? null,
            'source' => $data['source'],
            'pipeline_stage' => $data['pipeline_stage'] ?? 'new',
            'priority' => $data['priority'] ?? 'medium',
            'estimated_value' => $data['estimated_value'] ?? null,
            'notes' => $data['notes'] ?? null,
            'assigned_to' => $data['assigned_to'] ?? $user->id,
            'contact_inquiry_id' => $data['contact_inquiry_id'] ?? null,
            'last_contacted_at' => $data['last_contacted_at'] ?? now(),
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => true,
        ]);

        return $this->formatLead($lead->load(['assignee', 'contactInquiry']), detailed: true);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateLead(string $reference, array $data): ?array
    {
        $lead = $this->crmLeadRepository->findByReference($reference);

        if ($lead === null) {
            return null;
        }

        $lead = $this->crmLeadRepository->updateLead($lead, [
            'name' => $data['name'],
            'email' => $data['email'],
            'company' => $data['company'] ?? null,
            'phone' => $data['phone'] ?? null,
            'job_title' => $data['job_title'] ?? null,
            'source' => $data['source'],
            'pipeline_stage' => $data['pipeline_stage'],
            'priority' => $data['priority'],
            'estimated_value' => $data['estimated_value'] ?? null,
            'notes' => $data['notes'] ?? null,
            'assigned_to' => $data['assigned_to'] ?? null,
            'last_contacted_at' => $data['last_contacted_at'] ?? $lead->last_contacted_at,
            'sort_order' => $data['sort_order'] ?? $lead->sort_order,
            'is_active' => $data['is_active'] ?? $lead->is_active,
        ]);

        return $this->formatLead($lead, detailed: true);
    }

    public function updateStage(string $reference, string $stage): ?array
    {
        $lead = $this->crmLeadRepository->findByReference($reference);

        if ($lead === null) {
            return null;
        }

        $lead = $this->crmLeadRepository->updateLead($lead, [
            'pipeline_stage' => $stage,
            'last_contacted_at' => now(),
        ]);

        return $this->formatLead($lead, detailed: true);
    }

    public function convertInquiry(int $inquiryId, User $user): ?array
    {
        $inquiry = ContactInquiry::query()->find($inquiryId);

        if ($inquiry === null) {
            return null;
        }

        $existing = CrmLead::query()->where('contact_inquiry_id', $inquiry->id)->first();

        if ($existing !== null) {
            return $this->formatLead($existing->load(['assignee', 'contactInquiry']), detailed: true);
        }

        return $this->createLead([
            'name' => $inquiry->name,
            'email' => $inquiry->email,
            'company' => $inquiry->company,
            'phone' => $inquiry->phone,
            'source' => 'contact_form',
            'pipeline_stage' => 'new',
            'priority' => 'high',
            'notes' => $inquiry->message,
            'assigned_to' => $user->id,
            'contact_inquiry_id' => $inquiry->id,
        ], $user);
    }

    /**
     * @param  array<string, mixed>  $seed
     */
    public function seedLead(array $seed, ?User $assignee = null): CrmLead
    {
        return CrmLead::query()->updateOrCreate(
            ['reference' => $seed['reference']],
            [
                'name' => $seed['name'],
                'email' => $seed['email'],
                'company' => $seed['company'] ?? null,
                'phone' => $seed['phone'] ?? null,
                'job_title' => $seed['job_title'] ?? null,
                'source' => $seed['source'] ?? 'website',
                'pipeline_stage' => $seed['pipeline_stage'] ?? 'new',
                'priority' => $seed['priority'] ?? 'medium',
                'estimated_value' => $seed['estimated_value'] ?? null,
                'notes' => $seed['notes'] ?? null,
                'assigned_to' => $assignee?->id,
                'last_contacted_at' => now()->subDays($seed['last_contacted_days_ago'] ?? 0),
                'sort_order' => $seed['sort_order'] ?? 0,
                'is_active' => true,
            ],
        );
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function getInboundInquiries(): array
    {
        $linkedIds = CrmLead::query()
            ->whereNotNull('contact_inquiry_id')
            ->pluck('contact_inquiry_id');

        return ContactInquiry::query()
            ->whereNotIn('id', $linkedIds)
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn (ContactInquiry $inquiry) => [
                'id' => $inquiry->id,
                'reference' => $inquiry->reference,
                'name' => $inquiry->name,
                'email' => $inquiry->email,
                'company' => $inquiry->company,
                'inquiry_type' => $inquiry->inquiry_type,
                'inquiry_type_label' => $this->inquiryTypeLabel($inquiry->inquiry_type),
                'status' => $inquiry->status,
                'created_at' => $inquiry->created_at?->toIso8601String(),
            ])
            ->values()
            ->all();
    }

    /**
     * @param  list<array<string, mixed>>  $leads
     * @return array<string, mixed>
     */
    private function buildSummary(array $leads): array
    {
        $collection = collect($leads);

        return [
            'total' => $collection->count(),
            'pipeline_value' => $collection->sum('estimated_value'),
            'won' => $collection->where('pipeline_stage', 'won')->count(),
            'high_priority' => $collection->where('priority', 'high')->count(),
            'inbound_inquiries' => count($this->getInboundInquiries()),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatLead(CrmLead $lead, bool $detailed = false): array
    {
        $formatted = [
            'reference' => $lead->reference,
            'name' => $lead->name,
            'email' => $lead->email,
            'company' => $lead->company,
            'phone' => $lead->phone,
            'job_title' => $lead->job_title,
            'source' => $lead->source,
            'source_label' => $this->sourceLabel($lead->source),
            'pipeline_stage' => $lead->pipeline_stage,
            'pipeline_stage_label' => $this->stageLabel($lead->pipeline_stage),
            'priority' => $lead->priority,
            'priority_label' => ucfirst($lead->priority),
            'estimated_value' => $lead->estimated_value !== null ? (float) $lead->estimated_value : null,
            'estimated_value_label' => $this->formatCurrency($lead->estimated_value),
            'assignee' => $lead->assignee?->name,
            'assigned_to' => $lead->assigned_to,
            'last_contacted_at' => $lead->last_contacted_at?->toIso8601String(),
            'updated_at' => $lead->updated_at?->toIso8601String(),
        ];

        if ($detailed) {
            $formatted['notes'] = $lead->notes;
            $formatted['sort_order'] = $lead->sort_order;
            $formatted['is_active'] = $lead->is_active;
            $formatted['contact_inquiry_id'] = $lead->contact_inquiry_id;
            $formatted['contact_inquiry_reference'] = $lead->contactInquiry?->reference;
        }

        return $formatted;
    }

    private function generateReference(): string
    {
        return 'CRM-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));
    }

    private function stageLabel(string $stage): string
    {
        return collect(config('cyra.crm.pipeline_stages', []))
            ->firstWhere('slug', $stage)['label'] ?? ucfirst(str_replace('-', ' ', $stage));
    }

    private function sourceLabel(string $source): string
    {
        return collect(config('cyra.crm.sources', []))
            ->firstWhere('slug', $source)['label'] ?? ucfirst(str_replace('_', ' ', $source));
    }

    private function inquiryTypeLabel(string $type): string
    {
        return collect(config('cyra.contact.inquiry_types', []))
            ->firstWhere('slug', $type)['label'] ?? ucfirst($type);
    }

    private function formatCurrency(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return '₦'.number_format((float) $value, 0);
    }
}

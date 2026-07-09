<?php

namespace App\Http\Requests;

use App\Services\CatalogAdminService;
use Illuminate\Foundation\Http\FormRequest;

class StoreCatalogItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('cms.create') ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $module = $this->route('catalog_module');

        return app(CatalogAdminService::class)->getValidationRules((string) $module);
    }

    /**
     * @return array<string, mixed>
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);
        $validated['is_active'] = $this->boolean('is_active', true);
        $validated['is_featured'] = $this->boolean('is_featured', false);

        return $validated;
    }
}

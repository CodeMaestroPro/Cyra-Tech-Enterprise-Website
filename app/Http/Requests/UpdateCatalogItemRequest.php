<?php

namespace App\Http\Requests;

use App\Services\CatalogAdminService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCatalogItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('cms.update') ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $module = $this->route('catalog_module');
        $slug = $this->route('slug');

        return app(CatalogAdminService::class)->getValidationRules(
            (string) $module,
            isUpdate: true,
            slug: is_string($slug) ? $slug : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);
        $validated['is_active'] = $this->boolean('is_active', false);
        $validated['is_featured'] = $this->boolean('is_featured', false);

        return $validated;
    }
}

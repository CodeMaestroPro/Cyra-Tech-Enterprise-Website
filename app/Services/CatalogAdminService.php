<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CatalogAdminService extends BaseService
{
    /**
     * @return array<string, mixed>
     */
    public function getModuleConfig(string $module): array
    {
        $config = config("admin_catalog.modules.{$module}");

        abort_if($config === null, 404, 'Catalog module not found.');

        return $config;
    }

    /**
     * @return list<string>
     */
    public function getModuleKeys(): array
    {
        return array_keys(config('admin_catalog.modules', []));
    }

    /**
     * @return array<string, mixed>
     */
    public function getAdminCatalog(string $module, ?string $category = null): array
    {
        $config = $this->getModuleConfig($module);
        $repository = $this->resolveRepository($module);
        $collection = $repository->getAllRecords();

        if ($category !== null && $category !== 'all') {
            $collection = $collection->where('category', $category)->values();
        }

        $records = $collection
            ->map(fn (Model $record) => $this->formatAdminRecord($module, $record))
            ->values()
            ->all();

        return [
            'module' => $module,
            'config' => $config,
            'summary' => [
                'total' => count($records),
                'active' => collect($records)->where('is_active', true)->count(),
                'inactive' => collect($records)->where('is_active', false)->count(),
                'featured' => collect($records)->where('is_featured', true)->count(),
            ],
            'categories' => $this->getCategoryOptions($config),
            'records' => $records,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAdminRecord(string $module, string $slug, bool $detailed = false): ?array
    {
        $record = $this->resolveRepository($module)->findBySlug($slug);

        if ($record === null) {
            return null;
        }

        return $this->formatAdminRecord($module, $record, detailed: $detailed);
    }

    /**
     * @return array<string, mixed>
     */
    public function getFormOptions(string $module): array
    {
        $config = $this->getModuleConfig($module);

        return [
            'categories' => $this->getCategoryOptions($config),
            'fields' => $config['fields'],
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function createRecord(string $module, array $data): array
    {
        $attributes = $this->buildAttributes($module, $data);
        $record = $this->resolveRepository($module)->createRecord($attributes);

        return $this->formatAdminRecord($module, $record, detailed: true);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>|null
     */
    public function updateRecord(string $module, string $slug, array $data): ?array
    {
        $repository = $this->resolveRepository($module);
        $record = $repository->findBySlug($slug);

        if ($record === null) {
            return null;
        }

        $record = $repository->updateRecord($record, $this->buildAttributes($module, $data, isUpdate: true));

        return $this->formatAdminRecord($module, $record, detailed: true);
    }

    public function deleteRecord(string $module, string $slug): bool
    {
        $repository = $this->resolveRepository($module);
        $record = $repository->findBySlug($slug);

        if ($record === null) {
            return false;
        }

        $repository->deleteRecord($record);

        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function getValidationRules(string $module, bool $isUpdate = false, ?string $slug = null): array
    {
        $config = $this->getModuleConfig($module);
        $repository = $this->resolveRepository($module);
        $table = $repository->getModel()->getTable();
        $rules = [];

        foreach ($config['fields'] as $field) {
            $name = $field['name'];
            $type = $field['type'] ?? 'text';
            $required = (bool) ($field['required'] ?? false);
            $fieldRules = [];

            if ($name === 'slug') {
                $fieldRules[] = $isUpdate ? 'prohibited' : ($required ? 'required' : 'nullable');
                if (! $isUpdate) {
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:160';
                    $fieldRules[] = 'alpha_dash';
                    $fieldRules[] = Rule::unique($table, 'slug');
                }
            } elseif ($type === 'category') {
                $fieldRules[] = $required ? 'required' : 'nullable';
                $fieldRules[] = 'string';
                $fieldRules[] = Rule::in($this->getCategorySlugs($config));
            } elseif ($type === 'select') {
                $fieldRules[] = $required ? 'required' : 'nullable';
                $fieldRules[] = 'string';
                $fieldRules[] = Rule::in(array_keys($field['options'] ?? []));
            } elseif ($type === 'email') {
                $fieldRules[] = $required ? 'required' : 'nullable';
                $fieldRules[] = 'email';
                $fieldRules[] = 'max:255';
            } elseif ($type === 'url') {
                $fieldRules[] = $required ? 'required' : 'nullable';
                $fieldRules[] = 'url';
                $fieldRules[] = 'max:500';
            } elseif ($type === 'number') {
                $fieldRules[] = $required ? 'required' : 'nullable';
                $fieldRules[] = 'integer';
                $fieldRules[] = 'min:0';
            } elseif ($type === 'textarea' || $type === 'list') {
                $fieldRules[] = $required ? 'required' : 'nullable';
                $fieldRules[] = 'string';
                $fieldRules[] = 'max:5000';
            } else {
                $fieldRules[] = $required ? 'required' : 'nullable';
                $fieldRules[] = 'string';
                $fieldRules[] = 'max:500';
            }

            $rules[$name] = $fieldRules;
        }

        $rules['is_active'] = ['sometimes', 'boolean'];
        $rules['is_featured'] = ['sometimes', 'boolean'];

        return $rules;
    }

    public function routeName(string $module, string $action): string
    {
        return "admin.{$module}.{$action}";
    }

    /**
     * @return object
     */
    private function resolveRepository(string $module)
    {
        $class = $this->getModuleConfig($module)['repository'];

        return app($class);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function buildAttributes(string $module, array $data, bool $isUpdate = false): array
    {
        $config = $this->getModuleConfig($module);
        $attributes = [];

        foreach ($config['fields'] as $field) {
            $name = $field['name'];
            $type = $field['type'] ?? 'text';

            if ($name === 'slug' && $isUpdate) {
                continue;
            }

            if (! array_key_exists($name, $data)) {
                if (! $isUpdate && array_key_exists('default', $field)) {
                    $attributes[$name] = $field['default'];
                }

                continue;
            }

            $value = $data[$name];

            if ($type === 'list') {
                $attributes[$name] = $this->parseListField(is_string($value) ? $value : null);
            } elseif ($type === 'number') {
                $attributes[$name] = (int) ($value ?? $field['default'] ?? 0);
            } else {
                $attributes[$name] = $value;
            }
        }

        if (! $isUpdate && ! isset($attributes['slug']) && isset($attributes[$config['title_field']])) {
            $attributes['slug'] = Str::slug((string) $attributes[$config['title_field']]);
        }

        $attributes['is_active'] = (bool) ($data['is_active'] ?? true);
        $attributes['is_featured'] = (bool) ($data['is_featured'] ?? false);

        return $attributes;
    }

    /**
     * @return array<string, mixed>
     */
    private function formatAdminRecord(string $module, Model $record, bool $detailed = false): array
    {
        $config = $this->getModuleConfig($module);
        $formatted = $record->toArray();

        if (isset($formatted['category'])) {
            $formatted['category_label'] = $this->resolveCategoryLabel($config, (string) $formatted['category']);
        }

        $formatted['edit_url'] = route($this->routeName($module, 'edit'), $record->slug);
        $formatted['display_title'] = $formatted[$config['title_field']] ?? $record->slug;

        if (($config['public_route'] ?? null) !== null && $record->slug) {
            $formatted['public_url'] = route($config['public_route'], $record->slug);
        }

        if ($detailed) {
            foreach ($config['fields'] as $field) {
                if (($field['type'] ?? '') === 'list') {
                    $formatted[$field['name'].'_text'] = $this->formatListField($record->{$field['name']} ?? null);
                }
            }
        }

        return $formatted;
    }

    /**
     * @param  array<string, mixed>  $config
     * @return list<array<string, string>>
     */
    private function getCategoryOptions(array $config): array
    {
        $categoriesConfig = $config['categories_config'] ?? null;

        if ($categoriesConfig === null) {
            return [];
        }

        return collect(config($categoriesConfig, []))
            ->reject(fn (array $category) => ($category['slug'] ?? '') === 'all')
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $config
     * @return list<string>
     */
    private function getCategorySlugs(array $config): array
    {
        return collect($this->getCategoryOptions($config))
            ->pluck('slug')
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $config
     */
    private function resolveCategoryLabel(array $config, string $slug): string
    {
        return collect($this->getCategoryOptions($config))
            ->firstWhere('slug', $slug)['label'] ?? ucfirst(str_replace('-', ' ', $slug));
    }

    /**
     * @param  list<string>|null  $items
     */
    private function formatListField(?array $items): string
    {
        return collect($items ?? [])
            ->filter()
            ->implode("\n");
    }

    private function parseListField(?string $value): array
    {
        if ($value === null || trim($value) === '') {
            return [];
        }

        return collect(preg_split('/\R/', $value) ?: [])
            ->map(fn (string $line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}

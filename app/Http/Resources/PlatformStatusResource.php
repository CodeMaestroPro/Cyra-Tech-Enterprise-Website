<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlatformStatusResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->resource['name'],
            'tagline' => $this->resource['tagline'],
            'version' => $this->resource['version'],
            'environment' => $this->resource['environment'],
            'database' => $this->resource['database'],
            'modules' => $this->resource['modules'],
            'stack' => $this->resource['stack'],
            'initialized_at' => $this->resource['initialized_at'],
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource['id'],
            'name' => $this->resource['name'],
            'email' => $this->resource['email'],
            'is_active' => $this->resource['is_active'],
            'last_login_at' => $this->resource['last_login_at'],
            'roles' => $this->resource['roles'],
            'primary_role' => $this->resource['primary_role'],
            'permissions' => $this->resource['permissions'],
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AiProviderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'provider' => $this->provider,
            'display_name' => $this->display_name,
            'base_url' => $this->base_url,
            'default_model' => $this->default_model,
            'has_key' => !is_null($this->getRawOriginal('api_key_encrypted')),
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
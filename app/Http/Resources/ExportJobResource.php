<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExportJobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'format' => $this->format,
            'status' => $this->status,
            'download_url' => $this->when($this->status === 'ready', $this->download_url),
            'expires_at' => $this->when($this->status === 'ready', $this->expires_at?->toIso8601String()),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
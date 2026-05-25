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
            'options' => $this->options,
            'download_url' => $this->when($this->status === 'ready', $this->download_url),
            'expires_at' => $this->when($this->status === 'ready', $this->expires_at?->toIso8601String()),
            'error_message' => $this->when($this->status === 'failed', $this->error_message),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
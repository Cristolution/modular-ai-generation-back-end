<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AiJobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'template_id' => $this->template_id,
            'file_id' => $this->file_id,
            'provider' => $this->provider,
            'model' => $this->model,
            'layer' => $this->layer,
            'status' => $this->status,
            'error_message' => $this->error_message,
            'tokens_used' => $this->tokens_used,
            'duration_ms' => $this->duration_ms,
            'created_at' => $this->created_at?->toIso8601String(),
            'completed_at' => $this->completed_at?->toIso8601String(),
        ];
    }
}
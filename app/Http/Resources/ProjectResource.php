<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'template_id' => $this->template_id,
            'type' => new TypeResource($this->whenLoaded('type')),
            'origin_template_name' => $this->origin_template_name,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'visibility' => $this->visibility,
            'tags' => $this->tags ?? [],
            'locale' => $this->locale,
            'direction' => $this->direction,
            'cloned_at' => $this->cloned_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'template_id' => $this->template_id,
            'project_id' => $this->project_id,
            'layer' => $this->layer,
            'name' => $this->name,
            'extension' => $this->extension,
            'sort_order' => $this->sort_order,
            'content' => $this->when($this->shouldLoadContent($request), $this->content),
            'storage_url' => $this->storage_url,
            'size_bytes' => $this->size_bytes,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }

    private function shouldLoadContent(Request $request): bool
    {
        return $request->user() && ($request->user()->id === $this->user_id || in_array($request->user()->role, ['admin', 'superadmin']));
    }
}

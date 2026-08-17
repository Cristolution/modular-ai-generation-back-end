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
        $user = $request->user();

        // Owner and admins always see content.
        if ($user && ($user->id === $this->user_id || in_array($user->role, ['admin', 'superadmin']))) {
            return true;
        }

        // Template files inherit the template's visibility — a public template
        // is meant to be previewed by anyone, so its content must be returned
        // to allowed viewers. (The caller has already passed Gate::authorize
        // 'view' on the parent template, so privacy is enforced upstream.)
        if ($this->template_id) {
            $template = $this->relationLoaded('template') ? $this->template : null;
            if ($template && $template->visibility === 'public') {
                return true;
            }
        }

        return false;
    }
}

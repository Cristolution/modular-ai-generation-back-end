<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $request->user();

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'author' => new UserSummaryResource($this->whenLoaded('user')),
            'type' => new TypeResource($this->whenLoaded('type')),
            'name' => $this->name,
            'description' => $this->description,
            'thumbnail_url' => $this->thumbnail_url,
            'visibility' => $this->visibility,
            'tags' => $this->tags ?? [],
            'locale' => $this->locale,
            'direction' => $this->direction,
            'fork_count' => $this->fork_count,
            'upvote_count' => $this->upvote_count,
            'is_upvoted' => $user ? $this->upvotes()->where('user_id', $user->id)->exists() : false,
            'is_bookmarked' => $user ? $this->bookmarks()->where('user_id', $user->id)->exists() : false,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

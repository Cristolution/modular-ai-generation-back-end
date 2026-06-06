<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $request->user();

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'author' => new UserSummaryResource($this->whenLoaded('user')),
            'forked_from_id' => $this->forked_from_id,
            'kind' => $this->kind,
            'name' => $this->name,
            'description' => $this->description,
            'content' => $this->content,
            'placeholders' => $this->placeholders ?? [],
            'visibility' => $this->visibility,
            'tags' => $this->tags ?? [],
            'upvote_count' => $this->upvotes()->count(),
            'fork_count' => $this->forks()->count(),
            'is_upvoted' => $user ? $this->upvotes()->where('user_id', $user->id)->exists() : false,
            'is_bookmarked' => $user ? $this->bookmarks()->where('user_id', $user->id)->exists() : false,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
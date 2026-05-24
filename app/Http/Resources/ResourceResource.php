<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
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
            'is_upvoted' => $this->when($request->user(), fn () => $this->upvotes()->where('user_id', $request->user()->id)->exists()),
            'is_bookmarked' => $this->when($request->user(), fn () => $this->bookmarks()->where('user_id', $request->user()->id)->exists()),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
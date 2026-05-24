<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'author' => new UserSummaryResource($this->whenLoaded('user')),
            'target_id' => $this->target_id,
            'target_type' => $this->target_type,
            'parent_id' => $this->parent_id,
            'body' => $this->body,
            'replies' => $this->when($this->replies->isNotEmpty(), function () {
                return CommentResource::collection($this->replies);
            }),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
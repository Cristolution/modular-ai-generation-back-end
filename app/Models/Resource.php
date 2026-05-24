<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'forked_from_id',
        'kind',
        'name',
        'description',
        'content',
        'placeholders',
        'visibility',
        'tags',
    ];

    protected function casts(): array
    {
        return [
            'placeholders' => 'array',
            'tags' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function forkedFrom(): BelongsTo
    {
        return $this->belongsTo(Resource::class, 'forked_from_id');
    }

    public function forks(): HasMany
    {
        return $this->hasMany(Resource::class, 'forked_from_id');
    }

    public function upvotes(): MorphMany
    {
        return $this->morphMany(Upvote::class, 'target');
    }

    public function bookmarks(): MorphMany
    {
        return $this->morphMany(Bookmark::class, 'target');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'target', 'target_type');
    }
}
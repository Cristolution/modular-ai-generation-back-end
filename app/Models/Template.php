<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'type_id',
        'name',
        'description',
        'thumbnail_url',
        'visibility',
        'tags',
        'locale',
        'direction',
        'fork_count',
        'upvote_count',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'fork_count' => 'integer',
            'upvote_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function upvotes(): MorphMany
    {
        return $this->morphMany(Upvote::class, 'target');
    }

    public function bookmarks(): MorphMany
    {
        return $this->morphMany(Bookmark::class, 'target');
    }
}

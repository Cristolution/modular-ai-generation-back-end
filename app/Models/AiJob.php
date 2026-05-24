<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiJob extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'file_id',
        'project_id',
        'template_id',
        'triggered_by',
        'provider_id',
        'provider',
        'model',
        'layer',
        'prompt',
        'status',
        'error_message',
        'tokens_used',
        'duration_ms',
        'created_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'tokens_used' => 'integer',
            'duration_ms' => 'integer',
            'created_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function triggeredByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    public function aiProvider(): BelongsTo
    {
        return $this->belongsTo(UserAiProvider::class, 'provider_id');
    }

    public function markAsRunning(): void
    {
        $this->update(['status' => 'running']);
    }

    public function markAsSuccess(int $tokensUsed, int $durationMs): void
    {
        $this->update([
            'status' => 'success',
            'tokens_used' => $tokensUsed,
            'duration_ms' => $durationMs,
            'completed_at' => now(),
        ]);
    }

    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'completed_at' => now(),
        ]);
    }
}
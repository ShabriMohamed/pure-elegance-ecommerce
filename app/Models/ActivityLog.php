<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'model_type', 'model_id', 'changes', 'ip_address', 'user_agent',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record an activity entry from the current request context.
     */
    public static function record(string $action, ?Model $subject = null, array $changes = []): void
    {
        static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $subject ? $subject::class : null,
            'model_id' => $subject?->getKey(),
            'changes' => $changes ?: null,
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 500),
        ]);
    }
}

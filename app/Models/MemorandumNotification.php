<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemorandumNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'memorandum_id',
        'actor_id',
        'type',
        'title',
        'message',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who receives this notification
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the memorandum
     */
    public function memorandum()
    {
        return $this->belongsTo(Memorandum::class);
    }

    /**
     * Get the user who performed the action
     */
    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Create notification (with duplicate prevention)
     */
    public static function createNotification($userId, $memorandumId, $actorId, $type, $title, $message)
    {
        // Prevent self-notification
        if ($userId == $actorId) {
            return null;
        }

        // Check for duplicate within last 5 minutes
        $exists = self::where('user_id', $userId)
            ->where('memorandum_id', $memorandumId)
            ->where('type', $type)
            ->where('created_at', '>', now()->subMinutes(5))
            ->exists();

        if ($exists) {
            return null;
        }

        return self::create([
            'user_id' => $userId,
            'memorandum_id' => $memorandumId,
            'actor_id' => $actorId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
        ]);
    }
}

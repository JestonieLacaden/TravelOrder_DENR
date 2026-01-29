<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemorandumForward extends Model
{
    use HasFactory;

    protected $fillable = [
        'memorandum_id',
        'forwarded_by',
        'forwarded_to',
        'message',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function memorandum()
    {
        return $this->belongsTo(Memorandum::class, 'memorandum_id');
    }

    public function forwardedByUser()
    {
        return $this->belongsTo(User::class, 'forwarded_by');
    }

    public function forwardedToUser()
    {
        return $this->belongsTo(User::class, 'forwarded_to');
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}

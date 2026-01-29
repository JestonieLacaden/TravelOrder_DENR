<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemorandumComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'memorandum_id',
        'user_id',
        'comment',
        'section',
        'action_type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the memorandum that owns the comment
     */
    public function memorandum()
    {
        return $this->belongsTo(Memorandum::class);
    }

    /**
     * Get the user who made the comment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

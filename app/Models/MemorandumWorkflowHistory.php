<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemorandumWorkflowHistory extends Model
{
    use HasFactory;

    protected $table = 'memorandum_workflow_history';

    protected $fillable = [
        'memorandum_id',
        'from_user_id',
        'to_user_id',
        'can_edit',
        'action',
        'previous_status',
        'new_status',
        'remarks',
        'old_content',
    ];

    protected $casts = [
        'can_edit' => 'boolean',
        'old_content' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Get the user who received (for forward actions)
     */
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}

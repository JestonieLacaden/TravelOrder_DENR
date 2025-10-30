<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApproval extends Model
{
    protected $fillable = [
        'leave_id',
        'step',
        'approver_employee_id',
        'approver_name',
        'approver_position',
        'signature_path',
        'approved_at',
    ];

    public function leave()
    {
        return $this->belongsTo(Leave::class);
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approver_employee_id');
    }
}
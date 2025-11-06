<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveSignatory extends Model
{
    protected $table = 'leave_signatory';
    protected $fillable = [
        'name',
        'approver1',
        'approver2',
        'approver3',
        'signature1_path',
        'signature2_path',
        'signature3_path',
    ];

    use HasFactory;

    protected $guarded = [];

    public function Employee1()
    {
        return $this->belongsTo(Employee::class, 'approver1', 'id');
    }
    public function Employee2()
    {
        return $this->belongsTo(Employee::class, 'approver2', 'id');
    }
    public function Employee3()
    {
        return $this->belongsTo(Employee::class, 'approver3', 'id');
    }
    public function setLeaveSignatory()
    {
        return $this->hasMany(setLeaveSignatory::class, 'id');
    }
    public function getSignature1UrlAttribute()
    {
        return $this->signature1_path ? asset('storage/' . $this->signature1_path) : null;
    }
    public function getSignature2UrlAttribute()
    {
        return $this->signature2_path ? asset('storage/' . $this->signature2_path) : null;
    }
    public function getSignature3UrlAttribute()
    {
        return $this->signature3_path ? asset('storage/' . $this->signature3_path) : null;
    }
}
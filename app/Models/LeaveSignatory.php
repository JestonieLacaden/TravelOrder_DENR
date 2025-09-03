<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveSignatory extends Model
{
    protected $table='leave_signatory';

    use HasFactory;

    protected $guarded = []; 

    public function Employee1() {
        return $this->belongsTo(Employee::class,'approver1', 'id');
    }
    public function Employee2() {
        return $this->belongsTo(Employee::class,'approver2', 'id');
    }
    public function Employee3() {
        return $this->belongsTo(Employee::class,'approver3', 'id');
    }
    public function setLeaveSignatory() {
        return $this->hasMany(setLeaveSignatory::class,'id');
    }
}

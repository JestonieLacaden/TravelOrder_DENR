<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetLeaveSignatory extends Model
{
    protected $table='set_leave_signatory';

    use HasFactory;

    protected $guarded = []; 
    
    public function Section() {
        return $this->belongsTo(Section::class,'sectionid');
    }
    public function Office() {
        return $this->belongsTo(Office::class,'officeid');
    }

    public function LeaveSignatory() {
        return $this->belongsTo(LeaveSignatory::class,'leavesignatoryid');
    }
}

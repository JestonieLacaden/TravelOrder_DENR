<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelOrder extends Model
{
    protected $table='travel_order';

    use HasFactory;

    protected $guarded = []; 

    public function Employee() {
        return $this->belongsTo(Employee::class,'employeeid');
    }

    public function TravelOrderApproved() {
        return $this->belongsTo(TravelOrderApproved::class,'id');
    }

    public function User() {
        return $this->belongsTo(User::class,'userid','id');
    }
    
}

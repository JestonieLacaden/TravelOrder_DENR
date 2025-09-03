<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelOrderSignatory extends Model
{
    protected $table='travel_order_signatory';

    use HasFactory;

    protected $guarded = []; 

    public function Employee1() {
        return $this->belongsTo(Employee::class,'approver1', 'id');
    }
    public function Employee2() {
        return $this->belongsTo(Employee::class,'approver2', 'id');
    }
  
    public function SetTravelOrderSignatory() {
        return $this->hasMany(SetTravelOrderSignatory::class,'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelOrderApproved extends Model
{
    protected $table='travel_order_approved';

    use HasFactory;

    protected $guarded = []; 

    public function Employee() {
        return $this->belongsTo(Employee::class,'id');
    }

    public function TravelOrder() {
        return $this->belongsTo(TravelOrder::class,'id');
    }

}

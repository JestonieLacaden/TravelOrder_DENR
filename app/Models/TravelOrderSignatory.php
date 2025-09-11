<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelOrderSignatory extends Model
{
    use HasFactory;

    protected $table = 'travel_order_signatory';
    protected $guarded = [];

    public function Employee1()
    {
        return $this->belongsTo(Employee::class, 'approver1', 'id');
    }
    public function Employee2()
    {
        return $this->belongsTo(Employee::class, 'approver2', 'id');
    }

    public function SetTravelOrderSignatory()
    {
        return $this->hasMany(SetTravelOrderSignatory::class, 'travelordersignatoryid', 'id');
    }
}
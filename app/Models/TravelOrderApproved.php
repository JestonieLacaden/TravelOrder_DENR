<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelOrderApproved extends Model
{
    protected $table = 'travel_order_approved';

    use HasFactory;

    protected $fillable = [
        'employeeid',     // or 'employeeid' if that is your actual column
        'travelorderid',
        'request_id'
    ];


    protected $guarded = [];

    // public function Employee() {
    //     return $this->belongsTo(Employee::class,'id');
    // }

    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class, 'employeeid', 'id');
    }

    public function travelOrder()
    {
        return $this->belongsTo(\App\Models\TravelOrder::class, 'request_id', 'id');
    }

    public $timestamps = true; // your table has created_at/updated_at
}
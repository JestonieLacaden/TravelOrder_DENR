<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelOrder extends Model
{
    use HasFactory;

    protected $table = 'travel_order';
    protected $guarded = [];

    protected $fillable = [
        'userid',
        'employeeid',
        'daterange',
        'destinationoffice',
        'purpose',
        'perdime',
        'appropriation',
        'remarks',
        'travelordersignatoryid',
        'is_approve1',
        'is_approve2',
        'is_rejected1',
        'is_rejected2',
    ];

    public function Employee()
    {
        return $this->belongsTo(Employee::class, 'employeeid', 'id');
    }
    public function User()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    public function selectedSignatory()
    {
        return $this->belongsTo(TravelOrderSignatory::class, 'travelordersignatoryid', 'id');
    }
    public function Signatory()
    {
        return $this->selectedSignatory();
    }
}
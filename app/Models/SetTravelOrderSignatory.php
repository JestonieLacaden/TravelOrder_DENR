<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetTravelOrderSignatory extends Model
{
    use HasFactory;

    protected $table = 'set_travel_order_signatory';
    protected $guarded = [];

    public function Section()
    {
        return $this->belongsTo(Section::class, 'sectionid', 'id');
    }
    public function Office()
    {
        return $this->belongsTo(Office::class, 'officeid', 'id');
    }
    public function TravelOrderSignatory()
    {
        return $this->belongsTo(TravelOrderSignatory::class, 'travelordersignatoryid', 'id');
    }
}
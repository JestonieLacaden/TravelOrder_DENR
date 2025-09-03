<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetTravelOrderSignatory extends Model
{
    protected $table='set_travel_order_signatory';

    use HasFactory;

    protected $guarded = []; 

    public function Section() {
        return $this->belongsTo(Section::class,'sectionid');
    }
    public function Office() {
        return $this->belongsTo(Office::class,'officeid');
    }

    public function TraveOrderSignatory() {
        return $this->belongsTo(TravelOrderSignatory::class,'travelordersignatoryid');
    }
}

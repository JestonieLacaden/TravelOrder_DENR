<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DtrSignatory extends Model
{
    protected $table='dtr_signatory';

    use HasFactory;

    protected $guarded = []; 

    public function Employee() {

        return $this->belongsTo(Employee::class,'employeeid');
    }

    public function Signatory() {

        return $this->belongsTo(Employee::class,'signatory');
    }

}

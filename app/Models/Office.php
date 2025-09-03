<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $table='Office';

    use HasFactory;

    protected $guarded = []; 

    public function Section() {
        return $this->hasOne(Section::class,'id');
    }
    public function Unit() {
        return $this->hasOne(Unit::class,'id');
    }

    public function Employee() {
        return $this->belongsTo(Employee::class,'id');
    }

    public function Route() {
        return $this->hasMany(Route::class,'id');
    }
    public function SetLeaveSignatory() {
        return $this->hasMany(SetLeaveSignatory::class,'id');
    }

    public function SetTravelOrderSignatory() {
        return $this->hasMany(SetTravelOrderSignatory::class,'id');
    }
}

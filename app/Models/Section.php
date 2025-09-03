<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table='Section';
    use HasFactory;
    protected $guarded = [];    
  

    public function Office() {
       return $this->belongsTo(Office::class,'officeid');
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

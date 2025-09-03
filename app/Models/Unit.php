<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table='unit';
    use HasFactory;
    protected $guarded = [];    

    public function Section() {
       return $this->belongsTo(Section::class,'sectionid');
    }
    
    public function Office() {
        return $this->belongsTo(Office::class,'officeid');
    } 
 
     public function Employee() {
        return $this->belongsTo(Employee::class,'email','email');
    }
    public function Route() {
        return $this->hasMany(Route::class,'id');
    }
    public function FMRoute() {
        return $this->hasMany(FinancialManagementRoute::class,'id');
    }
}   

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $table = 'office';
    protected $guarded = [];

    public function sections()
    {
        return $this->hasMany(Section::class, 'officeid', 'id');
    }
    public function Section()
    {
        return $this->sections();
    } // legacy alias
    public function employees()
    {
        return $this->hasMany(Employee::class, 'officeid', 'id');
    }
    public function setLeaveSignatories()
    {
        return $this->hasMany(SetLeaveSignatory::class, 'officeid', 'id');
    }
    public function setTravelOrderSignatories()
    {
        return $this->hasMany(SetTravelOrderSignatory::class, 'officeid', 'id');
    }
}
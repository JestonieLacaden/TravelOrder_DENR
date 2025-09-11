<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    // Table name (lowercase to match MySQL)
    protected $table = 'section';
    protected $guarded = [];

    // Relationships (keep ONE name only, lower camelCase)

    public function office()
    {
        // section.officeid -> office.id
        return $this->belongsTo(Office::class, 'officeid', 'id');
    }

    public function employees()
    {
        // employee.sectionid -> section.id
        return $this->hasMany(Employee::class, 'sectionid', 'id');
    }

    public function setLeaveSignatory()
    {
        // set_leave_signatory.sectionid -> section.id
        return $this->hasMany(SetLeaveSignatory::class, 'sectionid', 'id');
    }

    public function setTravelOrderSignatory()
    {
        // set_travel_order_signatory.sectionid -> section.id
        return $this->hasMany(SetTravelOrderSignatory::class, 'sectionid', 'id');
    }

    // The rest are kept for compatibility; adjust only if you really use them.
    public function unit()
    {
        return $this->hasOne(Unit::class, 'sectionid', 'id');
    }

    public function route()
    {
        return $this->hasMany(Route::class, 'sectionid', 'id');
    }
}
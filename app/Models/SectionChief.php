<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionChief extends Model
{
    use HasFactory;

    protected $table = 'section_chief';

    protected $fillable = [
        'unitid',
        'employeeid',
    ];

    /**
     * Get the unit that this chief belongs to
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unitid', 'id');
    }

    /**
     * Get the employee who is the section chief
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employeeid', 'id');
    }
}

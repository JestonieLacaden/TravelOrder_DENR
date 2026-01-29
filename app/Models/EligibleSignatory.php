<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EligibleSignatory extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'role',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $table='leave';

    use HasFactory;

    protected $guarded = []; 

    public function Employee() {
        return $this->belongsTo(Employee::class, 'employeeid','id');
    }

    public function Leave_Type() {
        return $this->belongsTo(Leave_Type::class, 'leaveid','id');
    }
    public function User() {
        return $this->belongsTo(User::class, 'userid','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave_Type extends Model
{
    protected $table='leave_type';

    use HasFactory;

    protected $guarded = []; 

    public function Leave() {
        return $this->hasMany(Leave::class,'leaveid','id');
    }
}

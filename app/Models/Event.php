<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table='event';

    use HasFactory;

    protected $guarded = []; 

    public function User() {
        return $this->belongsTo(User::class,'userid');
    }
}

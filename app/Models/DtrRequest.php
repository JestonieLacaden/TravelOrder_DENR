<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DtrRequest extends Model
{
    protected $table='dtr_request';

    use HasFactory;

    protected $guarded = []; 

}

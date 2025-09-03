<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dtr_History extends Model
{
    protected $table='dtr_history';

    use HasFactory;

    protected $guarded = []; 

    public function User() {

        return $this->belongsTo(User::class,'userid');
    }
    public function Employee() {

        return $this->belongsTo(Employee::class,'employeeid');
    }

    public function Late(Request $request) {

    }
}

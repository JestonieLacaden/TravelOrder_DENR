<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table='user_roles';

    use HasFactory;

    protected $guarded = []; 

    public function User() {
        return $this->belongsTo(User::class,'id','userid');
    }
    public function Role() {
        return $this->belongsTo(Role::class,'roleid');
    }

}

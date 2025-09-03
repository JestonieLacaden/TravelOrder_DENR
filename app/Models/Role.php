<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table='Role';

    use HasFactory;

    protected $guarded = []; 

    public function User() {
        return $this->belongsto(User::class,'id');
    }

    public function UserRole() {
        return $this->hasMany(UserRole::class,'id');
    }
    public const admin = 1;
    public const user = 2;
    public const records = 3;
    public const planning = 4;
    public const msd = 5;
}

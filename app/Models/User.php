<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'roleid',
        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Role() {
        return $this->hasOne(Role::class,'id','roleid');
    
    }

    public function UserRole() {
        return $this->hasMany(UserRole::class,'roleid','id');
    }

    public function Employee() {

        return $this->belongsTo(Employee::class,'email');
    }

    public function Route() {
        return $this->hasmany(Route::class,'id');
    }

    public function Dtr_History() {
        return $this->hasmany(Dtr_History::class,'id');
    }
    public function Event() {
        return $this->hasOne(Event::class,'id');
    }

    public function Leave() {
        return $this->hasMany(Leave::class,'id');
    }
}

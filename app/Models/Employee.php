<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Employee extends Model
{
    protected $table='employee';

    use HasFactory;

    // protected $guarded = []; 

    protected $fillable = [
        'employeeid',
        'firstname',
        'lastname',
        'middlename',
        'suffix',
        'email',
        'position',
        'unitid',
        'sectionid',
        'officeid',
        'contactnumber',
        'picture',
        'signature_path',
    ];

    
    

    // public function Section() {
    //     return $this->hasOne(Section::class,'id','sectionid');
    // }
    public function Unit() {
        return $this->hasOne(Unit::class,'id','unitid');
    }
    // public function Office() {
    //     return $this->hasOne(Office::class,'id','officeid');
    // }

    public function DtrSignatory() {
        return $this->hasOne(DtrSignatory::class,'employeeid','employeeid');
    }

    public function User() {
        return $this->hasOne(User::class,'email', 'email');
    }
    public function Dtr_History() {
        return $this->hasMany(dtr_history::class,'employeeid', 'id');
    }
    public function Leave() {
        return $this->hasMany(Leave::class, 'id');
    }

    public function LeaveSignatory1() {
        return $this->hasMany(LeaveSignatory::class,'approver1', 'id');
    }
    public function LeaveSignatory2() {
        return $this->hasMany(LeaveSignatory::class,'approver2', 'id');
    }
    public function LeaveSignatory3() {
        return $this->hasMany(LeaveSignatory::class,'approver3', 'id');
    }

    // public function TravelOrderSignatory1() {
    //     return $this->hasMany(TravelOrderSignatory::class,'approver1', 'id');
    // }
    // public function TravelOrderSignatory2() {
    //     return $this->hasMany(TravelOrderSignatory::class,'approver2', 'id');
    // }
    // public function TravelOrder() {
    //     return $this->hasMany(TravelOrder::class, 'id');
    // }
    public function TravelOrderApproved() {
        return $this->hasMany(TravelOrderApproved::class, 'id');
    }

    //Bagong lagay ko
    public function office()
    {
        return $this->belongsTo(Office::class, 'officeid', 'id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'sectionid', 'id');
    }

    // Ako bilang approver
    public function TravelOrderSignatory1()
    {
        return $this->hasMany(TravelOrderSignatory::class, 'approver1', 'id');
    }
    public function TravelOrderSignatory2()
    {
        return $this->hasMany(TravelOrderSignatory::class, 'approver2', 'id');
    }

    // Mga Travel Orders na ako ang requester
    public function TravelOrder()
    {
        return $this->hasMany(TravelOrder::class, 'employeeid', 'id');
    }

    public function getSignatureUrlAttribute(): ?string
    {
        if (!$this->signature_path) return null;

        $p = ltrim(str_replace('\\', '/', $this->signature_path), '/');
        if (Storage::disk('public')->exists($p)) return Storage::url($p);
        if (file_exists(public_path($p))) return asset($p);

        return null; // fallback handled sa Blade
    }
    
}
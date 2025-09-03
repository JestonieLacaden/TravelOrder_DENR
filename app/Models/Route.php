<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $table='Route';
    use HasFactory;
    protected $guarded = [];    
  

    public function Document() {
       return $this->belongsTo(Document::class,'documentid','PDN');
    }

    public function User() {
        return $this->belongsTo(User::class,'userid');
    }
    public function Office() {
        return $this->belongsTo(Office::class,'officeid');
    }
    public function Section() {
        return $this->belongsTo(Section::class,'sectionid');
    }
    public function Unit() {
        return $this->belongsTo(Unit::class,'unitid');
    }

    public function userUnit() {
        return $this->belongsTo(Unit::class,'userunitid');
    }

    public function Attachment() {
        return $this->hasOne(Attachment::class,'attachment','remarks');
    }


  
}

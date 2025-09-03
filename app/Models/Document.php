<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    
    protected $table = "Document";
    
    use HasFactory;

    protected $guarded = [];

    public function attachment() {
        return $this->hasMany(Attachment::class,'documentid', 'PDN');
    }

    public function Route() {
        return $this->hasMany(Route::class,'documentid','PDN')->orderby('created_at', 'desc');
    }

    public function lastRoute() {
        return $this->hasMany(Route::class,'documentid','PDN')->orderby('created_at', 'desc');
    }
}

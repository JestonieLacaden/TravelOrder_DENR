<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $table = "Attachment";    
    
    use HasFactory;

    protected $guarded = [];

    public function Document() {
        return $this-> belongsto(Document::class, 'PDN');
    }

    public function Route() {
        return $this-> belongsTo(Route::class,'attachment');
    }
}

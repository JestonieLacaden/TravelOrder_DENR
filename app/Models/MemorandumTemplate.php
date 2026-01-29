<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemorandumTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'version',
        'description',
        'layout_config',
        'header_line_1',
        'header_line_2',
        'header_line_3',
        'header_line_4',
        'footer_template',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'layout_config' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function memorandums()
    {
        return $this->hasMany(Memorandum::class, 'template_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Methods
    public static function getActiveTemplate()
    {
        return self::where('is_active', true)->first();
    }
}

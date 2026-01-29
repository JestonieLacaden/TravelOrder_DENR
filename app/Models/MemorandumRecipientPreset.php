<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemorandumRecipientPreset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'office',
        'position',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get formatted text for preview
     */
    public function getFormattedTextAttribute()
    {
        $text = $this->name . "\n";
        $text .= $this->office;

        if ($this->position) {
            $text .= ', ' . $this->position;
        }

        return $text;
    }

    /**
     * Scope for active presets only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('display_order');
    }
}

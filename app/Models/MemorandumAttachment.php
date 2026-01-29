<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemorandumAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'memorandum_attachments';

    protected $fillable = [
        'memorandum_id',
        'original_filename',
        'stored_path',
        'file_type',
        'file_size',
        'uploaded_by',
    ];

    // Allowed file types
    const ALLOWED_TYPES = ['pdf', 'jpg', 'jpeg', 'png', 'docx'];
    const MAX_FILE_SIZE = 10485760; // 10MB in bytes

    /**
     * Relationship: Belongs to Memorandum
     */
    public function memorandum()
    {
        return $this->belongsTo(Memorandum::class);
    }

    /**
     * Relationship: Belongs to User (uploader)
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get human-readable file size
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }

    /**
     * Get file type icon class
     */
    public function getFileIconAttribute()
    {
        $icons = [
            'pdf' => 'fas fa-file-pdf text-danger',
            'jpg' => 'fas fa-file-image text-info',
            'jpeg' => 'fas fa-file-image text-info',
            'png' => 'fas fa-file-image text-info',
            'docx' => 'fas fa-file-word text-primary',
        ];
        return $icons[$this->file_type] ?? 'fas fa-file text-secondary';
    }

    /**
     * Check if file is previewable (PDF or image)
     */
    public function isPreviewable()
    {
        return in_array($this->file_type, ['pdf', 'jpg', 'jpeg', 'png']);
    }
}

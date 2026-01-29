<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Memorandum extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'memorandums';

    protected $fillable = [
        'template_id',
        'memorandum_date',
        'memorandum_number',
        'transaction_number',
        'recipient_type',
        'recipient_for',
        'recipient_to',
        'through',
        'attn',
        'from_text',
        'from_user_id',
        'from_name',
        'from_position',
        'show_position',
        'subject',
        'body',
        'use_esignature',
        'signature_path',
        'signature_override_path',
        'status',
        'created_by',
        'current_holder_id',
        'previous_holder_id',
        'revision_count',
        'generated_at',
        'signed_at',
        'approved_at',
        'returned_at',
        'docx_path',
        'pdf_path',
    ];

    protected $casts = [
        'memorandum_date' => 'date',
        'use_esignature' => 'boolean',
        'show_position' => 'boolean',
        'generated_at' => 'datetime',
        'signed_at' => 'datetime',
        'approved_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    // Relationships
    public function template()
    {
        return $this->belongsTo(MemorandumTemplate::class, 'template_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function currentHolder()
    {
        return $this->belongsTo(User::class, 'current_holder_id');
    }

    public function previousHolder()
    {
        return $this->belongsTo(User::class, 'previous_holder_id');
    }

    public function forwards()
    {
        return $this->hasMany(MemorandumForward::class, 'memorandum_id');
    }

    public function comments()
    {
        return $this->hasMany(MemorandumComment::class, 'memorandum_id')->orderBy('created_at', 'desc');
    }

    public function workflowHistory()
    {
        return $this->hasMany(MemorandumWorkflowHistory::class, 'memorandum_id')->orderBy('created_at', 'desc');
    }

    public function notifications()
    {
        return $this->hasMany(MemorandumNotification::class, 'memorandum_id')->orderBy('created_at', 'desc');
    }

    public function attachments()
    {
        return $this->hasMany(MemorandumAttachment::class, 'memorandum_id')->orderBy('created_at', 'desc');
    }

    // Methods
    public function getParagraphsArray()
    {
        return array_filter(explode("\n", $this->body));
    }

    public function generateMemorandumNumber()
    {
        $year = date('Y');
        $month = date('m');

        // Count memorandums created in the current year and month
        $count = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count() + 1;

        return sprintf('MEMO-%s-%s-%04d', $year, $month, $count);
    }
}

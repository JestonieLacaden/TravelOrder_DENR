<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelOrder extends Model
{
    use HasFactory;

    protected $table = 'travel_order';
    // protected $guarded = [];

    protected $fillable = [
        'id',
        'userid',
        'employeeid',
        'daterange',
        'destinationoffice',
        'purpose',
        'perdime',
        'appropriation',
        'remarks',
        'is_prepayment',
        'employee_signature',
        'travelordersignatoryid',
        'is_approve1',
        'is_approve2',
        'is_approve3',
        'is_rejected1',
        'is_rejected2',
        'is_rejected3',
        'is_returned1',
        'is_returned2',
        'is_returned3',
        'rejected1_reason',
        'rejected2_reason',
        'rejected3_reason',
        'returned1_reason',
        'returned2_reason',
        'returned3_reason',
        'approve1_by',
        'approve1_at',
        'approve2_by',
        'approve2_at',
        'approve3_by',
        'approve3_at',
    ];

    protected $casts = [
        'is_approve1'  => 'bool',
        'is_approve2'  => 'bool',
        'is_rejected1' => 'bool',
        'is_rejected2' => 'bool',
        'is_returned1' => 'bool',
        'is_returned2' => 'bool',
        'is_returned3' => 'bool',
        'is_prepayment' => 'bool',
        'approve1_at' => 'datetime',
        'approve2_at' => 'datetime',
    ];

    public function Employee()
    {
        return $this->belongsTo(Employee::class, 'employeeid', 'id');
    }
    public function User()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    public function selectedSignatory()
    {
        return $this->belongsTo(TravelOrderSignatory::class, 'travelordersignatoryid', 'id');
    }
    public function Signatory()
    {
        return $this->selectedSignatory();
    }

    public function approved()
    {
        // TravelOrderApproved.travelorderid -> TravelOrder.id
        return $this->hasOne(\App\Models\TravelOrderApproved::class, 'request_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $table = 'leave';

    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        // When deleting a leave, restore the balance
        static::deleting(function ($leave) {
            // Only restore if leave was fully approved
            if ($leave->is_approve1 && $leave->is_approve2 && $leave->is_approve3) {
                $employee = $leave->Employee;
                if ($employee) {
                    // Get leave type
                    $leaveType = strtolower(optional($leave->Leave_Type)->leave_type ?? '');

                    // Calculate days to restore
                    [$startDate, $endDate] = explode(' - ', $leave->daterange);
                    $start = \Carbon\Carbon::parse($startDate);
                    $end = \Carbon\Carbon::parse($endDate);
                    $daysToRestore = $leave->is_half_day ? 0.5 : ($start->diffInDays($end) + 1);

                    // Restore balance
                    if (str_contains($leaveType, 'vacation')) {
                        $newBalance = $employee->vacation_leave_balance + $daysToRestore;
                        $employee->update(['vacation_leave_balance' => $newBalance]);
                        \Log::info("Restored vacation balance: Employee {$employee->id}, Added: {$daysToRestore}, New Balance: {$newBalance}");
                    } elseif (str_contains($leaveType, 'sick')) {
                        $newBalance = $employee->sick_leave_balance + $daysToRestore;
                        $employee->update(['sick_leave_balance' => $newBalance]);
                        \Log::info("Restored sick balance: Employee {$employee->id}, Added: {$daysToRestore}, New Balance: {$newBalance}");
                    }
                }
            }
        });
    }

    public function Employee()
    {
        return $this->belongsTo(Employee::class, 'employeeid', 'id');
    }

    public function Leave_Type()
    {
        return $this->belongsTo(Leave_Type::class, 'leaveid', 'id');
    }
    public function User()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }
    public function approvals()
    {
        return $this->hasMany(\App\Models\LeaveApproval::class);
    }
    public function approvalsOrdered()
    {
        return $this->approvals()->orderBy('step');
    }
}

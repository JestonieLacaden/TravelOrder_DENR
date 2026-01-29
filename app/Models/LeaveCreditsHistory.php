<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveCreditsHistory extends Model
{
    use HasFactory;

    protected $table = 'leave_credits_history';

    protected $fillable = [
        'employeeid',
        'period_year',
        'period_month',
        'vacation_earned',
        'sick_earned',
        'vacation_balance',
        'sick_balance',
        'imported_by',
        'imported_at'
    ];

    protected $casts = [
        'vacation_earned' => 'decimal:3',
        'sick_earned' => 'decimal:3',
        'vacation_balance' => 'decimal:3',
        'sick_balance' => 'decimal:3',
        'imported_at' => 'datetime'
    ];

    /**
     * Get the employee that owns the leave credits history
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employeeid');
    }

    /**
     * Get the user who imported this record
     */
    public function importedBy()
    {
        return $this->belongsTo(User::class, 'imported_by');
    }

    /**
     * Scope to get records for specific employee
     */
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employeeid', $employeeId);
    }

    /**
     * Scope to get records for specific year
     */
    public function scopeForYear($query, $year)
    {
        return $query->where('period_year', $year);
    }

    /**
     * Scope to get records for specific period
     */
    public function scopeForPeriod($query, $year, $month = null)
    {
        $query = $query->where('period_year', $year);

        if ($month !== null) {
            $query = $query->where('period_month', $month);
        }

        return $query;
    }

    /**
     * Get current balance for an employee (sum up to current date)
     */
    public static function getCurrentBalance($employeeId, $leaveType = 'vacation')
    {
        $earnedColumn = $leaveType . '_earned';

        return self::where('employeeid', $employeeId)
            ->sum($earnedColumn);
    }

    /**
     * Get month name
     */
    public function getMonthNameAttribute()
    {
        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];

        return $months[$this->period_month] ?? 'Unknown';
    }

    /**
     * Get period display (Year - Month)
     */
    public function getPeriodDisplayAttribute()
    {
        return $this->period_year . ' - ' . $this->month_name;
    }
}

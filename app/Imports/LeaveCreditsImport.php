<?php

namespace App\Imports;

use App\Models\LeaveCreditsHistory;
use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class LeaveCreditsImport implements ToCollection
{
    protected $employee;
    protected $parsedData = [];

    public function __construct()
    {
        // Initialize
    }

    /**
     * Process Excel collection
     */
    public function collection(Collection $rows)
    {
        // This will be implemented to parse the Leave Card format
        // For now, we'll create a basic structure

        $this->parsedData = [
            'employee_name' => 'Sample Employee',
            'employee_id' => null,
            'employee_matched' => false,
            'records' => [
                [
                    'period_year' => 2024,
                    'period_month' => 12,
                    'month_name' => 'December',
                    'vacation_earned' => 1.25,
                    'sick_earned' => 1.25,
                    'vacation_balance' => 50.00,
                    'sick_balance' => 45.00,
                    'valid' => true,
                    'errors' => []
                ]
            ],
            'summary' => [
                'total_records' => 1,
                'valid_records' => 1,
                'invalid_records' => 0
            ]
        ];
    }

    /**
     * Get parsed data
     */
    public function getParsedData()
    {
        return $this->parsedData;
    }

    /**
     * Parse Leave Card Excel format (to be implemented)
     */
    private function parseLeaveCard(Collection $rows)
    {
        // This will contain the logic to:
        // 1. Extract employee name from header
        // 2. Find rows with period data (October, November, etc.)
        // 3. Extract earned credits from columns
        // 4. Validate data

        return [];
    }

    /**
     * Match employee by name
     */
    private function matchEmployee($employeeName)
    {
        // Try to find employee by name
        // Handle variations like "LASTNAME, FIRSTNAME MIDDLE"

        $nameParts = explode(',', $employeeName);
        if (count($nameParts) >= 2) {
            $lastname = trim($nameParts[0]);
            $firstMiddle = trim($nameParts[1]);
            $firstnameParts = explode(' ', $firstMiddle);
            $firstname = trim($firstnameParts[0]);

            $employee = Employee::where('lastname', 'LIKE', "%{$lastname}%")
                ->where('firstname', 'LIKE', "%{$firstname}%")
                ->first();

            return $employee;
        }

        return null;
    }

    /**
     * Validate record data
     */
    private function validateRecord($record)
    {
        $errors = [];

        if (!isset($record['period_year']) || !is_numeric($record['period_year'])) {
            $errors[] = 'Invalid year';
        }

        if (!isset($record['period_month']) || $record['period_month'] < 1 || $record['period_month'] > 12) {
            $errors[] = 'Invalid month';
        }

        if (!is_numeric($record['vacation_earned']) || $record['vacation_earned'] < 0) {
            $errors[] = 'Invalid vacation earned amount';
        }

        if (!is_numeric($record['sick_earned']) || $record['sick_earned'] < 0) {
            $errors[] = 'Invalid sick earned amount';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}

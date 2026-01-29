<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveCreditsHistory;
use App\Models\Employee;
use App\Models\User;
use App\Imports\LeaveCreditsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeaveCreditsController extends Controller
{
    /**
     * Display the import page
     */
    public function index()
    {
        $recentImports = LeaveCreditsHistory::with(['employee', 'importedBy'])
            ->select('imported_by', 'imported_at', 'employeeid')
            ->whereNotNull('imported_at')
            ->orderBy('imported_at', 'desc')
            ->limit(10)
            ->get()
            ->groupBy('imported_at');

        return view('admin.leave-credits.import', compact('recentImports'));
    }

    /**
     * Upload and preview Excel file
     */
    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('excel_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('temp', $fileName, 'local');

            // Parse the Excel file
            $data = $this->parseExcelFile(storage_path('app/' . $filePath));

            // Store parsed data in session for preview
            session(['excel_preview_data' => $data, 'excel_file_path' => $filePath]);

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'File parsed successfully. Please review the data below.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Parse Excel file and extract leave credits data
     */
    private function parseExcelFile($filePath)
    {
        try {
            $import = new LeaveCreditsImport();
            Excel::import($import, $filePath);

            return $import->getParsedData();
        } catch (\Exception $e) {
            // Return sample data structure for now
            return [
                'employee_name' => 'Excel Parse Error: ' . $e->getMessage(),
                'employee_id' => null,
                'employee_matched' => false,
                'records' => [],
                'summary' => [
                    'total_records' => 0,
                    'valid_records' => 0,
                    'invalid_records' => 0
                ]
            ];
        }
    }

    /**
     * Import the previewed data into database
     */
    public function import(Request $request)
    {
        $previewData = session('excel_preview_data');
        $filePath = session('excel_file_path');

        if (!$previewData || !$filePath) {
            return response()->json([
                'success' => false,
                'message' => 'No data to import. Please upload a file first.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $importedCount = 0;
            $errors = [];

            foreach ($previewData['records'] as $record) {
                if (!$record['valid']) {
                    $errors[] = "Skipped invalid record for {$record['month_name']} {$record['period_year']}";
                    continue;
                }

                // Check if record already exists
                $existing = LeaveCreditsHistory::where([
                    'employeeid' => $previewData['employee_id'],
                    'period_year' => $record['period_year'],
                    'period_month' => $record['period_month']
                ])->first();

                if ($existing) {
                    $errors[] = "Record for {$record['month_name']} {$record['period_year']} already exists";
                    continue;
                }

                // Create new record
                LeaveCreditsHistory::create([
                    'employeeid' => $previewData['employee_id'],
                    'period_year' => $record['period_year'],
                    'period_month' => $record['period_month'],
                    'vacation_earned' => $record['vacation_earned'],
                    'sick_earned' => $record['sick_earned'],
                    'vacation_balance' => $record['vacation_balance'],
                    'sick_balance' => $record['sick_balance'],
                    'imported_by' => Auth::id(),
                    'imported_at' => now()
                ]);

                $importedCount++;
            }

            DB::commit();

            // Clean up temp file
            if (file_exists(storage_path('app/' . $filePath))) {
                unlink(storage_path('app/' . $filePath));
            }

            // Clear session data
            session()->forget(['excel_preview_data', 'excel_file_path']);

            return response()->json([
                'success' => true,
                'message' => "Successfully imported {$importedCount} records.",
                'imported_count' => $importedCount,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Error importing data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get leave credits history for an employee
     */
    public function getEmployeeCredits($employeeId)
    {
        $employee = Employee::find($employeeId);

        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        $credits = LeaveCreditsHistory::forEmployee($employeeId)
            ->orderBy('period_year', 'desc')
            ->orderBy('period_month', 'desc')
            ->get();

        $currentBalance = [
            'vacation' => LeaveCreditsHistory::getCurrentBalance($employeeId, 'vacation'),
            'sick' => LeaveCreditsHistory::getCurrentBalance($employeeId, 'sick')
        ];

        return response()->json([
            'employee' => $employee,
            'credits' => $credits,
            'current_balance' => $currentBalance
        ]);
    }

    /**
     * Cancel upload (clear session data)
     */
    public function cancelUpload()
    {
        $filePath = session('excel_file_path');

        if ($filePath && file_exists(storage_path('app/' . $filePath))) {
            unlink(storage_path('app/' . $filePath));
        }

        session()->forget(['excel_preview_data', 'excel_file_path']);

        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers\Msd;

use App\Http\Controllers\Controller;
use App\Models\EligibleSignatory;
use App\Models\Employee;
use Illuminate\Http\Request;

class EligibleSignatoryController extends Controller
{
    public function index()
    {
        $eligibleSignatories = EligibleSignatory::with('employee')->get();

        // Get all employees to allow adding anyone as eligible signatory
        $allEmployees = Employee::orderBy('lastname', 'asc')->get();

        return view('msd-panel.eligible-signatories.index', compact('eligibleSignatories', 'allEmployees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employee,id',
            'role' => 'required|in:section_chief,division_chief,penro'
        ]);

        EligibleSignatory::updateOrCreate(
            [
                'employee_id' => $request->employee_id,
                'role' => $request->role
            ],
            [
                'employee_id' => $request->employee_id,
                'role' => $request->role
            ]
        );

        return back()->with('message', 'Eligible signatory added successfully!');
    }

    public function destroy(EligibleSignatory $eligibleSignatory)
    {
        $eligibleSignatory->delete();
        return back()->with('message', 'Eligible signatory removed successfully!');
    }
}

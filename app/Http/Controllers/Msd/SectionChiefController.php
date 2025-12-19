<?php

namespace App\Http\Controllers\Msd;

use App\Http\Controllers\Controller;
use App\Models\SectionChief;
use App\Models\Unit;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionChiefController extends Controller
{
    /**
     * Display a listing of units with their assigned chiefs
     */
    public function index()
    {
        // Exclude special units: PENRO, MSD Chief, TSD Chief, OTHERS, OJT
        $units = Unit::with(['Section', 'Office', 'sectionChief.employee'])
            ->whereNotIn('id', [1, 6, 14, 15, 18]) // Exclude special units
            ->orderBy('unit', 'asc')
            ->get();

        return view('msd-panel.travel-order-settings.set-section-chief.index', compact('units'));
    }

    /**
     * Store a newly created section chief
     */
    public function store(Request $request)
    {
        $request->validate([
            'unitid' => 'required|exists:unit,id',
            'employeeid' => 'required|exists:employee,id',
        ]);

        // Check if employee is PERMANENT
        $employee = Employee::find($request->employeeid);
        if (strtolower($employee->empstatus ?? '') !== 'permanent') {
            return back()->with('error', 'Only PERMANENT employees can be assigned as Section Chiefs!');
        }

        // Check if unit already has a chief (prevent duplicate)
        $existingChief = SectionChief::where('unitid', $request->unitid)->first();
        if ($existingChief) {
            return back()->with('error', 'This unit already has a Section Chief assigned! Please use Edit to update.');
        }

        // Create new section chief assignment
        SectionChief::create([
            'unitid' => $request->unitid,
            'employeeid' => $request->employeeid,
        ]);

        return back()->with('message', 'Section Chief assigned successfully!');
    }

    /**
     * Update the specified section chief
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'unitid' => 'required|exists:unit,id',
            'employeeid' => 'required|exists:employee,id',
        ]);

        // Check if employee is PERMANENT
        $employee = Employee::find($request->employeeid);
        if (strtolower($employee->empstatus ?? '') !== 'permanent') {
            return back()->with('error', 'Only PERMANENT employees can be assigned as Section Chiefs!');
        }

        // Find section chief record
        $sectionChief = SectionChief::findOrFail($id);

        // If changing to a different unit, check if that unit already has a chief
        if ($sectionChief->unitid != $request->unitid) {
            $existingChief = SectionChief::where('unitid', $request->unitid)
                ->where('id', '!=', $id)
                ->first();
            if ($existingChief) {
                return back()->with('error', 'The selected unit already has a Section Chief!');
            }
        }

        // Update section chief
        $sectionChief->update([
            'unitid' => $request->unitid,
            'employeeid' => $request->employeeid,
        ]);

        return back()->with('message', 'Section Chief updated successfully!');
    }

    /**
     * Remove the specified section chief
     */
    public function destroy($id)
    {
        try {
            $sectionChief = SectionChief::findOrFail($id);
            $sectionChief->delete();

            return back()->with('message', 'Section Chief removed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove Section Chief: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint: Get permanent employees by unit (for AJAX)
     */
    public function getEmployeesByUnit($unitid)
    {
        \Log::info('Getting employees for unit: ' . $unitid);

        $employees = Employee::where('unitid', $unitid)
            ->where('empstatus', 'permanent')
            ->select('id', 'firstname', 'lastname', 'position', 'unitid', 'empstatus')
            ->orderBy('firstname', 'asc')
            ->get();

        \Log::info('Found ' . $employees->count() . ' employees');

        return response()->json($employees);
    }
}

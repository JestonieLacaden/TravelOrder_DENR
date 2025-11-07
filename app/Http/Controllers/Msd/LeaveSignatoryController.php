<?php

namespace App\Http\Controllers\Msd;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LeaveSignatory;
use Illuminate\Support\Facades\Storage;

class LeaveSignatoryController extends Controller
{
    
    public function index() 
    {

        $this->authorize('viewany', \App\Models\LeaveSignatory::class);
        
        $Employees = Employee::orderby('lastname','asc')->get();
        $Signatories = LeaveSignatory::with('Employee1','Employee2', 'Employee3')->get();
        return view('msd-panel.leave-signatory.index',compact('Employees', 'Signatories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', \App\Models\LeaveSignatory::class);

        $formfields = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'approver1'  => ['required', 'integer'],
            'approver2'  => ['required', 'integer', 'different:approver1'],
            'approver3'  => ['required', 'integer', 'different:approver1', 'different:approver2'],
            'signature1' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:512'],
            'signature2' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:512'],
            'signature3' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:512'],
        ]);

        // Unique signatory name
        if (LeaveSignatory::where('name', $formfields['name'])->exists()) {
            return back()->with('SignatoryError', 'Error! Duplicate signatory name.');
        }

        // Handle signatures or fallback to employee.signature_path
        foreach ([1, 2, 3] as $i) {
            $uploadKey = "signature{$i}";
            $col       = "signature{$i}_path";

            if ($request->hasFile($uploadKey)) {
                $formfields[$col] = $request->file($uploadKey)->store('signatures/leave', 'public');
            } else {
                // fallback to employee.signature_path
                $empId = $formfields["approver{$i}"];
                $emp   = Employee::find($empId);
                if ($emp && $emp->signature_path) {
                    $formfields[$col] = $emp->signature_path;
                }
            }
        }

        LeaveSignatory::create($formfields);

        return back()->with('message', 'Signatory Saved Successfully!');
    }


    public function destroy($LeaveSignatory) {
 
        $Signatory = LeaveSignatory::where('id','=',$LeaveSignatory)->get()->first();
        $this->authorize('delete', $Signatory);

     
        $Signatory->delete();
        
        return back()->with('message', "Signatory Deleted Successfully!");

    }

    public function update(Request $request, LeaveSignatory $leave_signatory)
    {
        $this->authorize('update', $leave_signatory);

        $formfields = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'approver1'  => ['required', 'integer'],
            'approver2'  => ['required', 'integer', 'different:approver1'],
            'approver3'  => ['required', 'integer', 'different:approver1', 'different:approver2'],
            'signature1' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:512'],
            'signature2' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:512'],
            'signature3' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:512'],
            // remove_signature1/2/3 are checkboxes (optional), no validation needed
        ]);

        foreach ([1, 2, 3] as $i) {
            $input    = "signature{$i}";
            $remove   = $request->boolean("remove_signature{$i}");
            $column   = "signature{$i}_path";
            $oldPath  = $leave_signatory->{$column};

            // If user wants to remove current file (even when not uploading a new one)
            if ($remove && $oldPath) {
                Storage::disk('public')->delete($oldPath);
                $formfields[$column] = null; // remove from DB
            }

            // If a new file is uploaded, store it (and remove old if still present)
            if ($request->hasFile($input)) {
                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
                $formfields[$column] = $request->file($input)->store('signatures/leave', 'public');
            }
        }

        // Unique name check only if changed
        if ($request->name !== $leave_signatory->name) {
            $exists = \App\Models\LeaveSignatory::where('name', $request->name)->exists();
            if ($exists) {
                return back()->with('SignatoryError', 'Error! Duplicate signatory name.');
            }
        }

        $leave_signatory->update($formfields);

        return back()->with('message', 'Signatory Updated Successfully!');
    }
}
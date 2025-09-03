<?php

namespace App\Http\Controllers\Msd;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LeaveSignatory;

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
            'name' => 'required',
            'approver1' => 'required',
            'approver2' => 'required',
            'approver3' => 'required',
        ]);

        $check = LeaveSignatory::where('name', '=', $request->name)->get()->first();
      
        if ($check) {
          return back()->with('SignatoryError', 'Error!');
        }
        else
        {
        LeaveSignatory::create($formfields);
        return back()->with('message', "Signatory Saved Successfully!");
        }
    }

    public function destroy($LeaveSignatory) {
 
        $Signatory = LeaveSignatory::where('id','=',$LeaveSignatory)->get()->first();
        $this->authorize('delete', $Signatory);

     
        $Signatory->delete();
        
        return back()->with('message', "Signatory Deleted Successfully!");

    }

    public function update(Request $request, $LeaveSignatory) {
        $Signatory = LeaveSignatory::where('id','=',$LeaveSignatory)->get()->first();
        $this->authorize('delete', $Signatory);

        $formfields = $request->validate([
            'name' => 'required',
            'approver1' => 'required',
            'approver2' => 'required',
            'approver3' => 'required',
        ]);

        if($request->name == $Signatory->name) 
        {
            $Signatory->update($formfields);
            return back()->with('message', "Signatory Updated Successfully!");

        }
        else
        {
            $check = LeaveSignatory::where('name', '=', $request->name)->get()->first();
            if ($check) {
                return back()->with('SignatoryError', 'Error!');
              }
              else
              {
                $Signatory->update($formfields);
              return back()->with('message', "Signatory Updated Successfully!");
              }
        }

    }
}

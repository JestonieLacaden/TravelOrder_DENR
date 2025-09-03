<?php

namespace App\Http\Controllers\Msd;

use App\Models\Employee;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TravelOrderSignatory;

class TravelOrderSignatoryController extends Controller
{
    public function index() 
    {

        $this->authorize('viewAny', \App\Models\TravelOrderSignatory::class);

    
        $Employees = Employee::orderby('lastname','asc')->get();
        $Signatories = TravelOrderSignatory::with('Employee1','Employee2')->get();
        return view('msd-panel.travel-order-signatory.index',compact('Employees', 'Signatories'));
    }     
    
    public function store(Request $request)
    {
        $this->authorize('create', \App\Models\TravelOrderSignatory::class);

        $formfields = $request->validate([
            'name' => 'required',
            'approver1' => 'required',
            'approver2' => 'required',
            
        ]);

        $check = TravelOrderSignatory::where('name', '=', $request->name)->get()->first();
      
        if ($check) {
          return back()->with('SignatoryError', 'Error!');
        }
        else
        {
        TravelOrderSignatory::create($formfields);
        return back()->with('message', "Signatory Saved Successfully!");
        }
    }

    public function destroy($TravelOrderSignatory) {
 
        $TravelOrderSignatory = TravelOrderSignatory::where('id','=',$TravelOrderSignatory)->get()->first();
        $this->authorize('delete', $TravelOrderSignatory);

     
        $TravelOrderSignatory->delete();
        
        return back()->with('message', "Signatory Deleted Successfully!");

    }

    public function update(Request $request, $TravelOrderSignatory) {
        $TravelOrderSignatory = TravelOrderSignatory::where('id','=',$TravelOrderSignatory)->get()->first();
       
        $this->authorize('delete', $TravelOrderSignatory);

        $formfields = $request->validate([
            'name' => 'required',
            'approver1' => 'required',
            'approver2' => 'required',
    
        ]);

        if($request->name == $TravelOrderSignatory->name) 
        {
            $TravelOrderSignatory->update($formfields);
            return back()->with('message', "Signatory Updated Successfully!");

        }
        else
        {
            $check = TravelOrderSignatory::where('name', '=', $request->name)->get()->first();
            if ($check) {
                return back()->with('SignatoryError', 'Error!');
              }
              else
              {
                $TravelOrderSignatory->update($formfields);
              return back()->with('message', "Signatory Updated Successfully!");
              }
        }

    }
}

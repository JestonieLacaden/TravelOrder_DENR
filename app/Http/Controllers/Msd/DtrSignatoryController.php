<?php

namespace App\Http\Controllers\Msd;

use App\Models\Office;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DtrSignatory;

class DtrSignatoryController extends Controller
{
    public function index() {

        $this->authorize('create', \App\Models\DtrSignatory::class);
         
          $Employees = Employee::orderBy('firstname')->get(); 
          $DtrSignatory = DtrSignatory::with('employee')->with('Signatory')->get();
            
          return view('msd-panel.dtrsignatory.index', compact('DtrSignatory','Employees'));
       
         
        }

    public function store(Request $request) {
      $this->authorize('create', \App\Models\DtrSignatory::class);
      $check = DtrSignatory::where('employeeid', '=', $request->employeeid)->first();

      if($check) {
        return back()->with('error', 'Employee already have Signatory!');
      }

      $formfields = $request->validate([              
      
               'employeeid' => 'required',
              'signatory' => 'required',
             
           ]);
      DtrSignatory::create($formfields); 
      return redirect()->route('dtr-signatory.index')->with('success', 'User Added Succesfully!');
    }


    public function update(Request $request, DtrSignatory $DtrSignatory) {
      $this->authorize('update',$DtrSignatory);
     
      $formfields = $request->validate([              
      
               'employeeid' => 'required',
              'signatory' => 'required',
             
           ]);
           $DtrSignatory->update($formfields); 
      return redirect()->route('dtr-signatory.index')->with('success', 'User Updated Succesfully!');
    }

    // public function stossre(Request $request) {

    
          
    //   $this->authorize('create',User::class);
    //   $email = $request->email;
    
    //   $check = User::where([
    //     ['email', '=', $email],

    //   ])->first();

    //   if ($check) {
    //     return back()->with('error', 'Duplicate Email!');
    //   }
                
    //         $formfields = $request->validate([              
      
    //             'email' => 'required',
    //             'password' => ['required','min:6'],
         
    //           ]);



       
    //         $formfields['password'] = bcrypt($formfields['password']); 
    //         $formfields['username'] = $request->username;
                  
    //         user::create($formfields); 

    //         return redirect()->route('user.index')->with('success', 'User Added Succesfully!');
    //       }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Office;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OfficeController extends Controller
{
    public function index() {
 
      $this->authorize('viewany', \App\Models\Office::class);
  
           $Offices = Office::orderBy('office')->get(); 
          return view('admin.employee-mgmt.office.index', compact('Offices'));
        }
        
        public function store(Request $request) {
          $this->authorize('create',Office::class);
                $id = $request->office;
                $check = Office::where([
                  ['office', '=', $id],
                ])->first();
        
                if ($check) {
                  return back()->with('error', 'Duplicate Office Name!');
                }
                    
                $formfields = $request->validate([              
                    'office' => ['required','min:3']
                  ]);
                  
                
                Office::create($formfields) ; 
                return redirect()->route('office.index')->with('success', 'Office Added Succesfully!');
              }
  
        public function destroy(Office $Office) {
          $this->authorize('delete', $Office);
                $Office->delete();
                      
                 return redirect()->route('office.index')->with ('success', 'Office Deleted Successfully!');
            
              }
  
        public function update(Request $request, Office $Office) {
          $this->authorize('delete', $Office);
        
               $formfields = $request->validate([
                  'office' => ['required', 'min:3']
                ]);
           
                $id = $request->office; 
                $check = Office::where([
                  ['office', '=', $id],
        
                ])->first();
        
                if ($check) {
                  return with('error', 'Duplicate Office Name!');
                }
  
                  $Office->update($formfields);
                    
                  return redirect()->route('office.index')->with ('success', 'Office Updated Successfully!');
              }
}

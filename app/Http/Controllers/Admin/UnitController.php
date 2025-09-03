<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Models\Office;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    
    public function index() {
        $this->authorize('viewany', \App\Models\Section::class);

         $Units = Unit::with('section')->orderBy('id')->get(); 
         $Sections = Section::orderBy('section')->get();
         $Offices = Office::orderby('id')->get();
      

        return view('admin.employee-mgmt.unit.index', compact('Sections', 'Units', 'Offices'));
      }
      
      public function store(Request $request) {
        $this->authorize('create',Unit::class);

        {
          $validator = Validator::make($request->all(), [
            'unit' => ['required','min:3'],
            'officesection' => 'required',
          
          ]);
      
          // Check validation failure
          if ($validator->fails()) {
            return back()->with('error', 'Fields must not be empty!');
          
          }
            
              $id = $request->unit;
              $data = $request->officesection;
              list($Office, $Section) = explode(",", $data);
          


              $check = Unit::where([
                ['unit', '=', $id],
                ['officeid', '=', $Office],
                ['sectionid', '=', $Section],
              ])->first();
      
              if ($check) {
                return back()->with('error', 'Duplicate Unit Name!');
              }
                        
              $formfields = $request->validate([              
                  'unit' => ['required','min:3'],
                              

                ]);
              $formfields['officeid'] = $Office;
              $formfields['sectionid'] = $Section;
              Unit::create($formfields) ; 
              return redirect()->route('unit.index')->with('success', 'Unit Added Succesfully!');
          }
        }
      public function destroy(Unit $Unit) {
        $this->authorize('delete', $Unit);
          
              $Unit->delete();
                    
               return redirect()->route('unit.index')->with ('success', 'Unit Deleted Successfully!');
          
            }

      public function update(Request $request, Unit $Unit) {
        $this->authorize('delete', $Unit);
          $validator = Validator::make($request->all(), [
            'unit' => ['required','min:3'],
            'officesection' => 'required',
          ]);
      
          // Check validation failure
          if ($validator->fails()) {
            return back()->with('error', 'Fields must not be empty!');
          
          }
      
             $formfields = $request->validate([
                'unit' => ['required', 'min:3'],
             ]);
         
              $id = $request->unit; 
              $data = $request->officesection;
              list($Office, $Section) = explode(",", $data);
           
              $check = Unit::where([
                ['unit', '=', $id],
                ['officeid', '=', $Office],
                ['sectionid', '=', $Section],

                
              ])->first();
      
              if ($check) {
                return back()->with('error', 'Duplicate Unit Name!');
              }

           
               $formfields['officeid'] = $Office;
                $formfields['sectionid'] = $Section;
                $Unit->update($formfields);
                  
                return redirect()->route('unit.index')->with ('success', 'Unit Updated Successfully!');
            }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Office;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SectionController extends Controller
{
    public function index() {
 
        $this->authorize('viewany', \App\Models\Section::class);
  
           $Sections = Section::with('office')->orderBy('id')->get(); 
           $Offices = Office::orderBy('office')->get();
          
          return view('admin.employee-mgmt.section.index', compact('Sections', 'Offices'));
        }
        
        public function store(Request $request) {
          $this->authorize('create',Section::class);
                $id = $request->section;
                $office = $request->officeid;
                $check = Section::where([
                  ['section', '=', $id],
                  ['officeid', "=", $office],
                ])->first();
        
                if ($check) {
                  return back()->with('error', 'Duplicate Section Name!');
                }
                    
                $formfields = $request->validate([              
                    'section' => ['required','min:3'],
                    'officeid' => 'required',
                  ]);
            
                Section::create($formfields) ; 
                return redirect()->route('section.index')->with('success', 'Section Added Succesfully!');
              }
  
        public function destroy(Section $Section) {
          
          
          $this->authorize('delete', $Section);

                $Section->delete();
                      
                 return redirect()->route('section.index')->with ('success', 'Section Deleted Successfully!');
            
              }
  
        public function update(Request $request, Section $Section) {
            $this->authorize('update', $Section);
        
               $formfields = $request->validate([
                  'section' => ['required', 'min:3'],
                  'officeid' => 'required',
                ]);
           
                $id = $request->section; 
                $office = $request->officeid;
                $check = Section::where([
                  ['Section', '=', $id],
                  ['officeid', "=", $office],
                  
                ])->first();
        
                if ($check) {
                  return back()->with('error', 'Duplicate Section Name!');
                }
  
                  $Section->update($formfields);
                    
                  return redirect()->route('section.index')->with ('success', 'Section Updated Successfully!');
              }

              public function getsection() {
                $Sections = Section::wherehas('officeid', function($query) {
                    $query->whereId(request()->input('officeid'));
                })->pluck('section','id');
               
                return response()->json($Sections);
              }
}

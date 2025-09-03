<?php

namespace App\Http\Controllers\Msd;

use App\Models\Office;
use App\Models\Section;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\LeaveSignatory;
use App\Models\SetLeaveSignatory;
use App\Http\Controllers\Controller;

class SetLeaveSignatoryController extends Controller
{
    public function index() 
    {

        $this->authorize('viewany', \App\Models\SetLeaveSignatory::class);
      
      
        $Sections = Section::with('office')->get();
        $SetLeaveSignatories = SetLeaveSignatory::with('section','Office')->get();
        $Offices = Office::with('section')->get();
        $LeaveSignatories = LeaveSignatory::get();

        return view('msd-panel.set-leave-signatory.index',compact('LeaveSignatories','SetLeaveSignatories','Sections','Offices'));
    }     

    public function store(Request $request) {
        
        $this->authorize('create', \App\Models\SetLeaveSignatory::class);

        $formfields = $request->validate([
             
            'sectionid' => 'required',
            'leavesignatoryid' => 'required',
           
        ]);

      
        $data = $request->sectionid;
        list($Officeid, $Sectionid) = explode(",", $data);

        $check = SetLeaveSignatory::where([
            ['sectionid', '=', $Sectionid],
  
          ])->first();
  
          if ($check) {
            return back()->with('SetSignatoryError', 'error!');
          }

        $formfields['officeid'] = $Officeid;
        $formfields['sectionid'] = $Sectionid;
    
            SetLeaveSignatory::create($formfields);
            return back()->with('message', 'Leave Signatory Added Successfully!');

    }

    public function update(Request $request,$SetLeaveSignatory) {
        $SetLeave = SetLeaveSignatory::where('id','=', $SetLeaveSignatory)->get()->first();
 
        $this->authorize('update', $SetLeave);

        $formfields = $request->validate([
             'leavesignatoryid' => 'required',   
        ]);
    
            $SetLeave->update($formfields);
            return back()->with('message', 'Leave Signatory Updated Successfully!');

    }
    
}

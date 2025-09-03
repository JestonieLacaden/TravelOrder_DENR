<?php

namespace App\Http\Controllers\Msd;

use App\Http\Controllers\Controller;
use App\Models\Leave_Type;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller

{
    public function index() {

        $this->authorize('viewany', \App\Models\Leave_Type::class);
    
        $Leaves = Leave_Type::orderBy('id', 'asc')->get();
        return view('msd-panel.leave-approver.index',compact('Leaves'));
    }

    public function update(Request $request, Leave_Type $leaveType) {

        $this->authorize('update', $leaveType);

        $formfields = $request->validate([
             
            'leave_type' => 'required',
            'available' => 'required',
            
        ]);
    
        $leavetype = Leave_Type::where('leave_type', '=', $request->leave_type)->get()->first();

       
        $leavetype->update($formfields);          


        $Leaves = Leave_Type::orderBy('id', 'asc')->get();
        return redirect()->route('leave-mgmt.index',compact('Leaves'))->with('message', 'Leave Updated Successfully');
    }

}

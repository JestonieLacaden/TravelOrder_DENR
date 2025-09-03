<?php

namespace App\Http\Controllers\Msd;

use App\Models\Office;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SetTravelOrderSignatory;
use App\Models\TravelOrderSignatory;

class SetTravelOrderSignatoryController extends Controller
{
    public function index() 
    {

        $this->authorize('viewAny', \App\Models\SetTravelOrderSignatory::class);
      
      
        $Sections = Section::with('office')->get();
        $SetTravelOrderSignatories = SetTravelOrderSignatory::with('section','Office','TraveOrderSignatory')->get();
        $Offices = Office::with('section')->get();
        $TravelOrderSignatories = TravelOrderSignatory::get();
   
     

        return view('msd-panel.set-travel-order-signatory.index',compact('TravelOrderSignatories','SetTravelOrderSignatories','Sections','Offices'));
    }   
    
    public function store(Request $request) {
        
        $this->authorize('create', \App\Models\SetTravelOrderSignatory::class);

        $formfields = $request->validate([
             
            'sectionid' => 'required',
            'travelordersignatoryid' => 'required',
           
        ]);

      
        $data = $request->sectionid;
        list($Officeid, $Sectionid) = explode(",", $data);

        $check = SetTravelOrderSignatory::where([
            ['sectionid', '=', $Sectionid],
  
          ])->first();
  
          if ($check) {
            return back()->with('SetSignatoryError', 'error!');
          }

        $formfields['officeid'] = $Officeid;
        $formfields['sectionid'] = $Sectionid;
    
            SetTravelOrderSignatory::create($formfields);
            return back()->with('message', 'Leave Signatory Added Successfully!');

    }

    public function update(Request $request,$SetTravelOrderSignatory) {
        $SetTravelOrder = SetTravelOrderSignatory::where('id','=', $SetTravelOrderSignatory)->get()->first();
 
        $this->authorize('update', $SetTravelOrder);

        $formfields = $request->validate([
             'travelordersignatoryid' => 'required',   
        ]);
    
            $SetTravelOrder->update($formfields);
            return back()->with('message', 'Leave Signatory Updated Successfully!');

    }
}

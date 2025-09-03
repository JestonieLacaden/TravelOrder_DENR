<?php

namespace App\Http\Controllers\User;

use App\Models\Route;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Support\Facades\Redis;

class RouteController extends Controller
{

    public function close(Request $request) {  
    
        $document = Document::where('id', '=', $request->documentid)->get()->first();
      
        
        $this->authorize('addAction', $document);


        $formfields['documentid'] = $document->PDN;
        $formfields['userid'] = auth()->user()->id;
        $formfields['is_active'] = FALSE;
        $formfields['action'] = 'CLOSED';
        $formfields['actiondate'] = now();
   

        $Office = Employee::where('email','=',auth()->user()->email)->get()->first();
   
        $formfields['officeid'] = $Office->officeid;
        $formfields['sectionid'] = $Office->sectionid;
        $formfields['unitid'] = $Office->unitid;
        $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
        $formfields['userunitid'] = $employee->unitid;


    
        Route::create($formfields);

        return back()->with('message', "Route Closed Successfully!");

    }

    public function destroy(Route $Route) {

        $this->authorize('delete', $Route);

        $Route->delete();

        return back()->with('message', 'Route Deleted Successfully!');
          
      }

      public function update(Route $Route, Request $request) {

        $this->authorize('update', $Route);

        $formfields = $request->validate([
            'action' => 'required',
            'officeid' => 'required',
            'actiondate' => 'required',
        ]);
   
        $formfields['remarks'] = $request->remarks;
        $data = $request->officeid;     
        list($Office, $Section, $Unit) = explode(",", $data);
        $formfields['officeid'] = $Office;
        $formfields['sectionid'] = $Section;
        $formfields['unitid'] = $Unit;

        $Route->update($formfields);
    
        return back()->with('message', "Route Updated Successfully!");

          
      }


    public function store(Request $request, Document $Document) {

        // $this->authorize('addRoute', $Document);

        $formfields = $request->validate([
            'action' => 'required',
            'officeid' => 'required',
            'actiondate' => 'required',
        ]);
        $document = Document::where('PDN', '=', $request->documentid)->get()->first();
        $formfields['documentid'] = $document->PDN;
        $formfields['userid'] = auth()->user()->id;
        $formfields['remarks'] = $request->remarks;
        $formfields['is_active'] = true;
        
        $data = $request->officeid;     
        list($Office, $Section, $Unit) = explode(",", $data);
        $formfields['officeid'] = $Office;
        $formfields['sectionid'] = $Section;
        $formfields['unitid'] = $Unit;

        $formfields['is_forwarded'] = true;
        $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
        $formfields['userunitid'] = $employee->unitid;

        Route::updateorCreate($formfields);
    
      

        return back()->with('message', "Route Saved Successfully!");

    }

    public function acceptIncoming(Request $request) {
   
        $document = Document::where('PDN', '=', $request->documentid)->get()->first();
        
        $this->authorize('acceptIncoming', $document);

        $formfields['documentid'] = $document->PDN;
        $formfields['userid'] = auth()->user()->id;
        $formfields['is_active'] = true;
        
        $officeunitsection = Employee::where('email','=', auth()->user()->email)->get()->first();

        $formfields['officeid'] = $officeunitsection->officeid;
        $formfields['sectionid'] = $officeunitsection->sectionid;
        $formfields['unitid'] = $officeunitsection->unitid;
        $formfields['actiondate'] = now();
        $formfields['action'] = 'ACCEPTED';
        $formfields['is_accepted'] = true;
        $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
        $formfields['userunitid'] = $employee->unitid;

        Route::create($formfields);

        return back()->with('message', "Route Accepted Successfully!");



    }

 

    

    public function rejectIncoming(Request $request) {

        $document = Document::where('PDN', '=', $request->documentid)->get()->first();
    
        $this->authorize('acceptIncoming', $document);

        $formfields['documentid'] = $document->PDN;
        $formfields['userid'] = auth()->user()->id;
        $formfields['is_active'] = true;
        
        $officeunitsection = Employee::where('email','=', auth()->user()->email)->get()->first();

        $formfields['officeid'] = $officeunitsection->officeid;
        $formfields['sectionid'] = $officeunitsection->sectionid;
        $formfields['unitid'] = $officeunitsection->unitid;
        $formfields['actiondate'] = now();
        $formfields['action'] = 'REJECTED';
        $formfields['is_rejected'] = true;

        $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
        $formfields['userunitid'] = $employee->unitid;
        Route::create($formfields);

        return back()->with('message', "Route Rejected Successfully!");

    }

   


}

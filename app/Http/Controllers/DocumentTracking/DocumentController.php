<?php

namespace App\Http\Controllers\DocumentTracking;

use App\Models\Unit;
use App\Models\User;
use App\Models\Route;
use App\Models\Office;
use App\Models\Section;
use App\Models\Document;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attachment;

class DocumentController extends Controller
{
    public function index() {

        $this->authorize('viewany', \App\Models\Document::class);

        $Documents = Document::orderby('id','desc')->with('LastRoute')->get();

        
   
        return view('document-tracking.index', compact('Documents'));
    }

    public function destroy($Document) {
     
        $this->authorize('viewany', \App\Models\Document::class);

        Document::where('id', $Document)->delete();

        return redirect()->route('document-tracking.index')->with('message', "Document Deleted Successfully!");
    }
    public function create() {

        $this->authorize('viewany', \App\Models\Document::class);

        return view('document-tracking.newdocument');
    }
 

    public function viewdocument() {

        $Document = Document::orderby('created_at','desc')->where('userid','=',Auth()->user()->id)->get()->first();
        $this->authorize('view', $Document);

        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

       

        $RouteSpecific = Route::where('documentid', $Document->PDN)->where('action','=','FORWARD TO')->orderBy('created_at', 'desc')->get()->first();  


        $Routes = Route::where('documentid', $Document->PDN)->orderby('created_at', 'desc')->with('Office', 'Section', 'Unit', 'Attachment')->get();

        $Employee = Employee::where('email','=', auth()->user()->email)->get()->first();
             
        return view('document-tracking.view', compact('Document','Offices', 'Sections','Units','Routes','RouteSpecific','Employee'));
    }

    public function view(Document $Document) {

        // $this->authorize('view', $Document);

        $Offices = Office::orderby('id')->get();
        $Sections = Section::orderby('section')->get();
        $Units = Unit::orderby('unit')->get();

        $RouteSpecific = Route::where('documentid', $Document->PDN)->where('action','=','FORWARD TO')->orderBy('created_at', 'desc')->get()->first();  


        $Routes = Route::where('documentid', $Document->PDN)->orderby('created_at', 'desc')->with('Office', 'Section', 'Unit', 'Attachment')->get();

        $Employee = Employee::where('email','=', auth()->user()->email)->get()->first();
             
        return view('document-tracking.view', compact('Document','Offices', 'Sections','Units','Routes','RouteSpecific','Employee'));
    }

    public function edit(Document $Document) {

        $this->authorize('update', $Document);

        return view('document-tracking.edit', compact('Document'));
    }

    public function print(Document $Document) {

        if(auth()->check());
        
        {
 
            $Routes = Route::where('documentid', '=', $Document->PDN)->orderBy('created_at', 'asc')->with('userUnit')->get();
      
            $Employees = Employee::with('Unit','Office','User')->get();
        
            $AttachmentDetails = Attachment::where('documentid','=', $Document->PDN)->get();

            return view('document-tracking.print', compact('Employees','Document','Routes','AttachmentDetails'));
        }

       
    }
  
    public function update(Request $request, $Document) {

    //   $this->authorize('update', $Document);

        $doc = Document::findOrFail($Document);
    
        $constraints = [
            'originatingoffice' => 'required',
            'sendername' => 'required',
            'senderaddress' => 'required',
            'datereceived' => 'required',
            'addressee' => 'required',
            'doc_type' => 'required',
            'subject' => 'required',
             'is_urgent' => 'required',
            ];
        $input = [
            'originatingoffice' => $request['originatingoffice'],
            'sendername' => $request['sendername'],
            'senderaddress' => $request['senderaddress'],   
            'datereceived' => $request['datereceived'],
            'addressee' => $request['addressee'],
            'doc_type' => $request['doc_type'],
            'subject' => $request['subject'],
             'is_urgent' => $request['is_urgent']
         
        ];
      
        $this->validate($request, $constraints);
        Document::where('id', $Document)
            ->update($input);
        
        return redirect()->route('document-tracking.index')->with('message', "Document Updated Successfully!");


    }
    public function store(Request $request) {

        $this->authorize('create', \App\Models\Document::class);
        
        $formfields = $request->validate([
            'originatingoffice' => 'required',
            'sendername' => 'required',
            'senderaddress' => 'required',
            'datereceived' => 'required',
            'addressee' => 'required',
            'doc_type' => 'required',
            'subject' => 'required',
            'is_urgent' => 'required',
        ]);

    
        $project = Document::where('PDN', 'LIKE', '%' . date('Y') . '%')->latest()->first();
        
        if($project != null) 
            {
              $newproject = $project->id;
              $projectcount = (int)$newproject + 1;
            }
            else
              {
                 $projectcount = 1;
              }

        $formfields['PDN'] = 'P'.'-'.date('Y') .'-'. str_pad($projectcount, 8, '0', STR_PAD_LEFT) ;
        // $formfields['userid'] = auth()->user()->id;
        $formfields['userid'] = auth()->user()->id ;
    
        $EmployeeOffice = Employee::where('email',auth()->user()->email)->get()->first();
    
        $data['actiondate'] = now();
        $data['documentid'] = 'P'.'-'.date('Y') .'-'. str_pad($projectcount, 8, '0', STR_PAD_LEFT);
        $data['officeid'] = $EmployeeOffice->officeid;
        $data['sectionid'] =  $EmployeeOffice->sectionid;
        $data['unitid'] =  $EmployeeOffice->unitid;
        $data['action'] = 'DOCUMENT CREATED';;
        $data['is_active'] = true;
        $data['userid'] =  auth()->user()->id;

        $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
        $data['userunitid'] = $employee->unitid;
        
        Route::updateorCreate($data);
    
        Document::updateorCreate($formfields);
     

        return redirect()->route('document-tracking.create')->with('message', "Document Saved Successfully!");
    }
    
  


}

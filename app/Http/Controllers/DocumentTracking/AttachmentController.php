<?php

namespace App\Http\Controllers\DocumentTracking;

use App\Models\Route;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Attachment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request) {

       
        $Document =  Document::where('id','=',$request->documentid)->get()->first();

        $this->authorize('addattachment', $Document);

        $formfields = $request->validate([
            'attachmentdetails' => 'required',
             'attachment' => 'required',
           
        ]);
        $formfields['documentid'] = $Document->PDN;
        if ($request->hasFile('attachment')) {
          $formfields['attachment'] = $request->file('attachment')->store('attachment', 'public');
            // // $formfields['attachment'] = $request->file('attachment')
            // // dd($request->file('attachment'));
            // $file = $request->file('attachment');
            // $ext = $request->file('attachment')->getClientOriginalExtension();
            // // $filename = str_replace($ext,date('Y-d-m-H-i') . "." . $ext, $request->file('attachment')->getClientOriginalName());
            // $filename = date('Y-m-d-H-i-s') .  "_" . $request->file('attachment')->getClientOriginalName();
            // // dd($filename);
            // $formfields['attachment'] = $filename;
          
            // storage::disk('public')->put($request->file('attachment'));
           }

     
    //    $formfields['documentid'] = $Document;


    $EmployeeOffice = Employee::where('email','=',auth()->user()->email)->get()->first();
    
    $attacmentcount = Attachment::get()->last();
   

    if($attacmentcount != null) 
            {
              $newproject = $attacmentcount->id;
              $projectcount = (int)$newproject + 1;
            }
            else
              {
                 $projectcount = 1;
              }

    $data['actiondate'] = now();
    $data['documentid'] =  $Document->PDN;
    $data['officeid'] = $EmployeeOffice->officeid;
    $data['sectionid'] =  $EmployeeOffice->sectionid;
    $data['unitid'] =  $EmployeeOffice->unitid;
    $data['userunitid'] =  $EmployeeOffice->unitid;
    $data['action'] = 'ATTACHED A FILE';
    $data['is_active'] = true;
    $data['userid'] =  auth()->user()->id;

    if ($request->hasFile('attachment')) {
      $data['remarks'] = $request->file('attachment')->store('attachment');
      $request->file('attachment')->move('storage/attachment', $request->file('attachment')->store('attachment', 'public'));
}
    
    Route::create($data);
              
    $formfields['userid'] = auth()->user()->id;
    Attachment::create($formfields);
        
    return back()->with('message', "Attachment Saved Successfully!");


    }

        public function view($Attachment) {

          $this->authorize('viewany', \App\Models\Document::class);

          $attach = Attachment::where('id', $Attachment)->pluck('attachment')->first();
     
          return response()->file(public_path('storage/'. $attach ));

            //
            // $attach = Attachment::where('id', $Attachment)->pluck('attachment')->first();
            // $path = public_path('storage/' . $attach);


            // return response()->file($path);
          
  
          //
          
        }


        public function destroy($Attachment) {
            
            $this->authorize('create', \App\Models\Document::class);
     

            Attachment::where('id', $Attachment)->delete();

            return back()->with('message', "Attachment Deleted Successfully!");
            
        }
    
}

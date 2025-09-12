<?php

namespace App\Http\Controllers\Msd;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Section;
use App\Models\SetTravelOrderSignatory;
use App\Models\TravelOrderSignatory;
use Illuminate\Http\Request;

class SetTravelOrderSignatoryController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', \App\Models\SetTravelOrderSignatory::class);

        $Sections = \App\Models\Section::with('office')
            ->orderBy('officeid')
            ->orderBy('section')  // column name in travelorder2
            ->get();

        $SetTravelOrderSignatories = \App\Models\SetTravelOrderSignatory::with([
            'Section',
            'Office',
            'TravelOrderSignatory.Employee1:id,firstname,middlename,lastname',
            'TravelOrderSignatory.Employee2:id,firstname,middlename,lastname',
        ])
            ->orderBy('officeid')
            ->orderBy('sectionid')
            ->get();

        $Offices = \App\Models\Office::with('Section')
            ->orderBy('office')   // column name in travelorder2
            ->get();

        $TravelOrderSignatories = \App\Models\TravelOrderSignatory::with(['Employee1', 'Employee2'])
            ->orderBy('name')
            ->get();

        return view(
            'msd-panel.set-travel-order-signatory.index',
            compact('TravelOrderSignatories', 'SetTravelOrderSignatories', 'Sections', 'Offices')
        );
    }

    public function store(Request $request)
    {
        $this->authorize('create', SetTravelOrderSignatory::class);

        $formfields = $request->validate([
            'sectionid'               => ['required'], 
            'travelordersignatoryid'  => ['required', 'exists:travel_order_signatory,id'],
        ]);

        
        [$Officeid, $Sectionid] = array_map('intval', explode(',', $request->sectionid));

        
        $exists = SetTravelOrderSignatory::where('officeid', $Officeid)
            ->where('sectionid', $Sectionid)
            ->where('travelordersignatoryid', $formfields['travelordersignatoryid'])
            ->exists();

        if ($exists) {
            return back()->with('SetSignatoryError', 'This Office/Section already has this signatory assigned.');
        }

        $formfields['officeid'] = $Officeid;
        $formfields['sectionid'] = $Sectionid;

        SetTravelOrderSignatory::create($formfields);
        return back()->with('message', 'Travel Order Signatory mapping added successfully!');
    }

    public function update(Request $request, $id)
    {
        $SetTravelOrder = SetTravelOrderSignatory::where('id', $id)->firstOrFail();
        $this->authorize('update', $SetTravelOrder);

        $data = $request->validate([
            'travelordersignatoryid' => ['required', 'exists:travel_order_signatory,id'],
        ]);

        $SetTravelOrder->update($data);
        return back()->with('message', 'Travel Order Signatory mapping updated successfully!');
    }
}


// public function store(Request $request)
//     {
//         $this->authorize('create', TravelOrderSignatory::class);

//         $data = $request->validate([
//             'name'                  => ['required', 'string', 'max:255'],
//             'approver1'             => ['required', 'exists:employee,id'],
//             'approver2'             => ['required', 'exists:employee,id'],
//             'approver1_signature'   => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
//             'approver2_signature'   => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
//         ]);

//         $sign = TravelOrderSignatory::create([
//             'name'      => $data['name'],
//             'approver1' => $data['approver1'],
//             'approver2' => $data['approver2'],
//         ]);

//         if ($request->hasFile('approver1_signature')) {
//             $this->saveSignatureForEmployee($data['approver1'], $request->file('approver1_signature'));
//         }
//         if ($request->hasFile('approver2_signature')) {
//             $this->saveSignatureForEmployee($data['approver2'], $request->file('approver2_signature'));
//         }

//         return back()->with('message', 'Signatory created!');
//     }

//     public function update(Request $request, TravelOrderSignatory $sign)
//     {
//         $this->authorize('update', $sign);

//         $data = $request->validate([
//             'name'                  => ['required', 'string', 'max:255'],
//             'approver1'             => ['required', 'exists:employee,id'],
//             'approver2'             => ['required', 'exists:employee,id'],
//             'approver1_signature'   => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
//             'approver2_signature'   => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
//             'clear_approver1_signature' => ['nullable', 'boolean'],
//             'clear_approver2_signature' => ['nullable', 'boolean'],
//         ]);

//         $sign->update([
//             'name'      => $data['name'],
//             'approver1' => $data['approver1'],
//             'approver2' => $data['approver2'],
//         ]);

     
//         if ($request->boolean('clear_approver1_signature')) {
//             $this->saveSignatureForEmployee($data['approver1'], null, true);
//         }
//         if ($request->boolean('clear_approver2_signature')) {
//             $this->saveSignatureForEmployee($data['approver2'], null, true);
//         }

       
//         if ($request->hasFile('approver1_signature')) {
//             $this->saveSignatureForEmployee($data['approver1'], $request->file('approver1_signature'));
//         }
//         if ($request->hasFile('approver2_signature')) {
//             $this->saveSignatureForEmployee($data['approver2'], $request->file('approver2_signature'));
//         }

//         return back()->with('message', 'Signatory updated!');
//     }

//     private function saveSignatureForEmployee(int $employeeId, ?\Illuminate\Http\UploadedFile $file, bool $clearIfNull = false): void
//     {
//         $emp = \App\Models\Employee::find($employeeId);
//         if (!$emp) return;


//         if ($emp->signature_path && Storage::disk('public')->exists($emp->signature_path)) {
//             if ($clearIfNull || $file) {
//                 Storage::disk('public')->delete($emp->signature_path);
//                 $emp->signature_path = null;
//             }
//         }

//         if ($file) {
//             $path = $file->store('signatures', 'public');
//             $emp->signature_path = $path;
//         }

//         $emp->save();
//     }
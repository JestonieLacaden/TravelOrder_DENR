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
            'sectionid'               => ['required'], // coming as "officeid,sectionid"
            'travelordersignatoryid'  => ['required', 'exists:travel_order_signatory,id'],
        ]);

        // Split "officeid,sectionid" coming from the UI
        [$Officeid, $Sectionid] = array_map('intval', explode(',', $request->sectionid));

        // ✅ Allow multiple mappings per section; only block exact duplicates
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
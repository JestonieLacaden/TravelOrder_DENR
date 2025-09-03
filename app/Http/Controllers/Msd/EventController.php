<?php

namespace App\Http\Controllers\Msd;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index() {

        $this->authorize('viewany', \App\Models\Event::class);
    
        $Events = Event::orderBy('date', 'asc')->with('User')->get();
        return view('msd-panel.event-panel.event.index', compact('Events'));
        }

        public function store(Request $request) {
 

            $this->authorize('create', \App\Models\Event::class);

            $formfields = $request->validate([
             
                'date' => 'required',
                'type' => 'required',
                'subject' => 'required',
                'schedule' => 'required',
                
            ]);

             
            $check = Event::where('date', '=', $request->date)->get()->first();
      
              if ($check) {
                return back()->with('EventError', 'Error!');
              }

            $formfields['userid'] = auth()->user()->id;
            $formfields['remarks'] = $request->remarks;
            
            if(!empty($request->attachment)) {
                if ($request->hasFile('attachment')) {
                    $formfields['attachment'] = $request->file('attachment')->store('events', 'public'); 
               }
            }
            
            Event::create($formfields);
            return redirect()->route('event.index')->with('message', "Event Saved Successfully!");
            }
    public function destroy(Event $event) {
 
        $this->authorize('create', \App\Models\Event::class);

        $event->delete();
        
        return redirect()->route('event.index')->with('message', "Event Deleted Successfully!");

    }

    public function viewattachment($event) {

        $this->authorize('viewany', \App\Models\Event::class);

        $attach = Event::where('id', $event)->pluck('attachment')->first();
   
        return response()->file(public_path('storage/'. $attach ));
    }

    public function update(Request $request,Event $event) {
        $this->authorize('update', $event);

        $formfields = $request->validate([
             
            'date' => 'required',
            'type' => 'required',
            'subject' => 'required',
            'schedule' => 'required',
           
        ]);

        $formfields['userid'] = auth()->user()->id;
        $formfields['remarks'] = $request->remarks;

        if(!empty($request->attachment)) {
            if ($request->hasFile('attachment')) {
                $formfields['attachment'] = $request->file('attachment')->store('events', 'public'); 
           }
        }

        $event->update($formfields);

           
        return redirect()->route('event.index')->with('message', "Event Updated Successfully!");

        

    }
}

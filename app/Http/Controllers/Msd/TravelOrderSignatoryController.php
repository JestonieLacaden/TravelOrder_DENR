<?php

namespace App\Http\Controllers\Msd;

use App\Models\Employee;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TravelOrderSignatory;
use Illuminate\Support\Facades\Storage;

class TravelOrderSignatoryController extends Controller
{
    public function index()
    {

        $this->authorize('viewAny', \App\Models\TravelOrderSignatory::class);


        $Employees = Employee::orderby('lastname', 'asc')->get();
        $Signatories = TravelOrderSignatory::with('Employee1', 'Employee2', 'Employee3')->get();
        return view('msd-panel.travel-order-signatory.index', compact('Employees', 'Signatories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', TravelOrderSignatory::class);

        $data = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'approver1'           => ['required', 'exists:employee,id'],
            'approver2'           => ['required', 'exists:employee,id'],
            'approver3'           => ['required', 'exists:employee,id'],
            'approver1_signature' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'approver2_signature' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'approver3_signature' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ]);

        // Prevent duplicate signatory name
        if (TravelOrderSignatory::where('name', $data['name'])->exists()) {
            return back()->with('SignatoryError', 'Error! (Duplicate signatory name)');
        }

        $signatory = TravelOrderSignatory::create([
            'name'      => $data['name'],
            'approver1' => $data['approver1'],
            'approver2' => $data['approver2'],
            'approver3' => $data['approver3'],
        ]);

        // Upload signatures to employees
        $this->saveSignatureForEmployee($request->file('approver1_signature'), (int) $data['approver1']);
        $this->saveSignatureForEmployee($request->file('approver2_signature'), (int) $data['approver2']);
        $this->saveSignatureForEmployee($request->file('approver3_signature'), (int) $data['approver3']);

        return back()->with('message', 'Signatory Saved Successfully!');
    }

    public function update(Request $request, $id)
    {
        $signatory = TravelOrderSignatory::findOrFail($id);
        $this->authorize('update', $signatory); // <-- fix from 'delete' to 'update'

        $data = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'approver1'           => ['required', 'exists:employee,id'],
            'approver2'           => ['required', 'exists:employee,id'],
            'approver3'           => ['required', 'exists:employee,id'],
            'approver1_signature' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'approver2_signature' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'approver3_signature' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'clear_approver1_signature'  => ['sometimes', 'boolean'],
            'clear_approver2_signature'  => ['sometimes', 'boolean'],
            'clear_approver3_signature'  => ['sometimes', 'boolean'],
        ]);

        // Unique name check except current row
        if (
            TravelOrderSignatory::where('name', $data['name'])
            ->where('id', '!=', $signatory->id)
            ->exists()
        ) {
            return back()->with('SignatoryError', 'Error! (Duplicate signatory name)');
        }

        $signatory->update([
            'name'      => $data['name'],
            'approver1' => $data['approver1'],
            'approver2' => $data['approver2'],
            'approver3' => $data['approver3'],
        ]);

        // Clear toggles (kahit walang bagong upload)
        if ($request->boolean('clear_approver1_signature')) {
            $this->clearSignatureForEmployee((int) $data['approver1']);
        }
        if ($request->boolean('clear_approver2_signature')) {
            $this->clearSignatureForEmployee((int) $data['approver2']);
        }
        if ($request->boolean('clear_approver3_signature')) {
            $this->clearSignatureForEmployee((int) $data['approver3']);
        }

        // Upload/replace signatures (optional per file)
        $this->saveSignatureForEmployee($request->file('approver1_signature'), (int) $data['approver1']);
        $this->saveSignatureForEmployee($request->file('approver2_signature'), (int) $data['approver2']);
        $this->saveSignatureForEmployee($request->file('approver3_signature'), (int) $data['approver3']);

        return back()->with('message', 'Signatory Updated Successfully!');
    }

    private function saveSignatureForEmployee(?\Illuminate\Http\UploadedFile $file, int $employeeId): void
    {
        if (!$file) return;

        $emp = Employee::findOrFail($employeeId);

        // delete old file kung meron
        if ($emp->signature_path && Storage::disk('public')->exists($emp->signature_path)) {
            Storage::disk('public')->delete($emp->signature_path);
        }

        // store new file under storage/app/public/signatures
        $path = $file->store('signatures', 'public');

        $emp->forceFill(['signature_path' => $path])->save();
    }

    private function clearSignatureForEmployee(int $employeeId): void
    {
        $emp = Employee::findOrFail($employeeId);
        if ($emp->signature_path && Storage::disk('public')->exists($emp->signature_path)) {
            Storage::disk('public')->delete($emp->signature_path);
        }
        $emp->signature_path = null;
        $emp->save();
    }

    // public function store(Request $request)
    // {
    //     $this->authorize('create', \App\Models\TravelOrderSignatory::class);

    //     $formfields = $request->validate([
    //         'name' => 'required',
    //         'approver1' => 'required',
    //         'approver2' => 'required',

    //     ]);

    //     $check = TravelOrderSignatory::where('name', '=', $request->name)->get()->first();

    //     if ($check) {
    //       return back()->with('SignatoryError', 'Error!');
    //     }
    //     else
    //     {
    //     TravelOrderSignatory::create($formfields);
    //     return back()->with('message', "Signatory Saved Successfully!");
    //     }
    // }

    // public function update(Request $request, $TravelOrderSignatory)
    // {
    //     $TravelOrderSignatory = TravelOrderSignatory::where('id', '=', $TravelOrderSignatory)->get()->first();

    //     $this->authorize('delete', $TravelOrderSignatory);

    //     $formfields = $request->validate([
    //         'name' => 'required',
    //         'approver1' => 'required',
    //         'approver2' => 'required',

    //     ]);

    //     if ($request->name == $TravelOrderSignatory->name) {
    //         $TravelOrderSignatory->update($formfields);
    //         return back()->with('message', "Signatory Updated Successfully!");
    //     } else {
    //         $check = TravelOrderSignatory::where('name', '=', $request->name)->get()->first();
    //         if ($check) {
    //             return back()->with('SignatoryError', 'Error!');
    //         } else {
    //             $TravelOrderSignatory->update($formfields);
    //             return back()->with('message', "Signatory Updated Successfully!");
    //         }
    //     }
    // }

    public function destroy($TravelOrderSignatory)
    {

        $TravelOrderSignatory = TravelOrderSignatory::where('id', '=', $TravelOrderSignatory)->get()->first();
        $this->authorize('delete', $TravelOrderSignatory);


        $TravelOrderSignatory->delete();

        return back()->with('message', "Signatory Deleted Successfully!");
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee record not found.');
        }

        return view('user.profile', compact('employee'));
    }

    public function updateSignature(Request $request)
    {
        $request->validate([
            'signature' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            return back()->with('error', 'Employee record not found.');
        }

        // Delete old signature if exists
        if ($employee->signature_path && Storage::disk('public')->exists($employee->signature_path)) {
            Storage::disk('public')->delete($employee->signature_path);
        }

        // Store new signature
        $path = $request->file('signature')->store('signatures', 'public');

        $employee->signature_path = $path;
        $employee->save();

        return back()->with('message', 'Signature updated successfully!');
    }

    public function updateInfo(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contactnumber' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            return back()->with('error', 'Employee record not found.');
        }

        $employee->update([
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'contactnumber' => $request->contactnumber,
        ]);

        // Update user email if changed
        if ($user->email !== $request->email) {
            $user->email = $request->email;
            $user->save();
        }

        return back()->with('message', 'Profile updated successfully!');
    }
}

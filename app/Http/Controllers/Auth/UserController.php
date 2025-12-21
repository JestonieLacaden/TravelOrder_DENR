<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  public function index()
  {

    $this->authorize('viewany', User::class);

    $Users = User::with('Role')->orderBy('id')->get();
    $Emails = Employee::where('has_account', false)->orderBy('email')->get();
    $Roles = Role::orderBy('rolename')->get();
    //    $Offices = Office::orderby('office')->get();



    return view('admin.user-mgmt.user.index', compact('Users', 'Emails', 'Roles'));
  }

  public function store(Request $request)
  {



    $this->authorize('create', User::class);
    $email = $request->email;

    $check = User::where([
      ['email', '=', $email],

    ])->first();

    if ($check) {
      return back()->with('error', 'Duplicate Email!');
    }

    $formfields = $request->validate([

      'email' => 'required',
      'username' => ['required', 'min:3'],
      'password' => ['required', 'min:6', 'confirmed'],

    ]);




    $formfields['password'] = bcrypt($formfields['password']);

    user::create($formfields);

    return redirect()->route('user.index')->with('success', 'User Added Succesfully!');
  }


  public function update(Request $request, User $User)
  {

    $this->authorize('update', $User);



    $formfields = $request->validate([
      'username' => ['required', 'min:3'],
      'email' => 'required',
      'password' => ['required', 'min:6'],


    ]);


    $formfields['password'] = bcrypt($formfields['password']);


    $User->update($formfields);

    return redirect()->route('user.index')->with('success', 'User Updated Succesfully!');
  }

  public function destroy(User $User)
  {
    $this->authorize('delete', $User);

    if ($User->id == 1) {
      abort(403);
    }

    $Employeeid = Employee::where('email', '=', $User->email)->get()->first();
    $has_account['has_account'] = false;
    Employee::where('id', '=', $Employeeid->id)->update($has_account);


    $User->delete();



    return redirect()->route('user.index')->with('success', 'User Deleted Successfully!');
  }

  public function changepassword()
  {

    $User = User::where('email', '=', auth()->user()->email)->get()->first();
    $this->authorize('changepassword', $User);

    return view('auth.changepassword', compact('User'));
  }

  public function updatepassword(Request $request)
  {

    $User = User::where('email', '=', auth()->user()->email)->get()->first();
    $this->authorize('changepassword', $User);


    $formfields = $request->validate([
      'password' => ['required', 'min:6'],
    ]);

    if ($request->password == $request->password_confirmation) {
      $formfields['password'] = bcrypt($formfields['password']);

      $User->update($formfields);

      return back()->with('message', "Password Updated Successfully! It will take effect on your next log in! ");
    } else {
      return back()->with('errormessage', "Password not match!");
    }
  }
}

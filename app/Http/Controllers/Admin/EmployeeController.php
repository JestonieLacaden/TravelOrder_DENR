<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Models\Office;
use App\Models\Section;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
  public function index()
  {

    $this->authorize('viewany', \App\Models\Employee::class);

    $Employees = Employee::with('Office')->with('Section')->with('Unit')->orderBy('id')->get();
    $Offices = Office::orderby('office')->get();
    $Emails = Employee::orderby('email')->get()->pluck('id', 'email');

    return view('admin.employee-mgmt.employee.index', compact('Employees', 'Offices', 'Emails'));
  }

  public function view(Employee $Employee)
  {
    $this->authorize('viewany', \App\Models\Employee::class);

    $Offices = Office::orderby('id')->get();
    $Sections = Section::orderby('section')->get();
    $Units = Unit::orderby('unit')->get();


    return view('admin.employee-mgmt.employee.view', compact('Offices', 'Sections', 'Units', 'Employee'));
  }

  public function create()
  {

    $this->authorize('create', Employee::class);

    $Offices = Office::orderby('id')->get();
    $Sections = Section::orderby('section')->get();
    $Units = Unit::orderby('unit')->get();


    return view('admin.employee-mgmt.employee.create', compact('Offices', 'Sections', 'Units'));
  }

  public function edit(Employee $Employee)
  {

    $this->authorize('update', $Employee);

    $Offices = Office::orderby('id')->get();
    $Sections = Section::orderby('section')->get();
    $Units = Unit::orderby('unit')->get();


    return view('admin.employee-mgmt.employee.edit', compact('Offices', 'Sections', 'Units', 'Employee'));
  }



  public function store(Request $request)
  {

    $this->authorize('create', Employee::class);

    $validator = Validator::make($request->all(), [
      'employeeid' => ['required', Rule::unique('employee', 'employeeid')],
      'firstname' => 'required',
      'middlename' => 'required',
      'lastname' => 'required',
      'birthdate' => 'required',
      'contactnumber' => 'required',
      'email' => ['required', 'email', Rule::unique('employee', 'email')],
      'address' => 'required',
      'officesectionunit' => 'required',
      'position' => 'required',
      'datehired' => 'required',
      'empstatus' => 'required',

    ]);

    // // Check validation failure
    // if ($validator->fails()) {
    //   return back()->with('error', 'Fields must not be empty!');

    // }

    $formfields = $request->validate([


      'employeeid' => ['required', Rule::unique('employee', 'employeeid')],
      'firstname' => 'required',
      'middlename' => 'required',
      'lastname' => 'required',
      'birthdate' => 'required',
      'contactnumber' => 'required',
      'email' => ['required', 'email', Rule::unique('employee', 'email')],
      'address' => 'required',
      'position' => 'required',
      'datehired' => 'required',
      'empstatus' => 'required',
      'officesectionunit' => 'required',


      // 'picture' => 'attachment/2123.jpg',
      // 'signature' => 'attachment/2123.jpg',


    ]);
    $id = $request->employeeid;
    $email = $request->email;

    $check = Employee::where([
      ['employeeid', '=', $id],

    ])->first();

    if ($check) {
      return back()->with('error', 'Duplicate Employee ID!');
    }
    $check = Employee::where([
      ['email', '=', $email],

    ])->first();

    if ($check) {
      return back()->with('error', 'Duplicate Email Address!');
    }



    $data = $request->officesectionunit;
    list($Office, $Section, $Unit) = explode(",", $data);

    if ($request->hasFile('picture')) {
      $formfields['picture'] = $request->file('picture')->store('picture', 'public');
      $request->file('picture')->move('storage/profilepicture');
    }

    if ($request->hasFile('signature')) {
      $formfields['signature_path'] = $request->file('signature')->store('signatures', 'public');
    }

    $formfields['officeid'] = $Office;
    $formfields['sectionid'] = $Section;
    $formfields['unitid'] = $Unit;
    $formfields['has_account'] = false;


    //   if ($request->hasFile('attachment')) {
    //    $formfields['attachment'] = $request->file('attachment')->store('attachment', 'public');
    // }


    //  try {
    //   Employee::create($formfields) ;

    //  } catch (QueryException $e) {

    //   $errorCode = $e->errorInfo[1];          
    //       if($errorCode == 1062){
    //           throw ('Duplicate Entry');

    //       }
    //   }

    Employee::create($formfields);

    return redirect()->route('employee.index')->with('success', 'Employee Added Succesfully!');
  }

  public function destroy(Employee $Employee)
  {
    $this->authorize('delete', $Employee);
    $Employee->delete();

    return redirect()->route('employee.index')->with('success', 'Employee Deleted Succesfully!');
  }

  public function update(Request $request, Employee $Employee)
  {

    $this->authorize('update', $Employee);




    $formfields = $request->validate([

      'firstname' => 'required',
      'middlename' => 'required',
      'lastname' => 'required',
      'birthdate' => 'required',
      'contactnumber' => 'required',
      'officesectionunit' => 'required',
      'address' => 'required',
      'position' => 'required',
      'datehired' => 'required',
      'empstatus' => 'required',

    ]);

    // if ($request->hasFile('logo')) {
    //     $formfields['logo'] = $request->file('logo')->store('attachment', 'public');

    // }

    $data = $request->officesectionunit;
    list($Office, $Section, $Unit) = explode(",", $data);

    if ($request->hasFile('signature')) {
      $formfields['signature_path'] = $request->file('signature')->store('signatures', 'public');
    }

    $formfields['officeid'] = $Office;
    $formfields['sectionid'] = $Section;
    $formfields['unitid'] = $Unit;

    $Employee->update($formfields);


    return redirect()->route('employee.index')->with('success', 'Employee Updated Succesfully!');
  }
}

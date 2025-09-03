<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RolesController extends Controller
{
    public function index() {
 
        $Roles = Role::orderBy('rolename')->get();

        $Users = User::where('has_role','=',false)->get();

        $UsersWithRoles = User::orderby('created_at','desc')->get();

        $UserRoles = UserRole::orderby('roleid','asc')->get();
    

          return view('admin.user-mgmt.role.index', compact('Roles','Users','UsersWithRoles','UserRoles'));
    }

    public function store(Request $request) {

        $this->authorize('create',UserRole::class);

        $formfields = $request->validate([              
          
            'roleid' => 'required',
            'userid' => 'required',

         ]);

        $RoleIds = $request->roleid;
        
   
            foreach($RoleIds as $Roleid)
            {
                $formfields['roleid'] = $Roleid;
                $formfields['userid'] = $request->userid;
                UserRole::create($formfields); 
            }
    

        $UserId = User::where('id','=',$request->userid)->get()->first();
      
        if (!empty($UserId)) {
            $has_role['has_role'] = true; 
            User::where('id','=',$request->userid)->update($has_role);
        }
        
        return redirect()->route('role.index')->with('message', 'Role Added Succesfully!');
        }

        
        public function edit(User $User) {
        
            $this->authorize('edit', UserRole::class);

            $Roles = Role::orderBy('rolename')->get();

            $UserRoles = UserRole::where('userid','=',$User->id)->get();


               
            return view('admin.user-mgmt.role.edit', compact('Roles','User', 'UserRoles'));
        }

    public function update(Request $request) {

        $this->authorize('create',UserRole::class);

        $formfields = $request->validate([              
          
            'roleid' => 'required',
            'userid' => 'required',

         ]);

         $UserId = UserRole::where('userid','=',$request->userid)->get();
      
         if (!empty($UserId)) {
            foreach($UserId as $User)
            {
                $User->delete();
            }
            
         }

    
        $RoleIds = $request->roleid;

            foreach($RoleIds as $Roleid)
            {
                $formfields['roleid'] = $Roleid;
                $formfields['userid'] = $request->userid;
                UserRole::create($formfields); 
            }
    
        
        return redirect()->route('role.index')->with('message', 'Role Added Succesfully!');
        }
}

      // $formfields = $request->all();
                  // $roles = $formfields['roleid'];
                  // $formfields['roleid'] = implode(',', $roles);

<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use App\Models\UserRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '1' || $Role->roleid == '5' || $Role->roleid == '8' )
                {
                    return ($user); 
                }

            }
        }    
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Employee $employee)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '1' || $Role->roleid == '5' || $Role->roleid == '8' )
                {
                    return ($user); 
                }

            }
        }  
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '1' || $Role->roleid == '5')
                {
                    return ($user); 
                }

            }
        }  
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Employee $employee)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '1' || $Role->roleid == '5')
                {
                    return ($user); 
                }

            }
        }  
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Employee $employee)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '1' || $Role->roleid == '5')
                {
                    return ($user); 
                }

            }
        }  
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Employee $employee)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Employee $employee)
    {
        //
    }
}

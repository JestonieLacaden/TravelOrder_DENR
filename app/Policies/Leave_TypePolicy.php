<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserRole;
use App\Models\Leave_Type;
use Illuminate\Auth\Access\HandlesAuthorization;

class Leave_TypePolicy
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
                if  ($Role->roleid == '1' || $Role->roleid =='6' || $Role->roleid =='8')
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
     * @param  \App\Models\Leave_Type  $leaveType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Leave_Type $leaveType)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave_Type  $leaveType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Leave_Type $leaveType)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if  ($Role->roleid == '1' || $Role->roleid =='6' || $Role->roleid =='8')
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
     * @param  \App\Models\Leave_Type  $leaveType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Leave_Type $leaveType)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave_Type  $leaveType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Leave_Type $leaveType)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave_Type  $leaveType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Leave_Type $leaveType)
    {
        //
    }
}

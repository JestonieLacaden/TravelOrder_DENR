<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserRole;
use App\Models\FMAllocationPAPSAA;
use Illuminate\Auth\Access\HandlesAuthorization;

class FMAllocationPAPSAAPolicy
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
                if ($Role->roleid == '14' || $Role->roleid == '16') 
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
     * @param  \App\Models\FMAllocationPAPSAA  $fMAllocationPAPSAA
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, FMAllocationPAPSAA $fMAllocationPAPSAA)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '14' || $Role->roleid == '16') 
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
                if ($Role->roleid == '14' || $Role->roleid == '16') 
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
     * @param  \App\Models\FMAllocationPAPSAA  $fMAllocationPAPSAA
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, FMAllocationPAPSAA $fMAllocationPAPSAA)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '14' || $Role->roleid == '16') 
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
     * @param  \App\Models\FMAllocationPAPSAA  $fMAllocationPAPSAA
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, FMAllocationPAPSAA $fMAllocationPAPSAA)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '14' || $Role->roleid == '16') 
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
     * @param  \App\Models\FMAllocationPAPSAA  $fMAllocationPAPSAA
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, FMAllocationPAPSAA $fMAllocationPAPSAA)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FMAllocationPAPSAA  $fMAllocationPAPSAA
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, FMAllocationPAPSAA $fMAllocationPAPSAA)
    {
        //
    }
}

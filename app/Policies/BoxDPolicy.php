<?php

namespace App\Policies;

use App\Models\BoxD;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class BoxDPolicy
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
                if ($Role->roleid == '18' ||$Role->roleid == '19' || $Role->roleid == '20' ) 
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
     * @param  \App\Models\BoxD  $boxD
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, BoxD $boxD)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '18' ||$Role->roleid == '19' || $Role->roleid == '20' ) 
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
                if ($Role->roleid == '18' ||$Role->roleid == '19' || $Role->roleid == '20' ) 
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
     * @param  \App\Models\BoxD  $boxD
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, BoxD $boxD)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '18' ||$Role->roleid == '19' || $Role->roleid == '20' ) 
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
     * @param  \App\Models\BoxD  $boxD
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, BoxD $boxD)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '18' ||$Role->roleid == '19' || $Role->roleid == '20' ) 
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
     * @param  \App\Models\BoxD  $boxD
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, BoxD $boxD)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BoxD  $boxD
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, BoxD $boxD)
    {
        //
    }
}

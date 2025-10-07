<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserRole;
use App\Models\SetTravelOrderSignatory;
use Illuminate\Auth\Access\HandlesAuthorization;

class SetTravelOrderSignatoryPolicy
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
                if ($Role->roleid == '1' || $Role->roleid =='8'  || $Role->roleid =='11' || $Role->roleid =='6' )
                {
                    return ($user); 
                }

            }
        }


    // protected function isAdmin(User $user): bool
    // {
    //     $roles = \App\Models\UserRole::where('userid', $user->id)->pluck('roleid')->all();
    //     return in_array('1', $roles) || in_array('5', $roles);
    // }

    // public function viewAny(User $user)
    // {
    //     return $this->isAdmin($user);
    // }
    // public function create(User $user)
    // {
    //     return $this->isAdmin($user);
    // }
    // public function update(User $user)
    // {
    //     return $this->isAdmin($user);
    // }

    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SetTravelOrderSignatory  $setTravelOrderSignatory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, SetTravelOrderSignatory $setTravelOrderSignatory)
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
        $Roles = UserRole::where('userid','=',$user->id)->get();
    
          
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '1' || $Role->roleid =='8'  || $Role->roleid =='11' || $Role->roleid =='6' )
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
     * @param  \App\Models\SetTravelOrderSignatory  $setTravelOrderSignatory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, SetTravelOrderSignatory $setTravelOrderSignatory)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();
    
          
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '1' || $Role->roleid =='8'  || $Role->roleid =='11' || $Role->roleid =='6' )
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
     * @param  \App\Models\SetTravelOrderSignatory  $setTravelOrderSignatory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, SetTravelOrderSignatory $setTravelOrderSignatory)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();
    
          
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '1' || $Role->roleid =='8'  || $Role->roleid =='11' || $Role->roleid =='6' )
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
     * @param  \App\Models\SetTravelOrderSignatory  $setTravelOrderSignatory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, SetTravelOrderSignatory $setTravelOrderSignatory)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SetTravelOrderSignatory  $setTravelOrderSignatory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, SetTravelOrderSignatory $setTravelOrderSignatory)
    {
        //
    }
    public function edit(User $user)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();
    
          
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '1' || $Role->roleid =='8'  || $Role->roleid =='11' || $Role->roleid =='6' )
                {
                    return ($user); 
                }

            }
        }     
    }
}
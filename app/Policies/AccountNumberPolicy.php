<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserRole;
use App\Models\AccountNumber;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountNumberPolicy
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
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountNumber  $accountNumber
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, AccountNumber $accountNumber)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '17') 
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
                if ($Role->roleid == '17') 
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
     * @param  \App\Models\AccountNumber  $accountNumber
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, AccountNumber $accountNumber)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '17') 
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
     * @param  \App\Models\AccountNumber  $accountNumber
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, AccountNumber $accountNumber)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '17') 
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
     * @param  \App\Models\AccountNumber  $accountNumber
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, AccountNumber $accountNumber)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountNumber  $accountNumber
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, AccountNumber $accountNumber)
    {
        //
    }
}

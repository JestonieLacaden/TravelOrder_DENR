<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Employee;
use App\Models\UserRole;
use App\Models\Dtr_History;
use Illuminate\Auth\Access\HandlesAuthorization;

class Dtr_HistoryPolicy
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
                if ($Role->roleid == '1' || $Role->roleid == '5' )
                {
                    return ($user); 
                }

            }
        }  
    }

    public function viewMSDSection(User $user)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '1' || $Role->roleid == '5' || $Role->roleid == '8' || $Role->roleid == '7' )
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
     * @param  \App\Models\Dtr_History  $dtrHistory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Dtr_History $dtrHistory)
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
                if ($Role->roleid == '1' || $Role->roleid == '5' )
                {
                    return ($user); 
                }

            }
        }  

    }

    public function print(User $user)
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

    public function userview(User $user)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
            return ($user); 
        }  

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dtr_History  $dtrHistory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Dtr_History $dtrHistory)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dtr_History  $dtrHistory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Dtr_History $dtrHistory)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dtr_History  $dtrHistory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Dtr_History $dtrHistory)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dtr_History  $dtrHistory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Dtr_History $dtrHistory)
    {
        //
    }


    public function viewDTRIndex(User $user)
    
    {
       $Roles = UserRole::where('userid','=',$user->id)->get();
   
         
           if(!empty($Roles))
           {
             foreach ($Roles as $Role)
               {
                   if ($Role->roleid != '1' && auth()->check())
                   {
                       
                       $Employee = Employee::where('email','=',auth()->user()->email)->get()->first();
    
          
                       if(!empty($Employee))
                       {
                          
                            return ($user); 
                       
                       }     
                   
                   }
   
               }
           }     
    }
}

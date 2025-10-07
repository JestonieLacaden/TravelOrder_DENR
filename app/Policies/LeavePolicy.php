<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Leave;
use App\Models\Employee;
use App\Models\UserRole;
use App\Models\LeaveSignatory;
use App\Models\SetLeaveSignatory;
use App\Models\TravelOrderSignatory;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeavePolicy
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

        {
        $LeaveSignatories = LeaveSignatory::get();
        $Employee = Employee::where('email','=',auth()->user()->email)->get()->first();
 
    
          
            if(!empty($LeaveSignatories))
            {
              foreach ($LeaveSignatories as $LeaveSignatory)
                {
                    if ($LeaveSignatory->approver1 == $Employee->id || $LeaveSignatory->approver2 == $Employee->id || $LeaveSignatory->approver3 == $Employee->id)
                    {
                        return ($user); 
                    }
    
                }
            }     
        
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave  $Leave
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Leave $Leave)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '1' || $Role->roleid =='5' || $Role->roleid =='8')
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
                if  ($Role->roleid == '1' || $Role->roleid =='5')
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
     * @param  \App\Models\Leave  $Leave
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Leave $Leave)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

        if(!empty($Roles))
        {
         if($Leave->is_approve1 == false && $Leave->is_rejected1 == false) 
         {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '1' || $Role->roleid =='5')
                {
                    return ($user); 
                }

            }
         }
        }

        // $Employee = Employee::where('email','=',auth()->user()->email)->get()->first();

        $Employee = Employee::where('email', auth()->user()->email)->first();
        if (!$Employee) {
            // User has no employee record; deny gracefully instead of crashing
            return false;
        }
     
           
        if(!empty($Employee))
        {
            if ($Employee->empstatus == 'PERMANENT' && $Leave->employeeid == $Employee->id && $Leave->userid == auth()->user()->id && $Leave->is_approve1 == false && $Leave->is_rejected1 == false)
            {
                return ($user); 
            }
        }     
        
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave  $Leave
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Leave $Leave)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
         if($Leave->is_approve1 == false && $Leave->is_rejected1 == false) 
         {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '1' || $Role->roleid =='5')
                {
                    return ($user); 
                }

            }
         }

         $Employee = Employee::where('email','=',auth()->user()->email)->get()->first();
     
           
         if(!empty($Employee))
         {
             if ($Employee->empstatus == 'PERMANENT' && $Leave->employeeid == $Employee->id && $Leave->userid == auth()->user()->id && $Leave->is_approve1 == false && $Leave->is_rejected1 == false)
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
     * @param  \App\Models\Leave  $Leave
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Leave $Leave)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave  $Leave
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Leave $Leave)
    {
        //
    }


    
//
     public function acceptrequest(User $user)
    
    {
        $LeaveSignatories = LeaveSignatory::get();
        $Employee = Employee::where('email','=',auth()->user()->email)->get()->first();


        if(!empty($LeaveSignatories))
        {
      
          foreach ($LeaveSignatories as $LeaveSignatory)
            {
                if ($LeaveSignatory->approver1 == $Employee->id && auth()->check())
                {          
                    { 
                        return ($user); 
                    }
                }
                if ($LeaveSignatory->approver2 == $Employee->id && auth()->check())
                {
                    { 
                        return ($user); 
                    }
                }
                if ($LeaveSignatory->approver3 == $Employee->id && auth()->check())
                {
                         { 
                        return ($user); 
                    }
                }
            }
        }
    }

    //     public function acceptemployee(User $user)
    // {
    //     $emp = \App\Models\Employee::where('email', $user->email)->first();
    //     if (!$emp) return false;

    //     return \App\Models\LeaveSignatory::where('approver1', $emp->id)
    //             ->orWhere('approver2', $emp->id)
    //             ->orWhere('approver3', $emp->id)
    //             ->exists()
    //         || \App\Models\TravelOrderSignatory::where('approver1', $emp->id)
    //             ->orWhere('approver2', $emp->id)
    //             ->exists();
    // }

    public function acceptemployee(\App\Models\User $user)
    {
        $employee = \App\Models\Employee::where('email', $user->email)->first();
        if (!$employee) return false;

        $hasLeave = \App\Models\LeaveSignatory::where(function ($q) use ($employee) {
            $q->where('approver1', $employee->id)
                ->orWhere('approver2', $employee->id)
                ->orWhere('approver3', $employee->id);
        })->exists();

        $hasTO = \App\Models\TravelOrderSignatory::where(function ($q) use ($employee) {
            $q->where('approver1', $employee->id)
                ->orWhere('approver2', $employee->id);
        })->exists();

        return $hasLeave || $hasTO;
    }





    public function accept(User $user, Leave $Leave)
    {
        $ApproverEmployee = Employee::where('email','=',auth()->user()->email)->get()->first();
      
        $LeaveofEmployee = Employee::where('id','=', $Leave->employeeid)->get()->first();
        $SetLeaveSignatory = SetLeaveSignatory::where('sectionid','=',$LeaveofEmployee->sectionid)->get()->first();
    
        
        if(!empty($SetLeaveSignatory))
        {
            $LeaveSignatory = LeaveSignatory::where('id','=',$SetLeaveSignatory->leavesignatoryid)->get()->first();
            if(!empty($LeaveSignatory))
            {
                
                if( $LeaveSignatory->id == $SetLeaveSignatory->leavesignatoryid)
                {
                    if($LeaveSignatory->approver1 == $ApproverEmployee->id)
                    {
                        if($Leave->is_rejected1 == false && $Leave->is_approve1 == false)
                        {
                            return($user);
                        }   
                    }
                    if($LeaveSignatory->approver2 == $ApproverEmployee->id)
                    {
                        if($Leave->is_rejected2 == false && $Leave->is_rejected1 == false  && $Leave->is_approve2 == false && $Leave->is_approve1 == true)
                        {
                            return($user);
                        }   
                    }
                    if ($LeaveSignatory->approver3 == $ApproverEmployee->id && auth()->check())
                        {
                            if($Leave->is_rejected2 == false  && $Leave->is_rejected1  == false && $Leave->is_approve3 == false  && $Leave->is_approve2 == true && $Leave->is_approve1 == true)
                            { 
                                return ($user); 
                            }
                        }
    
                }
            }
        }



        // $LeaveSignatories = LeaveSignatory::get();
        // $Employee = Employee::where('email','=',auth()->user()->email)->get()->first();
        // $SetLeaveSignatories = SetLeaveSignatory::get();
        // $LeaveofEmployee = Employee::where('id','=', $Leave->employeeid)->get()->first();

      
        // if(!empty($LeaveSignatories))
        // {
      
        //   foreach ($LeaveSignatories as $LeaveSignatory)
        //     {
        //         if(!empty($SetLeaveSignatories))
        //         {
        //             foreach($SetLeaveSignatories as $SetLeaveSignatory)
        //             {
        //                 if($SetLeaveSignatory->sectionid == $Employee->sectionid)
        //                 {
        //                     if ($LeaveSignatory->approver1 == $Employee->id && auth()->check())
        //                     {
        //                         if($Leave->is_rejected1 == false && $Leave->is_approve1 == false && $LeaveofEmployee->sectionid == $SetLeaveSignatory->sectionid) 
        //                         { 
        //                             return ($user); 
        //                         }
        //                     }
        //                     if ($LeaveSignatory->approver2 == $Employee->id && auth()->check())
        //                     {
        //                         if($Leave->is_rejected2 == false && $Leave->is_rejected1 == false  && $Leave->is_approve2 == false && $Leave->is_approve1 == true && $LeaveofEmployee->sectionid == $SetLeaveSignatory->sectionid)
        //                         { 
        //                             return ($user); 
        //                         }
        //                     }
        //                     if ($LeaveSignatory->approver3 == $Employee->id && auth()->check())
        //                     {
        //                         if($Leave->is_rejected2 == false  && $Leave->is_rejected1  == false && $Leave->is_approve3 == false  && $Leave->is_approve2 == true && $Leave->is_approve1 == true && $LeaveofEmployee->sectionid == $SetLeaveSignatory->sectionid)
        //                         { 
        //                             return ($user); 
        //                         }
        //                     }
        //                 }
        //             }
        //         }    
        //     }
        // }
    }
    

    public function reject(User $user, Leave $Leave)
    {
        $ApproverEmployee = Employee::where('email','=',auth()->user()->email)->get()->first();
      
        $LeaveofEmployee = Employee::where('id','=', $Leave->employeeid)->get()->first();
        $SetLeaveSignatory = SetLeaveSignatory::where('sectionid','=',$LeaveofEmployee->sectionid)->get()->first();
    
        
        if(!empty($SetLeaveSignatory))
        {
            $LeaveSignatory = LeaveSignatory::where('id','=',$SetLeaveSignatory->leavesignatoryid)->get()->first();
            if(!empty($LeaveSignatory))
            {
                
                if( $LeaveSignatory->id == $SetLeaveSignatory->leavesignatoryid)
                {
                    if($LeaveSignatory->approver1 == $ApproverEmployee->id)
                    {
                        if($Leave->is_rejected1 == false && $Leave->is_approve1 == false)
                        {
                            return($user);
                        }   
                    }
                    if($LeaveSignatory->approver2 == $ApproverEmployee->id)
                    {
                        if($Leave->is_rejected2 == false && $Leave->is_rejected1 == false  && $Leave->is_approve2 == false && $Leave->is_approve1 == true)
                        {
                            return($user);
                        }   
                    }
                    if ($LeaveSignatory->approver3 == $ApproverEmployee->id && auth()->check())
                        {
                            if($Leave->is_rejected2 == false  && $Leave->is_rejected1  == false && $Leave->is_approve3 == false  && $Leave->is_approve2 == true && $Leave->is_approve1 == true)
                            { 
                                return ($user); 
                            }
                        }
    
                }
            }
        }
    }
    

     public function viewLeave(User $user, Leave $Leave)
     {
        $ApproverEmployee = Employee::where('email','=',auth()->user()->email)->get()->first();
      
        $LeaveofEmployee = Employee::where('id','=', $Leave->employeeid)->get()->first();
        $SetLeaveSignatory = SetLeaveSignatory::where('sectionid','=',$LeaveofEmployee->sectionid)->get()->first();
    
        
        if(!empty($SetLeaveSignatory))
        {
            $LeaveSignatory = LeaveSignatory::where('id','=',$SetLeaveSignatory->leavesignatoryid)->get()->first();
            if(!empty($LeaveSignatory))
            {
                
                if( $LeaveSignatory->id == $SetLeaveSignatory->leavesignatoryid)
                {
                    if($LeaveSignatory->approver1 == $ApproverEmployee->id)
                    {
                        if($Leave->is_rejected1 == false && $Leave->is_approve1 == false)
                        {
                            return($user);
                        }   
                    }
                    if($LeaveSignatory->approver2 == $ApproverEmployee->id)
                    {
                        if($Leave->is_rejected2 == false && $Leave->is_rejected1 == false  && $Leave->is_approve2 == false && $Leave->is_approve1 == true)
                        {
                            return($user);
                        }   
                    }
                    if ($LeaveSignatory->approver3 == $ApproverEmployee->id && auth()->check())
                        {
                            if($Leave->is_rejected2 == false  && $Leave->is_rejected1  == false && $Leave->is_approve3 == false  && $Leave->is_approve2 == true && $Leave->is_approve1 == true)
                            { 
                                return ($user); 
                            }
                        }
    
                }
            }
        }
    }
    
     public function viewLeaveindex(User $user)
    
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
                            if ($Employee->empstatus == 'PERMANENT')
                            {
                                return ($user); 
                            }
                        }     
                    
                    }
    
                }
            }     
     }

     public function AddUserLeave(User $user)
    
     {
 
         {
           $Employee = Employee::where('email','=',auth()->user()->email)->get()->first();
     
           
             if(!empty($Employee))
             {
                 if ($Employee->empstatus == 'PERMANENT')
                 {
                     return ($user); 
                 }
             }     
         
         }
     }

    // app/Policies/LeavePolicy.php
    public function print(User $user, Leave $leave): bool
    {
        // Admin / MSD (hal. roleid 1 or 5)
        $roles = UserRole::where('userid', $user->id)->pluck('roleid')->map(fn($r) => (int)$r)->all();
        $isAdminOrMsd = in_array(1, $roles, true) || in_array(5, $roles, true);

        // Owner ng request
        $employee = Employee::where('email', $user->email)->first();
        $isOwner = $employee && ($leave->employeeid == $employee->id || $leave->userid == $user->id);

        // fully approved, walang rejection
        $isFullyApproved = $leave->is_approve3
            && !$leave->is_rejected1 && !$leave->is_rejected2 && !$leave->is_rejected3;

        return ($isAdminOrMsd || $isOwner) && $isFullyApproved;
    }


    //  public function print(User $user, Leave $Leave)
    // {
    //     $Roles = UserRole::where('userid','=',$user->id)->get();

    //     if(!empty($Roles))
    //     {
    //      {
    //       foreach ($Roles as $Role)
    //         {
    //             if ($Role->roleid == '1' || $Role->roleid =='5')
    //             {
    //                 if($Leave->is_rejected1 != true && $Leave->is_rejected2 != true  && $Leave->is_rejected3 != true  && $Leave->is_approve1 == true)
    //                 {
    //                     return ($user); 
    //                 }
    //             }

    //         }
    //      }
    //     }


    //     $Employee = Employee::where('email','=',auth()->user()->email)->get()->first();


    //     if(!empty($Employee))
    //     {
    //         if ($Employee->empstatus == 'PERMANENT' && $Leave->employeeid == $Employee->id || $Leave->userid == auth()->user()->id)
    //         {
    //             if($Leave->is_rejected1 != true && $Leave->is_rejected2 != true  && $Leave->is_rejected3 != true  )
    //             {
    //             return ($user); 
    //             }

    //         }
    //     }     

    // }

    public function summary(User $user)
    {
      

        $Employee = Employee::where('email','=',auth()->user()->email)->get()->first();
     
           
        if(!empty($Employee))
        {
            if ($Employee->empstatus == 'PERMANENT')
            {
      
                return ($user); 

                    
            }
        }     
        
    }
   
    public function MSDaddLeave(User $user)
    
    {

        {
            $Roles = UserRole::where('userid','=',$user->id)->get();
    
          
            if(!empty($Roles))
            {
              foreach ($Roles as $Role)
                {
                    if ($Role->roleid == '1' || $Role->roleid =='5' )
                    {
                        return ($user); 
                    }
    
                }
            }     
        
        }
    }
   
}
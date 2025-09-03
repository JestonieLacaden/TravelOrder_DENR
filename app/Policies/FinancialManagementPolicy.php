<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Employee;
use App\Models\UserRole;
use App\Models\FinancialManagement;
use App\Models\FinancialManagementRoute;
use Illuminate\Auth\Access\HandlesAuthorization;

class FinancialManagementPolicy
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
            return (auth()->user());
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FinancialManagement  $financialManagement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, FinancialManagement $financialManagement)
    {
        return (auth()->user());
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
                if ($Role->roleid == '2') 
                {
                    return ($user); 
                }

            }
        }  
    }

    public function createBoxA(User $user)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '1') 
                {
                    return ($user); 
                }

            }
        }  
    }

    public function FMPlanning(User $user)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '14') 
                {
                    return ($user); 
                }

            }
        }  
    }

    public function FMOthers(User $user)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '21') 
                {
                    return ($user); 
                }

            }
        }  
    }

    public function FMBudget(User $user)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '16') 
                {
                    return ($user); 
                }

            }
        }  
    }

    public function FMAccounting(User $user)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '15') 
                {
                    return ($user); 
                }

            }
        }  
    }

    public function FMCashier(User $user)
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

    public function FMRecords(User $user)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '13') 
                {
                    return ($user); 
                }

            }
        }  
    }

    public function FMSignatory(User $user)
    {
        $Roles = UserRole::where('userid','=',$user->id)->get();

      
        if(!empty($Roles))
        {
          foreach ($Roles as $Role)
            {
                if ($Role->roleid == '18' || $Role->roleid == '19' || $Role->roleid == '20') 
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
     * @param  \App\Models\FinancialManagement  $financialManagement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, FinancialManagement $financialManagement)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FinancialManagement  $financialManagement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, FinancialManagement $financialManagement)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FinancialManagement  $financialManagement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, FinancialManagement $financialManagement)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FinancialManagement  $financialManagement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, FinancialManagement $financialManagement)
    {
        //
    }

    public function deleteORS(User $user, FinancialManagement $Voucher) {
        $Routeclose = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->where('is_active','=',false)->get()->last();

      
        if(empty($Routeclose))
        {
            $Route = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->where('action','=','FORWARD TO')->get()->last();

            if(!empty($Route))
            {
                $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
            
                if($Route->unitid == $employee->unitid)
                {
                    return ($user);
                    // return  auth()->check() && auth()->user()->id == $fMORS->userid;
                }
            }
    
        }
    }

    public function deleteDV(User $user, FinancialManagement $Voucher) {
        $Routeclose = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->where('is_active','=',false)->get()->last();

      
        if(empty($Routeclose))
        {
            $Route = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->where('action','=','FORWARD TO')->get()->last();

            if(!empty($Route))
            {
                $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
            
                if($Route->unitid == $employee->unitid)
                {
                    return ($user);
                    // return  auth()->check() && auth()->user()->id == $fMORS->userid;
                }
            }
    
        }
    }

    public function acceptIncoming(User $user, FinancialManagement $Voucher)   
    
    {

        $Routeclose = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->where('is_active','=',false)->get()->last();

      
        if(empty($Routeclose))
        {
            $Route = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->where('action','=','FORWARD TO')->orwhere('action', 'FOR LDDAP / ADA SIGNATURE')->get()->last();

            if(!empty($Route))
            {
                $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
            
                if($Route->unitid == $employee->unitid)
                {
                    return ($user);
                }
            }
          
         
    
        }

    }

    public function addBudgetAction(User $user, FinancialManagement $Voucher)   
    
    {

        $Routeclose = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->where('is_active','=',false)->get()->last();

        
        if(empty($Routeclose))
        {
            $Route = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->where('is_active','=',true)->get()->last();

            if(!empty($Route))
            {
                $employee = Employee::where('email','=',auth()->user()->email)->get()->first();               

                if($Route->unitid == $employee->unitid && $Route->is_active == true && $Route->is_accepted == true)
                {
                    $Roles = UserRole::where('userid','=',$user->id)->get();

      
                    if(!empty($Roles))
                    {
                      foreach ($Roles as $Role)
                        {
                            if ($Role->roleid == '16') 
                            {
                                return ($user); 
                            }
            
                        }
                    }  
                }
            }
        }
    }

    public function addAccountingAction(User $user, FinancialManagement $Voucher)   
    
    {

        $Routeclose = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->where('is_active','=',false)->get()->last();

        
        if(empty($Routeclose))
        {
            $Route = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->where('is_active','=',true)->get()->last();

            if(!empty($Route))
            {
                $employee = Employee::where('email','=',auth()->user()->email)->get()->first();               

                if($Route->unitid == $employee->unitid && $Route->is_active == true && $Route->is_accepted == true)
                {
                    $Roles = UserRole::where('userid','=',$user->id)->get();

      
                    if(!empty($Roles))
                    {
                      foreach ($Roles as $Role)
                        {
                            if ($Role->roleid == '15') 
                            {
                                return ($user); 
                            }
            
                        }
                    }  
                }
            }
        }
    }

    public function addCashierAction(User $user, FinancialManagement $Voucher)   
    
    {

        $Routeclose = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->where('is_active','=',false)->get()->last();

        
        if(empty($Routeclose))
        {
            $Route = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->where('is_active','=',true)->get()->last();

            if(!empty($Route))
            {
                $employee = Employee::where('email','=',auth()->user()->email)->get()->first();               

                if($Route->unitid == $employee->unitid && $Route->is_active == true && $Route->is_accepted == true)
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
            }
        }
    }

    public function addPlanningAction(User $user, FinancialManagement $Voucher)   
    
    {

        $Routeclose = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->where('is_active','=',false)->get()->last();

        
        if(empty($Routeclose))
        {
            $Route = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->where('is_active','=',true)->get()->last();

            if(!empty($Route))
            {
                $employee = Employee::where('email','=',auth()->user()->email)->get()->first();               

                if($Route->unitid == $employee->unitid && $Route->is_active == true && $Route->is_accepted == true)
                {
                    $Roles = UserRole::where('userid','=',$user->id)->get();

      
                    if(!empty($Roles))
                    {
                      foreach ($Roles as $Role)
                        {
                            if ($Role->roleid == '14') 
                            {
                                return ($user); 
                            }
            
                        }
                    }  
                }
            }
        }
    }

    public function printdv(User $user, FinancialManagement $Voucher)
    {
        $Routeclose = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->where('is_active','=',false)->get()->last();

      
        if(empty($Routeclose))
        {

        $Route = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->where('is_active','=',true)->where('action','!=','ATTACHED A FILE')->get()->last();

           
            $Routeclose = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->orderby('created_at','desc')->get();
           
            if(!empty($Routeclose))
            {
                foreach($Routeclose as $Route)
                {
                    if ($Route->is_accepted == true || $Route->is_forwarded == true || $Route->is_rejected == true )
                    {
                        break;
                    }
                    else
                    {
                        if( $Route->action == 'VOUCHER CREATED')
                        {

                            if($Route->userid == $user->id)
                            {
                              return ($user); 
                            }
         
                        }

                    }

                }
            } 
        }
    }

    public function addRoute(User $user, FinancialManagement $Voucher)

    {
        
        $Routeclose = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->where('is_active','=',false)->get()->last();

      
        if(empty($Routeclose))
        {

        $Route = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->where('is_active','=',true)->where('action','!=','ATTACHED A FILE')->get()->last();

            if(!empty($Route))
            {
                $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
            
                if($Route->unitid == $employee->unitid && $Route->is_active == true)
                {
                    if( $Route->is_rejected == true || $Route->is_accepted == true) 
                    {
                        return ($user);
                    }
            
                
                }    
            }
            $Routeclose = FinancialManagementRoute::where('sequenceid','=',$Voucher->sequenceid)->orderby('created_at','desc')->get();
           
            if(!empty($Routeclose))
            {
                foreach($Routeclose as $Route)
                {
                    if ($Route->is_accepted == true || $Route->is_forwarded == true || $Route->is_rejected == true )
                    {
                        break;
                    }
                    else
                    {
                        if( $Route->action == 'VOUCHER CREATED')
                        {

                            if($Route->userid == $user->id)
                            {
                              return ($user); 
                            }
         
                        }

                    }

                }
            } 
        }
    }
}

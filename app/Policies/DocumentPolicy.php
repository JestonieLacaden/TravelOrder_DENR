<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Route;
use App\Models\Document;
use App\Models\Employee;
use App\Models\UserRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
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
        return (auth()->user());
    }

    /**
     * Determine whether the user can view the model.
     *  
     * @param  \App\Models\User  $user
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user,Document $Document)
    {
        return (auth()->user());

        // $Employee  = Employee::where('email','=', auth()->user()->email);
        // dd($Employee);
        // $DocumentRoute = Route::where('documentid','=', $document)->where('action','=','FORWARD TO')->orderby('created_at', 'desc')->get()->first();
     
     
        // if($Employee->unitid)
        // {
        //     return;
        // }
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
                if ($Role->roleid == '9' || $Role->roleid == '10') 
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
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Document $document)
    {
       
        $Routeclose = Route::where('documentid','=',$document->PDN)->orderby('created_at','desc')->get();
           
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
                      
                            $Roles = UserRole::where('userid','=',$user->id)->get();
                            if(!empty($Roles))
                            {
                            foreach ($Roles as $Role)
                                {
                                    if (  $Role->roleid == '9' || $Role->roleid == '10')
                                    {
                                        return ($user); 
                                    }
                
                                }
                            }  
                      

                    }

                }
            } 
       
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Document $document)
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

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Document $document)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Document $document)
    {
        //
    }

    public function print(User $user, Document $document)
    {

        return (auth()->user());
  
    }

    public function addAttachment(User $user, Document $document)

    {
            $Routeclose = Route::where('documentid','=',$document->PDN)->where('is_active','=',false)->get()->last();

        
            if(empty($Routeclose))
            {
            $Route = Route::where('documentid','=',$document->PDN)->where('is_active','=',true)->where('action','!=','ATTACHED A FILE')->get()->last();

            if(!empty($Route))
            {
                $employee = Employee::where('email','=',auth()->user()->email)->get()->first();               

                if($Route->unitid == $employee->unitid && $Route->is_active == true && $Route->is_accepted == true)
                {
                    return ($user);
                }
            }

            $Routeclose = Route::where('documentid','=',$document->PDN)->orderby('created_at','desc')->get();
           
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
                        if( $Route->action == 'DOCUMENT CREATED')
                        {
                            $Roles = UserRole::where('userid','=',$user->id)->get();
                            if(!empty($Roles))
                            {
                            foreach ($Roles as $Role)
                                {
                                    if (  $Role->roleid == '9' || $Role->roleid == '10')
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
    }

    public function addRoute(User $user, Document $document)

    {
        
        $Routeclose = Route::where('documentid','=',$document->PDN)->where('is_active','=',false)->get()->last();

      
        if(empty($Routeclose))
        {

        $Route = Route::where('documentid','=',$document->PDN)->where('is_active','=',true)->where('action','!=','ATTACHED A FILE')->get()->last();

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

            $Routeclose = Route::where('documentid','=',$document->PDN)->orderby('created_at','desc')->get();
           
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
                        if( $Route->action == 'DOCUMENT CREATED')
                        {
                            $Roles = UserRole::where('userid','=',$user->id)->get();
                            if(!empty($Roles))
                            {
                            foreach ($Roles as $Role)
                                {
                                    if ($Role->roleid == '9' || $Role->roleid == '10')
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

        $Roles = UserRole::where('userid','=',$user->id)->get();
        if(!empty($Roles))
        {
        foreach ($Roles as $Role)
            {
                if (  $Role->roleid == '1')
                {
                    return ($user); 
                }

            }
        }  
    }

    public function addAction(User $user, Document $document)
    {

        $Routeclose = Route::where('documentid','=',$document->PDN)->where('is_active','=',false)->get()->last();

      
        if(empty($Routeclose))
        {

        $Route = Route::where('documentid','=',$document->PDN)->where('is_active','=',true)->where('action','!=','ATTACHED A FILE')->get()->last();

            if(!empty($Route))
            {
                $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
            
                if($Route->unitid == $employee->unitid && $Route->is_active == true && $Route->is_accepted == true)
                {
                    return ($user);
                }    
            }
        }
    
        // $Routeclose = Route::where('documentid','=',$document->id)->where('is_active','=',true)->get()->last();

      
        //     if(empty($Routeclose))
        //     {
        //         $Route = Route::where('documentid','=',$document->id)->where('is_accepted','=',true)->get()->last();

        //         if(!empty($Route))
        //         {
        //             $employee = Employee::where('email','=',auth()->user()->email)->get()->first();
                
        //             if($Route->unitid == $employee->unitid &&  $Route->is_active == true && $Route->is_accepted == true)
        //             {
        //                 return ($user);
        //             }
        //         }
             
        
        //     }
    }

    public function acceptIncoming(User $user, Document $document)   
    
    {

        $Routeclose = Route::where('documentid','=',$document->PDN)->where('is_active','=',false)->get()->last();

      
        if(empty($Routeclose))
        {
            $Route = Route::where('documentid','=',$document->PDN)->where('action','=','FORWARD TO')->get()->last();

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

    public function rejectIncoming(User $user, Document $document)   
    
    {

        $Routeclose = Route::where('documentid','=',$document->PDN)->where('is_active','=',false)->get()->last();

      
        if(empty($Routeclose))
        {
            $Route = Route::where('documentid','=',$document->PDN)->where('action','=','FORWARD TO')->get()->last();

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
 

}

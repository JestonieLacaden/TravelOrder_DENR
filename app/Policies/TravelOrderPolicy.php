<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Employee;
use App\Models\UserRole;
use App\Models\TravelOrder;
use App\Models\TravelOrderSignatory;
use App\Models\SetTravelOrderSignatory;
use Illuminate\Auth\Access\HandlesAuthorization;

class TravelOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    { {
            $TravelOrderSignatories = TravelOrderSignatory::get();
            $Employee = Employee::where('email', '=', auth()->user()->email)->get()->first();



            if (!empty($TravelOrderSignatories)) {
                foreach ($TravelOrderSignatories as $TravelOrderSignatory) {
                    if ($TravelOrderSignatory->approver1 == $Employee->id || $TravelOrderSignatory->approver2 == $Employee->id || $TravelOrderSignatory->approver3 == $Employee->id) {
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
     * @param  \App\Models\TravelOrder  $travelOrder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TravelOrder $travelOrder)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */

    public function acceptemployee(User $user)
    {
        $emp = \App\Models\Employee::where('email', $user->email)->first();
        if (!$emp) return false;

        // user is an approver in any travel-order signatory
        return \App\Models\TravelOrderSignatory::where('approver1', $emp->id)
            ->orWhere('approver2', $emp->id)
            ->orWhere('approver3', $emp->id)
            ->exists();
    }


    public function create(User $user)
    {
        $Roles = UserRole::where('userid', '=', $user->id)->get();


        if (!empty($Roles)) {
            foreach ($Roles as $Role) {
                if ($Role->roleid == '1' || $Role->roleid == '5') {
                    return ($user);
                }
            }
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TravelOrder  $travelOrder
     * @return \Illuminate\Auth\Access\Response|bool
     */

    public function update(User $user, TravelOrder $travelOrder)
    {
        // Block if already acted on
        if ($travelOrder->is_approve1 || $travelOrder->is_rejected1) {
            return false;
        }

        // Optional: Admin roles (1 or 5) can update before first approval
        $roles = \App\Models\UserRole::where('userid', $user->id)->get();
        foreach ($roles as $role) {
            if ($role->roleid == '1' || $role->roleid == '5') {
                return ($user);
            }
        }

        // Allow approver1 to update before first approval/rejection
        $approverEmp = \App\Models\Employee::where('email', $user->email)->first();
        if (!$approverEmp) return false;

        $reqEmp = \App\Models\Employee::find($travelOrder->employeeid);
        if (!$reqEmp) return false;

        $set = \App\Models\SetTravelOrderSignatory::where('sectionid', $reqEmp->sectionid)->first();
        if (!$set) return false;

        $sig = \App\Models\TravelOrderSignatory::find($set->travelordersignatoryid);
        if ($sig && $sig->approver1 == $approverEmp->id) {
            return ($user);
        }

        return false;
    }

    public function updateFinal(\App\Models\User $user, \App\Models\TravelOrder $travelOrder)
    {
        // must be approved by approver1, not yet by approver2, and not rejected
        if ($travelOrder->is_approve1 !== true || $travelOrder->is_approve2 === true) return false;
        if ($travelOrder->is_rejected1 || $travelOrder->is_rejected2) return false;

        $approverEmp = \App\Models\Employee::where('email', $user->email)->first();
        $reqEmp      = \App\Models\Employee::find($travelOrder->employeeid);
        if (!$approverEmp || !$reqEmp) return false;

        $set = \App\Models\SetTravelOrderSignatory::where('sectionid', $reqEmp->sectionid)->first();
        $sig = $set ? \App\Models\TravelOrderSignatory::find($set->travelordersignatoryid) : null;

        // only the actual Approver 2
        return ($sig && $sig->approver2 == $approverEmp->id);
    }




    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TravelOrder  $travelOrder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TravelOrder $travelOrder)
    {

        $Roles = UserRole::where('userid', '=', $user->id)->get();


        if (!empty($Roles)) {
            if ($travelOrder->is_approve1 == false && $travelOrder->is_rejected1 == false) {
                foreach ($Roles as $Role) {
                    if ($Role->roleid == '1' || $Role->roleid == '5') {
                        return ($user);
                    }
                }
            }

            $Employee = Employee::where('email', '=', auth()->user()->email)->get()->first();


            if (!empty($Employee)) {
                if ($travelOrder->employeeid == $Employee->id && $travelOrder->userid == auth()->user()->id && $travelOrder->is_approve1 == false && $travelOrder->is_rejected1 == false) {
                    return ($user);
                }
            }
        }
    }


    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TravelOrder  $travelOrder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, TravelOrder $travelOrder)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TravelOrder  $travelOrder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, TravelOrder $travelOrder)
    {
        //
    }

    public function print(User $user, TravelOrder $travelOrder)
    {
        $Roles = UserRole::where('userid', '=', $user->id)->get();

        if (!empty($Roles)) { {
                foreach ($Roles as $Role) {
                    if ($Role->roleid == '1' || $Role->roleid == '5') {
                        if ($travelOrder->is_rejected1 != true && $travelOrder->is_rejected2 != true && $travelOrder->is_rejected3 != true && $travelOrder->is_approve1 == true && $travelOrder->is_approve2 == true && $travelOrder->is_approve3 == true) {
                            return ($user);
                        }
                    }
                }
            }
        }


        $Employee = Employee::where('email', '=', auth()->user()->email)->get()->first();


        if (!empty($Employee)) {
            if ($travelOrder->employeeid == $Employee->id || $travelOrder->userid == auth()->user()->id) {
                if ($travelOrder->is_rejected1 != true && $travelOrder->is_rejected2 != true && $travelOrder->is_rejected3 != true && $travelOrder->is_approve1 == true && $travelOrder->is_approve2 == true && $travelOrder->is_approve3 == true) {
                    return ($user);
                }
            }
        }
    }

    public function acceptrequest(User $user)
    {
        $Employee = Employee::where('email', '=', auth()->user()->email)->first();
        if (!$Employee) return false;

        // ✅ Option 2: Check if user is Section Chief, Division Chief, or PENRO

        // Check if Section Chief in section_chief table
        $isSectionChief = \App\Models\SectionChief::where('employeeid', $Employee->id)->exists();
        if ($isSectionChief) {
            return true;
        }

        // Check if approver in any existing signatory (for Division Chiefs & PENRO)
        $isApprover = TravelOrderSignatory::where('approver1', $Employee->id)
            ->orWhere('approver2', $Employee->id)
            ->orWhere('approver3', $Employee->id)
            ->exists();

        if ($isApprover) {
            return true;
        }

        return false;
    }

    public function accept(User $user, TravelOrder $TravelOrder)
    {
        $ApproverEmployee = Employee::where('email', '=', auth()->user()->email)->get()->first();
        if (!$ApproverEmployee) return false;

        // ✅ Option 2: Use the TO's signatory directly
        $TravelOrderSignatory = TravelOrderSignatory::find($TravelOrder->travelordersignatoryid);
        if (!$TravelOrderSignatory) return false;

        // Check if current user is approver1 (Section Chief)
        if ($TravelOrderSignatory->approver1 == $ApproverEmployee->id) {
            if ($TravelOrder->is_rejected1 == false && $TravelOrder->is_approve1 == false) {
                return ($user);
            }
        }

        // Check if current user is approver2 (Division Chief)
        if ($TravelOrderSignatory->approver2 == $ApproverEmployee->id) {
            if ($TravelOrder->is_rejected2 == false && $TravelOrder->is_rejected1 == false  && $TravelOrder->is_approve2 == false && $TravelOrder->is_approve1 == true) {
                return ($user);
            }
        }

        // Check if current user is approver3 (PENRO)
        if ($TravelOrderSignatory->approver3 == $ApproverEmployee->id) {
            if ($TravelOrder->is_rejected3 == false && $TravelOrder->is_rejected2 == false && $TravelOrder->is_rejected1 == false && $TravelOrder->is_approve3 == false && $TravelOrder->is_approve2 == true && $TravelOrder->is_approve1 == true) {
                return ($user);
            }
        }

        return false;
    }

    public function reject(User $user, TravelOrder $TravelOrder)
    {
        $ApproverEmployee = Employee::where('email', '=', auth()->user()->email)->get()->first();
        if (!$ApproverEmployee) return false;

        // ✅ Option 2: Use the TO's signatory directly
        $TravelOrderSignatory = TravelOrderSignatory::find($TravelOrder->travelordersignatoryid);
        if (!$TravelOrderSignatory) return false;

        // Check if current user is approver1 (Section Chief)
        if ($TravelOrderSignatory->approver1 == $ApproverEmployee->id) {
            if ($TravelOrder->is_rejected1 == false && $TravelOrder->is_approve1 == false) {
                return ($user);
            }
        }

        // Check if current user is approver2 (Division Chief)
        if ($TravelOrderSignatory->approver2 == $ApproverEmployee->id) {
            if ($TravelOrder->is_rejected2 == false && $TravelOrder->is_rejected1 == false && $TravelOrder->is_approve2 == false && $TravelOrder->is_approve1 == true) {
                return ($user);
            }
        }

        // Check if current user is approver3 (PENRO)
        if ($TravelOrderSignatory->approver3 == $ApproverEmployee->id) {
            if ($TravelOrder->is_rejected3 == false && $TravelOrder->is_rejected2 == false && $TravelOrder->is_rejected1 == false && $TravelOrder->is_approve3 == false && $TravelOrder->is_approve2 == true && $TravelOrder->is_approve1 == true) {
                return ($user);
            }
        }

        return false;
    }

    public function viewTravelOrderIndex(User $user)

    {
        $Roles = UserRole::where('userid', '=', $user->id)->get();


        if (!empty($Roles)) {
            foreach ($Roles as $Role) {
                if ($Role->roleid != '1' && auth()->check()) {

                    $Employee = Employee::where('email', '=', auth()->user()->email)->get()->first();


                    if (!empty($Employee)) {

                        return ($user);
                    }
                }
            }
        }
    }

    public function AddUserTravelOrder(User $user)

    { {
            $Employee = Employee::where('email', '=', auth()->user()->email)->get()->first();


            if (!empty($Employee)) {

                return ($user);
            }
        }
    }

    public function MsdCreate(User $user)
    {
        $Roles = UserRole::where('userid', '=', $user->id)->get();


        if (!empty($Roles)) {
            foreach ($Roles as $Role) {
                if ($Role->roleid == '1' || $Role->roleid == '5') {
                    return ($user);
                }
            }
        }
    }
}

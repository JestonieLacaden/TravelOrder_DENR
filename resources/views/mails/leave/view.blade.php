<div class="modal fade" id="view-leave-modal-lg{{ $Leave->id }}" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Leave Credits</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

          <div class="modal-body">
            <div class="mb-3 form row">
                <div class="col-sm-8">
                    <span class="text-bold">Employee Name:</span>  {{ $Leave->employee->firstname . ' ' . $Leave->employee->lastname}}
                </div>
            
                 <div class="col-sm-2 text-right">
                    Year :
                 </div>
                 <div class="col-sm-2">
                    <select name="year" id="year" class="select2 " style="width: 100%;">
                    <option value="{{ $Year['1']}}">{{ $Year['1']}}</option>
                    </select>
                 </div>          
                </div>

            <div class="table-responsive mailbox-messages">
                <table  id="example1" class="table table-hover table-striped">
                    <thead>
                        <td class="text-center"> Leave Type </td>
                        <td class="text-center"> Available</td>
                        <td class="text-center"> Used</td>
                        <td class="text-center"> Remaining</td>
                    </thead>
                  <tbody>
                  @foreach($Employees as $Employee)
                    @if($Leave->employeeid == $Employee->id)
                      @if(!empty($EmployeeLeaveCount))
                        @foreach($EmployeeLeaveCount as $EmployeeLeave)
                            @foreach($Leave_types as $Leave_type)   
                                @if($EmployeeLeave['0'] == $Employee->id)
                                  @if($EmployeeLeave['1'] == $Leave_type->id && $Year['1'] == $EmployeeLeave['3'])
                                    <tr>
                                      <td>{{ $Leave_type->leave_type}}</td>
                                      <td class="text-center"> {{$Leave_type->available}}</td>
                                      <td  class="text-center"> {{ $EmployeeLeave['2'] }}</td>
                                      <td  class="text-center"> {{$Leave_type->available - $EmployeeLeave['2'] }}</td>               
                                    </tr>                        
                                  @endif
                                @endif                   
                              @endforeach
                          @endforeach
                        @endif
                    @endif
                  @endforeach
                </tbody>
              </table>
                <!-- /.table -->
            </div>  
        </div>
     </div>
  </div>
</div>
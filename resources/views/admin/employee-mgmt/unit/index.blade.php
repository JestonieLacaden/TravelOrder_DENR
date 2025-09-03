@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Unit Management</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item active">Data Management</li>
              <li class="breadcrumb-item active">Employee</li>
              <li class="breadcrumb-item active">Unit Management</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


   <!-- Main content -->
   <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          

          <div class="card">
            <div class="card-header">
            @can('create', \App\Models\Unit::class)
              <button type="button" class="btn btn-default" data-toggle="modal" data-target="#new-unit-modal-lg"  data-backdrop="static" data-keyboard="false">
                {{ __ ('New')}}
              </button>
            @endcan
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                 <thead>
                <tr>
                    <th style="width: 120px" class="text-center">Unit ID</th>
                    <th class="text-center">Unit Name</th>
                    <th class="text-center">Office Name</th>
                    <th class="text-center">Section Name</th>
                    <th style="width: 120px" class="text-center">Date Created</th>
                    <th style="width: 150px" class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($Units as $Unit)
                    <tr>
                      <td class="text-center">{{$Unit->id}}</td>
                      <td>{{ $Unit->unit }}</td>
                      <td>{{ $Unit->office->office  }}</td>
                      <td>{{ $Unit->section->section  }}</td>
                      <td>{{ $Unit->created_at }}</td>
                      <td class="text-center">
                          @can('update', $Unit)
                              <button type="button" class="btn btn-default" title="Edit" style="background-color: #ff851b" data-toggle="modal"  data-target="#edit-unit-modal-lg{{ $Unit->id }} " data-backdrop="static" data-keyboard="false">
                              <i class="fas fa-edit" style="color:white"></i>
                              </button>
                          @endcan
                          @can('delete', $Unit)
                              <button type="button" class="btn btn-default" title="Delete" style="background-color: #d81b60" data-toggle="modal"  data-target="#delete-unit-modal-lg{{ $Unit->id }} " data-backdrop="static" data-keyboard="false">
                                <i class="fas fa-trash-alt" style="color:white"></i>
                              </button>
                          @endcan
                      </td>
                    </tr>
                     @include('admin.employee-mgmt.unit.edit')
                    @include('admin.employee-mgmt.unit.delete')
                    @endforeach 
                </tbody>
                
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
  
@include('admin.employee-mgmt.unit.create')

@endsection

@section('specific-scipt')

          <!-- DataTables  & Plugins -->
          <script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}"></script>
          <script src="{{ asset('/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
          <script src="{{ asset('/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
          <script src="{{ asset('/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
          <script src="{{ asset('/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
          <script src="{{ asset('/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
          <script src="{{ asset('/plugins/jszip/jszip.min.js') }}"></script>
          <script src="{{ asset('/plugins/pdfmake/pdfmake.min.js') }}"></script>
          <script src="{{ asset('/plugins/pdfmake/vfs_fonts.js') }}"></script>
          <script src="{{ asset('/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
          <script src="{{ asset('/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
          <script src="{{ asset('/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
     

          <!-- Page specific script -->
          <script>
            $(function () {
              $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
              }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
              $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
              });
            });
          </script>


          
          
   
   {{-- <script>
    $(document).ready(function(){
      $('#officeid').change(function() {
        var $section = $('#sectionid');

        $.ajax({
            url: "{{ route('unit.getSection')}}",
            data: {
              officeid: $(this).val()
            },
            success:function (data) {
              $section.html('<option value="" selected> --Select Section--</option>');
              $.each(data, function (id,value) {
                $section.append('<option value="' + id + '">' + value + '</option>');
            });
             
            }
        });
        $('#sectionid').val("")
   });
     
  
      // $(document).on('change','.productname',function () {
      //   var prod_id=$(this).val();
  
      //   var a=$(this).parent();
      //   console.log(prod_id);
      //   var op="";
      //   $.ajax({
      //     type:'get',
      //     url:'{!!URL::to('findPrice')!!}',
      //     data:{'id':prod_id},
      //     dataType:'json',//return data will be json
      //     success:function(data){
      //       console.log("price");
      //       console.log(data.price);
  
      //       // here price is coloumn name in products table data.coln name
  
      //       a.find('.prod_price').val(data.price);
  
      //     },
      //     error:function(){
  
      //     }
      //   });
  
  
      // });
  
    });
  </script> --}}
  
       
          {{-- {{$error = Session::get('error') }}
          
          @if(!empty($error) )
            <script>
              $(function() {
                  $('#new-section-modal-lg').modal('show');
              });
            </script>
          @endif --}}
          
@include('partials.flashmessage')
@endsection

@section('specific-layout')
 <!-- DataTables -->
 <link rel="stylesheet" href="{{asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
 <link rel="stylesheet" href="{{asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
 <link rel="stylesheet" href="{{asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">  
@endsection



      
     
  


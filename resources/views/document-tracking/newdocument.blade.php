@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">New Document</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item active">New Document</li>
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

          @if ($errors->any())
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Failed to save!</h5>
            <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
          </div>
          @endif
          @if(session()->has('message'))
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i>    {{ session()->get('message') }}</h5>
            <a href="{{ route('document-tracking.viewdocument') }}"><span class="text-"></span class="pl-2"> View Document</a>
          </div>
          @endif

          <div class="card">
        
          
   
           
            <!-- /.card-header -->
            <div class="card-body">
                <form method="POST" action="{{ route('document-tracking.store') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-2" for="is_urgent">Urgent :<span class="text-danger">*</span></label>
                            <div class="col-sm-10" >
                              <select  id="is_urgent"  name="is_urgent" class="form-control select2" style="width: 100%;">
                              
                                <option value = "0" selected>NO</option>
                                <option value = "1">YES</option>
                            </select>
                            @error('is_urgent')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            </div>                          
                          </div>
                       
                          <div class="form-group row">
                            <label class="col-sm-2" for="datereceived">Date Received :<span class="text-danger">*</span></label>
                            <div class=" col-sm-10">
                              <input name="datereceived" id="datereceived" class="form-control" type="date"  value={{ now() }} oninput="this.value = this.value.toUpperCase()">
                              @error('datereceived')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                              @enderror
        
                            </div>
                          
                          </div>
                            
                            <div class="form-group row">
                                <label  class="col-sm-2" for="originatingoffice">Originating Office :<span class="text-danger">*</span></label>
                               <div class="col-sm-10">
                                <input name="originatingoffice" id="originatingoffice" class="form-control" type="text" placeholder="Enter Originating Office" value="{{old('originatingoffice')}}" oninput="this.value = this.value.toUpperCase()">
                                @error('originatingoffice')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                               </div>
                            
                            </div>   
                           
        
                            <div class="form-group row">
                              <label class="col-sm-2" for="sendername">Sender Name :<span class="text-danger">*</span></label>
                              <div class=" col-sm-10">
                              <input name="sendername" id="sendername" class="form-control" type="text" placeholder="Enter Sender Name" value="{{old('sendername')}}" oninput="this.value = this.value.toUpperCase()">
                              @error('sendername')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                              @enderror
                              </div>
                            </div>
        
                            <div class="form-group row">
                              <label  class="col-sm-2" for="senderaddress">Sender Address : <span class="text-danger">*</span></label>
                              <div class=" col-sm-10">
                              <input name="senderaddress" id="senderaddress" class="form-control" type="text" placeholder="Enter Sender Address" value="{{old('senderaddress')}}" oninput="this.value = this.value.toUpperCase()">
                              @error('senderaddress')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                              @enderror
                              </div>
                            </div>
        
        
                            <div class="form-group row" >
                              <label class="col-sm-2" for="addressee">Addressee :<span class="text-danger">*</span></label>
                              <div class=" col-sm-10">
                              <input name="addressee" id="addressee" class="form-control" type="text" placeholder="Enter Sender Address" value="PENRO" oninput="this.value = this.value.toUpperCase()">
                              @error('addressee')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                              @enderror
                              </div>
                            </div>
                          
                           
                           
        
        
                              <div class="form-group row ">
                                  <label class="col-sm-2"> Document Type :<span class="text-danger">*</span></label>
          
                                  <datalist id="mylist2">
                                      <option value="MEMORANDUM">
                                      <option value="LETTER">
                                      <option value="SPECIAL ORDER">
                                      <option value="REGIONAL SPECIAL ORDER">
                                      <option value="DENR SPECIAL ORDER">
                                      <option value="DENR MEMORANDUM CIRCULAR">
                                      <option value="FAX MESSAGE">
                                      <option value="ELECTRONIC MESSAGE FOR TRANSMISSION">
                          
                                  </datalist>
                                  <div class=" col-sm-10">
                                  <input
                                  type="text"
                                  class="form-control"
                                  name="doc_type"
                                  list="mylist2"
                                placeholder="- ADD OR SELECT DOCUMENT TYPE - "
                                oninput="this.value = this.value.toUpperCase()"
                                value="{{old('doc_type')}}"
                                  />
                                  @error('doc_type')
                                  <p class="text-danger text-xs mt-1">{{$message}}</p>
                                  @enderror
                              </div>
                              </div>
                             
        
                              <div class="form-group row">
                                <label  class="col-sm-2" for="subject">Subject :<span class="text-danger">*</span></label>
                                <div class=" col-sm-10">
                                  <textarea 
                                  placeholder="Enter Subject"
                                  class="form-control"
                                  name="subject"
                                  rows="1" oninput="this.value = this.value.toUpperCase()">{{old('subject')}}</textarea>
                                @error('subject')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                                </div>    
                              </div>             
                         
                        </div>
                        <!-- /.card-body -->
        
                        <div class="card-footer">
                          <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                        </div>
                      </form>
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
  

@endsection



      
     
  


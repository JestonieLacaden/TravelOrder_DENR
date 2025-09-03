@extends('layouts.app')

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Update Document</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('document-tracking.index') }}">Document Tracking System</a></li>
            <li class="breadcrumb-item active">Update Document</li>
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
                
              <form method="POST" action="{{ route('document-tracking.update', $Document ) }}" id="myForm" enctype="multipart/form-data">


                {{ csrf_field() }}
                @method('PUT')
                <div class="card-body">
              
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-2" for="is_urgent">Urgent</label>
                            <div class="col-sm-10" >
                              <select  id="is_urgent"  name="is_urgent" class="form-control select2" style="width: 100%;">
                              
                                @if ( $Document->is_urgent == 1 )
                                {
                                  <option value = "1" selected>YES</option>
                                  <option value = "0">NO</option>
                                }                                   
                                @else {
                                  <option value = "0" selected>NO</option>
                                  <option value = "1">YES</option>
                                }
                            
                                @endif
                               
                            </select>
                            @error('is_urgent')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            </div>
                           
                          
                          </div>
                       
                          <div class="form-group row">
                            <label class="col-sm-2" for="datereceived">Date Received</label>
                            <div class=" col-sm-10">
                              <input name="datereceived" id="datereceived" class="form-control" type="date"  value={{ $Document->datereceived }} oninput="this.value = this.value.toUpperCase()">
                              @error('datereceived')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                              @enderror
        
                            </div>
                          
                          </div>
                            
                            <div class="form-group row">
                                <label  class="col-sm-2" for="originatingoffice">Originating Office</label>
                               <div class="col-sm-10">
                                <input name="originatingoffice" id="originatingoffice" class="form-control" type="text" placeholder="Enter Originating Office" value="{{$Document->originatingoffice}}" oninput="this.value = this.value.toUpperCase()">
                                @error('originatingoffice')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                               </div>
                            
                            </div>   
                           
        
                            <div class="form-group row">
                              <label class="col-sm-2" for="sendername">Sender Name</label>
                              <div class=" col-sm-10">
                              <input name="sendername" id="sendername" class="form-control" type="text" placeholder="Enter Sender Name" value="{{$Document->sendername}}"  oninput="this.value = this.value.toUpperCase()">
                              @error('sendername')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                              @enderror
                              </div>
                            </div>
        
                            <div class="form-group row">
                              <label  class="col-sm-2" for="senderaddress">Sender Address</label>
                              <div class=" col-sm-10">
                              <input name="senderaddress" id="senderaddress" class="form-control" type="text" placeholder="Enter Sender Address" value="{{$Document->senderaddress}}" oninput="this.value = this.value.toUpperCase()">
                              @error('senderaddress')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                              @enderror
                              </div>
                            </div>
        
        
                            <div class="form-group row" >
                              <label class="col-sm-2" for="addressee">Addressee</label>
                              <div class=" col-sm-10">
                              <input name="addressee" id="addressee" class="form-control" type="text" placeholder="Enter Sender Address" value="{{$Document->addressee}}"  oninput="this.value = this.value.toUpperCase()">
                              @error('addressee')
                              <p class="text-danger text-xs mt-1">{{$message}}</p>
                              @enderror
                              </div>
                            </div>
                          
                           
                           
        
        
                              <div class="form-group row ">
                                  <label class="col-sm-2"> Document Type</label>
          
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
                                value="{{$Document->doc_type}}" 
                                  />
                                  @error('doc_type')
                                  <p class="text-danger text-xs mt-1">{{$message}}</p>
                                  @enderror
                              </div>
                              </div>
        
                              <div class="form-group row">
                                <label  class="col-sm-2" for="subject">Subject</label>
                                <div class=" col-sm-10">
                                <textarea
                                    class="form-control"
                                    name="subject"
                                    rows="1"
                                    placeholder="Enter Subject"
                                    oninput="this.value = this.value.toUpperCase()"
                                  > {{$Document->subject}}
                                </textarea>
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
          </div>
        </div>
      </div>
    </div>
   </section>
</div>
@endsection
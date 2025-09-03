
<div class="modal fade" id="new-document-modal-xl" >
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Document</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <div class="modal-body">
        
        
        <!-- Main content -->

        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <!-- left column -->
              <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Document Information</h3>
                
                  </div>
                  
                  <!-- /.card-header -->
                  <!-- form start -->

       
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
              </div>
            </div>
          </div>
        </section>
          </div>
      </div>
    </div>
</div>

@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">New Employee</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">MSD Panel</li>
                        <li class="breadcrumb-item"><a href="{{ route('employee.index')}}">Employee Management</a></li>
                        <li class="breadcrumb-item active">New </li>

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
                <!-- left column -->
                <div class="col-md m-auto">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Employee Information</h3>

                        </div>

                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ route('employee.store') }}" enctype="multipart/form-data">

                            {{ csrf_field() }}

                            <div class="card-body">

                                <div class="form-group row  mb-4 {{ $errors->get('employeeid') ? 'has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label" for="employeeid">Employee ID : <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input name="employeeid" class="form-control" type="text" maxlength="40" placeholder="Enter Employee ID" value=" {{old('employeeid')}} " oninput="this.value = this.value.toUpperCase()" required>
                                        @error('employeeid')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-4 ">
                                    <label class="col-sm-2 col-form-label" for="firstname">First Name :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input name="firstname" id="firstname" class="form-control" type="text" placeholder="Enter First Name" value="{{old('firstname')}}" oninput="this.value = this.value.toUpperCase()">
                                        @error('firstname')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-4 ">
                                    <label class="col-sm-2 col-form-label" for="middlename">Middle Name :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input name="middlename" class="form-control" type="text" placeholder="Enter Middle Name" value="{{old('middlename')}}" oninput="this.value = this.value.toUpperCase()">
                                        @error('middlename')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="lastname">Last Name :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input name="lastname" class="form-control" type="text" placeholder="Enter Last Name" value="{{old('lastname')}}" oninput="this.value = this.value.toUpperCase()">
                                        @error('lastname')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="birthdate">Birth Date :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input name="birthdate" class="form-control" min="1930-01-01" type="date" value="{{old('birthdate')}}">
                                        @error('birthdate')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="email">Email :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input name="email" class="form-control" type="text" placeholder="Enter Email Address" value="{{old('email')}}">
                                        @error('email')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>


                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="contactnumber">Contact Number :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input name="contactnumber" class="form-control" maxlength="11" type="text" placeholder="Enter Contact Number" value="{{old('contactnumber')}}">
                                        @error('contactnumber')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="address">Address :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <textarea name="address" class="form-control" rows="2" placeholder="Enter Address" oninput="this.value = this.value.toUpperCase()"> {{old('address')}} </textarea>
                                        @error('address')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="officesectionunit">Office / Section : <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <select name="officesectionunit" id="officesectionunit" class="form-control select2" style="width: 100%;">
                                            <option value="" selected>-- Choose office --</option>
                                            @foreach($Offices as $Office)
                                            <div>
                                                <optgroup label="{{$Office->office }}" class="bg-light text-center">
                                                    </option>
                                                    @foreach ($Sections as $Section)
                                                    @if ( $Section->officeid == $Office->id );
                                                <optgroup label="- {{$Section->section }}"><strong></strong></option>
                                                    @foreach ($Units as $Unit)
                                                    @if ( $Unit->sectionid == $Section->id );
                                                    <option value="{{$Office->id }},{{$Section->id }},{{$Unit->id}}" class="bg-light pl-4"> {{$Unit->unit }}</option>
                                                    @endif
                                                    @endforeach
                                                </optgroup>
                                                @endif
                                                @endforeach
                                                </optgroup>
                                            </div>
                                            @endforeach
                                        </select>
                                        @error('officesectionunit')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="position">Position :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input name="position" class="form-control" type="text" placeholder="Enter Position" value="{{old('position')}}" oninput="this.value = this.value.toUpperCase()">
                                        @error('position')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="datehired">Date Hired :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input name="datehired" class="form-control" type="date" value="{{old('datehired')}}">
                                        @error('datehired')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="picture"> Profile Picture : <span class="text-danger">*</span></label>
                                    <div class="input-group col-sm-10 ">
                                        <div class="custom-file">
                                            <input type="file" name="picture" class="custom-file-input" accept="image/x-png,image/jpg,image/jpeg">
                                            <label class="custom-file-label" for="picture" name="picture">Choose file</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 col-form-label" for="empstatus">Status :<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <select name="empstatus" class="form-control select2" style="width: 100%;" value="{{old('empstatus')}}">
                                            <option selected="selected">PERMANENT</option>
                                            <option>CONTRACTUAL</option>
                                        </select>
                                        @error('empstatus')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="signature"> Signature Upload : </label>
                                    <div class="input-group col-sm-10">
                                        <div class="custom-file">
                                            <input type="file" name="signature" class="custom-file-input" accept="image/x-png,image/jpg,image/jpeg">
                                            <label class="custom-file-label" for="signature">Choose signature file (Optional)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-10 offset-sm-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i> Upload a clear and transparent signature image (PNG, JPG, JPEG). This will be used in leave applications.
                                        </small>
                                    </div>
                                    @error('signature')
                                    <p class="text-danger text-xs mt-1 col-sm-10 offset-sm-2">{{$message}}</p>
                                    @enderror
                                </div>


                                {{-- <div class="form-group">
                      <label for="exampleInputFile">Profile Picture</label>
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="ProfilePicture">
                          <label class="custom-file-label" for="ProfilePicture">Choose file</label>
                        </div>
                        <div class="input-group-append">
                          <span class="input-group-text">Upload</span>
                        </div>
                      </div>
                    </div> --}}

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
</div>


@endsection


@section('page-script')
<!-- bs-custom-file-input -->
<script src="{{asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
    $(function() {
        bsCustomFileInput.init();
    });

</script>
@endsection

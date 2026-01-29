@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">My Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">My Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i> Failed to update!</h5>
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
                        <h5><i class="icon fas fa-check"></i> {{ session()->get('message') }}</h5>
                    </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Employee Information</h3>
                        </div>
                        <form method="POST" action="{{ route('user.profile.update-info') }}">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstname">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="firstname" name="firstname" value="{{ old('firstname', $employee->firstname) }}" required>
                                            @error('firstname')
                                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="middlename">Middle Name</label>
                                            <input type="text" class="form-control" id="middlename" name="middlename" value="{{ old('middlename', $employee->middlename) }}">
                                            @error('middlename')
                                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="lastname">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="lastname" name="lastname" value="{{ old('lastname', $employee->lastname) }}" required>
                                            @error('lastname')
                                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Position:</label>
                                            <p class="form-control-static">{{ $employee->position }}</p>
                                            <small class="text-muted">Position cannot be changed. Contact administrator for updates.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $employee->email) }}" required>
                                            @error('email')
                                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="contactnumber">Contact Number</label>
                                            <input type="text" class="form-control" id="contactnumber" name="contactnumber" value="{{ old('contactnumber', $employee->contactnumber) }}" placeholder="e.g., 09123456789">
                                            @error('contactnumber')
                                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Office:</label>
                                            <p class="form-control-static">{{ $employee->office->office ?? 'N/A' }}</p>
                                            <small class="text-muted">Office cannot be changed. Contact administrator for updates.</small>
                                        </div>
                                        <div class="form-group">
                                            <label>Unit:</label>
                                            <p class="form-control-static">{{ $employee->unit->unit ?? 'N/A' }}</p>
                                            <small class="text-muted">Unit cannot be changed. Contact administrator for updates.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Information
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Digital Signature</h3>
                        </div>
                        <form method="POST" action="{{ route('user.profile.update-signature') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="signature">Upload Signature <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="signature" name="signature" accept="image/*">
                                                    <label class="custom-file-label" for="signature">Choose file</label>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">
                                                Recommended: PNG or JPG image with transparent background. Max 2MB.
                                            </small>
                                            @error('signature')
                                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        @if(!empty($employee->signature_path))
                                        <div class="form-group">
                                            <label>Current Signature:</label>
                                            <div class="p-3 border rounded bg-light">
                                                @php
                                                $sigPath = ltrim(str_replace('\\', '/', $employee->signature_path), '/');
                                                $sigUrl = '';
                                                if (file_exists(public_path('storage/' . $sigPath))) {
                                                    $sigUrl = asset('storage/' . $sigPath);
                                                } elseif (Storage::disk('public')->exists($sigPath)) {
                                                    $fullPath = storage_path('app/public/' . $sigPath);
                                                    if (file_exists($fullPath)) {
                                                        $imageData = base64_encode(file_get_contents($fullPath));
                                                        $mimeType = mime_content_type($fullPath);
                                                        $sigUrl = 'data:' . $mimeType . ';base64,' . $imageData;
                                                    }
                                                }
                                                @endphp
                                                @if(!empty($sigUrl))
                                                <img src="{{ $sigUrl }}" alt="Current Signature" style="max-height: 100px; border: 1px solid #ddd; padding: 5px; background: white;">
                                                @else
                                                <p class="text-muted mb-0">No signature uploaded yet</p>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="col-md-6">
                                        <div class="alert alert-info">
                                            <h5><i class="icon fas fa-info"></i> Signature Guidelines</h5>
                                            <ul class="mb-0">
                                                <li>Upload a clear image of your signature</li>
                                                <li>Use PNG format with transparent background for best results</li>
                                                <li>Make sure the signature is legible</li>
                                                <li>This will be used on Leave Forms and Travel Orders</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Signature
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

@endsection

@section('specific-scipt')
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
$(function () {
    bsCustomFileInput.init();
});
</script>
@include('partials.flashmessage')
@endsection

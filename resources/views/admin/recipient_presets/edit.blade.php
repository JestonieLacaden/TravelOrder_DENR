@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Recipient Preset</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.recipient-presets.index') }}">Recipient Presets</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Recipient Preset</h3>
                </div>
                <form action="{{ route('admin.recipient-presets.update', $recipientPreset->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $recipientPreset->name) }}" placeholder="e.g., Juan Dela Cruz" required>
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Full name of the recipient</small>
                        </div>

                        <div class="form-group">
                            <label for="office">Office <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('office') is-invalid @enderror" id="office" name="office" value="{{ old('office', $recipientPreset->office) }}" placeholder="e.g., CENRO San Jose" required>
                            @error('office')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Office name or department</small>
                        </div>

                        <div class="form-group">
                            <label for="position">Position</label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position', $recipientPreset->position) }}" placeholder="e.g., OIC">
                            @error('position')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Position or designation (optional)</small>
                        </div>

                        <div class="form-group">
                            <label for="display_order">Display Order</label>
                            <input type="number" class="form-control @error('display_order') is-invalid @enderror" id="display_order" name="display_order" value="{{ old('display_order', $recipientPreset->display_order) }}" min="0">
                            @error('display_order')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Order in dropdown (lower numbers appear first)</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $recipientPreset->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                            <small class="form-text text-muted">Only active presets will appear in the dropdown</small>
                        </div>

                        <div class="alert alert-info">
                            <strong>Preview:</strong> This preset will display as:<br>
                            <span id="preview-text" class="text-monospace">
                                <span id="preview-name">-</span><br>
                                <span id="preview-office">-</span><span id="preview-position"></span>
                            </span>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Preset
                        </button>
                        <a href="{{ route('admin.recipient-presets.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        function updatePreview() {
            var name = $('#name').val().toUpperCase() || '-';
            var office = $('#office').val().toUpperCase() || '-';
            var position = $('#position').val().toUpperCase();

            $('#preview-name').text(name);
            $('#preview-office').text(office);

            if (position) {
                $('#preview-position').text(', ' + position);
            } else {
                $('#preview-position').text('');
            }
        }

        $('#name, #office, #position').on('input', updatePreview);
        updatePreview();
    });

</script>
@endpush
@endsection

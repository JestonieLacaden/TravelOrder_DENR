@extends('layouts.app')

@push('styles')
<!-- Select2 CSS with Bootstrap 4 theme (same as Role UI) -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    /* Preserve exact case for recipient text */
    #recipient_for,
    #recipient_to {
        text-transform: none !important;
    }

</style>
@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create New Memorandum</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('memorandums.index') }}">Memorandum</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Memorandum Details</h3>
                            <div class="card-tools">
                                <a href="{{ route('memorandums.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('memorandums.store') }}" method="POST" id="memorandumForm" enctype="multipart/form-data">
                                @csrf

                                <!-- Template Selection - Hidden but with active template value -->
                                <input type="hidden" name="template_id" value="{{ $activeTemplate->id }}">

                                <!-- Template Selection - COMMENTED OUT FOR NOW
                                <div class="form-group">
                                    <label for="template_id">Template Version <span class="text-danger">*</span></label>
                                    <select name="template_id" id="template_id" class="form-control @error('template_id') is-invalid @enderror" required>
                                        @foreach($templates as $template)
                                        <option value="{{ $template->id }}" {{ $template->is_active ? 'selected' : '' }}>
                                            {{ $template->name }} (v{{ $template->version }}) {{ $template->is_active ? '- ACTIVE' : '' }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('template_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                -->

                                <!-- Date -->
                                <div class="form-group">
                                    <label for="memorandum_date">Date</label>
                                    <input type="date" name="memorandum_date" id="memorandum_date" class="form-control @error('memorandum_date') is-invalid @enderror" value="{{ old('memorandum_date') }}">
                                    @error('memorandum_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Tracking Number -->
                                <div class="form-group">
                                    <label for="transaction_number">Tracking Number</label>
                                    <input type="text" name="transaction_number" id="transaction_number" class="form-control @error('transaction_number') is-invalid @enderror" value="{{ old('transaction_number') }}" placeholder="Enter tracking number (optional)">
                                    @error('transaction_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Recipient Type -->
                                <div class="form-group">
                                    <label for="recipient_type">Recipient Type <span class="text-danger">*</span></label>
                                    <select name="recipient_type" id="recipient_type" class="form-control @error('recipient_type') is-invalid @enderror" required>
                                        <option value="FOR" {{ old('recipient_type') === 'FOR' ? 'selected' : '' }}>FOR</option>
                                        <option value="TO" {{ old('recipient_type') === 'TO' ? 'selected' : '' }}>TO</option>
                                        <option value="BOTH" {{ old('recipient_type') === 'BOTH' ? 'selected' : '' }}>BOTH</option>
                                    </select>
                                    @error('recipient_type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Recipient FOR -->
                                <div class="form-group" id="recipient_for_group">
                                    <label for="recipient_for">FOR (Recipients)</label>

                                    <!-- Preset Dropdown -->
                                    <div class="mb-2">
                                        <select id="preset_for" class="form-control select2" multiple="multiple" data-placeholder="Select recipient presets or choose 'Custom' to type manually">
                                            <option value="custom">Custom / Others</option>
                                            @foreach($recipientPresets as $preset)
                                            <option value="{{ $preset->id }}" data-formatted="{{ $preset->formatted_text }}">
                                                {{ $preset->name }} - {{ $preset->office }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Select preset recipients or choose "Custom" to type manually</small>
                                    </div>

                                    <!-- Textarea -->
                                    <textarea name="recipient_for" id="recipient_for" class="form-control" rows="5" placeholder="Enter recipients..." style="display: none;"></textarea>
                                    <small class="form-text text-muted textarea-hint" style="display: none;">Type names, positions, or offices. Press Enter for new lines.</small>
                                </div>

                                <!-- Recipient TO -->
                                <div class="form-group" id="recipient_to_group" style="display: none;">
                                    <label for="recipient_to">TO (Recipients)</label>

                                    <!-- Preset Dropdown -->
                                    <div class="mb-2">
                                        <select id="preset_to" class="form-control select2" multiple="multiple" data-placeholder="Select recipient presets or choose 'Custom' to type manually">
                                            <option value="custom">Custom / Others</option>
                                            @foreach($recipientPresets as $preset)
                                            <option value="{{ $preset->id }}" data-formatted="{{ $preset->formatted_text }}">
                                                {{ $preset->name }} - {{ $preset->office }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Select preset recipients or choose "Custom" to type manually</small>
                                    </div>

                                    <!-- Textarea -->
                                    <textarea name="recipient_to" id="recipient_to" class="form-control" rows="5" placeholder="Enter recipients..." style="display: none;"></textarea>
                                    <small class="form-text text-muted textarea-hint" style="display: none;">Type names, positions, or offices. Press Enter for new lines.</small>
                                </div>

                                <!-- THRU -->
                                <div class="form-group">
                                    <label for="through">THRU (Optional)</label>
                                    <input type="text" name="through" id="through" class="form-control" value="{{ old('through') }}" placeholder="e.g. Office of the Director">
                                </div>

                                <!-- ATT'N -->
                                <div class="form-group">
                                    <label for="attn">ATT'N (Optional)</label>
                                    <input type="text" name="attn" id="attn" class="form-control" value="{{ old('attn') }}" placeholder="e.g. Designated Property/Supply Officer">
                                </div>

                                <!-- FROM Text -->
                                <div class="form-group">
                                    <label for="from_text">FROM <span class="text-danger">*</span></label>
                                    <input type="text" name="from_text" id="from_text" class="form-control @error('from_text') is-invalid @enderror" value="{{ old('from_text') }}" placeholder="e.g., THE {{ Auth::user()->Employee && Auth::user()->Employee->position ? strtoupper(Auth::user()->Employee->position) : 'POSITION' }}" required>
                                    @error('from_text')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Enter the FROM designation manually</small>
                                </div>

                                <!-- SUBJECT -->
                                <div class="form-group">
                                    <label for="subject">SUBJECT <span class="text-danger">*</span></label>
                                    <textarea name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" rows="2" placeholder="Enter subject or select from suggestions" required>{{ old('subject') }}</textarea>
                                    @error('subject')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <div id="subject_suggestions" class="mt-2"></div>
                                </div>

                                <!-- BODY -->
                                <div class="form-group">
                                    <label for="body">Body <span class="text-danger">*</span></label>
                                    <textarea name="body" id="body" rows="10" class="form-control @error('body') is-invalid @enderror" placeholder="Enter memorandum body. Each Enter key creates a new paragraph." required>{{ old('body') }}</textarea>
                                    @error('body')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Each Enter key creates a new justified paragraph with first-line indentation.</small>
                                </div>

                                <!-- Signature Name Override -->
                                <div class="form-group">
                                    <label for="from_name">Signature Name (Optional Override)</label>
                                    <input type="text" name="from_name" id="from_name" class="form-control" placeholder="Leave blank to use your full name: {{ Auth::user()->Employee->fullname ?? Auth::user()->username }}">
                                    <small class="form-text text-muted">Override the name shown below the signature</small>
                                </div>

                                <!-- Signature Position Override -->
                                <div class="form-group">
                                    <label for="from_position">Signature Position (Optional Override)</label>
                                    <input type="text" name="from_position" id="from_position" class="form-control" placeholder="Leave blank to use your position{{ Auth::user()->Employee && Auth::user()->Employee->position ? ': ' . Auth::user()->Employee->position : '' }}">
                                    <small class="form-text text-muted">Override the position shown below the signature</small>
                                </div>

                                <!-- Show Position Checkbox -->
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="show_position" name="show_position" value="1" {{ old('show_position') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="show_position">Show position below signature</label>
                                    </div>
                                    <small class="form-text text-muted">Uncheck to hide position in the document</small>
                                </div>

                                <!-- E-Signature -->
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="use_esignature" name="use_esignature" value="1" {{ old('use_esignature') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="use_esignature">Use E-Signature</label>
                                    </div>
                                    <small class="form-text text-muted">If unchecked, space will be provided for wet signature.</small>
                                </div>

                                <!-- Signature Override -->
                                <div class="form-group" id="signature_override_group" style="display: none;">
                                    <label for="signature_override">E-Signature Override</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('signature_override') is-invalid @enderror" id="signature_override" name="signature_override" accept="image/*">
                                        <label class="custom-file-label" for="signature_override">Choose signature image...</label>
                                    </div>
                                    <small class="form-text text-muted">Upload a custom signature image to override your account signature (Max 2MB, images only).</small>
                                    @error('signature_override')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Attachments (Optional) -->
                                <div class="form-group">
                                    <label for="attachments">
                                        <i class="fas fa-paperclip"></i> Attachments (Optional)
                                    </label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('attachments') is-invalid @enderror" id="attachments" name="attachments[]" accept=".pdf,.jpg,.jpeg,.png,.docx" multiple>
                                        <label class="custom-file-label" for="attachments">Choose files...</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Upload supporting documents (PDF, Images, DOCX). Max 10MB per file. Multiple files allowed.
                                    </small>
                                    @error('attachments')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    @error('attachments.*')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Action Buttons -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Save as Draft
                                    </button>
                                    <a href="{{ route('memorandums.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@push('scripts')
<!-- Select2 JS (local file like Role page) -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    jQuery(document).ready(function($) {
        console.log('Initializing Recipient Presets...');

        // Initialize Select2 for recipient presets
        $('.select2').select2({
            theme: 'bootstrap4'
            , width: '100%'
        });

        // Handle FOR preset selection
        $('#preset_for').on('change', function() {
            const selectedValues = $(this).val() || [];
            const textarea = $('#recipient_for');
            const textareaHint = $('#recipient_for_group .textarea-hint');

            if (selectedValues.includes('custom')) {
                // Show textarea when "Custom" is selected
                textarea.show();
                textareaHint.show();

                // Remove custom from selection to allow other presets
                const otherSelections = selectedValues.filter(v => v !== 'custom');

                // Auto-fill textarea with selected presets
                let content = '';
                otherSelections.forEach(function(presetId) {
                    const option = $('#preset_for option[value="' + presetId + '"]');
                    const formattedText = option.data('formatted');
                    if (formattedText) {
                        if (content) content += '\n\n';
                        content += formattedText;
                    }
                });
                textarea.val(content);
            } else if (selectedValues.length > 0) {
                // Hide textarea when only presets are selected
                textarea.hide();
                textareaHint.hide();

                // Auto-fill hidden textarea with selected presets
                let content = '';
                selectedValues.forEach(function(presetId) {
                    const option = $('#preset_for option[value="' + presetId + '"]');
                    const formattedText = option.data('formatted');
                    if (formattedText) {
                        if (content) content += '\n\n';
                        content += formattedText;
                    }
                });
                textarea.val(content);
            } else {
                // No selection - hide textarea and clear content
                textarea.hide().val('');
                textareaHint.hide();
            }
        });

        // Handle TO preset selection
        $('#preset_to').on('change', function() {
            const selectedValues = $(this).val() || [];
            const textarea = $('#recipient_to');
            const textareaHint = $('#recipient_to_group .textarea-hint');

            if (selectedValues.includes('custom')) {
                // Show textarea when "Custom" is selected
                textarea.show();
                textareaHint.show();

                // Remove custom from selection to allow other presets
                const otherSelections = selectedValues.filter(v => v !== 'custom');

                // Auto-fill textarea with selected presets
                let content = '';
                otherSelections.forEach(function(presetId) {
                    const option = $('#preset_to option[value="' + presetId + '"]');
                    const formattedText = option.data('formatted');
                    if (formattedText) {
                        if (content) content += '\n\n';
                        content += formattedText;
                    }
                });
                textarea.val(content);
            } else if (selectedValues.length > 0) {
                // Hide textarea when only presets are selected
                textarea.hide();
                textareaHint.hide();

                // Auto-fill hidden textarea with selected presets
                let content = '';
                selectedValues.forEach(function(presetId) {
                    const option = $('#preset_to option[value="' + presetId + '"]');
                    const formattedText = option.data('formatted');
                    if (formattedText) {
                        if (content) content += '\n\n';
                        content += formattedText;
                    }
                });
                textarea.val(content);
            } else {
                // No selection - hide textarea and clear content
                textarea.hide().val('');
                textareaHint.hide();
            }
        });

        // Handle recipient type change
        $('#recipient_type').on('change', function() {
            const recipientType = $(this).val();

            if (recipientType === 'FOR') {
                $('#recipient_for_group').show();
                $('#recipient_to_group').hide();
            } else if (recipientType === 'TO') {
                $('#recipient_for_group').hide();
                $('#recipient_to_group').show();
            } else if (recipientType === 'BOTH') {
                $('#recipient_for_group').show();
                $('#recipient_to_group').show();
            }
        }).trigger('change');

        // Auto-suggest subjects based on body content
        let typingTimer;
        const doneTypingInterval = 1000; // 1 second

        $('#body').on('keyup', function() {
            clearTimeout(typingTimer);
            const bodyText = $(this).val();

            if (bodyText.length > 20) {
                typingTimer = setTimeout(function() {
                    fetchSubjectSuggestions(bodyText);
                }, doneTypingInterval);
            }
        });

        function fetchSubjectSuggestions(bodyText) {
            $.ajax({
                url: '{{ route("memorandums.suggest-subjects") }}'
                , method: 'POST'
                , data: {
                    _token: '{{ csrf_token() }}'
                    , body: bodyText
                }
                , success: function(response) {
                    displaySubjectSuggestions(response.suggestions);
                }
                , error: function() {
                    console.log('Error fetching subject suggestions');
                }
            });
        }

        function displaySubjectSuggestions(suggestions) {
            const container = $('#subject_suggestions');
            container.empty();

            if (suggestions && suggestions.length > 0) {
                container.append('<small class="text-muted">Suggested subjects:</small><br>');
                suggestions.forEach(function(suggestion) {
                    const badge = $('<span class="badge badge-primary mr-1 mb-1" style="cursor: pointer;"></span>')
                        .text(suggestion)
                        .on('click', function() {
                            $('#subject').val(suggestion);
                        });
                    container.append(badge);
                });
            }
        }
    });

    // Toggle signature override field based on E-Signature checkbox
    $('#use_esignature').on('change', function() {
        if ($(this).is(':checked')) {
            $('#signature_override_group').slideDown();
        } else {
            $('#signature_override_group').slideUp();
            $('#signature_override').val('');
            $('.custom-file-label').text('Choose signature image...');
        }
    });

    // Update file input label when file is selected
    $('#signature_override').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });

    // Update attachments file input label
    $('#attachments').on('change', function() {
        var files = $(this)[0].files;
        var fileNames = Array.from(files).map(f => f.name).join(', ');
        if (fileNames.length > 50) {
            fileNames = files.length + ' file(s) selected';
        }
        $(this).next('.custom-file-label').html(fileNames || 'Choose files...');
    });

    // Show signature override field on page load if checkbox is checked
    if ($('#use_esignature').is(':checked')) {
        $('#signature_override_group').show();
    }

</script>
@endpush

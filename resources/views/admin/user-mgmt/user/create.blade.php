<!-- /.modal -->

<div class="modal fade" id="new-user-modal-lg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">New User</h4>
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
                                        <h3 class="card-title">User Information</h3>

                                    </div>

                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data" id="new-user-form" autocomplete="off">

                                        {{ csrf_field() }}
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="email">Email Address</label>
                                                <select id="email" name="email" class="form-control select2" style="width: 100%;" required autocomplete="off">
                                                    <option value="" selected>-- Choose Email --</option>
                                                    @foreach($Emails as $Email)

                                                    <option value="{{ $Email->email }}">{{ $Email->email }}</option>
                                                    @endforeach
                                                </select>
                                                @error('email')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror
                                            </div>
                                            {{-- @livewire('email-employee') --}}
                                            <div class="form-group">
                                                <label for="username">Username</label>
                                                <input name="username" id="username" class="form-control" type="text" placeholder="Enter Username" required minlength="3" autocomplete="off">
                                            </div>
                                            @error('username')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <div class="input-group">
                                                    <input type="password" name="password" class="form-control" id="password" placeholder="Password" required minlength="6" autocomplete="new-password">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password" aria-label="Show/Hide Password"><i class="fa fa-eye"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="password_confirmation">Confirm Password</label>
                                                <div class="input-group">
                                                    <input name="password_confirmation" id="password_confirmation" type="password" class="form-control" placeholder="Password" required minlength="6" autocomplete="new-password">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password_confirmation" aria-label="Show/Hide Password"><i class="fa fa-eye"></i></button>
                                                    </div>
                                                </div>
                                                <small id="pw-mismatch" class="text-danger d-none">Password not match.</small>
                                                <small id="pw-match" class="text-success d-none">Password matched.</small>
                                            </div>


                                            {{-- <input type="text" name="employeeid" id="employeeid" value="{{$Emails->employeeid}}" hidden> --}}
                                            {{-- <input type="text" name="empid" id="empid"  hidden>  --}}
                                        </div>
                                        <!-- /.card-body -->

                                        <div class="card-footer">
                                            <button type="submit" id="new-user-submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            {{-- <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div> --}}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Close Confirmation Modal -->
<div class="modal fade" id="close-confirm-modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-white">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Unsaved Changes
                </h5>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-exclamation-circle text-warning" style="font-size: 4rem;"></i>
                <h4 class="mt-3">Are you sure you want to close?</h4>
                <p class="text-muted">All your filled information will be lost if you close this form.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-arrow-left mr-1"></i>Go Back
                </button>
                <button type="button" class="btn btn-danger" id="confirm-close-btn">
                    <i class="fas fa-times mr-1"></i>Yes, Close It
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Password Mismatch Alert Modal -->
<div class="modal fade" id="password-mismatch-alert" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Password Mismatch
                </h5>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-times-circle text-danger" style="font-size: 4rem;"></i>
                <h4 class="mt-3">Passwords do not match!</h4>
                <p class="text-muted">Please make sure your passwords match before submitting.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fas fa-check mr-1"></i>OK, I'll fix it
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        console.log('Script loaded - waiting for modal');

        // ATTACH ALL HANDLERS WHEN MODAL IS SHOWN
        $('#new-user-modal-lg').on('shown.bs.modal', function() {
            console.log('Modal shown - attaching handlers');

            const $modal = $(this);
            const $form = $modal.find('#new-user-form');
            const $password = $modal.find('#password');
            const $passwordConf = $modal.find('#password_confirmation');
            const $pwMismatch = $modal.find('#pw-mismatch');
            const $pwMatch = $modal.find('#pw-match');

            console.log('Found elements:', {
                form: $form.length
                , password: $password.length
                , passwordConf: $passwordConf.length
            });

            function checkPasswordMatch() {
                const pwVal = $password.val() || '';
                const pwcVal = $passwordConf.val() || '';

                console.log('Checking passwords:', pwVal.length, pwcVal.length);

                if (pwVal.length > 0 && pwcVal.length > 0) {
                    if (pwVal === pwcVal) {
                        $pwMismatch.addClass('d-none');
                        $pwMatch.removeClass('d-none');
                        $passwordConf.removeClass('is-invalid').addClass('is-valid');
                        return true;
                    } else {
                        $pwMismatch.removeClass('d-none');
                        $pwMatch.addClass('d-none');
                        $passwordConf.removeClass('is-valid').addClass('is-invalid');
                        return false;
                    }
                } else {
                    $pwMismatch.addClass('d-none');
                    $pwMatch.addClass('d-none');
                    $passwordConf.removeClass('is-invalid is-valid');
                    return true;
                }
            }

            // Remove old handlers first (para hindi mag-double)
            $password.off('input.pwcheck');
            $passwordConf.off('input.pwcheck');
            $form.off('submit.pwcheck');
            $modal.find('.toggle-password').off('click.pwcheck');

            // Password input handlers
            $password.on('input.pwcheck', function() {
                console.log('Password input event');
                checkPasswordMatch();
            });

            $passwordConf.on('input.pwcheck', function() {
                console.log('Password confirmation input event');
                checkPasswordMatch();
            });

            // Form submit - PREVENT if passwords don't match
            $form.on('submit.pwcheck', function(e) {
                console.log('Form submit triggered');
                const pwVal = $password.val() || '';
                const pwcVal = $passwordConf.val() || '';

                if (pwVal.length > 0 && pwcVal.length > 0 && pwVal !== pwcVal) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();

                    // Show modal instead of alert
                    $('#password-mismatch-alert').modal('show');

                    checkPasswordMatch();
                    console.log('Form submission blocked - passwords do not match');
                    return false;
                }
                console.log('Form submission allowed');
            });

            // Toggle password visibility
            $modal.find('.toggle-password').on('click.pwcheck', function(e) {
                e.preventDefault();
                e.stopPropagation();

                console.log('Toggle button clicked');

                const targetId = $(this).data('target');
                const $input = $modal.find(targetId);
                const currentType = $input.attr('type');

                console.log('Toggle target:', targetId, 'Current type:', currentType);

                if (currentType === 'password') {
                    $input.attr('type', 'text');
                    $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                    console.log('Password revealed');
                } else {
                    $input.attr('type', 'password');
                    $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                    console.log('Password hidden');
                }
            });

            console.log('All handlers attached');
        });

        // Intercept close button click
        $('#new-user-modal-lg').on('hide.bs.modal', function(e) {
            const $modal = $(this);
            const $form = $modal.find('#new-user-form');

            // Check if form has any filled data
            const email = $modal.find('#email').val();
            const username = $modal.find('#username').val();
            const password = $modal.find('#password').val();
            const passwordConf = $modal.find('#password_confirmation').val();

            const hasData = (email && email !== '') ||
                (username && username.trim() !== '') ||
                (password && password !== '') ||
                (passwordConf && passwordConf !== '');

            console.log('Form has data:', hasData);

            // If form has data and not already confirmed, show warning
            if (hasData && !$modal.data('confirmed-close')) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Showing close confirmation');
                $('#close-confirm-modal').modal('show');
            }
        });

        // Handle confirm close button
        $('#confirm-close-btn').on('click', function() {
            console.log('Close confirmed');
            $('#close-confirm-modal').modal('hide');
            const $mainModal = $('#new-user-modal-lg');
            $mainModal.data('confirmed-close', true);
            $mainModal.modal('hide');
        });

        // Reset confirmed flag after close confirmation modal hides
        $('#close-confirm-modal').on('hidden.bs.modal', function() {
            // If user clicked "Go Back", reset the flag
            if (!$('#new-user-modal-lg').is(':visible')) {
                $('#new-user-modal-lg').removeData('confirmed-close');
            }
        });

        // Reset when modal closes
        $('#new-user-modal-lg').on('hidden.bs.modal', function() {
            console.log('Modal closing - resetting form');

            const $modal = $(this);
            const $form = $modal.find('#new-user-form');
            const form = $form[0];

            if (form) form.reset();

            // Reset select2 dropdown specially
            $modal.find('#email').val('').trigger('change');

            $modal.find('#pw-mismatch').addClass('d-none');
            $modal.find('#pw-match').addClass('d-none');
            $modal.find('#password_confirmation').removeClass('is-invalid is-valid');
            $modal.find('#password').removeClass('is-invalid is-valid');
            $modal.find('#password').attr('type', 'password');
            $modal.find('#password_confirmation').attr('type', 'password');
            $modal.find('.toggle-password i').removeClass('fa-eye-slash').addClass('fa-eye');

            // Reset confirmed flag
            $modal.removeData('confirmed-close');
        });

        console.log('Modal event listeners registered');
    });

</script>

<!-- Edit Travel Order Date Range (PENRO only) -->
<div class="modal fade" id="edit-travelorder-modal-lg" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Travel Order - Date Range Only</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">Travel Order Information</h3>
                                    </div>

                                    {{-- IMPORTANT: no route() here. JS will set action dynamically. --}}
                                    <form id="editTOForm" method="POST" action="{{ url('msd-management/encoder/travel-order') }}/__ID__" data-action-base="{{ url('msd-management/encoder/travel-order') }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label class="col-sm-3">Employee Name : <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <input class="form-control" id="to-employee" type="text" readonly disabled>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3">Date Range : <span class="text-danger">*</span></label>
                                                <div class="input-group col-sm-9">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                    </div>
                                                    <input type="text" name="daterange" class="form-control border-warning" id="to-daterange">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3">Destination : <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <input class="form-control" id="to-destination" type="text" readonly disabled>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3">Purpose of travel : <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <input class="form-control" id="to-purpose" type="text" readonly disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-warning js-update-penro" disabled>Update Date Range</button>
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

@push('scripts')
<script>
    $(document).ready(function() {
        const MODAL = '#edit-travelorder-modal-lg';
        const DATE_INPUT = '#to-daterange';

        // Remove any existing event handlers to prevent duplicates on refresh
        $(MODAL).off('show.bs.modal hidden.bs.modal');

        // Make sure modal is hidden on page load
        $(MODAL).modal('hide');

        (function() {
        const MODAL = '#edit-travelorder-modal-lg';
        const DATE_INPUT = '#to-daterange';

        // Accept “MM/DD/YYYY” or “YYYY-MM-DD”
        function parseFlex(s) {
            let m = moment(s, 'MM/DD/YYYY', true);
            if (!m.isValid()) m = moment(s, 'YYYY-MM-DD', true);
            return m.isValid() ? m : null;
        }

        function normalizeRange(raw) {
            if (!raw || typeof raw !== 'string' || !raw.includes(' - ')) return '';
            const [s, e] = raw.split(' - ');
            const ms = parseFlex(s)
                , me = parseFlex(e);
            if (!ms || !me) return '';
            return ms.format('MM/DD/YYYY') + ' - ' + me.format('MM/DD/YYYY');
        }

        $(MODAL).on('show.bs.modal', function(ev) {
            const $btn = $(ev.relatedTarget); // the Edit button
            const id = $btn.data('id');
            const range = ($btn.data('daterange') || '').trim();
            const emp = $btn.data('employee') || '';
            const dest = $btn.data('destination') || '';
            const purp = $btn.data('purpose') || '';
            const isPenro = Number($btn.data('is-penro')) === 1;

            const $m = $(this);
            const $form = $m.find('#editTOForm');
            const base = $form.data('action-base'); // /msd-management/encoder/travel-order
            const $updateBtn = $form.find('.js-update-penro');

            // point form to /.../{id}
            $form.attr('action', `${base}/${id}`);

            // Fill read-only fields
            $('#to-employee').val(emp);
            $('#to-destination').val(dest);
            $('#to-purpose').val(purp);

            // Clean up existing daterangepicker before creating new one
            const $dr = $(DATE_INPUT);
            if ($dr.data('daterangepicker')) {
                try {
                    $dr.data('daterangepicker').remove();
                } catch(e) {}
            }
            $dr.off('.daterangepicker');

            let start = moment();
            let end = moment();

            if (range && range.includes(' - ')) {
                const [s, e] = range.split(' - ');
                const startParsed = parseFlex(s);
                const endParsed = parseFlex(e);

                if (startParsed && startParsed.isValid()) {
                    start = startParsed;
                }
                if (endParsed && endParsed.isValid()) {
                    end = endParsed;
                }
            }

            // Initialize daterangepicker with initial dates
            $dr.daterangepicker({
                autoUpdateInput: false
                , parentEl: MODAL
                , startDate: start
                , endDate: end
                , locale: {
                    format: 'MM/DD/YYYY'
                    , cancelLabel: 'Clear'
                }
            }, function(chosenStart, chosenEnd) {
                // Callback when user selects a date
                $dr.val(chosenStart.format('MM/DD/YYYY') + ' - ' + chosenEnd.format('MM/DD/YYYY'));
            });

            // Set the initial value after a short delay to ensure picker is ready
            if (range && start.isValid() && end.isValid()) {
                setTimeout(function() {
                    $dr.val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
                }, 50);
            }

            const baseline = normalizeRange(range);

            function toggleButtons() {
                const current = normalizeRange(($dr.val() || '').trim());
                const changed = current.length && current !== baseline;
                $updateBtn.prop('disabled', !changed);
            }

            // Remove old event handlers before adding new ones to prevent duplicates
            $dr.off('apply.daterangepicker cancel.daterangepicker input change blur');
            $dr.on('apply.daterangepicker cancel.daterangepicker input change blur', toggleButtons);
            toggleButtons();
        });

        $(MODAL).on('hidden.bs.modal', function() {
            const $form = $(this).find('#editTOForm');
            const $dr = $(DATE_INPUT);

            // Clean up daterangepicker properly
            if ($dr.data('daterangepicker')) {
                try {
                    $dr.data('daterangepicker').remove();
                } catch(e) {
                    console.log('Error removing daterangepicker on close:', e);
                }
            }
            $dr.off('.daterangepicker');

            // Reset form safely
            if ($form[0]) $form[0].reset();
            $form.find('.js-update-penro').prop('disabled', true);
            $('#to-employee,#to-destination,#to-purpose').val('');
            $dr.val('');
        });
    })();

</script>
@endpush

<!-- Edit Travel Order (generic modal used by approver 1 & 2) -->
<div class="modal fade" id="edit-travelorder-modal-lg">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Travel Order</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Travel Order Information</h3>
                  </div>

                  {{-- IMPORTANT: no route() here. JS will set action dynamically. --}}
                  <form id="editTOForm" method="POST" action="{{ url('msd-management/encoder/travel-order') }}/__ID__"
                    data-action-base="{{ url('msd-management/encoder/travel-order') }}">
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
                          <input type="text" name="daterange" class="form-control border-primary" id="to-daterange">
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
                      {{-- approver 1 button --}}
                      <button type="submit" class="btn btn-primary js-update1" disabled>Update</button>
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
    const ms = parseFlex(s), me = parseFlex(e);
    if (!ms || !me) return '';
    return ms.format('MM/DD/YYYY') + ' - ' + me.format('MM/DD/YYYY');
  }

  $(MODAL).on('show.bs.modal', function (ev) {
    const $btn   = $(ev.relatedTarget);            // the Edit button
    const id     = $btn.data('id');
    const range  = ($btn.data('daterange') || '').trim();
    const emp    = $btn.data('employee') || '';
    const dest   = $btn.data('destination') || '';
    const purp   = $btn.data('purpose') || '';
    const canA2  = Number($btn.data('can-approve2')) === 1;  // 1 only for approver 2 rows

    const $m     = $(this);
    const $form  = $m.find('#editTOForm');
    const base   = $form.data('action-base');      // /msd-management/encoder/travel-order
    const $u1    = $form.find('.js-update1');
    const $u2    = $form.find('.js-update2');

    // point form to /.../{id}
    $form.attr('action', `${base}/${id}`);
    // approver2 submits to /.../{id}/update-approve2
    $u2.attr('formaction', `${base}/${id}/update-approve2`);
    $u2.toggleClass('d-none', !canA2);

    // fill read-only fields
    $('#to-employee').val(emp);
    $('#to-destination').val(dest);
    $('#to-purpose').val(purp);

    // init daterangepicker with current value
    const $dr = $(DATE_INPUT);
    if ($dr.data('daterangepicker')) {
      $dr.data('daterangepicker').remove();
      $dr.off('.daterangepicker');
    }

    let start=null, end=null;
    if (range.includes(' - ')) {
      const [s, e] = range.split(' - ');
      start = parseFlex(s); end = parseFlex(e);
    }

    $dr.val(range);
    $dr.daterangepicker({
      autoUpdateInput: true,
      parentEl: MODAL,
      startDate: start || moment(),
      endDate:   end   || moment(),
      locale: { format: 'MM/DD/YYYY', cancelLabel: 'Clear' }
    });

    const baseline = normalizeRange(range);
    function toggleButtons() {
      const current = normalizeRange(($dr.val() || '').trim());
      const changed = current.length && current !== baseline;
      $u1.prop('disabled', !changed);
      $u2.prop('disabled', !changed);
    }
    $dr.on('apply.daterangepicker cancel.daterangepicker input change blur', toggleButtons);
    toggleButtons();
  });

  $(MODAL).on('hidden.bs.modal', function () {
    const $form = $(this).find('#editTOForm');
    const $dr   = $(DATE_INPUT);
    if ($dr.data('daterangepicker')) $dr.data('daterangepicker').remove();
    $form[0]?.reset();
    $form.find('.js-update1').prop('disabled', true);
    $form.find('.js-update2').prop('disabled', true).addClass('d-none');
    $('#to-employee,#to-destination,#to-purpose').val('');
    $dr.val('');
  });
})();
</script>
@endpush
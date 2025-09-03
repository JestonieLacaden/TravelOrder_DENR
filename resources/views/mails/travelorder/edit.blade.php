{{-- @extends('mails.employeerequest.index') --}}
<!-- /.modal -->

{{-- @extends('mails.travelorder.index') --}}
{{--
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" /> --}}

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

        <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <!-- left column -->
              <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Travel Order Information</h3>
                  </div>

                  <!-- /.card-header -->
                  <!-- form start -->

                  {{-- ensure $TravelOrder exists by starting the loop BEFORE the form --}}
                  @foreach($TravelOrders as $TravelOrder)
                  @foreach($TravelOrderSignatories as $TravelOrderSignatory)
                  @if($TravelOrder->is_approve1 != true && $TravelOrder->is_rejected1 != true)
                  @if($TravelOrderSignatory->approver1 == $UserEmployee->id && auth()->check())

                  {{-- action points to resource update route; only Date Range will be submitted --}}
                  <form method="POST"
                    action="{{ url('msd-management/encoder/travel-order/'.$TravelOrder->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                      <div class="card-body">
                        <div class="form-group row">
                          <label class="col-sm-3" for="employeeid">Employee Name : <span
                              class="text-danger">*</span></label>
                          <div class="col-sm-9">
                            <input name="employeeid" id="employeeid" class="form-control" type="text"
                              oninput="this.value = this.value.toUpperCase()"
                              value="{{ $TravelOrder->Employee->lastname . ', ' . $TravelOrder->Employee->firstname . ' ' . $TravelOrder->Employee->middlename }}"
                              readonly disabled>
                            @error('employeeid')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-sm-3" for="daterange">Date Range : <span
                              class="text-danger">*</span></label>
                          <div class="input-group col-sm-9">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                              </span>
                            </div>
                            {{-- ONLY field being posted/updated --}}
                            <input type="text" name="daterange"
                              class="form-control float-right border-primary daterangeEdit"
                              oninput="this.value = this.value.toUpperCase()" value="{{ $TravelOrder->daterange }}">
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-sm-3" for="destinationoffice">Destination : <span
                              class="text-danger">*</span></label>
                          <div class=" col-sm-9">
                            <input name="destinationoffice" id="destinationoffice" class="form-control" type="text"
                              placeholder="Enter Destination" oninput="this.value = this.value.toUpperCase()"
                              value="{{ $TravelOrder->destinationoffice }}" disabled>
                            @error('destination')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-sm-3" for="purpose">Purpose of travel : <span
                              class="text-danger">*</span></label>
                          <div class=" col-sm-9">
                            <input name="purpose" id="purpose" class="form-control" type="text"
                              placeholder="Enter Purpose of Travel" oninput="this.value = this.value.toUpperCase()"
                              value="{{ $TravelOrder->purpose }}" disabled>
                            @error('purpose')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- /.card-body -->
                    <div class="card-footer">
                      @can('update', $TravelOrder)
                      <button type="submit" class="btn btn-primary js-update-btn" disabled>Update</button>
                      @endcan
                    </div>

                  </form>

                  @endif
                  @endif
                  @endforeach
                  @endforeach

                </div>
                <!-- /.card -->
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@push('scripts')
<script>
  const modalSel = '#edit-travelorder-modal-lg';

  // Parse MM/DD/YYYY or YYYY-MM-DD; return moment or null
  const parseFlex = (s) => {
    let m = moment(s, 'MM/DD/YYYY', true);
    if (!m.isValid()) m = moment(s, 'YYYY-MM-DD', true);
    return m.isValid() ? m : null;
  };

  // Normalize a date range string to a single display format so comparisons are reliable
  const normalizeRange = (raw) => {
    if (!raw || typeof raw !== 'string' || !raw.includes(' - ')) return '';
    const [s, e] = raw.split(' - ');
    const ms = parseFlex(s), me = parseFlex(e);
    if (!ms || !me) return '';
    // Use the same format the picker uses in the input
    return ms.format('MM/DD/YYYY') + ' - ' + me.format('MM/DD/YYYY');
  };

  $(modalSel).on('show.bs.modal', function (ev) {
    const btn = $(ev.relatedTarget);
    const $modal = $(this);
    const $form  = $modal.find('form');
    const $drInp = $form.find('input[name=daterange]');
    const $submit = $form.find('.js-update-btn');

    // Point action to the record
    const id  = btn.data('id');
    $form.attr('action', "{{ url('msd-management/encoder/travel-order') }}/" + id);

    // Get DB value and normalize it (this is our baseline)
    const dbRaw = (btn.data('daterange') || '').trim();
    const baseline = normalizeRange(dbRaw);

    // Put DB value into the input exactly as stored so user sees current value
    $drInp.val(dbRaw);

    // Clean any previous picker instance
    if ($drInp.data('daterangepicker')) {
      $drInp.data('daterangepicker').remove();
      $drInp.off('.daterangepicker');
    }

    // Determine start/end for the picker from DB value
    let start=null, end=null;
    if (dbRaw.includes(' - ')) {
      const [s,e] = dbRaw.split(' - ');
      start = parseFlex(s);
      end   = parseFlex(e);
    }

    // Initialize picker (display format MM/DD/YYYY)
    $drInp.daterangepicker({
      autoUpdateInput: true,
      parentEl: modalSel,
      startDate: start || moment(),
      endDate:   end   || moment(),
      locale: { format: 'MM/DD/YYYY', cancelLabel: 'Clear' }
    })
    .on('apply.daterangepicker', function() { toggleSubmit(); })
    .on('cancel.daterangepicker', function() { $(this).val(''); toggleSubmit(); });

    // Also react to manual typing/pasting
    $drInp.on('input change blur', toggleSubmit);

    // Initial state: disabled unless value differs from baseline and is non-empty/valid
    toggleSubmit();

    function toggleSubmit() {
      const current = normalizeRange($drInp.val().trim());
      const changed = current.length && current !== baseline;
      $submit.prop('disabled', !changed);
    }
  });

  // Reset state when the modal is fully hidden
  $(modalSel).on('hidden.bs.modal', function () {
    const $form = $(this).find('form');
    const $drInp = $form.find('input[name=daterange]');
    const $submit = $form.find('.js-update-btn');

    // Reset form & controls so nothing sticks next time
    if ($drInp.data('daterangepicker')) $drInp.data('daterangepicker').remove();
    $form[0]?.reset();
    $drInp.val('');
    $submit.prop('disabled', true);
  });
</script>
@endpush

{{-- @push('scripts')
<script>
  $('#edit-travelorder-modal-lg').on('shown.bs.modal', function () {
    const $inputs = $(this).find('.daterangeEdit');
    $inputs.each(function () {
      if ($(this).data('daterangepicker')) {
        $(this).data('daterangepicker').remove();
        $(this).off('.daterangepicker');
      }
      const raw = $(this).val();
      let start=null,end=null;
      if (raw && raw.includes(' - ')) {
        const [s,e] = raw.split(' - ');
        start = moment(s, 'YYYY-MM-DD', true);
        end   = moment(e, 'YYYY-MM-DD', true);
      }
      $(this).daterangepicker({
        autoUpdateInput: true,
        parentEl: '#edit-travelorder-modal-lg',
        locale: { format: 'YYYY-MM-DD', cancelLabel: 'Clear' },
        startDate: (start && start.isValid()) ? start : moment(),
        endDate:   (end && end.isValid())   ? end   : moment()
      }).on('cancel.daterangepicker', function () { $(this).val(''); });
    });
  });
</script>
@endpush --}}
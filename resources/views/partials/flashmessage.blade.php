@if ($message = Session::get('success'))
    <script>
        Swal.fire({
        icon: 'success',
        title: '{{ $message}}',
        showConfirmButton: false,
        timer: 1500
    })
    </script>
@endif

@if ($message = Session::get('error'))
    <script>
        Swal.fire({
        icon: 'error',
        title: 'Ooopppsss...',
        text: '{{ $message}}',
        showConfirmButton: false,
        timer: 2000
        })
    </script>
@endif

@if(!empty(Session::get('error_code')))
            <script>
            $(function() {
                Swal.fire({
                icon: 'error',
                title: 'Sorry...',
                text: 'File not Saved! Fields must not be empty!',
                showConfirmButton: false,
                timer: 3000
                })
                $('#new-document-modal-lg').modal('show'); 
            
            });
            </script>
@endif

@section('page-script')
   
@endsection





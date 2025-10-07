<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Cannot Print</title>
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
</head>

<body class="p-4">
    <div class="alert alert-warning">
        <h4 class="alert-heading">Cannot print this Travel Order yet</h4>
        <p>{{ $message ?? 'No Travel Order number issued yet.' }}</p>
        <hr>
        <a href="{{ url()->previous() ?: url('/') }}" class="btn btn-secondary">Go back</a>
    </div>
</body>

</html>
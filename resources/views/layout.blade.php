<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Lucky App')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">

    {{-- Flash success --}}
    @if(session('status') === 'success_lucky_call')
        <div class="alert {{ session('win') ? 'alert-info' : 'alert-light' }}">
            You rolled: <strong>{{ session('number') }}</strong><br>
            Result: <strong>{{ session('win') ? 'Win' : 'Lose' }}</strong><br>
            Amount: <strong>{{ session('amount') }}</strong>
        </div>
    @endif

    {{-- Flash error --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- Flash info --}}
    @if (session('info'))
        <div class="alert alert-info fade show" role="alert">
            {{ session('info') }}
        </div>
    @endif

    @yield('content')
</div>
</body>
</html>

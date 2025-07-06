@extends('layout')

@section('title', 'Lucky Link')

@section('content')
    <h2 class="mb-3">Welcome, {{ $link->user->username }}!</h2>

    <p>This is your lucky link <b>(expires {{ $link->expires_at->format('F j, Y \a\t H:i') }})</b></p>

    <form method="POST" action="{{ route('link.deactivate', $link->token) }}" class="mb-2">
        @csrf
        <button type="submit" class="btn btn-danger">Deactivate Link</button>
    </form>

    <form method="POST" action="{{ route('link.regenerate', $link->token) }}" class="mb-2">
        @csrf
        <button type="submit" class="btn btn-warning">Generate New Link</button>
    </form>

    <form method="POST" action="{{ route('link.lucky', $link->token) }}" class="mb-2">
        @csrf
        <button type="submit" class="btn btn-success">Imfeelinglucky</button>
    </form>

    <a href="{{ route('link.history', $link->token) }}" class="btn btn-secondary">History</a>
@endsection

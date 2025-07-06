@extends('layout')

@section('title', 'History')

@section('content')
    <h2 class="mb-4">Last 3 Attempts</h2>

    <table class="table table-striped mb-4">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Number</th>
            <th scope="col">Result</th>
            <th scope="col">Amount</th>
        </tr>
        </thead>
        <tbody>
        @forelse($attempts as $index => $attempt)
            <tr>
                <th scope="row">{{ $index + 1 }}</th>
                <td>{{ $attempt->number }}</td>
                <td>
                    <span class="badge bg-{{ $attempt->win ? 'success' : 'secondary' }}">
                        {{ $attempt->win ? 'Win' : 'Lose' }}
                    </span>
                </td>
                <td>${{ $attempt->amount }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">No attempts yet</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <a href="{{ route('link.show', $luckyLink->token) }}" class="btn btn-secondary">Back</a>
@endsection

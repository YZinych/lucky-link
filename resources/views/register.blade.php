@extends('layout')

@section('title', 'Register')

@section('content')
    <h2 class="mb-4">Register</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text"
                   name="username"
                   class="form-control @error('username') is-invalid @enderror"
                   value="{{ old('username') }}"
                   required>
            @error('username')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="text"
                   name="phone_number"
                   class="form-control @error('phone_number') is-invalid @enderror"
                   value="{{ old('phone_number') }}"
                   required>
            @error('phone_number')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>
@endsection

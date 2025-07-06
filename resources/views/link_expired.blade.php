@extends('layout')

@section('title', 'Link Expired')

@section('content')
    <h2 class="text-danger">Ваше посилання більше не активне</h2>
    <p>Термін дії вичерпано. Згенеруйте нове посилання, якщо потрібно.</p>

    <a href="{{ route('register.form') }}" class="btn btn-primary">Повернутись до сторінки реєстрації</a>
@endsection

@extends('email.emailLayout')
@section('content')
    <p>Kepada Yth. {{ $user['name'] }}</p>
    <p>Anda sudah terdaftar sebagai pengguna baru di
        <a href="{{ config('app.url') }}" rel="nofollow noreferrer noopener" target="_blank">Situs GSN</a></p>
    <p>Berikut ini adalah username dan password baru Anda:</p>
    <p>username: {{ $user['username'] }}</p>
    <p>password: {{ $newPassword }}</p>
    <p>Terima Kasih</p>
    <p>Administrator GSN</p>
@endsection

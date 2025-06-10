@extends('email.emailLayout')
@section('content')
    <p>Kepada Yth. {{ $user['name'] }}</p>
    <p>Reset password berhasil dilakukan untuk username {{ $user['username'] }}</p>
    <p>Berikut ini adalah password baru Anda:</p>
    <p>{{ $newPassword }}</p>
    <p>Silakan login di <a href="{{ config('app.url') }}" rel="nofollow noreferrer noopener" target="_blank">Situs GSN</a> dengan menggunakan password baru Anda.</p>
    <p>Terima Kasih</p>
    <p>Administrator GSN</p>
@endsection

@include('layouts.navbars.navs.guest')

<div class="wrapper wrapper-full-page ">
    <div class="full-page section-image" filter-color="black" data-image="{{ asset('paper') . '/' . ($backgroundImagePath ?? "img/bg/2023-02-01.jpg") }}">
        @yield('content')
        @if(Route::current()->getName() != 'register-siswa')
        @include('layouts.footer')
        @endif
    </div>
</div>

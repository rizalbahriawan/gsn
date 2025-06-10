{{-- sidebar --}}
<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">
        <a href="#" class="simple-text logo-mini">
            <div class="logo-image-small">
                <img src="{{ asset('paper') }}/img/logo-small.png">
            </div>
        </a>
        <a href="#" class="simple-text logo-normal">
            {{ auth()->user()->peran->nama_peran }}
            {{-- {{ Session::get('peran') }} --}}
        </a>
    </div>
    {{-- <p>{{$elementActive}}</p> --}}
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="{{ $elementActive == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('welcome') }}">
                    <i class="nc-icon nc-bank"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            @if(auth()->user()->peran->kode_peran <= 2)
            <li class="{{ $elementActive == 'user' || $elementActive == 'peran' ? 'active' : '' }}">
                <a data-toggle="collapse" aria-expanded="true" href="#penggunaSub">
                    <i class="nc-icon nc-single-02">
                        {{-- <img src="{{ asset('paper/img/laravel.svg') }}"> --}}
                    </i>
                    <p>
                            {{ __('Pengguna') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse show" id="penggunaSub">
                    <ul class="nav">
                        <li class="{{ $elementActive == 'user' ? 'active' : '' }}">
                            <a href="{{ route('user.index') }}">
                                <span class="sidebar-mini-icon">{{ __('MP') }}</span>
                                <span class="sidebar-normal">{{ __(' Manajemen Pengguna ') }}</span>
                            </a>
                        </li>
                        <li class="{{ $elementActive == 'peran' ? 'active' : '' }}">
                            <a href="{{ route('peran.index') }}">
                                <span class="sidebar-mini-icon">{{ __('P') }}</span>
                                <span class="sidebar-normal">{{ __(' Peran ') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif


            @if(auth()->user()->peran->kode_peran <= 2)
            <li class="{{ $elementActive == 'rekap' ? 'active' : '' }}">
                <a href="{{ route('rekap.index') }}">
                    <i class="nc-icon nc-badge"></i>
                    <p>{{ __('Data Rekap Siswa') }}</p>
                </a>
            </li>
            @endif

            @if(auth()->user()->peran->kode_peran == 5)
            <li class="{{ $elementActive == 'siswa' ? 'active' : '' }}">
                <a href="{{ route('siswa.show') . '?id_user='. auth()->user()->id}}">
                    <i class="nc-icon nc-badge"></i>
                    <p>{{ __('Detail Siswa') }}</p>
                </a>
            </li>
            @endif

            @if(auth()->user()->peran->kode_peran <= 4)
            <li class="{{ $elementActive == 'siswa' ? 'active' : '' }}">
                <a href="{{ route('siswa.index') }}">
                    <i class="nc-icon nc-badge"></i>
                    <p>{{ __('Data Siswa') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'proses' ? 'active' : '' }}">
                <a href="{{ route('proses.index') }}">
                    <i class="nc-icon nc-tag-content"></i>
                    <p>{{ __('Simpanan Dana Abadi') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'realisasi' ? 'active' : '' }}">
                <a href="{{ route('realisasi.index') }}">
                    <i class="nc-icon nc-send"></i>
                    <p>{{ __('Realisasi Manfaat') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'wilayah' ? 'active' : '' }}">
                <a href="{{ route('wilayah.index') }}">
                    <i class="nc-icon nc-pin-3"></i>
                    <p>{{ __('Data Wilayah') }}</p>
                </a>
            </li>
            @endif

            {{-- <li class="{{ $elementActive == 'tables' ? 'active' : '' }}">
                <a href="{{ route('home', 'tables') }}">
                    <i class="nc-icon nc-bulb-63"></i>
                    <p>{{ __('Forum') }}</p>
                </a>
            </li> --}}
            {{-- <li class="active-pro {{ $elementActive == 'upgrade' ? 'active' : '' }}">
                <a href="{{ route('home', 'upgrade') }}" class="bg-danger">
                    <i class="nc-icon nc-spaceship text-white"></i>
                    <p class="text-white">{{ __('Upgrade to PRO') }}</p>
                </a>
            </li> --}}
        </ul>
    </div>
</div>

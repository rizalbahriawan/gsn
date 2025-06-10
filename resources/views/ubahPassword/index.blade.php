@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'user'
])

@section('title', 'Ubah Password')

@section('content')
<div class="content">
    @if ($errors->any())
    <div class="col-md-12">
        <div class="alert alert-success alert-dismissible fade show mt-3" onshow="dismissed()" role="alert" style="background-color:#d90000">
            <strong>
                <p style="color: white">Gagal Mengubah Password</p>
            </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true" style="color: white">&times;</span>
            </button>
        </div>
    </div>
    @endif
    @if ($message = Session::get('success'))
    <div class="col-md-12">
        <div class="alert alert-success alert-dismissible fade show mt-3" onshow="dismissed()" role="alert">
            <strong>
                <p>{{ $message }}</p>
            </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    @endif
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Ubah Password</h4>
                <form method="POST" action="{{ route('ubahPassword.ubah') }}">
                    @csrf
                    <div class="form-group">
                        <label for="password">Masukkan Password Lama <span style="color: red"> * </span></label>
                        <input id="password" type="password" class="form-control" name="password_lama" value="{{ old('password_lama') }}">
                        @if($errors->has('password_lama'))
                        <div class="text-danger">
                            {{ $errors->first('password_lama')}}
                        </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="password">Masukkan Password Baru <span style="color: red"> * </span></label>
                        <input id="password_baru" type="password" class="form-control" name="password_baru" value="{{ old('password_baru') }}">
                        {{-- <p><i> Minimal 8 karakter serta memiliki huruf besar, huruf kecil dan numerik.</i></p> --}}
                        @if($errors->has('password_baru'))
                        <div class="text-danger">
                            {{ $errors->first('password_baru')}}
                        </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="password">Konfirmasi Password Baru <span style="color: red"> * </span></label>
                        <input id="konfirmasi_password_baru" type="password" class="form-control" name="konfirmasi_password_baru" value="{{ old('konfirmasi_password_baru') }}">
                        @if($errors->has('konfirmasi_password_baru'))
                        <div class="text-danger">
                            {{ $errors->first('konfirmasi_password_baru')}}
                        </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <label for="cari">&nbsp;</label>
                        <button type="submit" class="btn btn-primary">
                            Ubah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

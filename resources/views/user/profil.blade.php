@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'user'
])

@section('title', 'Profil User')

@section('content')
<div class="content">
    @if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert" onshow="dismissed" id="success-alert">
    <strong>
        <p>Gagal Mengubah Data Pengguna</p>
    </strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show mt-3" onshow="dismissed()" role="alert" id="success-alert">
    <strong>
        <p>{{ $message }}</p>
    </strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<form>
    <div class="form-row">
      <div class="form-group col-md-6">
        <input name='id' id='id' value="{{ $data->id }} " hidden>
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" value="{{ $data->username }}" disabled>
      </div>
      <div class="form-group col-md-6">
        <label for="name">Nama Lengkap</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $data->name }}"  placeholder="Nama Lengkap">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-4">
        <label for="email">Email</label>
        <input type="text" class="form-control" id="email" name="email" value={{ $data->email }} placeholder="Email">
      </div>
      <div class="form-group col-md-4">
        <label for="peran">Peran</label>
        <input name="id_peran" id="id_peran" class="form-control" value="{{ $data->peran }}" disabled>
        {{-- <select id="inputState" class="form-control">
            <option value="none" selected disabled>Pilih Peran</option>
            @foreach($perans as $peran)
            <option value="{{ $peran->id }}"
                @if(!empty($user) and $peran->id == $user->id_peran) selected @endif>
                {{ $peran->nama_peran }}
            </option>
            @endforeach
        </select> --}}
      </div>
      <div class="form-group col-md-4">
        <label for="no_hp">Nomor HP</label>
        <input type="text" placeholder="Nomor HP"  name="no_hp" class="form-control" id="no_hp" maxlength="15" onkeypress="return onlyNumberKey(event)" value={{ $data->no_hp }}>
        <p><i>No. Handphone berupa angka dan minimal 10 karakter (apabila diisi)</i></p>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
  </form>
</div>

<script>
function onlyNumberKey(evt) {

    // Only ASCII character in that range allowed
    var ASCIICode = (evt.which) ? evt.which : evt.keyCode
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
        return false;
    return true;
}
</script>
  @endsection

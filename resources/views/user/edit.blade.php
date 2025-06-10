@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'user'
])

@section('title', 'Ubah/View User')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}" />

<div class="content">
    @if (!empty($successMessage))
    <div class="alert alert-success alert-dismissible fade show mt-3" onshow="dismissed()" role="alert" id="success-alert">
        <strong><p>{{ $successMessage }}</p></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert" id="failedUbahPengguna"
        style="display:none;">
        <strong>
            <p id="failedUbahPenggunaText"></p>
            <p id="mainError"></p>
        </strong>
        <button type="button" class="close" onclick="$('#failedUbahPengguna').hide();" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @if ($permission == 'edit')
                        <h4 class="card-title">Ubah Pengguna</h4>
                    @elseif($permission == 'view')
                        <h4 class="card-title">Detail Pengguna</h4>
                        <br/>
                    @else
                        <h4 class="card-title">Tambah Pengguna</h4>
                    @endif
                    @if ($permission != 'view')
                        <p style='color:red'>* wajib diisi <p>
                    @endif
                </div>
                <div class="card-body">
                    <form id="myForm">
                        <div class="form-row">
                            <input type="hidden" name="id" value="{{ $user->id }}" id="id">
                            <div class="form-group col-md-6">
                                <label for="username" class="{{ $permission ==  'insert' ? 'required' : ''  }}">Username</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Username" value="{{ $user->username }}" aria-describedby="username">
                                <small class="text-danger error" id="err-username"></small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name" class="{{ $permission !=  'view' ? 'required' : ''  }}">Nama Lengkap</label>
                                <input type="text" aria-describedby="name" placeholder="Nama Lengkap"  name="name" class="form-control" id="name" value={{ $user->name }} >
                                <small class="text-danger error" id="err-name"></small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="email" class="{{ $permission !=  'view' ? 'required' : ''  }}">Email</label>
                                <input type="text" aria-describedby="email" placeholder="Email" class="form-control" id="email" name="email" value={{ $user->email }} >
                                <small class="text-danger error" id="err-email"></small>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="id_peran" class="{{ $permission !=  'view' ? 'required' : ''  }}">Peran</label>
                                <select id="peran_dropdown" class="form-control" name="id_peran">
                                    <option value="none" selected>Pilih Peran</option>
                                    @foreach($perans as $peran)
                                    <option value="{{ $peran->id }}" @if($peran->id == $user->id_peran) selected @endif >{{ $peran->nama_peran }}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger error" id="err-id_peran"></small>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="no_hp">Nomor HP</label>
                                <input type="text" aria-describedby="no_hp" placeholder="Nomor HP" class="form-control" id="no_hp" name="no_hp" value={{ $user->no_hp }} >
                                <small class="text-danger error" id="err-no_hp"></small>
                            </div>
                            @if ($permission != 'insert')
                                <div class="form-group col-md-2">
                                    <label for="flag_aktif" class="{{ $permission !=  'view' ? 'required' : ''  }}">Status</label>
                                    <select name="flag_aktif" class="form-control" id="flag_aktif_dropdown">
                                        <option value="none" selected disabled>Pilih Status</option>
                                        <option value="Y" @if($user->flag_aktif == 'Y') selected @endif>Aktif</option>
                                        <option value="T" @if($user->flag_aktif == 'T') selected @endif>Tidak Aktif</option>
                                    </select>
                                    <small class="text-danger error" id="err-flag_aktif"></small>
                                </div>
                            @endif
                        </div>
                        {{-- <button type="submit" class="btn btn-primary">Simpan</button> --}}

                        @if($permission == 'view')
                            {{-- <a id="ubah" class="btn btn-primary btn-1" href="{{ route('user.edit', $user->id) }}">Ubah</a> --}}
                            <a id="kembali" class="btn btn-dark btn-1" href="{{ route('user.index') }}">Kembali</a>
                        @elseif($permission == 'edit')
                            <button id="submit" type="submit" class="btn btn-primary btn-1">Simpan</button>
                            <a id="ubah" class="btn btn-dark btn-1" href="{{ route('user.index') }}">Batal</a>
                        @else
                            <button id="submit" type="submit" class="btn btn-primary btn-1">Simpan</button>
                            <a id="ubah" class="btn btn-dark btn-1" href="{{ route('user.index') }}">Batal</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
<script>
    var permission = '<?php echo $permission; ?>';
    var errorMessage = 'Gagal Mengubah Data Pengguna'
    if(permission == 'insert') {
        errorMessage = 'Gagal Menambah Data Pengguna'
    }

    var successMessage = 'Data Pengguna Berhasil Diubah'
    if(permission == 'insert') {
        successMessage = 'Data Pengguna Berhasil Ditambah'
    }

    disableOnView()

    function disableOnView() {
        if(permission === 'view') {
            $("#myForm :input").prop('readonly', true);
            $("#myForm :input").removeAttr('placeholder');
            $("select").prop("disabled", true);
        } else if(permission === 'edit') {
            $("#username").prop("disabled", "disabled");
        }
    }

    $('#submit').click(function(e) {
        $('#myForm').submit(function (event) {
            // console.log('permission ' + permission)
            event.preventDefault()
            event.stopImmediatePropagation();
            $('#mainError').html("")

            $('#submit').attr('disabled', 'disabled');
            var formData = new FormData($(this)[0]);
            formData.append('permission', permission);
            $('#failedUbahPengguna').hide();
            $('.error').each(function (i, obj) {
                obj.innerHTML = ''
            });
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('user.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('.error').each(function (i, obj) {
                        obj.innerHTML = ''
                    });
                    if(data.success) {

                        if(permission == 'insert') {
                            window.location.href = "{{ route('user.index') }}"
                        } else {
                            console.log('store success '+data.id )
                            window.location.href = "{{ route('user.show') }}" + '?id=' + data.id +'&message=' + successMessage
                        }
                    } else {
                        $('.error').each(function (i, obj) {
                            if (data.message[obj.id.substring(4)]) {
                                obj.innerHTML = data.message[obj.id.substring(4)].filter(Boolean).join(", ")
                            }
                        });

                        if(data.message['main_error']) {
                            $('#mainError').html(data.message['main_error']);
                        }

                        $('#failedUbahPenggunaText').html(errorMessage);
                        $('#failedUbahPengguna').show();
                    }
                    $('#submit').removeAttr('disabled');
                },
                error: function (data) {
                    $('#failedUbahPenggunaText').html(JSON.stringify(data));
                    $('#failedUbahPengguna').show();
                    $('#submit').removeAttr('disabled');
                }
            });
        })
    })
</script>
@endsection

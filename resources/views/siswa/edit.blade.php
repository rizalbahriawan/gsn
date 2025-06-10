@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'siswa'
])

@section('title', 'Ubah Siswa')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}" />

<div class="content">
    @if ($sukses = Session::get('sukses'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $sukses }}</strong>
    </div>
    @endif
    @if (!empty($successMessage))
    <div class="alert alert-success alert-dismissible fade show mt-3" onshow="dismissed()" role="alert" id="success-alert">
        <strong><p>{{ $successMessage }}</p></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert" id="failedUbahSiswa"
        style="display:none;">
        <strong>
            <p id="failedUbahSiswaText"></p>
        </strong>
        <button type="button" class="close" onclick="$('#failedUbahSiswa').hide();" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form id="myForm">
    @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        @if ($permission == 'edit')
                            <h4 class="card-title">Ubah Data Siswa</h4>
                        @elseif($permission == 'insert')
                            <h4 class="card-title">Tambah Siswa Baru</h4>
                            <br/>
                        @endif

                        <ul class="nav nav-pills nav-pills-primary" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#link1" role="tablist" aria-expanded="true">
                                    Isian Data Siswa
                                </a>
                            </li>
                            @if(auth()->user()->peran->kode_peran <= 4)
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#link2" role="tablist" aria-expanded="false">
                                    Isian Data Orangtua
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content tab-space">
                            <div class="tab-pane active" id="link1" aria-expanded="true">
                                <div class="form-row">
                                    <input type="hidden" name="id" value="{{ !empty($data) ? $data->id : null }}" id="id">
                                    <input type="hidden" name="permission" value="{{ $permission }}" id="permission">
                                    <div class="form-group col-md-6">
                                        <label for="nama_lengkap">Nama Lengkap<span style="color:red">*</span></label>
                                        <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="Nama Lengkap" value="{{ !empty($data) ? $data->nama_lengkap : null }}" aria-describedby="nama_lengkap">
                                        <small class="text-danger error" id="err-nama_lengkap"></small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="nomor_registrasi_gsn">Nomor Registrasi GSN<span style="color:red">*</span></label>
                                        <input @if(Route::current()->getName() == 'siswa.edit') readonly @endif type="text" class="form-control" name="nomor_registrasi_gsn" id="nomor_registrasi_gsn" placeholder="Nomor Registrasi GSN" value="{{ !empty($data) ? $data->nomor_registrasi_gsn : null }}" aria-describedby="nomor_registrasi_gsn">
                                        @if(Route::current()->getName() == 'siswa.tambah')<small><i>Huruf menggunakan kapital, dan tidak ada spasi</i></small> @endif
                                        <small class="text-danger error" id="err-nomor_registrasi_gsn"></small>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="tempat_lahir">Tempat Lahir<span style="color:red">*</span></label>
                                        <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" placeholder="Tempat Lahir" value="{{ !empty($data) ? $data->tempat_lahir : null }}" aria-describedby="tempat_lahir">
                                        <small class="text-danger error" id="err-tempat_lahir"></small>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="tanggal_lahir">Tanggal Lahir<span style="color:red">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" placeholder="Tanggal Lahir" value="{{ !empty($data) ? $data->tanggal_lahir : null }}" aria-describedby="tanggal_lahir">
                                        <small class="text-danger error" id="err-tanggal_lahir"></small>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="jenis_kelamin">Jenis Kelamin<span style="color:red">*</span></label>
                                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                                            <option value="none" selected>-- Pilih Jenis Kelamin --</option>
                                            @foreach ($jenis_kelamin as $jk)
                                            <option value="{{ $jk }}" @if(!empty($data) and $jk == $data->jenis_kelamin) selected @endif>{{ $jk }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger error" id="err-jenis_kelamin"></small>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="alamat_rumah">Alamat Rumah<span style="color:red">*</span></label>
                                        <input type="text" class="form-control" name="alamat_rumah" id="alamat_rumah" placeholder="Alamat Rumah" value="{{ !empty($data) ? $data->alamat_rumah : null }}" aria-describedby="alamat_rumah">
                                        <small class="text-danger error" id="err-alamat_rumah"></small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="alamat_sekolah">Alamat Sekolah<span style="color:red">*</span></label>
                                        <input type="text" class="form-control" name="alamat_sekolah" id="alamat_sekolah" placeholder="Alamat Sekolah" value="{{ !empty($data) ? $data->alamat_sekolah : null }}" aria-describedby="alamat_sekolah">
                                        <small class="text-danger error" id="err-alamat_sekolah"></small>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="id_kabupaten">Kabupaten<span style="color:red">*</span></label>
                                        <select name="id_kabupaten" id="id_kabupaten" class="form-control"  onchange="populateKecamatan()">
                                            <option value="none">-- Pilih Kabupaten --</option>
                                            @foreach($kabupatenList as $kabupaten)
                                            <option value="{{ $kabupaten->kode_kabupaten }}" @if(!empty($data) and $kabupaten->kode_kabupaten == $data->kode_kabupaten) selected @endif>{{ $kabupaten->kabupaten }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger error" id="err-id_kabupaten"></small>
                                    </div>
                                    <div class="form-group col-md-6" id="kecamatan_dropdown">
                                        <label for="id_kecamatan" class="required">Kecamatan<span style="color:red">*</span></label>
                                        <select name="id_kecamatan" id="id_kecamatan" class="form-control">
                                            <option value="none">-- Pilih Kecamatan --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="jarak">Jarak ke Sekolah<span style="color:red">*</span></label>
                                        <select name="jarak" id="jarak" class="form-control">
                                            <option value="none" selected>-- Pilih Jarak --</option>
                                            @foreach ($jarak as $j)
                                            <option value="{{ $j }}" @if(!empty($data) and $j == $data->jarak_ke_sekolah) selected @endif>{{ $j }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger error" id="err-jarak"></small>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="tingkat_pendidikan">Tingkat Pendidikan<span style="color:red">*</span></label>
                                        <select name="tingkat_pendidikan" id="tingkat_pendidikan" class="form-control">
                                            <option value="none" selected>-- Pilih Tingkat Pendidikan --</option>
                                            @foreach ($pendidikan as $tp)
                                            <option value="{{ $tp }}" @if(!empty($data) and $tp == $data->tingkat_pendidikan) selected @endif>{{ $tp }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger error" id="err-tingkat_pendidikan"></small>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="umur">Umur<span style="color:red">*</span></label>
                                        <input type="text" name="umur" id="umur" class="form-control" value="{{ !empty($data) ? $data->umur : null }}" placeholder="Umur">
                                        <small class="text-danger error" id="err-umur"></small>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="kelas">Kelas<span style="color:red">*</span></label>
                                        <select name="kelas" id="kelas" class="form-control">
                                            <option value="none" selected>-- Pilih Kelas --</option>
                                            @foreach ($kelas as $k)
                                            <option value="{{ $k }}" @if(!empty($data) and $k == $data->kelas) selected @endif>{{ $k }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger error" id="err-kelas"></small>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="semester">Semester<span style="color:red">*</span></label>
                                        <select name="semester" id="semester" class="form-control">
                                            <option value="none" selected>-- Pilih Semester --</option>
                                            @foreach ($semester as $s)
                                            <option value="{{ $s }}" @if(!empty($data) and $s == $data->semester) selected @endif>{{ $s }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger error" id="err-semester"></small>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="no_telp">Nomor Telp/HP<span style="color:red">*</span></label>
                                        <input type="text" name="no_telp" id="no_telp" class="form-control" value="{{ !empty($data) ? $data->no_telp : null }}" placeholder="Nomor Telpon">
                                        <small class="text-danger error" id="err-no_telp"></small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Email<span style="color:red">*</span></label>
                                        <input type="text" name="email" id="email" class="form-control" value="{{ !empty($data) ? $data->email : null }}" placeholder="Email">
                                        <small class="text-danger error" id="err-email"></small>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label for="foto_siswa">Foto Anak<span style="color:red">*</span></label>
                                        <input style="opacity:inherit; position:relative" type="file" name="foto_siswa" class="form-control">
                                        @if(!empty($data) and !empty($data->foto_siswa))
                                            @if(str_contains(strtolower($data->foto_siswa), 'google'))
                                                <a href="{{ $data->foto_siswa }}" target="_blank">File Sebelumnya</a>
                                            @else
                                                <a href="{{ url('/file_siswa/'. $data->id .'/' .$data->foto_siswa) }}" target="_blank">File Sebelumnya</a>
                                            @endif
                                        @endif
                                        <small class="text-danger error" id="err-foto_siswa"></small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="foto_sekolah">Foto Sekolah<span style="color:red">*</span></label>
                                        <input style="opacity:inherit; position:relative" type="file" name="foto_sekolah" class="form-control">
                                        @if(!empty($berkas_siswa) and !empty($berkas_siswa->foto_sekolah))
                                            @if(str_contains(strtolower($berkas_siswa->foto_sekolah), 'google'))
                                                <a href="{{ $berkas_siswa->foto_sekolah }}" target="_blank">File Sebelumnya</a>
                                            @else
                                                <a href="{{ url('/file_siswa/'. $data->id .'/' .$berkas_siswa->foto_sekolah) }}" target="_blank">File Sebelumnya</a>
                                            @endif
                                        @endif
                                        <small class="text-danger error" id="err-foto_sekolah"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="link2" aria-expanded="false">
                                {{-- Ortu --}}
                                <div class="row mb-3">
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Nama Ayah</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="nama_ayah" id="nama_ayah" class="form-control" value="{{ !empty($ortu) ? $ortu->nama_ayah : null }}">
                                            <small class="text-danger error" id="err-nama_ayah"></small>
                                        </div>
                                    </div>
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Nama Ibu</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="nama_ibu" id="nama_ibu" class="form-control" value="{{ !empty($ortu) ? $ortu->nama_ibu : null }}">
                                            <small class="text-danger error" id="err-nama_ibu"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Tempat Lahir Ayah</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="tempat_lahir_ayah" id="tempat_lahir_ayah" class="form-control" value="{{ !empty($ortu) ? $ortu->tempat_lahir_ayah : null }}">
                                            <small class="text-danger error" id="err-tempat_lahir_ayah"></small>
                                        </div>
                                    </div>
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Tempat Lahir Ibu</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="tempat_lahir_ibu" id="tempat_lahir_ibu" class="form-control" value="{{ !empty($ortu) ? $ortu->tempat_lahir_ibu : null }}">
                                            <small class="text-danger error" id="err-tempat_lahir_ibu"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Tanggal Lahir Ayah</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="date" name="tgl_lahir_ayah" id="tgl_lahir_ayah" class="form-control" value="{{ !empty($ortu) ? $ortu->tgl_lahir_ayah : null }}">
                                            <small class="text-danger error" id="err-tgl_lahir_ayah"></small>
                                        </div>
                                    </div>
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Tanggal Lahir Ibu</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="date" name="tgl_lahir_ibu" id="tgl_lahir_ibu" class="form-control" value="{{ !empty($ortu) ? $ortu->tgl_lahir_ibu : null }}">
                                            <small class="text-danger error" id="err-tgl_lahir_ibu"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Pekerjaan Ayah</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" class="form-control" value="{{ !empty($ortu) ? $ortu->pekerjaan_ayah : null }}">
                                            <small class="text-danger error" id="err-pekerjaan_ayah"></small>
                                        </div>
                                    </div>
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Pekerjaan Ibu</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" class="form-control" value="{{ !empty($ortu) ? $ortu->pekerjaan_ibu : null }}">
                                            <small class="text-danger error" id="err-pekerjaan_ibu"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Penghasilan Ayah per Bulan</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <select name="penghasilan_bulan_ayah" id="penghasilan_bulan_ayah" class="form-control">
                                                <option value="none" selected>-- Pilih Opsi --</option>
                                                @foreach ($penghasilan as $p)
                                                <option value="{{ $p }}" @if(!empty($ortu) and $p == $ortu->penghasilan_bulan_ayah) selected @endif>{{ $p }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-danger error" id="err-penghasilan_bulan_ayah"></small>
                                        </div>
                                    </div>
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Penghasilan Ibu per Bulan</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <select name="penghasilan_bulan_ibu" id="penghasilan_bulan_ibu" class="form-control">
                                                <option value="none" selected>-- Pilih Opsi --</option>
                                                @foreach ($penghasilan as $p)
                                                <option value="{{ $p }}" @if(!empty($ortu) and $p == $ortu->penghasilan_bulan_ibu) selected @endif>{{ $p }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-danger error" id="err-penghasilan_bulan_ibu"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Keahlian Ayah</b></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="keahlian_ayah" id="keahlian_ayah" class="form-control" value="{{ !empty($ortu) ? $ortu->keahlian_ayah : null }}">
                                        </div>
                                    </div>
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Keahlian Ibu</b></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="keahlian_ibu" id="keahlian_ibu" class="form-control" value="{{ !empty($ortu) ? $ortu->keahlian_ibu : null }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Peluang Usaha Ayah</b></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="peluang_usaha_ayah" id="peluang_usaha_ayah" class="form-control" value="{{ !empty($ortu) ? $ortu->peluang_usaha_ayah : null }}">
                                        </div>
                                    </div>
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Peluang Usaha Ibu</b></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="peluang_usaha_ibu" id="peluang_usaha_ibu" class="form-control" value="{{ !empty($ortu) ? $ortu->peluang_usaha_ibu : null }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Alamat Rumah Ayah</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="alamat_ayah" id="alamat_ayah" class="form-control" value="{{ !empty($ortu) ? $ortu->alamat_ayah : null }}">
                                            <small class="text-danger error" id="err-alamat_ayah"></small>
                                        </div>
                                    </div>
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Alamat Rumah Ibu</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="alamat_ibu" id="alamat_ibu" class="form-control" value="{{ !empty($ortu) ? $ortu->alamat_ibu : null }}">
                                            <small class="text-danger error" id="err-alamat_ibu"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Jarak ke Kota Ayah</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <select name="jarak_ke_kota_ayah" id="jarak_ke_kota_ayah" class="form-control">
                                                <option value="none" selected>-- Pilih Jarak --</option>
                                                @foreach ($jarak as $j)
                                                <option value="{{ $j }}" @if(!empty($ortu) and $j == $ortu->jarak_ke_kota_ayah) selected @endif>{{ $j }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-danger error" id="err-jarak_ke_kota_ayah"></small>
                                        </div>
                                    </div>
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Jarak ke Kota Ibu</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <select name="jarak_ke_kota_ibu" id="jarak_ke_kota_ibu" class="form-control">
                                                <option value="none" selected>-- Pilih Jarak --</option>
                                                @foreach ($jarak as $j)
                                                <option value="{{ $j }}" @if(!empty($ortu) and $j == $ortu->jarak_ke_kota_ibu) selected @endif>{{ $j }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-danger error" id="err-jarak_ke_kota_ibu"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Nomor Telpon Ayah</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="no_telp_ayah" id="no_telp_ayah" class="form-control" value="{{ !empty($ortu) ? $ortu->no_telp_ayah : null }}">
                                            <small class="text-danger error" id="err-no_telp_ayah"></small>
                                        </div>
                                    </div>
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Nomor Telpon Ibu</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="no_telp_ibu" id="no_telp_ibu" class="form-control" value="{{ !empty($ortu) ? $ortu->no_telp_ibu : null }}">
                                            <small class="text-danger error" id="err-no_telp_ibu"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Foto Orangtua</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <input style="opacity:inherit; position:inherit" type="file" name="foto_orangtua" class="form-control">
                                            @if(!empty($ortu) and !empty($ortu->foto_orangtua))
                                                @if(str_contains(strtolower($ortu->foto_orangtua), 'google'))
                                                    <a href="{{ $ortu->foto_orangtua }}" target="_blank">File Sebelumnya</a>
                                                @else
                                                    <a href="{{ url('/file_siswa/'. $data->id .'/' .$ortu->foto_orangtua) }}" target="_blank">File Sebelumnya</a>
                                                @endif
                                            @endif
                                            <small class="text-danger error" id="err-foto_orangtua"></small>
                                        </div>
                                    </div>
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Foto Rumah Tampak Depan</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <input style="opacity:inherit; position:inherit" type="file" name="foto_rumah_depan" class="form-control">
                                            @if(!empty($berkas_siswa) and !empty($berkas_siswa->foto_rumah_depan))
                                                @if(str_contains(strtolower($berkas_siswa->foto_rumah_depan), 'google'))
                                                    <a href="{{ $berkas_siswa->foto_rumah_depan }}" target="_blank">File Sebelumnya</a>
                                                @else
                                                    <a href="{{ url('/file_siswa/'. $data->id .'/' .$berkas_siswa->foto_rumah_depan) }}" target="_blank">File Sebelumnya</a>
                                                @endif
                                            @endif
                                            <small class="text-danger error" id="err-foto_rumah_depan"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Foto Rumah Tampak Samping atau Belakang</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <input style="opacity:inherit; position:inherit" type="file" name="foto_rumah_samping" class="form-control">
                                            @if(!empty($berkas_siswa) and !empty($berkas_siswa->foto_rumah_samping))
                                                @if(str_contains(strtolower($berkas_siswa->foto_rumah_samping), 'google'))
                                                    <a href="{{ $berkas_siswa->foto_rumah_samping }}" target="_blank">File Sebelumnya</a>
                                                @else
                                                    <a href="{{ url('/file_siswa/'. $data->id .'/' .$berkas_siswa->foto_rumah_samping) }}" target="_blank">File Sebelumnya</a>
                                                @endif
                                            @endif
                                            <small class="text-danger error" id="err-foto_rumah_samping"></small>
                                        </div>
                                    </div>
                                    <div class="row col-md-6">
                                        <label class="col-sm-4 col-label-form"><b>Foto Kantor Desa</b><span style="color:red">*</span></label>
                                        <div class="col-sm-8">
                                            <input style="opacity:inherit; position:inherit" type="file" name="foto_kantor_desa" class="form-control">
                                            @if(!empty($berkas_siswa) and !empty($berkas_siswa->foto_kantor_desa))
                                                @if(str_contains(strtolower($berkas_siswa->foto_kantor_desa), 'google'))
                                                    <a href="{{ $berkas_siswa->foto_kantor_desa }}" target="_blank">File Sebelumnya</a>
                                                @else
                                                    <a href="{{ url('/file_siswa/'. $data->id .'/' .$berkas_siswa->foto_kantor_desa) }}" target="_blank">File Sebelumnya</a>
                                                @endif
                                            @endif
                                            <small class="text-danger error" id="err-foto_kantor_desa"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button id="submit" type="submit" class="btn btn-primary btn-1">Simpan</button>
                                    <a id="ubah" class="btn btn-dark btn-1" href="{{ route('siswa.index') }}">Batal</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script>
    var permission = '<?php echo $permission; ?>';
    var errorMessage = 'Gagal Mengubah Data Siswa'
    if(permission == 'insert') {
        errorMessage = 'Gagal Menambah Data Siswa'
    }

    var successMessage = 'Data Siswa Berhasil Diubah'
    if(permission == 'insert') {
        successMessage = 'Data Siswa Berhasil Ditambah'
    }

    $(document).ready(function() {
        $('#kecamatan_dropdown').hide();
    });

    function populateKecamatan() {
        let kodeKabupaten = $('#id_kabupaten')
        console.log(kodeKabupaten.val());
        $('#kecamatan_dropdown').show();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            url: "{{ route('reg.getKecamatan') }}" ,
            type: "GET",
            data: {kodeKabupaten: kodeKabupaten.val()},
            success: function (data) {
                var kecamatan = $("#id_kecamatan");
                $("#id_kecamatan").empty();
                console.log(data.length)
                if (data.length != 0) {
                    $.each(data, function () {
                        kecamatan.append($("<option />").val(this.kode_kecamatan).text(this.kecamatan));
                    });
                } else {
                    $('#kecamatan_dropdown').hide();
                }
            },
        });
    }

    $('#submit').click(function(e) {
        $('#myForm').submit(function (event) {
            // console.log('permission ' + permission)
            event.preventDefault()
            event.stopImmediatePropagation();

            $('#submit').attr('disabled', 'disabled');
            var formData = new FormData($(this)[0]);
            formData.append('permission', permission);
            $('#failedUbahSiswa').hide();
            $('.error').each(function (i, obj) {
                obj.innerHTML = ''
            });
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('siswa.store') }}",
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
                            window.location.href = "{{ route('siswa.index') }}"
                        } else {
                            console.log('store success '+data.id )
                            window.location.href = "{{ route('siswa.show') }}" + '?id=' + data.id +'&message=' + successMessage
                        }
                    } else {
                        $('.error').each(function (i, obj) {
                            if (data.message[obj.id.substring(4)]) {
                                obj.innerHTML = data.message[obj.id.substring(4)].filter(Boolean).join(", ")
                            }
                        });

                        $('#failedUbahSiswaText').html(errorMessage);
                        $('#failedUbahSiswa').show();
                    }
                    $('#submit').removeAttr('disabled');
                },
                error: function (data) {
                    $('#failedUbahSiswaText').html(JSON.stringify(data));
                    $('#failedUbahSiswa').show();
                    $('#submit').removeAttr('disabled');
                }
            });
        })
    })
</script>
@endsection

@extends('layouts.app', [
    'class' => 'reg-siswa-page',
    'elementActive' => 'form_siswa'
    // 'backgroundImagePath' => 'img/bg/jan-sendereks.jpg'
])

@section('content')
    <div class="content" style='padding-top: 10vh'>
        @if ($sukses = Session::get('sukses'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $sukses }}</strong>
        </div>
        @endif
        @if ($fail = Session::get('fail'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="failed" style="display:none;">
            <strong>
                <strong>{{ $fail }}</strong>
            </strong>
            <button type="button" class="close" onclick="$('#failed').hide();" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <div class="container">
            <form action="{{ route('reg.postDataSiswa') }}" method="post" id="post_data_siswa" enctype="multipart/form-data">
                @csrf
                <div class="row" id="top">
                    <h4 style="margin:auto;padding-bottom:1em;color:whitesmoke">Verifikasi Data Anak dan Orang Tua GSN</h4>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="nav-tabs-navigation">
                                    <div class="nav-tabs-wrapper">
                                        <ul class="nav nav-tabs" data-tabs="tabs">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#link1" data-toggle="tab"><h5><b>Data Siswa</b></h5></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#link2" data-toggle="tab"><h5><b>Data Orangtua</b></h5></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="tab-content tab-space">
                                    <div class="tab-pane active" id="link1" aria-expanded="true">
                                        <h6>Isian dengan tanda <span style="color:red">*</span> wajib diisi</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nama_lengkap">Nama Lengkap<span style="color:red">*</span></label>
                                                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" placeholder="Nama Lengkap">
                                                    @if($errors->has('nama_lengkap'))
                                                    <small class="text-danger">{{ $errors->first('nama_lengkap')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nomor_registrasi_gsn">Nomor Registrasi GSN<span style="color:red">*</span></label>
                                                    <input type="text" name="nomor_registrasi_gsn" id="nomor_registrasi_gsn" class="form-control" placeholder="Nomor Registrasi GSN">
                                                    @if($errors->has('nomor_registrasi_gsn'))
                                                    <small class="text-danger">{{ $errors->first('nomor_registrasi_gsn')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="tempat_lahir">Tempat Lahir<span style="color:red">*</span></label>
                                                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" placeholder="Tempat Lahir">
                                                    @if($errors->has('tempat_lahir'))
                                                    <small class="text-danger">{{ $errors->first('tempat_lahir')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="tanggal_lahir">Tanggal Lahir<span style="color:red">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                            </div>
                                                        </div>
                                                        <input class="form-control" type="date" name="tanggal_lahir" id="tanggal_lahir">
                                                    </div>
                                                    @if($errors->has('tanggal_lahir'))
                                                    <small class="text-danger">{{ $errors->first('tanggal_lahir')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="jenis_kelamin">Jenis Kelamin<span style="color:red">*</span></label>
                                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                                                        <option value="" selected>-- Pilih Jenis Kelamin --</option>
                                                        @foreach ($jenis_kelamin as $jk)
                                                        <option value="{{ $jk }}">{{ $jk }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('jenis_kelamin'))
                                                    <small class="text-danger">{{ $errors->first('jenis_kelamin')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="alamat_rumah">Alamat Rumah<span style="color:red">*</span></label>
                                                    <input type="text" name="alamat_rumah" id="alamat_rumah" class="form-control" placeholder="Alamat Rumah">
                                                    @if($errors->has('alamat_rumah'))
                                                    <small class="text-danger">{{ $errors->first('alamat_rumah')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sekolah">Alamat Sekolah<span style="color:red">*</span></label>
                                                    <input type="text" name="alamat_sekolah" id="alamat_sekolah" class="form-control" placeholder="Alamat Sekolah">
                                                    {{-- <select name="id_sekolah" id="id_sekolah" class="form-control">
                                                        <option value="" selected>-- Pilih Sekolah --</option>
                                                        @foreach ($sekolah as $s)
                                                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                                                        @endforeach
                                                    </select> --}}
                                                    @if($errors->has('alamat_sekolah'))
                                                    <small class="text-danger">{{ $errors->first('alamat_sekolah')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_kabupaten">Kabupaten<span style="color:red">*</span></label>
                                                    <select name="id_kabupaten" id="id_kabupaten" class="form-control"  onchange="populateKecamatan()">
                                                        <option value="">-- Pilih Kabupaten --</option>
                                                        @foreach($kabupatenList as $kabupaten)
                                                        <option value="{{ $kabupaten->kode_kabupaten }}" >{{ $kabupaten->kabupaten }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('id_kabupaten'))
                                                    <small class="text-danger">{{ $errors->first('id_kabupaten')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group" id="kecamatan_dropdown">
                                                    <label for="id_kecamatan" class="required">Kecamatan<span style="color:red">*</span></label>
                                                    <select name="id_kecamatan" id="id_kecamatan" class="form-control">
                                                        <option value="">-- Pilih Kecamatan --</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="jarak">Jarak ke Sekolah<span style="color:red">*</span></label>
                                                    <select name="jarak" id="jarak" class="form-control">
                                                        <option value="" selected>-- Pilih Jarak --</option>
                                                        @foreach ($jarak as $j)
                                                        <option value="{{ $j }}">{{ $j }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('jarak'))
                                                    <small class="text-danger">{{ $errors->first('jarak')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="tingkat_pendidikan">Tingkat Pendidikan<span style="color:red">*</span></label>
                                                    <select name="tingkat_pendidikan" id="tingkat_pendidikan" class="form-control">
                                                        <option value="" selected>-- Pilih Tingkat Pendidikan --</option>
                                                        @foreach ($pendidikan as $tp)
                                                        <option value="{{ $tp }}">{{ $tp }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('tingkat_pendidikan'))
                                                    <small class="text-danger">{{ $errors->first('tingkat_pendidikan')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="umur">Umur<span style="color:red">*</span></label>
                                                    <input type="number" name="umur" id="umur" class="form-control" placeholder="Umur">
                                                    @if($errors->has('umur'))
                                                    <small class="text-danger">{{ $errors->first('umur')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="kelas">Kelas<span style="color:red">*</span></label>
                                                    <select name="kelas" id="kelas" class="form-control">
                                                        <option value="" selected>-- Pilih Kelas --</option>
                                                        @foreach ($kelas as $k)
                                                        <option value="{{ $k }}">{{ $k }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('kelas'))
                                                    <small class="text-danger">{{ $errors->first('kelas')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="semester">Semester<span style="color:red">*</span></label>
                                                    <select name="semester" id="semester" class="form-control">
                                                        <option value="" selected>-- Pilih Semester --</option>
                                                        @foreach ($semester as $s)
                                                        <option value="{{ $s }}">{{ $s }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('semester'))
                                                    <small class="text-danger">{{ $errors->first('semester')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="no_telp">Nomor Telp/HP<span style="color:red">*</span></label>
                                                    <input type="text" name="no_telp" id="no_telp" class="form-control" placeholder="Nomor Telpon">
                                                    @if($errors->has('no_telp'))
                                                    <small class="text-danger">{{ $errors->first('no_telp')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Email<span style="color:red">*</span></label>
                                                    <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                                                    @if($errors->has('email'))
                                                    <small class="text-danger">{{ $errors->first('email')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <hr><br>
                                        {{-- <h5 class="text-center">REALISASI SUMBANGAN PEMBANGUNAN PENDIDIKAN (SPP) DAN REGISTRASI</h5>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <p>Tahap 1<span style="color:red">*</span></p>
                                                    <input type="text" name="tahap_1" id="tahap_1" class="form-control">
                                                    @if($errors->has('tahap_1'))
                                                    <small class="text-danger">{{ $errors->first('tahap_1')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <p>Tahap 2<span style="color:red">*</span></p>
                                                    <input type="text" name="tahap_2" id="tahap_2" class="form-control">
                                                    @if($errors->has('tahap_2'))
                                                    <small class="text-danger">{{ $errors->first('tahap_2')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <p>Tahap 3<span style="color:red">*</span></p>
                                                    <input type="text" name="tahap_3" id="tahap_3" class="form-control">
                                                    @if($errors->has('tahap_3'))
                                                    <small class="text-danger">{{ $errors->first('tahap_3')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <hr><br>
                                        <h5 class="text-center">Seragam</h5>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <p>Seragam Nasional</p>
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" id="seragam_nasional" name="seragam_nasional" type="checkbox">
                                                            <span id = "seragam_nasional_text" class="form-check-sign">Belum terima</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <p>Seragam Putih Biru</p>
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" id="seragam_putih_biru" name="seragam_putih_biru" type="checkbox">
                                                            <span id = "seragam_putih_biru_text" class="form-check-sign">Belum terima</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <p>Seragam Putih Abu</p>
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" id="seragam_putih_abu" name="seragam_putih_abu" type="checkbox">
                                                            <span id = "seragam_putih_abu_text" class="form-check-sign">Belum terima</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <p>Seragam Pramuka</p>
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" id="seragam_pramuka" name="seragam_pramuka" type="checkbox">
                                                            <span id = "seragam_pramuka_text" class="form-check-sign">Belum terima</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr><br>
                                        <h5 class="text-center">Tas</h5>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <p>Tas PAUD</p>
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" id="tas_paud" name="tas_paud" type="checkbox">
                                                            <span id = "tas_paud_text" class="form-check-sign">Belum terima</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <p>Tas SD</p>
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" id="tas_sd" name="tas_sd" type="checkbox">
                                                            <span id = "tas_sd_text" class="form-check-sign">Belum terima</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <p>Tas SMP</p>
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" id="tas_smp" name="tas_smp" type="checkbox">
                                                            <span id = "tas_smp_text" class="form-check-sign">Belum terima</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <p>Tas SMA/SMK</p>
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" id="tas_sma_smk" name="tas_sma_smk" type="checkbox">
                                                            <span id = "tas_sma_smk_text" class="form-check-sign">Belum terima</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <p>Tas Perguruan Tinggi</p>
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" id="tas_perguruan_tinggi" name="tas_perguruan_tinggi" type="checkbox">
                                                            <span id = "tas_perguruan_tinggi_text" class="form-check-sign">Belum terima</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr><br>
                                        <h5 class="text-center">Buku & Pulpen</h5>
                                        <div class="row">
                                            <div class="col-md-3">
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <p>Buku</p>
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" id="buku" name="buku" type="checkbox">
                                                            <span id = "buku_text" class="form-check-sign">Belum terima</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <p>Pulpen</p>
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" id="bulpen" name="bulpen" type="checkbox">
                                                            <span id ="bulpen_text" class="form-check-sign">Belum terima</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                            </div>
                                        </div>
                                        <hr><br> --}}
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="foto_siswa">Foto Anak<span style="color:red">*</span></label>
                                                    <input style="opacity:inherit; position:inherit" type="file" name="foto_siswa" class="form-control">
                                                    @if($errors->has('foto_siswa'))
                                                    <small class="text-danger">{{ $errors->first('foto_siswa')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="foto_sekolah">Foto Sekolah<span style="color:red">*</span></label>
                                                    <input style="opacity:inherit; position:inherit" type="file" name="foto_sekolah" class="form-control">
                                                    @if($errors->has('foto_sekolah'))
                                                    <small class="text-danger">{{ $errors->first('foto_sekolah')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <a class="btn btn-warning" href="#top">back to top</a>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="link2" aria-expanded="false">
                                        <h6>Isian dengan tanda <span style="color:red">*</span> wajib diisi</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Ayah<span style="color:red">*</span></label>
                                                    <input type="text" name="nama_ayah" id="nama_ayah" class="form-control" placeholder="Nama Ayah">
                                                    @if($errors->has('nama_ayah'))
                                                    <small class="text-danger">{{ $errors->first('nama_ayah')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Ibu<span style="color:red">*</span></label>
                                                    <input type="text" name="nama_ibu" id="nama_ibu" class="form-control" placeholder="Nama Ibu">
                                                    @if($errors->has('nama_ibu'))
                                                    <small class="text-danger">{{ $errors->first('nama_ibu')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tempat Lahir Ayah<span style="color:red">*</span></label>
                                                    <input type="text" name="tempat_lahir_ayah" id="tempat_lahir_ayah" class="form-control" placeholder="Tempat Lahir Ayah">
                                                    @if($errors->has('tempat_lahir_ayah'))
                                                    <small class="text-danger">{{ $errors->first('tempat_lahir_ayah')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tempat Lahir Ibu<span style="color:red">*</span></label>
                                                    <input type="text" name="tempat_lahir_ibu" id="tempat_lahir_ibu" class="form-control" placeholder="Tempat Lahir Ibu">
                                                    @if($errors->has('tempat_lahir_ibu'))
                                                    <small class="text-danger">{{ $errors->first('tempat_lahir_ibu')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tanggal Lahir Ayah<span style="color:red">*</span></label>
                                                    <input type="date" name="tgl_lahir_ayah" id="tgl_lahir_ayah" class="form-control" placeholder="Tanggal Lahir Ayah">
                                                    @if($errors->has('tgl_lahir_ayah'))
                                                    <small class="text-danger">{{ $errors->first('tgl_lahir_ayah')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tanggal Lahir Ibu<span style="color:red">*</span></label>
                                                    <input type="date" name="tgl_lahir_ibu" id="tgl_lahir_ibu" class="form-control" placeholder="Tanggal Lahir Ibu">
                                                    @if($errors->has('tgl_lahir_ibu'))
                                                    <small class="text-danger">{{ $errors->first('tgl_lahir_ibu')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Pekerjaan Ayah<span style="color:red">*</span></label>
                                                    <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" class="form-control" placeholder="Pekerjaan Ayah">
                                                    @if($errors->has('pekerjaan_ayah'))
                                                    <small class="text-danger">{{ $errors->first('pekerjaan_ayah')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Pekerjaan Ibu<span style="color:red">*</span></label>
                                                    <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" class="form-control" placeholder="Pekerjaan Ibu">
                                                    @if($errors->has('pekerjaan_ibu'))
                                                    <small class="text-danger">{{ $errors->first('pekerjaan_ibu')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="penghasilan_bulan_ayah">Penghasilan Ayah (per bulan)<span style="color:red">*</span></label>
                                                    <select name="penghasilan_bulan_ayah" id="penghasilan_bulan_ayah" class="form-control">
                                                        <option value="" selected>-- Pilih Opsi --</option>
                                                        @foreach ($penghasilan as $p)
                                                        <option value="{{ $p }}">{{ $p }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('penghasilan_bulan_ayah'))
                                                    <small class="text-danger">{{ $errors->first('penghasilan_bulan_ayah')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="penghasilan_bulan_ibu">Penghasilan Ibu (per bulan)<span style="color:red">*</span></label>
                                                    <select name="penghasilan_bulan_ibu" id="penghasilan_bulan_ibu" class="form-control">
                                                        <option value="" selected>-- Pilih Opsi --</option>
                                                        @foreach ($penghasilan as $p)
                                                        <option value="{{ $p }}">{{ $p }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('penghasilan_bulan_ibu'))
                                                    <small class="text-danger">{{ $errors->first('penghasilan_bulan_ibu')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Keahlian Ayah</label>
                                                    <input type="text" name="keahlian_ayah" id="keahlian_ayah" class="form-control" placeholder="Keahlian yang dimiliki Ayah">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Keahlian Ibu</label>
                                                    <input type="text" name="keahlian_ibu" id="keahlian_ibu" class="form-control" placeholder="Keahlian yang dimiliki Ibu">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Peluang Usaha Ayah</label>
                                                    <input type="text" name="peluang_usaha_ayah" id="peluang_usaha_ayah" class="form-control" placeholder="Peluang Usaha Ayah">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Peluang Usaha Ibu</label>
                                                    <input type="text" name="peluang_usaha_ibu" id="peluang_usaha_ibu" class="form-control" placeholder="Peluang Usaha Ibu">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Alamat Rumah Ayah<span style="color:red">*</span></label>
                                                    <input type="text" name="alamat_ayah" id="alamat_ayah" class="form-control" placeholder="Alamat Rumah Ayah">
                                                    @if($errors->has('alamat_ayah'))
                                                    <small class="text-danger">{{ $errors->first('alamat_ayah')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Alamat Rumah Ibu<span style="color:red">*</span></label>
                                                    <input type="text" name="alamat_ibu" id="alamat_ibu" class="form-control" placeholder="Alamat Rumah Ibu">
                                                    @if($errors->has('alamat_ibu'))
                                                    <small class="text-danger">{{ $errors->first('alamat_ibu')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="jarak_ke_kota_ayah">Jarak Rumah Ke Pusat Kota Ayah<span style="color:red">*</span></label>
                                                    <select name="jarak_ke_kota_ayah" id="jarak_ke_kota_ayah" class="form-control">
                                                        <option value="" selected>-- Pilih Jarak --</option>
                                                        @foreach ($jarak as $j)
                                                        <option value="{{ $j }}">{{ $j }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('jarak_ke_kota_ayah'))
                                                    <small class="text-danger">{{ $errors->first('jarak_ke_kota_ayah')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="jarak_ke_kota_ibu">Jarak Rumah Ke Pusat Kota Ibu<span style="color:red">*</span></label>
                                                    <select name="jarak_ke_kota_ibu" id="jarak_ke_kota_ibu" class="form-control">
                                                        <option value="" selected>-- Pilih Jarak --</option>
                                                        @foreach ($jarak as $j)
                                                        <option value="{{ $j }}">{{ $j }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('jarak_ke_kota_ibu'))
                                                    <small class="text-danger">{{ $errors->first('jarak_ke_kota_ibu')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nomor Telpon Ayah<span style="color:red">*</span></label>
                                                    <input type="text" name="no_telp_ayah" id="no_telp_ayah" class="form-control" placeholder="Nomor Telpon Ayah">
                                                    @if($errors->has('no_telp_ayah'))
                                                    <small class="text-danger">{{ $errors->first('no_telp_ayah')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nomor Telpon Ibu<span style="color:red">*</span></label>
                                                    <input type="text" name="no_telp_ibu" id="no_telp_ibu" class="form-control" placeholder="Nomor Telpon Ibu">
                                                    @if($errors->has('no_telp_ibu'))
                                                    <small class="text-danger">{{ $errors->first('no_telp_ibu')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="foto_orangtua">Foto Orang Tua<span style="color:red">*</span></label>
                                                    <input style="opacity:inherit; position:inherit"  type="file" name="foto_orangtua" id="foto_orangtua" class="form-control">
                                                    @if($errors->has('foto_orangtua'))
                                                    <small class="text-danger">{{ $errors->first('foto_orangtua')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="foto_rumah_depan">Foto Rumah Tampak Depan<span style="color:red">*</span></label>
                                                    <input style="opacity:inherit; position:inherit" type="file" name="foto_rumah_depan" id="foto_rumah_depan" class="form-control">
                                                    @if($errors->has('foto_rumah_depan'))
                                                    <small class="text-danger">{{ $errors->first('foto_rumah_depan')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="foto_kantor_desa">Foto Kantor Desa<span style="color:red">*</span></label>
                                                    <input style="opacity:inherit; position:inherit" type="file" name="foto_kantor_desa" id="foto_kantor_desa" class="form-control">
                                                    @if($errors->has('foto_kantor_desa'))
                                                    <small class="text-danger">{{ $errors->first('foto_kantor_desa')}}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="foto_rumah_samping">Foto Rumah Tampak Samping atau Belakang<span style="color:red">*</span></label>
                                                    <input style="opacity:inherit; position:inherit" type="file" name="foto_rumah_samping" id="foto_rumah_samping" class="form-control">
                                                    @if($errors->has('foto_rumah_samping'))
                                                    <small class="text-danger">{{ $errors->first('foto_rumah_samping')}}</small>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                        <div class="text-center">
                                            <input type="submit" id="submit-reg-siswa" class="btn btn-primary" value="Submit">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            demo.checkFullPageBackgroundImage();
            $('#kecamatan_dropdown').hide();

            $('#nomor_registrasi_gsn').on('keyup',function(e) {
                $(this).val().replace(/ /g, "");
            })

            // $('#tahap_1').on('keyup',function(e) {
            //     $(this).val(this.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
            // })

            $('#seragam_nasional').click(function(){
                if ($('#seragam_nasional').is(":checked")) {
                    $('#seragam_nasional_text').html('Terima')
                } else {
                    $('#seragam_nasional_text').html('Belum terima')
                }
            })

            $('#seragam_putih_biru').click(function(){
                if ($('#seragam_putih_biru').is(":checked")) {
                    $('#seragam_putih_biru_text').html('Terima')
                } else {
                    $('#seragam_putih_biru_text').html('Belum terima')
                }
            })

            $('#seragam_putih_abu').click(function(){
                if ($('#seragam_putih_abu').is(":checked")) {
                    $('#seragam_putih_abu_text').html('Terima')
                } else {
                    $('#seragam_putih_abu_text').html('Belum terima')
                }
            })

            $('#seragam_pramuka').click(function(){
                if ($('#seragam_pramuka').is(":checked")) {
                    $('#seragam_pramuka_text').html('Terima')
                } else {
                    $('#seragam_pramuka_text').html('Belum terima')
                }
            })

            $('#tas_paud').click(function(){
                if ($('#tas_paud').is(":checked")) {
                    $('#tas_paud_text').html('Terima')
                } else {
                    $('#tas_paud_text').html('Belum terima')
                }
            })

            $('#tas_sd').click(function(){
                if ($('#tas_sd').is(":checked")) {
                    $('#tas_sd_text').html('Terima')
                } else {
                    $('#tas_sd_text').html('Belum terima')
                }
            })

            $('#tas_smp').click(function(){
                if ($('#tas_smp').is(":checked")) {
                    $('#tas_smp_text').html('Terima')
                } else {
                    $('#tas_smp_text').html('Belum terima')
                }
            })

            $('#tas_sma_smk').click(function(){
                if ($('#tas_sma_smk').is(":checked")) {
                    $('#tas_sma_smk_text').html('Terima')
                } else {
                    $('#tas_sma_smk_text').html('Belum terima')
                }
            })

            $('#tas_perguruan_tinggi').click(function(){
                if ($('#tas_perguruan_tinggi').is(":checked")) {
                    $('#tas_perguruan_tinggi_text').html('Terima')
                } else {
                    $('#tas_perguruan_tinggi_text').html('Belum terima')
                }
            })

            $('#buku').click(function(){
                if ($('#buku').is(":checked")) {
                    $('#buku_text').html('Terima')
                } else {
                    $('#buku_text').html('Belum terima')
                }
            })

            $('#bulpen').click(function(){
                if ($('#bulpen').is(":checked")) {
                    $('#bulpen_text').html('Terima')
                } else {
                    $('#bulpen_text').html('Belum terima')
                }
            })
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

        function separateDigit(input) {
            input.value = input.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }


    </script>
@endpush

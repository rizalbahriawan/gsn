@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'siswa'
])

@section('title', 'Detail Siswa')

@section('content')

<div class="content">
    @if (!empty($successMessage))
    <div class="alert alert-success alert-dismissible fade show mt-3" onshow="dismissed()" role="alert" id="success-alert">
        <strong><p>{{ $successMessage }}</p></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills nav-pills-primary" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#link1" role="tablist" aria-expanded="true">
                                Data Siswa
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#link2" role="tablist" aria-expanded="false">
                                Data Orangtua
                            </a>
                        </li>
                        @if(auth()->user()->peran->kode_peran <= 4)
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#link3" role="tablist" aria-expanded="false">
                                Riwayat Proses
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#link4" role="tablist" aria-expanded="false">
                                Data Realisasi
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content tab-space">
                        <div class="tab-pane active" id="link1" aria-expanded="true">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Nama Lengkap</b></label>
                                <div class="col-sm-10">
                                    {{ $data->nama_lengkap }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Alamat Sekolah</b></label>
                                <div class="col-sm-10">
                                    {{ $data->alamat_sekolah }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Nomor Registrasi GSN</b></label>
                                <div class="col-sm-10">
                                    {{ $data->nomor_registrasi_gsn }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Tempat Lahir</b></label>
                                <div class="col-sm-10">
                                    {{ $data->tempat_lahir }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Tanggal Lahir</b></label>
                                <div class="col-sm-10">
                                    {{ $data->tanggal_lahir }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Jenis Kelamin</b></label>
                                <div class="col-sm-10">
                                    {{ $data->jenis_kelamin }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Alamat Rumah</b></label>
                                <div class="col-sm-10">
                                    {{ $data->alamat_rumah }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Jarak ke Sekolah</b></label>
                                <div class="col-sm-10">
                                    {{ $data->jarak_ke_sekolah }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Tingkat Pendidikan</b></label>
                                <div class="col-sm-10">
                                    {{ $data->tingkat_pendidikan }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Umur</b></label>
                                <div class="col-sm-10">
                                    {{ $data->umur }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Kelas</b></label>
                                <div class="col-sm-10">
                                    {{ $data->kelas }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Nomor Telepon</b></label>
                                <div class="col-sm-10">
                                    {{ $data->no_telp }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Email</b></label>
                                <div class="col-sm-10">
                                    {{ $data->email }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Foto Siswa</b></label>
                                <div class="col-sm-10">
                                    @if(!empty($data) and !empty($data->foto_siswa))
                                        @if(str_contains(strtolower($data->foto_siswa), 'google'))
                                            <a href="{{ $data->foto_siswa }}" target="_blank">Foto {{ $data->nama_lengkap }}</a>
                                        @else
                                            <a href="{{ url('/file_siswa/'. $data->id .'/' .$data->foto_siswa) }}" target="_blank">Foto {{ $data->nama_lengkap }}</a>
                                        @endif
                                    @else
                                    <p>Tidak ada foto</p>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Foto Sekolah</b></label>
                                <div class="col-sm-10">
                                    @if(!empty($berkas_siswa) and !empty($berkas_siswa->foto_sekolah))
                                        @if(str_contains(strtolower($berkas_siswa->foto_sekolah), 'google'))
                                        {{-- foto dari google drive ga bisa tampil krn src nya bukan langsung ke ekstension file (jpg jpeg png) tp link view dr google drive --}}
                                            <a href="{{ $berkas_siswa->foto_sekolah }}" target="_blank">Foto {{ $data->alamat_sekolah }}</a>
                                        @else
                                            <a href="{{ url('/file_siswa/'. $data->id .'/' .$berkas_siswa->foto_sekolah) }}" target="_blank">Foto {{ $data->alamat_sekolah }}</a>
                                        @endif
                                    @else
                                    <p>Tidak ada foto</p>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Foto Rumah Tampak Depan</b></label>
                                <div class="col-sm-10">
                                    @if(!empty($berkas_siswa) and !empty($berkas_siswa->foto_rumah_depan))
                                        @if(str_contains(strtolower($berkas_siswa->foto_rumah_depan), 'google'))
                                        {{-- foto dari google drive ga bisa tampil krn src nya bukan langsung ke ekstension file (jpg jpeg png) tp link view dr google drive --}}
                                            <a href="{{ $berkas_siswa->foto_rumah_depan }}" target="_blank">Foto Rumah Tampak Depan {{ $data->nama_lengkap }}</a>
                                        @else
                                            <a href="{{ url('/file_siswa/'. $data->id .'/' .$berkas_siswa->foto_rumah_depan) }}" target="_blank">Foto Rumah Tampak Depan {{ $data->nama_lengkap }}</a>
                                        @endif
                                    @else
                                    <p>Tidak ada foto</p>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Foto Rumah Tampak Samping</b></label>
                                <div class="col-sm-10">
                                    @if(!empty($berkas_siswa) and !empty($berkas_siswa->foto_rumah_samping))
                                        @if(str_contains(strtolower($berkas_siswa->foto_rumah_samping), 'google'))
                                        {{-- foto dari google drive ga bisa tampil krn src nya bukan langsung ke ekstension file (jpg jpeg png) tp link view dr google drive --}}
                                            <a href="{{ $berkas_siswa->foto_rumah_samping }}" target="_blank">Foto Rumah Tampak Samping {{ $data->nama_lengkap }}</a>
                                        @else
                                            <a href="{{ url('/file_siswa/'. $data->id .'/' .$berkas_siswa->foto_rumah_samping) }}" target="_blank">Foto Rumah Tampak Samping {{ $data->nama_lengkap }}</a>
                                        @endif
                                    @else
                                    <p>Tidak ada foto</p>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Foto Kantor Desa</b></label>
                                <div class="col-sm-10">
                                    @if(!empty($berkas_siswa) and !empty($berkas_siswa->foto_kantor_desa))
                                        @if(str_contains(strtolower($berkas_siswa->foto_kantor_desa), 'google'))
                                        {{-- foto dari google drive ga bisa tampil krn src nya bukan langsung ke ekstension file (jpg jpeg png) tp link view dr google drive --}}
                                            <a href="{{ $berkas_siswa->foto_kantor_desa }}" target="_blank">Foto Kantor Desa</a>
                                        @else
                                            <a href="{{ url('/file_siswa/'. $data->id .'/' .$berkas_siswa->foto_kantor_desa) }}" target="_blank">Foto Kantor Desa</a>
                                        @endif
                                    @else
                                    <p>Tidak ada foto</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="link2" aria-expanded="false">
                            {{-- Ayah --}}
                            <div class="row mb-3">
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Nama Ayah</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->nama_ayah : null }}
                                    </div>
                                </div>
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Nama Ibu</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->nama_ibu : null }}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Tempat Lahir Ayah</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->tempat_lahir_ayah : null }}
                                    </div>
                                </div>
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Tempat Lahir Ibu</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->tempat_lahir_ibu : null }}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Tanggal Lahir Ayah</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->tgl_lahir_ayah : null }}
                                    </div>
                                </div>
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Tanggal Lahir Ibu</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->tgl_lahir_ibu : null }}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Pekerjaan Ayah</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->pekerjaan_ayah : null }}
                                    </div>
                                </div>
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Pekerjaan Ibu</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->pekerjaan_ibu : null }}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Penghasilan Ayah per Bulan</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->penghasilan_bulan_ayah : null }}
                                    </div>
                                </div>
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Penghasilan Ibu per Bulan</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->penghasilan_bulan_ibu : null }}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Keahlian Ayah</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->keahlian_ayah : null }}
                                    </div>
                                </div>
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Keahlian Ibu</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->keahlian_ibu : null }}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Peluang Usaha Ayah</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->peluang_usaha_ayah : null }}
                                    </div>
                                </div>
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Peluang Usaha Ibu</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->peluang_usaha_ibu : null }}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Alamat Ayah</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->alamat_ayah : null }}
                                    </div>
                                </div>
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Alamat Ibu</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->alamat_ibu : null }}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Jarak ke Kota Ayah</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->jarak_ke_kota_ayah : null }}
                                    </div>
                                </div>
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Jarak ke Kota Ibu</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->jarak_ke_kota_ibu : null }}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Nomor Telpon Ayah</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->no_telp_ayah : null }}
                                    </div>
                                </div>
                                <div class="row col-md-6">
                                    <label class="col-sm-4 col-label-form"><b>Nomor Telpon Ibu</b></label>
                                    <div class="col-sm-8">
                                        {{ !empty($ortu) ? $ortu->no_telp_ibu : null }}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-label-form"><b>Foto Orangtua</b></label>
                                <div class="col-sm-10">
                                    @if(!empty($ortu) and !empty($ortu->foto_orangtua))
                                        @if(str_contains(strtolower($ortu->foto_orangtua), 'google'))
                                            <a href="{{ $ortu->foto_orangtua }}" target="_blank">Foto Orang Tua {{ $data->nama_lengkap }}</a>
                                        @else
                                            <a href="{{ url('/file_siswa/'. $data->id .'/' .$ortu->foto_orangtua) }}" target="_blank">Foto Orang Tua {{ $data->nama_lengkap }}</a>
                                        @endif
                                    @else
                                    <p>Tidak ada foto</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="link3" aria-expanded="false">
                            @if($is_aktif)
                            <p>Status: <b>Anak Aktif GSN</b></p>
                            @endif
                            <div class="table-responsive">
                                <table class="table" id="tabelRiwayatProses">
                                    <thead class=" text-primary">
                                        <th>Tanggal</th>
                                        <th>Tahap 1</th>
                                        <th>Tahap 2</th>
                                        <th>Tahap 3</th>
                                        <th>Aktivis yang Bertugas</th>
                                        <th>Catatan</th>
                                    </thead>
                                    <tbody>
                                        @if($proses)
                                            @foreach($proses as $row)
                                                <tr>
                                                    <td>{{ $row->waktu_proses }}</td>
                                                    <td>{{ $row->tahap_1 }}</td>
                                                    <td>{{ $row->tahap_2 }}</td>
                                                    <td>{{ $row->tahap_3 }}</td>
                                                    <td>{{ $row->name }}</td>
                                                    <td>{{ $row->status }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="link4" aria-expanded="false">
                            <div class="table-responsive">
                                <table class="table" id="tabelRealisasi">
                                    <thead class=" text-primary">
                                        <th class="text-center">Seragam Nasional</th>
                                        <th class="text-center">Seragam Putih Biru</th>
                                        <th class="text-center">Seragam Putih Abu</th>
                                        <th class="text-center">Seragam Pramuka</th>
                                        <th class="text-center">Tas PAUD</th>
                                        <th class="text-center">Tas SD</th>
                                        <th class="text-center">Tas SMP</th>
                                        <th class="text-center">Tas SMA/SMK</th>
                                        <th class="text-center">Tas PT</th>
                                        <th class="text-center">Buku</th>
                                        <th class="text-center">Bulpen</th>
                                        <th>Terakhir Update</th>
                                    </thead>
                                    <tbody>
                                        @if($realisasi)
                                            <tr>
                                                <td class="text-center">
                                                    @if ($realisasi->seragam_nasional == 0)
                                                        <button class="btn btn-danger" style="cursor:default">Belum Terima</button>
                                                    @else
                                                        <button class="btn btn-success" style="cursor:default">Terima</button>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{-- {{ $realisasi->seragam_putih_biru }} --}}
                                                    @if ($realisasi->seragam_putih_biru == 0)
                                                        <button class="btn btn-danger" style="cursor:default">Belum Terima</button>
                                                    @else
                                                        <button class="btn btn-success" style="cursor:default">Terima</button>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{-- {{ $realisasi->seragam_putih_abu }} --}}
                                                    @if ($realisasi->seragam_putih_abu == 0)
                                                        <button class="btn btn-danger" style="cursor:default">Belum Terima</button>
                                                    @else
                                                        <button class="btn btn-success" style="cursor:default">Terima</button>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{-- {{ $realisasi->seragam_pramuka }} --}}
                                                    @if ($realisasi->seragam_pramuka == 0)
                                                        <button class="btn btn-danger" style="cursor:default">Belum Terima</button>
                                                    @else
                                                        <button class="btn btn-success" style="cursor:default">Terima</button>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{-- {{ $realisasi->tas_paud }} --}}
                                                    @if ($realisasi->tas_paud == 0)
                                                        <button class="btn btn-danger" style="cursor:default">Belum Terima</button>
                                                    @else
                                                        <button class="btn btn-success" style="cursor:default">Terima</button>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{-- {{ $realisasi->tas_sd }} --}}
                                                    @if ($realisasi->tas_sd == 0)
                                                        <button class="btn btn-danger" style="cursor:default">Belum Terima</button>
                                                    @else
                                                        <button class="btn btn-success" style="cursor:default">Terima</button>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{-- {{ $realisasi->tas_smp }} --}}
                                                    @if ($realisasi->tas_smp == 0)
                                                        <button class="btn btn-danger" style="cursor:default">Belum Terima</button>
                                                    @else
                                                        <button class="btn btn-success" style="cursor:default">Terima</button>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{-- {{ $realisasi->tas_sma_smk }} --}}
                                                    @if ($realisasi->tas_sma_smk == 0)
                                                        <button class="btn btn-danger" style="cursor:default">Belum Terima</button>
                                                    @else
                                                        <button class="btn btn-success" style="cursor:default">Terima</button>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{-- {{ $realisasi->tas_perguruan_tinggi }} --}}
                                                    @if ($realisasi->tas_perguruan_tinggi == 0)
                                                        <button class="btn btn-danger" style="cursor:default">Belum Terima</button>
                                                    @else
                                                        <button class="btn btn-success" style="cursor:default">Terima</button>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{-- {{ $realisasi->buku }} --}}
                                                    @if ($realisasi->buku == 0)
                                                        <button class="btn btn-danger" style="cursor:default">Belum Terima</button>
                                                    @else
                                                        <button class="btn btn-success" style="cursor:default">Terima</button>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{-- {{ $realisasi->bulpen }} --}}
                                                    @if ($realisasi->bulpen == 0)
                                                        <button class="btn btn-danger" style="cursor:default">Belum Terima</button>
                                                    @else
                                                        <button class="btn btn-success" style="cursor:default">Terima</button>
                                                    @endif
                                                </td>
                                                <td>{{ $realisasi->waktu_proses }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(auth()->user()->peran->kode_peran != 5)
    <div class="text-right">
        <a href="{{ route('siswa.cetak_pdf', $data->id) }}" class="btn btn-info" target="_blank">Cetak PDF</a>
        <a href="{{ route('siswa.index') }}" class="btn btn-primary float-end">Kembali ke Data Siswa</a>
    </div>
    @endif
</div>
<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
<script>
    var table = $('#tabelRiwayatProses').DataTable({
        order:[]
    })
</script>

@endsection

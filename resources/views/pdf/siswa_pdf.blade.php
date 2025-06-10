<!DOCTYPE html>
<html>
    <head>
        <style>
             /* @page {
                margin: 100px 25px;
            } */
            /* header {
                position: fixed;
                top: -30px;
                left: 0px;
                right: 0px;
                height: 600px;
                line-height: 35px;
                font-family:ARIAL;
            } */
            html {
              padding: 0;
            }
            div {
              font-family:ARIAL; font-size:9.2pt;
            }
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
                padding-left: 3px;
                padding-right: 3px;
            }
            td { text-align: left; }
            td:first-child { text-align: center; }
            .no_table {
                text-align: center;
            }
            .tbl_center {
                margin-left: auto;
                margin-right: auto;
            }
        </style>
    </head>
    <body>
        <header>
            <h3 style="text-align:center">
                <strong>
                VERIFIKASI DATA<br />
                YAYASAN KROMAN MALAKA ATAMBUA<br />
                GERAKAN GENOVA SCHOOL NUSANTARA
                </strong>
            </h3>
            <p style="text-align:center">
                <strong>
                    Jl. Tn. Bakel, Nufuak, Kel. Fatukbot, Kec. Atambua Selatan<br />
                    Kab. Belu – Nusa Tenggara Timur<br />
                </strong>
                <a href="mailto:genovanusantara@gmail.com">genovanusantara@gmail.com</a>
            </p>
        </header>

        <div>
            <p style="text-align:right">BERKAS-</p>
            <p style="text-align:right">Tanggal</p>

            <p>&nbsp;</p>
            <table class="tbl_center" style="width:90%;">
              <tbody>
                {{-- data anak --}}
                <tr>
                    <td colspan=3 style="text-align:center; background-color:rgb(156, 195, 229)">DATA ANAK</td>
                </tr>
                <tr>
                    <td>1.</td>
                    <td>Nama Lengkap</td>
                    <td>{{ $data->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Nomor Registrasi GSN</td>
                    <td>{{ $data->nomor_registrasi_gsn }}</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Tempat Lahir</td>
                    <td>{{ $data->tempat_lahir }}</td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>Tanggal Lahir</td>
                    <td>{{ $data->tanggal_lahir }}</td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>Jenis Kelamin</td>
                    <td>{{ $data->jenis_kelamin }}</td>
                </tr>
                <tr>
                    <td>6.</td>
                    <td>Alamat Lengkap</td>
                    <td>{{ $data->alamat_lengkap }}</td>
                </tr>
                <tr>
                    <td>7.</td>
                    <td>Alamat Sekolah</td>
                    <td>{{ $data->alamat_sekolah }}</td>
                </tr>
                <tr>
                    <td>8.</td>
                    <td>Jarak ke Sekolah</td>
                    <td>{{ $data->jarak_ke_sekolah }}</td>
                </tr>
                <tr>
                    <td>9.</td>
                    <td>Tingkat Pendidikan</td>
                    <td>{{ $data->tingkat_pendidikan }}</td>
                </tr>
                <tr>
                    <td>10.</td>
                    <td>Umur, Kelas/Semester</td>
                    <td>{{ $data->umur }} {{ $data->kelas }}</td>
                </tr>
                <tr>
                    <td>11.</td>
                    <td>No.Telp/HP</td>
                    <td>{{ $data->no_telp }}</td>
                </tr>
                <tr>
                    <td>12.</td>
                    <td>E-mail</td>
                    <td>{{ $data->email }}</td>
                </tr>
                {{-- realisasi --}}
                <tr>
                    <td colspan=3 style="text-align:center; background-color:rgb(156, 195, 229)">REALISASI<br/> SUMBANGAN PEMBANGUNAN PENDIDIKAN (SPP)</td>
                </tr>
                <tr>
                    <td style="width: 4%">1.</td>
                    <td>Tahap I</td>
                    <td>{{ !empty($proses) ? $proses->tahap_1 : null }}</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Tahap II</td>
                    <td>{{ !empty($proses) ? $proses->tahap_2 : null }}</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Tahap III</td>
                    <td>{{ !empty($proses) ? $proses->tahap_3 : null}}</td>
                </tr>
                {{-- seragam --}}
                <tr>
                    <td colspan=3 style="text-align:center; background-color:rgb(156, 195, 229)">SERAGAM, TAS, BUKU & PULPEN</td>
                </tr>
                <tr>
                    <td>1.</td>
                    <td>Nasional</td>
                    <td>{{ !empty($realisasi) ? ($realisasi->seragam_nasional == 0 ? 'Belum Terima' : 'Terima') : null}}</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Putih Biru</td>
                    <td>{{ !empty($realisasi) ? ($realisasi->seragam_putih_biru == 0 ? 'Belum Terima' : 'Terima') : null }}</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Putih Abu</td>
                    <td>{{ !empty($realisasi) ? ($realisasi->seragam_putih_abu == 0 ? 'Belum Terima' : 'Terima') : null }}</td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>Pramuka</td>
                    <td>{{ !empty($realisasi) ? ($realisasi->seragam_pramuka == 0 ? 'Belum Terima' : 'Terima') : null }}</td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>Tas</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align:right">PAUD</td>
                    <td>{{ !empty($realisasi) ? ($realisasi->tas_paud == 0 ? 'Belum Terima' : 'Terima') : null }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align:right">SD</td>
                    <td>{{ !empty($realisasi) ? ($realisasi->tas_sd == 0 ? 'Belum Terima' : 'Terima') : null }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align:right">SMP</td>
                    <td>{{ !empty($realisasi) ? ($realisasi->tas_smp == 0 ? 'Belum Terima' : 'Terima') : null }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align:right">SMA/SMK</td>
                    <td>{{ !empty($realisasi) ? ($realisasi->tas_sma_smk == 0 ? 'Belum Terima' : 'Terima') : null }}</td>
                </tr>
                <tr>
                    <td>6.</td>
                    <td>Buku/Pulpen</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align:right">Buku</td>
                    <td>{{ !empty($realisasi) ? ($realisasi->buku == 0 ? 'Belum Terima' : 'Terima') : null }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align:right">Pulpen</td>
                    <td>{{ !empty($realisasi) ? ($realisasi->bulpen == 0 ? 'Belum Terima' : 'Terima') : null }}</td>
                </tr>
                {{-- data orangtua --}}
                <tr>
                    <td colspan=3 style="text-align:center; background-color:rgb(156, 195, 229)">DATA ORANG TUA</td>
                </tr>
                <tr>
                    <td colspan=3 style="text-align:center; background-color:rgb(156, 195, 229)">AYAH</td>
                </tr>
                <tr>
                    <td>1.</td>
                    <td>Nama Ayah</td>
                    <td>{{ !empty($ortu) ? $ortu->nama_ayah : null }}</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Tempat Lahir</td>
                    <td>{{ !empty($ortu) ? $ortu->tempat_lahir_ayah : null }}</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Tanggal Lahir</td>
                    <td>{{ !empty($ortu) ? $ortu->tgl_lahir_ayah : null }}</td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>Pekerjaan</td>
                    <td>{{ !empty($ortu) ? $ortu->pekerjaan_ayah : null }}</td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>Penghasilan/Bulan</td>
                    <td>{{ !empty($ortu) ? $ortu->penghasilan_bulan_ayah : null }}</td>
                </tr>
                <tr>
                    <td>6.</td>
                    <td>Keahlian yang Dimiliki</td>
                    <td>{{ !empty($ortu) ? $ortu->keahlian_ayah : null }}</td>
                </tr>
                <tr>
                    <td>7.</td>
                    <td>Peluang Usaha</td>
                    <td>{{ !empty($ortu) ? $ortu->peluang_usaha_ayah : null }}</td>
                </tr>
                <tr>
                    <td>8.</td>
                    <td>Alamat Rumah</td>
                    <td>{{ !empty($ortu) ? $ortu->alamat_ayah : null }}</td>
                </tr>
                <tr>
                    <td>9.</td>
                    <td>Jarak Rumah ke Pusat Kota</td>
                    <td>{{ !empty($ortu) ? $ortu->jarak_ke_kota_ayah : null }}</td>
                </tr>
                <tr>
                    <td>10.</td>
                    <td>No.Telp/HP</td>
                    <td>{{ !empty($ortu) ? $ortu->no_telp_ayah : null }}</td>
                </tr>
                <tr>
                    <td colspan=3 style="text-align:center; background-color:rgb(156, 195, 229)">IBU</td>
                </tr>
                <tr>
                    <td>1.</td>
                    <td>Nama Ibu</td>
                    <td>{{ !empty($ortu) ? $ortu->nama_ibu : null }}</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Tempat Lahir</td>
                    <td>{{ !empty($ortu) ? $ortu->tempat_lahir_ibu : null }}</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Tanggal Lahir</td>
                    <td>{{ !empty($ortu) ? $ortu->tgl_lahir_ibu : null }}</td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>Pekerjaan</td>
                    <td>{{ !empty($ortu) ? $ortu->pekerjaan_ibu : null }}</td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>Penghasilan/Bulan</td>
                    <td>{{ !empty($ortu) ? $ortu->penghasilan_bulan_ibu : null }}</td>
                </tr>
                <tr>
                    <td>6.</td>
                    <td>Keahlian yang Dimiliki</td>
                    <td>{{ !empty($ortu) ? $ortu->keahlian_ibu : null }}</td>
                </tr>
                <tr>
                    <td>7.</td>
                    <td>Peluang Usaha</td>
                    <td>{{ !empty($ortu) ? $ortu->peluang_usaha_ibu : null }}</td>
                </tr>
                <tr>
                    <td>8.</td>
                    <td>Alamat Rumah</td>
                    <td>{{ !empty($ortu) ? $ortu->alamat_ibu : null }}</td>
                </tr>
                <tr>
                    <td>9.</td>
                    <td>Jarak Rumah ke Pusat Kota</td>
                    <td>{{ !empty($ortu) ? $ortu->jarak_ke_kota_ibu : null }}</td>
                </tr>
                <tr>
                    <td>10.</td>
                    <td>No.Telp/HP</td>
                    <td>{{ !empty($ortu) ? $ortu->no_telp_ibu : null }}</td>
                </tr>
              </tbody>
            </table>
            {{-- foto --}}
            <br/>
            <table class="tbl_center" style="width:100%">
                <tbody>
                  {{-- data foto --}}
                  {{-- <tr style="background-color:rgb(156, 195, 229)">
                    <td style="text-align:center;">Foto Siswa</td>
                    <td style="text-align:center;">Foto Orang Tua</td>
                  </tr> --}}
                  <tr>
                    <td style="width:50%" height="200">
                        @if(!empty($data) and !empty($data->foto_siswa))
                            @if(str_contains(strtolower($data->foto_siswa), 'google'))
                                <img src="{{ $data->foto_siswa }}" alt="">
                            @else
                            {{-- @if(!str_contains(strtolower($data->foto_siswa), 'google')) --}}
                                <img style="max-width:100%" src="{{ public_path().'/file_siswa/'. $data->id .'/' .$data->foto_siswa }}" alt="">
                            @endif
                        @endif
                    </td>
                    <td style="width:50%" height="200">
                        @if(!empty($ortu) and !empty($ortu->foto_orangtua))
                            @if(str_contains(strtolower($ortu->foto_orangtua), 'google'))
                                <img src="{{ $ortu->foto_orangtua }}" alt="">
                            @else
                                <img style="max-width:100%" src="{{ public_path().'/file_siswa/'. $data->id .'/' .$ortu->foto_orangtua }}" alt="">
                            @endif
                        @endif
                    </td>
                  </tr>
                  {{-- <tr style="background-color:rgb(156, 195, 229)">
                    <td style="text-align:center;">Foto Sekolah</td>
                    <td style="text-align:center;">Foto Rumah Tampak Depan</td>
                  </tr> --}}
                  <tr>
                    <td style="width:50%" height="200">
                        @if(!empty($berkas) and !empty($berkas->foto_sekolah))
                            @if(str_contains(strtolower($berkas->foto_sekolah), 'google'))
                                <img src="{{ $berkas->foto_sekolah }}" alt="">
                            @else
                                <img style="max-width:100%" src="{{ public_path().'/file_siswa/'. $data->id .'/' . $berkas->foto_sekolah }}" alt="">
                            @endif
                        @endif
                    </td>
                    <td style="width:50%" height="200">
                        @if(!empty($berkas) and !empty($berkas->foto_rumah_depan))
                            @if(str_contains(strtolower($berkas->foto_rumah_depan), 'google'))
                                <img src="{{ $berkas->foto_rumah_depan }}" alt="">
                            @else
                                <img style="max-width:100%" src="{{ public_path().'/file_siswa/'. $data->id .'/' .$berkas->foto_rumah_depan }}" alt="">
                            @endif
                        @endif
                    </td>
                  </tr>
                  {{-- <tr style="background-color:rgb(156, 195, 229)">
                    <td style="text-align:center;">Foto Rumah Tampak Samping</td>
                    <td style="text-align:center;">Foto Kantor Desa</td>
                  </tr> --}}
                  <tr>
                    <td style="width:50%" height="200">
                        @if(!empty($berkas) and !empty($berkas->foto_rumah_samping))
                            @if(str_contains(strtolower($berkas->foto_rumah_samping), 'google'))
                                <img src="{{ $berkas->foto_rumah_samping }}" alt="">
                            @else
                                <img style="max-width:100%" src="{{ public_path().'/file_siswa/'. $data->id .'/' .$berkas->foto_rumah_samping }}" alt="">
                            @endif
                        @endif
                    </td>
                    <td style="width:50%" height="200">
                        @if(!empty($berkas) and !empty($berkas->foto_kantor_desa))
                            @if(str_contains(strtolower($berkas->foto_kantor_desa), 'google'))
                                <img src="{{ $berkas->foto_kantor_desa }}" alt="">
                            @else
                                <img style="max-width:100%" src="{{ public_path().'/file_siswa/'. $data->id .'/' .$berkas->foto_kantor_desa }}" alt="">
                            @endif
                        @endif
                    </td>
                  </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>

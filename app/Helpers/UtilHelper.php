<?php

namespace App\Helpers;


use Illuminate\Support\Facades\DB;

class UtilHelper
{
    static function getParameter() {
        $kabupatenList = DB::table('master_wilayah')->whereNull('kode_kecamatan')->orderBy('kabupaten', 'asc')->get();
        $kecamatanList = DB::table('master_wilayah')->whereNotNull('kode_kecamatan')->orderBy('kabupaten', 'asc')->orderBy('kecamatan', 'asc')->get();

        $jenis_kelamin = DB::table('master_parameter')->where('type', '=','jenis_kelamin')->orderBy('kode', 'asc')->pluck('value')->toArray();
        // ["Laki-laki", "Perempuan"];
        $jarak = DB::table('master_parameter')->where('type', '=','jarak')->orderBy('kode', 'asc')->pluck('value')->toArray();
        // ["Kurang dari 1 KM", "Lebih dari 1 KM", "1 KM"];
        $pendidikan = DB::table('master_parameter')->where('type', '=','tingkat_pendidikan')->orderBy('kode', 'asc')->pluck('value')->toArray();
        // ["PAUD", "SD", "SMP", "SMA/SMK", "Perguruan Tinggi"];
        $kelas = [1,2,3,4,5,6,7,8,9,10,11,12];
        $semester = [1,2,3,4,5,6,7,8];
        $penghasilan =  DB::table('master_parameter')->where('type', '=','penghasilan')->orderBy('kode', 'asc')->pluck('value')->toArray();
        // ["0 - Rp. 250.000", "Rp. 500.000 - Rp. 1.000.000", "Rp. 1.000.000 - Rp. 1.500.000", "Rp. 1.500.000 ke atas"];

        return array('kabupatenList'    => $kabupatenList,
                    'jenis_kelamin'     => $jenis_kelamin,
                    'jarak'             => $jarak,
                    'pendidikan'        => $pendidikan,
                    'kelas'             => $kelas,
                    'semester'          => $semester,
                    'penghasilan'       => $penghasilan
                );
    }

}

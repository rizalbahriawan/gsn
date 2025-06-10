<?php


namespace App\Services;

use App\Mail\EmailResetPassword;
use App\Mail\EmailUserCreated;
use App\Models\Siswa;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use App\Mail\EmailNotification;
use App\Models\Proses;
use App\Models\Peran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\Helpers\IPHelper;

class RealisasiService
{

    public function getRealisasi() {
        $data = DB::table('tbl_realisasi as tr')
            ->selectRaw('tr.id, ts.nama_lengkap as nama_siswa, ts.nomor_registrasi_gsn as no_reg, ts.alamat_sekolah as sekolah_siswa, tu.username as aktivis')
            ->selectRaw('tr.seragam_nasional, tr.seragam_putih_biru, tr.seragam_putih_abu, tr.seragam_pramuka')
            ->selectRaw('tr.tas_paud, tr.tas_sd, tr.tas_smp, tr.tas_sma_smk, tr.tas_perguruan_tinggi, tr.buku, tr.bulpen, tr.waktu_proses')
            ->join('tbl_siswa as ts', 'ts.id','=','tr.id_siswa')
            ->join('tbl_user as tu', 'tu.id','=','tr.id_aktivis')
            ->orderBy('tr.waktu_proses', 'desc')
            ->get();

        // \Log::info('data proses: '. json_encode($data));
        return $data;
    }



    public function createValidator(Request $request) {
        $updateRule = ['id'=>'required'];
        $validator = Validator::make($request->all(), $updateRule);
        return $validator;
    }

    public function store(Request $request) {

        return DB::transaction(function () use ($request) {
            $realisasi = null;
            DB::table('tbl_realisasi')->where('id', $request->get('id'))->update(array(
                'id_aktivis' => auth()->user()->id,
                'seragam_nasional' => $request->has('seragam_nasional') ? 1 : 0,
                'seragam_putih_biru' => $request->has('seragam_putih_biru') ? 1 : 0,
                'seragam_putih_abu' => $request->has('seragam_putih_abu')  ? 1 : 0,
                'seragam_pramuka' => $request->has('seragam_pramuka')  ? 1 : 0,
                'tas_paud' => $request->has('tas_paud')  ? 1 : 0,
                'tas_sd' => $request->has('tas_sd')  ? 1 : 0,
                'tas_smp' => $request->has('tas_smp')  ? 1 : 0,
                'tas_sma_smk' => $request->has('tas_sma_smk')  ? 1 : 0,
                'tas_perguruan_tinggi' => $request->has('tas_perguruan_tinggi')  ? 1 : 0,
                'buku' => $request->has('buku')  ? 1 : 0,
                'bulpen' => $request->has('bulpen')  ? 1 : 0,
                'waktu_proses' => date('Y-m-d H:i:s'),
            ));
            $realisasi = DB::table('tbl_realisasi as tr')->join('tbl_siswa as ts', 'ts.id','=','tr.id_siswa')->where('tr.id', $request->get('id'))->first();
            \Log::info('realisasi update: ' . json_encode($realisasi));
            return $realisasi;
        });
    }


}

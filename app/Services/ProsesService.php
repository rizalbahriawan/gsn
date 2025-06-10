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

class ProsesService
{

    public function getProses() {
        $data = DB::table('tbl_proses as tp')
            ->selectRaw('tp.id, ts.nama_lengkap as nama_siswa, ts.nomor_registrasi_gsn as no_reg, ts.alamat_sekolah as sekolah_siswa, tu.username as aktivis')
            ->selectRaw('tp.tahap_1, tp.tahap_2, tp.tahap_3,tp.tahap_4, tp.tahap_5, tp.tahap_6,tp.tahap_7, tp.tahap_8, tp.tahap_9,tp.tahap_10, tp.amount, tp.waktu_proses')
            ->selectRaw('tp.status, tp.tahapan')
            ->join('tbl_siswa as ts', 'ts.id','=','tp.id_siswa')
            ->join('tbl_user as tu', 'tu.id','=','tp.id_aktivis')
            ->join('tbl_peran as p', 'p.id', '=', 'tu.id_peran')
            ->where('tp.is_last', 'T')
            ->orderBy('tp.waktu_proses', 'desc')
            ->get();

        // \Log::info('data proses: '. json_encode($data));
        return $data;
    }

    public function getRekap() {
        $data = DB::table('tbl_proses as tp')
            ->selectRaw('tp.id, ts.nama_lengkap as nama_siswa, ts.nomor_registrasi_gsn as no_reg, ts.alamat_sekolah as sekolah_siswa, tu.username as aktivis')
            ->selectRaw('tp.tahap_1, tp.tahap_2, tp.tahap_3,tp.tahap_4, tp.tahap_5, tp.tahap_6,tp.tahap_7, tp.tahap_8, tp.tahap_9,tp.tahap_10, tp.amount, tp.status, tp.tahapan, tp.waktu_proses')
            ->join('tbl_siswa as ts', 'ts.id','=','tp.id_siswa')
            ->join('tbl_user as tu', 'tu.id','=','tp.id_aktivis')
            ->join('tbl_peran as p', 'p.id', '=', 'tu.id_peran')
            ->whereRaw('tp.is_last = \'T\'')
            ->whereRaw('tp.tahapan in (3,4)')
            ->orderBy('tp.waktu_proses', 'desc')
            ->get();

        // \Log::info('data proses: '. json_encode($data));
        return $data;
    }



    public function createValidator(Request $request) {
        $updateRule = ['id'=>'required', 'tahap_1'=>'required|numeric', 'tahap_2'=>'required|numeric', 'tahap_3'=>'required|numeric',
        'tahap_4'=>'required|numeric', 'tahap_5'=>'required|numeric', 'tahap_6'=>'required|numeric',
        'tahap_7'=>'required|numeric', 'tahap_8'=>'required|numeric', 'tahap_9'=>'required|numeric', 'tahap_10'=>'required|numeric'];
        $validator = Validator::make($request->all(), $updateRule);
        return $validator;
    }

    public function store(Request $request) {

        return DB::transaction(function () use ($request) {
            $proses = null;
            $amount = $this->sumTahap($request);
            \Log::info('amount: ' . $amount);
            DB::table('tbl_proses')->where('id', $request->get('id'))->update(array(
                'id_aktivis' => auth()->user()->id,
                'tahap_1' => $request->get('tahap_1'),
                'tahap_2' => $request->get('tahap_2'),
                'tahap_3' => $request->get('tahap_3'),
                'tahap_4' => $request->get('tahap_4'),
                'tahap_5' => $request->get('tahap_5'),
                'tahap_6' => $request->get('tahap_6'),
                'tahap_7' => $request->get('tahap_7'),
                'tahap_8' => $request->get('tahap_8'),
                'tahap_9' => $request->get('tahap_9'),
                'tahap_10' => $request->get('tahap_10'),
                'amount' => $amount,
                // 'step' => $request->get('step'),
                // 'seragam_nasional' => $request->has('seragam_nasional') ? 1 : 0,
                // 'seragam_putih_biru' => $request->has('seragam_putih_biru') ? 1 : 0,
                // 'seragam_putih_abu' => $request->has('seragam_putih_abu')  ? 1 : 0,
                // 'seragam_pramuka' => $request->has('seragam_pramuka')  ? 1 : 0,
                // 'tas_paud' => $request->has('tas_paud')  ? 1 : 0,
                // 'tas_sd' => $request->has('tas_sd')  ? 1 : 0,
                // 'tas_smp' => $request->has('tas_smp')  ? 1 : 0,
                // 'tas_sma_smk' => $request->has('tas_sma_smk')  ? 1 : 0,
                // 'tas_perguruan_tinggi' => $request->has('tas_perguruan_tinggi')  ? 1 : 0,
                // 'buku' => $request->has('buku')  ? 1 : 0,
                // 'bulpen' => $request->has('bulpen')  ? 1 : 0,
                'waktu_proses' => date('Y-m-d H:i:s'),
            ));
            $proses = DB::table('tbl_proses as tp')->join('tbl_siswa as ts', 'ts.id','=','tp.id_siswa')->where('tp.id', $request->get('id'))->first();
            \Log::info('proses update: ' . json_encode($proses));
            return $proses;
        });
    }

    public function submitRow(Request $request) {
        return DB::transaction(function () use ($request) {
            DB::table('tbl_proses')->where('id', $request->get('id'))
                ->update(array(
                    'is_last'=> 'F',
                    'waktu_proses' => date('Y-m-d H:i:s')
            ));
            $previousData = DB::table('tbl_proses')->where('id', $request->get('id'))->first();
            DB::table('tbl_proses')
                ->insert(array(
                    'id_siswa' => $previousData->id_siswa,
                    'id_aktivis' => auth()->user()->id,
                    'tahap_1' => $previousData->tahap_1,
                    'tahap_2' => $previousData->tahap_2,
                    'tahap_3' => $previousData->tahap_3,
                    'tahap_4' => $previousData->tahap_4,
                    'tahap_5' => $previousData->tahap_5,
                    'tahap_6' => $previousData->tahap_6,
                    'tahap_7' => $previousData->tahap_7,
                    'tahap_8' => $previousData->tahap_8,
                    'tahap_9' => $previousData->tahap_9,
                    'tahap_10' => $previousData->tahap_10,
                    'amount' => $previousData->amount,
                    'step' => $previousData->step,
                    'status'=>'DIAJUKAN',
                    'tahapan'=>2,
                    'is_last'=> 'T',
                    'waktu_proses' => date('Y-m-d H:i:s')
            ));
            $data = Siswa::find($previousData->id_siswa);
            return $data;

        });
    }

    public function approveRow(Request $request) {
        return DB::transaction(function () use ($request) {
            DB::table('tbl_proses')->where('id', $request->get('id'))
                ->update(array(
                    'is_last'=> 'F',
                    'waktu_proses' => date('Y-m-d H:i:s')
            ));
            $previousData = DB::table('tbl_proses')->where('id', $request->get('id'))->first();
            DB::table('tbl_proses')
                ->insert(array(
                    'id_siswa' => $previousData->id_siswa,
                    'id_aktivis' => auth()->user()->id,
                    'tahap_1' => $previousData->tahap_1,
                    'tahap_2' => $previousData->tahap_2,
                    'tahap_3' => $previousData->tahap_3,
                    'tahap_4' => $previousData->tahap_4,
                    'tahap_5' => $previousData->tahap_5,
                    'tahap_6' => $previousData->tahap_6,
                    'tahap_7' => $previousData->tahap_7,
                    'tahap_8' => $previousData->tahap_8,
                    'tahap_9' => $previousData->tahap_9,
                    'tahap_10' => $previousData->tahap_10,
                    'amount' => $previousData->amount,
                    'step' => $previousData->step,
                    'status'=>'DISETUJUI',
                    'tahapan'=>4,
                    'is_last'=> 'T',
                    'waktu_proses' => date('Y-m-d H:i:s')
            ));
            DB::table('tbl_realisasi')->where('id_siswa', $previousData->id_siswa)->exists();
            $is_realisasi = DB::table('tbl_realisasi')->where('id_siswa', $previousData->id_siswa)->exists();
            if (!$is_realisasi) {
                DB::table('tbl_realisasi')
                    ->insert(array(
                        'id_siswa' => $previousData->id_siswa,
                        'id_aktivis' => auth()->user()->id,
                        'seragam_nasional' => 0,
                        'seragam_putih_biru' => 0,
                        'seragam_putih_abu' => 0,
                        'seragam_pramuka' => 0,
                        'tas_paud' => 0,
                        'tas_sd' => 0,
                        'tas_smp' => 0,
                        'tas_sma_smk' => 0,
                        'tas_perguruan_tinggi' => 0,
                        'buku' => 0,
                        'bulpen' => 0,
                        'waktu_proses' => date('Y-m-d H:i:s')
                ));
            }
            $data = Siswa::find($previousData->id_siswa);
            return $data;

        });
    }

    public function rejectRow(Request $request) {
        return DB::transaction(function () use ($request) {
            DB::table('tbl_proses')->where('id', $request->get('id'))
                ->update(array(
                    'is_last'=> 'F',
                    'waktu_proses' => date('Y-m-d H:i:s')
            ));
            $previousData = DB::table('tbl_proses')->where('id', $request->get('id'))->first();
            DB::table('tbl_proses')
                ->insert(array(
                    'id_siswa' => $previousData->id_siswa,
                    'id_aktivis' => auth()->user()->id,
                    'tahap_1' => $previousData->tahap_1,
                    'tahap_2' => $previousData->tahap_2,
                    'tahap_3' => $previousData->tahap_3,
                    'tahap_4' => $previousData->tahap_4,
                    'tahap_5' => $previousData->tahap_5,
                    'tahap_6' => $previousData->tahap_6,
                    'tahap_7' => $previousData->tahap_7,
                    'tahap_8' => $previousData->tahap_8,
                    'tahap_9' => $previousData->tahap_9,
                    'tahap_10' => $previousData->tahap_10,
                    'amount' => $previousData->amount,
                    'step' => $previousData->step,
                    'alasan_tolak' => $request->get('alasan'),
                    'status'=>'DITOLAK',
                    'tahapan'=>3,
                    'is_last'=> 'T',
                    'waktu_proses' => date('Y-m-d H:i:s')
            ));
            $data = Siswa::find($previousData->id_siswa);
            return $data;

        });
    }

    public function sumTahap($request) {
        return $request->get('tahap_1')+$request->get('tahap_2')+$request->get('tahap_3')+
            $request->get('tahap_4')+$request->get('tahap_5')+$request->get('tahap_6')+
            $request->get('tahap_7')+$request->get('tahap_8')+$request->get('tahap_9')+$request->get('tahap_10');
    }

    public function submit($id) {
        $data = DB::transaction(function () use ($id) {
            DB::table('tbl_proses')->where('id', $id)
                ->update(array(
                    'status'=>'DIAJUKAN', 'tahapan'=>2
                )
                     + array('waktu_proses' => date('Y-m-d H:i:s')));
        });
        return $data;
    }

    public function reject($id) {
        $data = DB::transaction(function () use ($id) {
            DB::table('tbl_proses')->where('id', $id)
                ->update(array(
                    'status'=>'DITOLAK', 'tahapan'=> 3
                )
                     + array('waktu_proses' => date('Y-m-d H:i:s')));
        });
        return $data;
    }

    public function approve($id) {
        $data = DB::transaction(function () use ($id) {
            DB::table('tbl_proses')->where('id', $id)
                ->update(array(
                    'status'=>'DISETUJUI', 'tahapan'=>4
                )
                     + array('waktu_proses' => date('Y-m-d H:i:s')));
        });
        return $data;
    }

    // public function insert(Request $request) {


    //     return DB::transaction(function () use ($request) {
    //         $date = date('Y-m-d H:i:s');

    //         $user = DB::table('tbl_proses')
    //         // ->create($request->only('username', 'email', 'id_peran', 'name') +
    //         //     array('created_at' => $date, 'updated_at' => $date, 'password' => Hash::make($newPassword)));

    //         $user = DB::table('tbl_proses')->where('id', $user->id);
    //         return $user;
    //     });

    // }

}

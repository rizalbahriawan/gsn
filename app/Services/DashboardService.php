<?php


namespace App\Services;

use App\Mail\EmailResetPassword;
use App\Mail\EmailUserCreated;
use Illuminate\Http\Request;
use App\Mail\EmailNotification;
use App\Models\User;
use App\Models\Peran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\Helpers\IPHelper;

class DashboardService
{

    public function getData() {
        $jumlah_siswa = DB::table('tbl_siswa')
            ->count('*');
        $total_proses = DB::table('tbl_proses')
            ->count('*');
        $jumlah_forum = DB::table('tbl_forum')
            ->count('*');
        $jumlah_keluarga = DB::table('tbl_orangtua')
            ->count('*');

        // \Log::info('dashboard ' . $jumlah_siswa);
        $data = array(
            'jumlah_siswa' => $jumlah_siswa,
            'total_proses' => $total_proses,
            'jumlah_keluarga' => $jumlah_keluarga,
            'jumlah_forum' => $jumlah_forum,
        );
        // return view('welcome', [
        //     'data' => $data
        // ]);
        return $data;
    }

}

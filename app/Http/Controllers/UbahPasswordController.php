<?php

namespace App\Http\Controllers;

use App\Helpers\IPHelper;
use App\Models\User;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UbahPasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('ubahPassword.index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request)
    {
        $request->validate([
            'password_lama' => ['required', new MatchOldPassword],
            'password_baru' => ['required','min:3','different:password_lama'
                // ,'regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/'
            ],
            'konfirmasi_password_baru' => ['same:password_baru'],
        ]
        // ,
//         [
// //            'same' => 'Konfirmasi password baru dan password baru harus sesuai.',
// //            'password_baru.min' => 'Password anda harus lebih dari 8 karakter.',
//             'regex' => 'Password baru tidak memenuhi syarat, harus mengandung setidaknya 1 Huruf Besar, 1 Huruf Kecil, dan 1 Numerik.'
//         ]
        );

        $password_lama = auth()->user()->password;
        $password_baru = Hash::make($request->password_baru);

        //set auth with new password
        auth()->user()->password = $password_baru;

        $user = User::where('id', auth()->id())
            ->first();
        $time_before_change = $user->updated_at;
        $user->keterangan = $request->password_baru;
        $user->password = $password_baru;
        $user->pwd_reset = null;
        $user->save();

        // \Log::info('request IP: ' . \Request::ip());
        // \Log::info('time_before_change: ' . $time_before_change);
        //Cek data pengguna berhasil diupdate
        if ($time_before_change != $user->updated_at) {
            DB::table('tbl_log_aktivitas')->insert([
                'username' => auth()->user()->username,
                'peran' => auth()->user()->peran->nama_peran,
                'waktu_aktivitas' => $user->updated_at,
                'jenis_aktivitas' => 'Ubah Password',
                'ip_address' => \Request::ip(),
                // 'ip_address' => IPHelper::get_client_ip(),
                'user_agent' => $request->server('HTTP_USER_AGENT'),
                'nilai_aktivitas' => 'password_lama=' . $password_lama . '||' . 'password_baru=' . $password_baru,
            ]);

            return redirect()->route('halamanUbahPassword')
                ->with('success', 'Password berhasil diubah.');
        } else return back()->with('error', 'Maaf, terjadi kesalahan');
    }


}

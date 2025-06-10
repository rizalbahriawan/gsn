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

class UserService
{

    public function getUserByID($id) {
        $users = User::select(['id', 'username', 'name', 'email', 'no_hp',
            'flag_aktif', 'id_peran',
            DB::raw('(select nama_peran from tbl_peran where tbl_peran.id = tbl_user.id_peran) as nama_peran')]);

        $user = $users->where('id', $id)->first();
        // if(!$user){
        //     $user = DB::table('v_user_petugas')->select([DB::raw('userid as id_pengguna'), 'username',
        //         DB::raw('nama as nama_lengkap'), 'nik', DB::raw('null as npwp'), 'unitkerja', 'jabatan',
        //         DB::raw('alamatemail as email'),
        //         DB::raw('null as no_hp'), DB::raw('10 as id_peran'),
        //         DB::raw('\'Petugas\' as nama_peran'), DB::raw('\'Y\' as flag_aktif')])
        //         ->where('username', $username)->first();
        // }

        return $user;
    }

    public function getUsersFileteredBySearchForm(Request $request) {

        $pengguna = $this->selectPenggunaDatatables();
        $petugas = $this->selectPetugasDatatables();
        $users = $pengguna;
        $peran = Peran::find($request->get('id_peran'));
        if ($request->has('id_peran') && $request->get('id_peran') == '-23'){
            $users =$pengguna->union($petugas);
        } else if ($request->has('id_peran') && $request->get('id_peran') != '') {
            if($peran->nama_peran == 'Petugas') { //cari ke v_user_petugas
                $users = $petugas;
            }
        }

        $data = DB::table(DB::raw("({$users->toSql()})"))
            ->select(['username', 'nama_lengkap', 'email',
                'flag_aktif', 'nama_peran'])
            ->where(function($query) use ($request) {
                if ($request->has('nama') && $request->get('nama') != null && $request->get('nama') != '') {
                    $nama = strtolower($request->get('nama'));
                    $query->where(DB::raw('lower(nama_lengkap)'), 'like', "%{$nama}%");
                }
                if ($request->has('username') && $request->get('username') != null && $request->get('username') != '') {
                    $query->where('username', 'like', "%{$request->get('username')}%");
                }
                if ($request->has('id_peran') && $request->get('id_peran') != '-23'
                && $request->get('id_peran') != '') {
                    $query->where('id_peran', '=', $request->get('id_peran'));
                }
            });



        return $data;
    }

    public function selectUserDatatables() {
        return DB::table('tbl_user')->select(['username', 'nama_lengkap', 'email',
            DB::raw('case flag_aktif when \'Y\' then \'Aktif\' else \'Tidak Aktif \' end as flag_aktif'),
            'id_peran',
            DB::raw('(select nama_peran from tbl_peran where tbl_peran.id = tbl_pengguna.id_peran) as nama_peran')]);
    }

    // public function selectPetugasDatatables() {
    //     return UserPetugas::select(['username',
    //         DB::raw('nama as nama_lengkap'),
    //         DB::raw('alamatemail as email'),
    //         DB::raw('\'Aktif\' as flag_aktif'), DB::raw('10 as id_peran'),
    //         DB::raw('\'Petugas\' as nama_peran')]);
    // }

    public function resetPassword(User $user)
    {
        if ($user) {
            // \Log::info('peran reset pwd: ' . strtolower($user->nama_peran));
            $newPassword = strtolower($user->nama_peran) == 'siswa' ? env("DEFAULT_STUDENT_PASSWORD", "student") : env("DEFAULT_PASSWORD", "gsn123");
            // \Log::info('newPassword reset pwd: ' . $newPassword);
            $user->keterangan = $newPassword;
            $user->password = Hash::make($newPassword);
            return DB::transaction(function () use ($user, $newPassword) {
                $user->pwd_reset = 'Y';
                if ($user->save()) {
                    // Mail::to($pengguna->email)->send(new EmailResetPassword($pengguna, $newPassword));
                    $logUpdate = 'username='.$user->username;
                    DB::table('tbl_log_aktivitas')->insert([
                        'username' => auth()->user()->username,
                        'peran' => auth()->user()->peran->nama_peran,
                        'waktu_aktivitas' => date('Y-m-d H:i:s'),
                        'jenis_aktivitas' => 'Reset Password',
                        'nilai_aktivitas' => $logUpdate,
                        // 'ip_address' => \Request::ip(),
                        // 'user_agent' => $request->server('HTTP_USER_AGENT'),
                    ]);
                    return true;
                }
                return false;
            });
        }

        return false;
    }

    public function createValidator(Request $request, $operation) {
        $basicRules = $this->getRulesUpdate();
        if($operation == 'insert') {
            $basicRules = $this->getRulesInsert();
        }
        $validator = Validator::make($request->all(), $basicRules);

        $validator->after(function ($validator) use ($request, $operation) {

            if ($request->get('id_peran') == 'none') {
                $validator->errors()->add('id_peran', 'peran harus dipilih');
            }
        });

        return $validator;
    }

    public function getRulesInsert() {
        return $this->getRulesBasic() + array('username' => 'required|unique:tbl_user,username|max:50');
    }

    public function getRulesUpdate() {
        return $this->getRulesBasic() + array('id' => 'required', 'flag_aktif' => 'required');
    }

    public function getRulesBasic() {
        return [
            'name'=>'required|string|max:100',
            'email'=>'required|email|max:50',
            // 'no_hp' => array('nullable','min:10','max:20','regex:/^[0-9]*$/'),
            'id_peran' => 'required',
        ];
    }

    public function updateUser(Request $request) {
        return DB::transaction(function () use ($request) {
            DB::table('tbl_user')->where('id', $request->get('id'))
                ->update($request->only(['name', 'email', 'id_peran', 'no_hp', 'flag_aktif']) + array('updated_at' => date('Y-m-d H:i:s')));
            $user = User::find($request->get('id'));
            \Log::info('user: ' . json_encode($user));
            return $user;
        });
    }

    public function insertUser(Request $request) {

        // $peran = Peran::find($request->get('id_peran'));

        return DB::transaction(function () use ($request) {
            $date = date('Y-m-d H:i:s');
            // $newPassword = $this->generatePassword();
            $newPassword = env('DEFAULT_PASSWORD', 'gsn123');
            $request['username'] = strtolower(trim($request->get('username')));
            $user = User::create($request->only('username', 'email', 'id_peran', 'name') +
                array('created_at' => $date, 'flag_aktif' => 'Y', 'updated_at' => $date, 'password' => Hash::make($newPassword), 'keterangan' => $newPassword));

            $user = User::find($user->id);
            // Mail::to($user->email)->send(new EmailUserCreated($user, $newPassword));
            return $user;
        });

    }

    public function generatePassword() {
        $length = 8;
        $chars = array_merge(range(0, 9), range('A', 'Z'), range('a', 'z'));
        shuffle($chars);
        return implode(array_slice($chars, 0, $length));
    }

    public function emptyTable() {
        return $this->selectUserDatatables()->whereRaw('not exists (Select * from tbl_user)');
    }

}

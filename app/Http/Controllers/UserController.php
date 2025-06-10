<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Peran;
use App\Services\UserService;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use DB;
use App\Models\UserPetugas;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;
use App\Helpers\IPHelper;

class UserController extends Controller
{

    public function __construct(){
    }

    public function index()
    {
        $daftarPeran = Peran::orderBy('nama_peran')->pluck('nama_peran', 'id');
        $data = DB::table('tbl_user as tu')
            ->selectRaw('tu.id, tu.username, tu.email, tu.name, tp.nama_peran as peran')
            ->join('tbl_peran as tp', 'tp.id', '=', 'tu.id_peran')
            ->orderBy('id', 'asc')
            ->get();

        return view('user.index', [
            'data' => $data, 'daftarPeran'=>$daftarPeran
        ]);
    }

    public function peranIndex()
    {
        $data = DB::table('tbl_peran')
            ->selectRaw('id, kode_peran, nama_peran, deskripsi')
            ->orderBy('kode_peran', 'asc')
            ->get();

        return view('peran.index', [
            'data' => $data
        ]);
    }

    public function tambah()
    {
        $user = (object) ['id' => -23, 'username' => null, 'name' => null, 'email' => null,
            'flag_aktif' => null, 'id_peran' => 'none', 'nama_peran' => null, 'no_hp' => null];
        // $roles = Peran::pluck('nama_peran', 'id')->toArray();
        return view('user.edit', [
            'user' => $user,
            'perans'=> Peran::select('*')->whereNotIn('nama_peran', ['Administrator'])->orderBy('kode_peran')->get(),
            'permission' => 'insert']);
    }


    // public function registration(Request $request)
    // {
    //     $this->validate($request, [
    //         'name'=>'required|string|max:100',
    //         'id_peran'=>'required',
    //         'email'=>'required|email|max:100|unique:users',
    //         'password'=>'required|confirmed|min:8',
    //         'username' => 'required|unique:users',
    //     ]);
    //     $this->userCreate($request);
    //     auth()->attempt($request->only('email', 'password'));
    //     return redirect()->route('home');
    // }

    // protected function userCreate(Request $data)
    // {
    //     event(new Registered($data));
    //     return User::create([
    //         'name' => $data->name,
    //         'id_peran' => $data->id_peran,
    //         'email' => $data->email,
    //         'keterangan' => $data->password,
    //         'password' => Hash::make($data->password),
    //         'username'=> $data->username
    //     ]);
    // }

    public function show(Request $request, UserService $userService)
    {
        $user = $userService->getUserByID($request->get('id'));
        if(!$user) {
            abort(404, 'User Tidak Terdaftar');
        }
        if($request->has('message') and !empty($request->get('message'))) {
            \Log::info('after edit '. $request->get('id') .' | '. $request->get('message'));
            return view('user.edit', ['user' => $user, 'perans' => Peran::all(),
                'permission' => 'view'])->with('successMessage', $request->get('message'));
        }
        return view('user.edit', ['user' => $user, 'perans' => Peran::all(),'permission' => 'view']);
    }

    public function edit(Request $request, UserService $userService)
    {
        $user = $userService->getUserByID($request->get('id'));
        if(!$user) {
            abort(404, 'User Tidak Terdaftar');
        }
        return view('user.edit', ['user' => $user, 'perans' => Peran::all(),
            'permission' => 'edit']);
    }

    public function updateProfil(Request $request)
    {
        $user = User::find($request->id);

        /**
         *  fungsi yang memanggil app\Policies\PenggunaPolicy > updatePengguna
         *  fungsi ini memberikan authorisasi untuk pengguna yang bersangkutan
         *  dan juga Administrator untuk mengubah profil pengguna.
         *
         *  jika dibutuhkan update dari fungsi authrisasi ini silahkan ubah file
         *  app\Policies\UserPolicy pada fungsi updatePengguna
         */
        // $this->authorize('updateUser', $user);

        $request->validate( [
            'name'=>'required|string|max:100',
            'email'=>'required|email|max:50',
            // 'nik'=> array('nullable','max:16','regex:/^[0-9]*$/'),
            // 'npwp'=> array('nullable','max:15','regex:/^[0-9]*$/'),
            'no_hp' => array('nullable','max:20','min:10','regex:/^[0-9]*$/'),
        ]);


        if($user){
            // $logUpdate = '';
            // $logUpdate .= 'username='.$pengguna->username.'||peran='.$pengguna->peran->nama_peran.'||nama_lengkap='.$request->nama_lengkap;
            // $logUpdate .= '||email='.$request->email.'||nik='.$request->nik.'||npwp='.$request->npwp.'||no_hp='.$request->no_hp;

            $user->update($request->except(['id', 'username']));

            // DB::table('tbl_log_aktivitas')->insert([
            //     'username' => auth()->user()->username,
            //     'peran' => auth()->user()->peran->nama_peran,
            //     'waktu_aktivitas' => $pengguna->updated_at,
            //     'jenis_aktivitas' => 'Ubah Profil',
            //     'nilai_aktivitas' => $logUpdate,
            //     'ip_address' => IPHelper::get_client_ip(),
            //     'user_agent' => $request->server('HTTP_USER_AGENT')
            // ]);

            return redirect()->route('profil')->with('success', 'Data Pengguna berhasil diubah.');
        }
        return back()->with('error', 'Gagal Mengubah Data Pengguna.');

    }

    public function softDelete($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->update(['flag_aktif' => 'T']);
            \Log::info('soft delete user: ' . json_encode($user));
        }
        return redirect()->route('user.index')->with('success', 'Data Pengguna berhasil di-delete.');
        // dd('destroy');
    }

    /**
     * show profil signedin in pengguna
     */
    //profil sendiri, buat dengan langsung editable seperti edit, nanti ada tombol simpan, ke method updateProfil
    public function profil(){
        $user = User::select('tbl_user.id','name', 'username', 'email', 'tbl_peran.nama_peran as peran')
                    ->join('tbl_peran', 'tbl_peran.id', '=', 'tbl_user.id_peran')
                    ->where('tbl_user.id', auth()->id())
                    ->first();
        return view('user.profil', array(
            'data'=>$user
        ));
    }

    // public function getData(Request $request, UserService $userService)
    // {
    //     $datatables =  Datatables::of($userService->getUsersFileteredBySearchForm($request))
    //         ->addIndexColumn()
    //         ->addColumn('action', function($row) use ($request) {
    //             $action = '<a class="btn btn-primary btn-table" href='.route('user.detail').'?username='
    //                 .$row->username.'>Detail</a>';
    //             if(!in_array($row->nama_peran, ['Administrator']) ) {
    //                 $action .= '<a class="btn btn-danger btn-small btn-table" id="reset-'.$row->username. '" '.
    //                     'onclick="resetPasswordAlert(this)">Reset Password</a>';
    //             }
    //             return $action;
    //         })
    //         ->rawColumns(['action']);
    //     return $datatables->make(true);
    // }

    public function resetPassword(Request $request, UserService $userService)
    {
        $user = $userService->getUserByID($request->get('id'));

        if(!$user) {
            return array('message' => 'User '.$user->username.' tidak terdaftar', 'success'=>false);
        } else {
            if($userService->resetPassword($user)) {
                return array('message' => 'Reset Password berhasil dilakukan untuk user '.$user->username, 'success'=>true);
            } else {
                return array('message' => 'Reset Password gagal dilakukan untuk user '.$user->username, 'success'=>false);
            }
        }
    }

    public function store(Request $request, UserService $userService) {
        $response = array('message' => '', 'success'=>false);
        $validator = $userService->createValidator($request, $request->get('permission'));
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            return $response;
        }
        $user = null;

        if($request->get('permission') == 'edit') {
            $user = $userService->updateUser($request);
        } else {
            $user = $userService->insertUser($request);
        }

        if($request->get('permission') == 'insert') {
            $request->session()->flash('success', 'Data Pengguna Berhasil Ditambah!');
        }
        \Log::info('id ' . $user->id);
        return array('message' => 'ok', 'success'=>true, 'id' => $user->id);
    }
}

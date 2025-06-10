<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Peran;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index(){

        return view('auth.login');
    }

    public function logout(Request $request){
        $request->session()->flush();
        if(auth()->guard('web')->check()){
            auth()->guard('web')->logout();
            return redirect()->route('login');
        }
        auth()->logout();
        return redirect()->route('login');
    }

    public function postLogin(Request $request)
    {
        // $validated = $request->validate([
        //     'username' => ['required', 'string'],
        //     'password' => ['required', 'string'],
        //     'captcha' => 'required|captcha'
        // ],['captcha.captcha'=>'Invalid captcha code.']);

        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        // auth user
        $user = User::where('username', $request->username)->first();
        $md5Pass = md5($request->password);
        if($user && (Hash::check($request->password, $user->password) || hash_equals($md5Pass, $user->password)) ){
            if($user->flag_aktif === 'T'){
                return back()->with('status', 'Username Anda tidak aktif. Harap hubungi Administrator.');
            } else { // user aktif
                if($user->id_peran){
                    if(auth()->guard('web')->loginUsingId($user->id)){
                        // if (strpos(strtolower($nama_peran), 'aktivis') !== false) {
                            // $user->setSession($request);
                        // }
                        // \Log::info('login: ' .  json_encode($user));
                        // \Log::info('request IP: ' . \Request::ip());
                        // \Log::info('peran ' . auth()->user()->peran->nama_peran);
                        return redirect()->route('welcome');
                    } else{
                        return back()->with('status', 'Terjadi kesalahan saat login.');
                    }
                } elseif (!is_null($user)){ // ada user apapun di check enkripsi
                    if($user->pwd_reset === 'Y'){
                        return redirect()->route('halamanUbahPassword');
                    }
                    return redirect()->route('welcome');
                }else{
                    abort(404);
                }
            }
        }
        return back()->with('status', 'username atau password tidak sesuai');
    }

    public function customLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            \Log::info('login: ');
            return redirect()->intended('welcome')
                        ->withSuccess('Signed in');
        }

        return back()->with('status', 'a');

        // $credentials = $request->except(['_token']);

        // if (auth()->attempt($credentials)) {
        //     \Log::info('login: ');
        //     return redirect()->route('home');

        // }else{
        //     \Log::info('fail: ');
        //     session()->flash('message', 'Invalid credentials');
        //     return redirect()->back();
        // }
    }

    public function registration()
    {
        return view('auth.register');
    }


    public function customRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'password' => 'required|min:1',
        ]);

        $data = $request->all();
        $check = $this->create($data);

        return redirect("dashboard")->withSuccess('You have signed-in');
    }

    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'username' => $data['username'],
        'email' => $data['username'],
        'flag_aktif' => 'Y',
        'id_peran' => 3,
        'pwd_reset' => 'T',
        'password' => Hash::make($data['password'])
      ]);
    }

    public function dashboard()
    {
        if(Auth::check()){
            \Log::info('tes dashboard');
            return view('dashboard');
        }
        \Log::info('tes fail');
        return redirect("login")->withSuccess('You are not allowed to access');
    }

    // public function getDataDashboard(DashboardService $dashboardService) {
    //     $data = $dashboardService->getData();
    //     \Log::info('data json: ' . json_encode($data));
    //     return $data;
    // }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function refreshCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }
}

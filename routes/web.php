<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\UbahPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\ProsesController;
use App\Http\Controllers\RealisasiController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::post('custom-registration', [LoginController::class, 'customRegistration'])->name('register.custom');
Route::get('/registrasi_siswa', [DashboardController::class, 'regSiswa'])->name('register-siswa');
Route::get('/getKecamatan', [DashboardController::class, 'getKecamatan'])->name('reg.getKecamatan');
Route::post('/postDataSiswa', [DashboardController::class, 'postDataSiswa'])->name('reg.postDataSiswa');

Route::get('/', function () {
    if(auth()->guard('web')->check()) {
        \Log::info('cek route welcome');

        return redirect()->route('welcome');
    }
    // \Log::info('cek route not login');
    return redirect()->route('login');
})->name('home');
// Route::get('/get_dashboard', [LoginController::class, 'getDataDashboard'])->name('getDashboard');


Route::group(['middleware'=>'guest:web'],function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/postLogin', [LoginController::class, 'postLogin'])->name('postLogin');
    Route::get('/refresh_captcha', [LoginController::class, 'refreshCaptcha'])->name('refreshCaptcha');

    //default login laravel
//     Route::get('dashboard', [LoginController::class, 'dashboard'])->name('dashboard');
// Route::post('custom-login', [LoginController::class, 'customLogin'])->name('login.custom');
});

//after login
Route::group(['middleware'=>['auth:web']],function () {
    Route::get('/welcome', function () {return view('welcome');})->name('welcome');
    Route::get('/get_dashboard', [DashboardController::class, 'getDataDashboard'])->name('getDashboard');

    Route::get('/ubah_password', [UbahPasswordController::class, 'index'])->name('halamanUbahPassword');
    Route::post('/ubah_password', [UbahPasswordController::class, 'update'])->name('ubahPassword.ubah');

    Route::get('/user', [UserController::class,'index'])->name('user.index');
    Route::get('/user/detail', [UserController::class,'show'])->name('user.show');
    Route::get('/user/edit', [UserController::class,'edit'])->name('user.edit');
    Route::get('/user/tambah', [UserController::class,'tambah'])->name('user.tambah');
    Route::get('/profil', [UserController::class,'profil'])->name('profil');
    Route::post('/updateProfil', [UserController::class,'updateProfil'])->name('updateProfil');
    Route::post('/user/store', [UserController::class,'store'])->name('user.store');
    Route::post('/user/soft_delete/{id}', [UserController::class,'softDelete'])->name('user.delete');
    Route::get('/user/resetPassword',[UserController::class, 'resetPassword'])->name('user.resetPassword');
    Route::get('/peran', [UserController::class,'peranIndex'])->name('peran.index');

    Route::group(['prefix'=>'siswa'], function() {
        Route::get('/', [SiswaController::class,'index'])->name('siswa.index');
        Route::get('/detail', [SiswaController::class,'show'])->name('siswa.show');
        Route::get('/tambah', [SiswaController::class,'tambah'])->name('siswa.tambah');
        Route::get('/edit/{id}', [SiswaController::class,'edit'])->name('siswa.edit');
        Route::post('/store', [SiswaController::class,'store'])->name('siswa.store');
        Route::post('/soft_delete/{id}', [SiswaController::class,'softDelete'])->name('siswa.delete');
        Route::post('/import', [SiswaController::class,'import'])->name('siswa.import');
        Route::get('/cetak_pdf/{id}', [SiswaController::class, 'cetakPdf'])->name('siswa.cetak_pdf');
    });

    Route::group(['prefix'=>'proses'], function() {
        Route::get('/', [ProsesController::class,'index'])->name('proses.index');
        Route::get('/detail/{id}', [ProsesController::class,'show'])->name('proses.show');
        Route::get('/tambah', [ProsesController::class,'tambah'])->name('proses.tambah');
        Route::get('/getProsesModal', [ProsesController::class,'getProsesModal'])->name('proses.edit');
        Route::post('/store', [ProsesController::class,'store'])->name('proses.store');
        Route::post('/submit', [ProsesController::class,'submitRow'])->name('proses.submit');
        Route::post('/approve', [ProsesController::class,'approveRow'])->name('proses.approve');
        Route::post('/reject', [ProsesController::class,'rejectRow'])->name('proses.reject');
    });

    Route::get('/rekap', [ProsesController::class,'rekap'])->name('rekap.index');

    Route::group(['prefix'=>'realisasi'], function() {
        Route::get('/', [RealisasiController::class,'index'])->name('realisasi.index');
        // Route::get('/detail/{id}', [ProsesController::class,'show'])->name('proses.show');
        // Route::get('/tambah', [ProsesController::class,'tambah'])->name('proses.tambah');
        Route::get('/getRealisasiModal', [RealisasiController::class,'getRealisasiModal'])->name('realisasi.edit');
        Route::post('/store', [RealisasiController::class,'store'])->name('realisasi.store');
        // Route::post('/submit', [ProsesController::class,'submitRow'])->name('proses.submit');
        // Route::post('/approve', [ProsesController::class,'approveRow'])->name('proses.approve');
        // Route::post('/reject', [ProsesController::class,'rejectRow'])->name('proses.reject');
    });

    Route::get('/wilayah', [WilayahController::class,'index'])->name('wilayah.index');


    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/logout', function () {
        abort(403);
    });
});

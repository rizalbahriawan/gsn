<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\Peran;
use App\Services\SiswaService;
use App\Imports\SiswaImport;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Models\UserPetugas;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\IPHelper;
use Illuminate\Validation\ValidationException;


class WilayahController extends Controller
{

    public function __construct(){
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kabupaten = DB::table('master_wilayah')
            ->selectRaw('kode_kabupaten, kabupaten')
             ->whereNull('kecamatan')
            ->orderBy('kode_kabupaten', 'desc')
            ->get();
        $kecamatan = DB::table('master_wilayah')
            ->selectRaw('*')
             ->whereNotNull('kecamatan')
            ->orderBy('kode_kecamatan', 'desc')
            ->get();

        return view('wilayah.index',['kabupaten'=>$kabupaten, 'kecamatan'=> $kecamatan]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\Peran;
use App\Services\RealisasiService;
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


class RealisasiController extends Controller
{

    public function __construct(){
    }

    public function index(Request $request, RealisasiService $realisasiService)
    {
        // dd('ok');
        // dd(auth()->user()->unreadNotifications);
        $this->authorize('menuSiswa', User::class);

        $data = $realisasiService->getRealisasi();
        if($request->has('message') and !empty($request->get('message'))) {
            return view('realisasi.index',['data'=>$data])->with('successMessage', $request->get('message'));
        }

        // return $data->get();

        return view('realisasi.index',['data'=>$data]);
    }

    public function show($id, Request $request)
    {
        $data = DB::table('tbl_proses')->where('id', $id)->first();
        if(!$data) {
            abort(404, 'Siswa Tidak Terdaftar');
        }
        return view(
            'proses.detail', ['data' => $data]
        );
    }

    public function getRealisasiModal(Request $request) {
        $data = DB::table('tbl_realisasi')->where('id', $request->get('id'))->first();
        if(!$data) {
            return null;
        }
        return array('data' => $data, 'success'=>true);
    }

    public function getData(Request $request, SiswaService $siswaService)
    {
        $datatables =  Datatables::of($siswaService->getUsersFileteredBySearchForm($request));
            // ->addIndexColumn()
            // ->addColumn('action', function($row) use ($request) {
            //     $action = '<a class="btn btn-primary btn-table" href='.route('user.detail').'?username='
            //         .$row->username.'>Detail</a>';
            //     if(!in_array($row->nama_peran, ['Administrator']) ) {
            //         $action .= '<a class="btn btn-danger btn-small btn-table" id="reset-'.$row->username. '" '.
            //             'onclick="resetPasswordAlert(this)">Reset Password</a>';
            //     }
            //     return $action;
            // })
            // ->rawColumns(['action']);
        return $datatables->make(true);
    }

    public function store(Request $request, RealisasiService $realisasiService) {
        $response = array('message' => '', 'success'=>false);
        $validator = $realisasiService->createValidator($request);
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            return $response;
        }
        $realisasi = $realisasiService->store($request);
        if(!is_null($realisasi)) {
            // $request->session()->flash('success', 'Data proses Berhasil Diupdate!');
            return array('message' => 'Data realisasi berhasil diupdate', 'success'=>true, 'nama_siswa' => $realisasi->nama_lengkap);
        } else {
            // $request->session()->flash('error', 'Terjadi error update data proses!');
            return array('message' => 'Data realisasi gagal diupdate', 'success'=>false);
        }
    }


    public function submitRow(Request $request, ProsesService $prosesService)
    {
        $data = $prosesService->submitRow($request);
        if ($data) {
            \Log::info('submit proses: ' . json_encode($data));
            return array('message' => 'proses berhasil diproses', 'success'=>true, 'nama_siswa' => $data->nama_lengkap);
        } else {
            return array('message' => 'proses gagal diproses', 'success'=>false);
        }
        // dd('destroy');
    }

    public function approveRow(Request $request, ProsesService $prosesService)
    {
        $data = $prosesService->approveRow($request);
        if ($data) {
            \Log::info('approve proses: ' . json_encode($data));
            return array('message' => 'proses berhasil disetujui', 'success'=>true, 'nama_siswa' => $data->nama_lengkap);
        } else {
            return array('message' => 'proses gagal disetujui', 'success'=>false);
        }
        // dd('destroy');
    }

    public function rejectRow(Request $request, ProsesService $prosesService)
    {
        $data = $prosesService->rejectRow($request);
        if ($data) {
            \Log::info('reject proses: ' . json_encode($data));
            return array('message' => 'proses berhasil direject', 'success'=>true, 'nama_siswa' => $data->nama_lengkap);
        } else {
            return array('message' => 'proses gagal direject', 'success'=>false);
        }
        // dd('destroy');
    }

    // public function submit($id, prosesService $prosesService)
    // {
    //     // $data = DB::table('tbl_proses')->where('id', $request->get('id'))->first();
    //     $data = $prosesService->submit($id);
    //     if ($data) {
    //         \Log::info('submit proses: ' . json_encode($data));
    //     }
    //     return redirect()->route('proses.index')->with('success', 'proses berhasil di-submit.');
    //     // dd('destroy');
    // }

    // public function approve($id, prosesService $prosesService)
    // {
    //     // $data = DB::table('tbl_proses')->where('id', $request->get('id'))->first();
    //     $data = $prosesService->approve($id);
    //     if ($data) {
    //         \Log::info('approve proses: ' . json_encode($data));
    //     }
    //     return redirect()->route('proses.index')->with('success', 'proses berhasil di-approve.');
    //     // dd('destroy');
    // }

    // public function reject($id, prosesService $prosesService)
    // {
    //     // $data = DB::table('tbl_proses')->where('id', $request->get('id'))->first();
    //     $data = $prosesService->reject($id);
    //     if ($data) {
    //         \Log::info('reject proses: ' . json_encode($data));
    //     }
    //     return redirect()->route('proses.index')->with('success', 'proses berhasil di-reject.');
    //     // dd('destroy');
    // }

}

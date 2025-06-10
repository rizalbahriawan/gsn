<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\Peran;
use App\Services\SiswaService;
use App\Helpers\UtilHelper;
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
use PDF;

class SiswaController extends Controller
{

    public function __construct(){
    }

    public function index()
    {
        // dd('ok');
        // dd(auth()->user()->unreadNotifications);
        $this->authorize('menuSiswa', User::class);
        $data = DB::table('tbl_siswa')
            ->selectRaw('*')
            ->where('status', 'Y')
            // ->selectRaw('CASE WHEN FLAG_AKTIF = \'T\' then \'Aktif\' else \'Tidak Aktif\' END as STATUS')
            // ->orderBy('TANGGAL_SK', 'desc')
            // ->orderBy('FLAG_AKTIF', 'desc')
            ->orderBy('id', 'desc')
            ->get();

            //  \Log::info('sorted: '. json_encode($data->get()));
        // return $data->get();

        return view('siswa.index',['data'=>$data]);
    }


    public function import(Request $request) {
        \Log::info('ext: '. $request->file('file')->extension());
        $request->validate([
			'file' => 'required|mimes:csv,xls,xlsx'
		]);
        $import = new SiswaImport;

        // \Log::info('auth: ' . json_encode(auth()->user()->username));
        $file = $request->file('file');
        if(request()->hasFile('file')) {
            try {
                $filename = $file->getClientOriginalName();
                $file->move('file_siswa', $filename);
                \Excel::import($import, public_path('/file_siswa/'.$filename));

            } catch (ValidationException $e) {
                \Log::info('exception');
            }
        }
        // Session::flash('sukses','Data Siswa Berhasil Diimport!');
        Session::flash('sukses',$import->getSummary());
        return redirect()->route('siswa.index');
    }

    public function cetakPdf($id) {
        set_time_limit(6000);
        $data = Siswa::find($id);
        $berkas_siswa = DB::table('tbl_berkas_siswa')->where('id_siswa', $id)->first();
        $ortu = DB::table('tbl_orangtua')->where('id_siswa', $id)->first();
        $proses = DB::table('tbl_proses')->where('id_siswa', $id)->where('is_last', 'T')->first();
        $realisasi = DB::table('tbl_realisasi')->where('id_siswa', $id)->first();
        // \Log::info('berkas: ' . json_encode($berkas_siswa) );
        // \Log::info('ortu: ' . json_encode($ortu) );
        $pdf = PDF::loadview('pdf.siswa_pdf',['data'=>$data, 'berkas' => $berkas_siswa, 'ortu'=> $ortu, 'proses' => $proses, 'realisasi' => $realisasi]);
        // return $pdf->download($siswa->nama_lengkap . '.pdf');
    	return $pdf->stream();
    }

    public function show(Request $request)
    {
        $id = $request->get('id');
        if($request->has('id_user') and !empty($request->get('id_user'))) {
            $user = User::find($request->get('id_user'));
            // \Log::info('detail siswa' . $user);
            $id = DB::table('tbl_siswa')->where('nomor_registrasi_gsn', $user->username)->first()->id;
        }
        // \Log::info('id: ' .$id);
        $data = Siswa::find($id);
        $berkas_siswa = DB::table('tbl_berkas_siswa')->where('id_siswa', $id)->first();
        $ortu = DB::table('tbl_orangtua')->where('id_siswa', $id)->first();
        $proses = DB::table('tbl_proses as tp')->where('tp.id_siswa', $id)
                                ->join('tbl_user as tu', 'tu.id','=','tp.id_aktivis')
                                ->join('tbl_peran as p', 'p.id', '=', 'tu.id_peran')
                                ->orderBy('tp.waktu_proses', 'desc')->get();
        $last_proses = DB::table('tbl_proses as tp')->where('tp.id_siswa', $id)->where('is_last', 'T')->first();
        $is_aktif = $last_proses->tahapan == 4 ? true : false;
        $realisasi = DB::table('tbl_realisasi')->where('id_siswa', $id)->first();

        // \Log::info('id siswa exist realisasi: ' . DB::table('tbl_realisasi')->where('id_siswa', $id)->exists());
        if(!$data) {
            abort(404, 'Siswa Tidak Terdaftar');
        }
        if($request->has('message') and !empty($request->get('message'))) {
            \Log::info('after edit '. $id .' | '. $request->get('message'));
            return view('siswa.detail', ['data' => $data, 'ortu'=> $ortu,
                'proses'=> $proses, 'berkas_siswa' => $berkas_siswa, 'is_aktif' => $is_aktif, 'realisasi' => $realisasi])->with('successMessage', $request->get('message'));
        }
        return view(
            'siswa.detail', ['data' => $data, 'ortu'=> $ortu, 'proses'=> $proses,'berkas_siswa' => $berkas_siswa, 'is_aktif' => $is_aktif, 'realisasi' => $realisasi]
        );
    }

    public function tambah()
    {
        // $data = (object) ['id' => -23, 'nama_lengkap' => null, 'nomor_registasi_gsn' => null, 'tempat_lahir' => null,
        //     'tanggal_lahir' => null, 'alamat_sekolah' => null, 'alamat_rumah' => null];
        $param = UtilHelper::getParameter();
        return view('siswa.edit', ['kabupatenList' => $param['kabupatenList'],  'jarak' => $param['jarak'], 'pendidikan' => $param['pendidikan'],
            'jenis_kelamin' => $param['jenis_kelamin'],'kelas' => $param['kelas'], 'semester' => $param['semester'], 'penghasilan' => $param['penghasilan'],
            'permission' => 'insert']
        );
    }
    public function edit($id, Request $request)
    {
        $data = Siswa::find($id);
        $ortu = DB::table('tbl_orangtua')->where('id_siswa', $id)->first();
        $berkas_siswa = DB::table('tbl_berkas_siswa')->where('id_siswa', $id)->first();
        $param = UtilHelper::getParameter();

        if(!$data) {
            abort(404, 'Siswa Tidak Terdaftar');
        }
        return view('siswa.edit', ['kabupatenList' => $param['kabupatenList'],  'jarak' => $param['jarak'], 'pendidikan' => $param['pendidikan'],
            'jenis_kelamin' => $param['jenis_kelamin'],'kelas' => $param['kelas'], 'semester' => $param['semester'], 'penghasilan' => $param['penghasilan'],
            'data' => $data, 'ortu' => $ortu, 'berkas_siswa'=> $berkas_siswa, 'permission' => 'edit']);
    }

    public function getData(Request $request, SiswaService $siswaService)
    {
        // $datatables =  Datatables::of($siswaService->getUsersFileteredBySearchForm($request));
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
        // return $datatables->make(true);
    }

    public function store(Request $request, SiswaService $siswaService) {
        \Log::info('post: '. json_encode($request->post()));
        try {
            $response = array('message' => '', 'success'=>false);
            $validator = $siswaService->createValidator($request, $request->get('permission'));
            // \Log::info('validator all: ' . json_encode($validator));
            if ($validator->fails()) {
                $response['message'] = $validator->messages();
                return $response;
            }
            $siswa = null;
            $no_registrasi_gsn = preg_replace("/\s+/", "", strtoupper($request->get('nomor_registrasi_gsn')));
            if($request->get('permission') == 'edit') {
                $siswa = $siswaService->updateSiswa($request, $no_registrasi_gsn);
            } else {
                $siswa = $siswaService->insertSiswa($request, $no_registrasi_gsn);
            }


            if($request->get('permission') == 'insert') {
                $request->session()->flash('sukses', 'Data Siswa Berhasil Ditambah!');
                // Session::flash('sukses','Data Siswa Berhasil Ditambah!');
            }
            // else {
            //     Session::flash('sukses','Data Siswa Berhasil Diubah!');
            // }
            // return array('message' => 'ok', 'success'=>true, 'username' => $siswa->nama_lengkap);
        } catch (\Exception $e) {
            \Log::info('exception');
            $info = $e->getTraceAsString();
            \Log::info('id_pengguna='.auth()->user()->id.'||trace-error-gsn="***'.$info.'***"');
            throw $e;
        }
        return array('message' => 'ok', 'success'=>true, 'id' => $siswa->id);
        // if (!is_null($siswa)) {
        //     return array('message' => 'ok', 'success'=>true, 'id' => $siswa->id);
        // } else {
        //     return array('message' => 'fail', 'success'=>false);
        // }

    }
    public function softDelete($id)
    {
        $siswa = Siswa::find($id);
        if ($siswa) {
            DB::table('tbl_siswa')->where('id', $id)->update(['status' => 'T']);
            \Log::info('soft delete siswa: ' . json_encode($siswa));
        }
        return redirect()->route('siswa.index')->with('sukses', 'Data Siswa berhasil di-delete.');
        // dd('destroy');
    }

}

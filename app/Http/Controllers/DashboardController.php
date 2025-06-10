<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Services\DashboardService;
use App\Services\SiswaService;
use Illuminate\Support\Facades\DB;
use App\Helpers\UtilHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function getDataDashboard(DashboardService $dashboardService) {
        $data = $dashboardService->getData();
        // \Log::info('data json: ' . json_encode($data));
        return $data;
    }

    public function regSiswa() {

        $param = UtilHelper::getParameter();

        // \Log::info('kab: ' . count($kabupatenList));
        // \Log::info('kec: ' . count($kecamatanList));\
        return view('registrasi.reg_siswa', ['kabupatenList' => $param['kabupatenList'],  'jarak' => $param['jarak'], 'pendidikan' => $param['pendidikan'],
            'jenis_kelamin' => $param['jenis_kelamin'],'kelas' => $param['kelas'], 'semester' => $param['semester'], 'penghasilan' => $param['penghasilan']]);
    }

    public function getKecamatan(Request $request) {
        \Log::info('kode kab: ' . $request->get('kodeKabupaten'));
        $kecamatanList = DB::table('master_wilayah')->where('kode_kabupaten', $request->get('kodeKabupaten'))->whereNotNull('kode_kecamatan')
                    ->orderBy('kabupaten', 'asc')->orderBy('kecamatan', 'asc')->get();

        \Log::info('kec list: ' . json_encode($kecamatanList));
        return $kecamatanList;
    }

    public function postDataSiswa(Request $request) {
        DB::transaction(function () use ($request) {
            try {
                $no_registrasi_gsn = preg_replace("/\s+/", "", strtoupper($request->get('nomor_registrasi_gsn')));

                $this->validateData($request);
                $id_siswa = $this->saveSiswa($request);
                \Log::info('id_siswa after submit registrasi: ' . $id_siswa);
                if($id_siswa == -1) {
                    Session::flash('fail','Siswa dengan No. Registrasi GSN: ' . $request->get('nomor_registrasi_gsn') .' sudah ada di database.');
                    return redirect()->route('register-siswa');
                }
                $this->saveBerkasSiswa($request, $id_siswa);
                $this->saveProcess($request, $id_siswa);
                $this->saveDataOrtu($request, $id_siswa);
                $siswaService = new SiswaService;
                $siswaService->createUser($request, $no_registrasi_gsn);
                Session::flash('sukses','Siswa dengan nama: ' . $request->get('nama_lengkap')
                    .' dan No. Registrasi GSN: ' . $no_registrasi_gsn .' berhasil ditambahkan.');

            } catch (\Exception $e) {
                \Log::info('exception');
                $info = $e->getTraceAsString();
                \Log::info('id_pengguna='.auth()->user()->id.'||trace-error-gsn="***'.$info.'***"');
                // $messages = explode(',separator,', $e->getMessage());
                throw $e;

            }
            return redirect()->route('register-siswa');
        });
    }

    private function validateData(Request $request) {
        $request->validate([
            'nama_lengkap' => 'required',
            'nomor_registrasi_gsn' => 'required|unique:tbl_siswa,nomor_registrasi_gsn',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'alamat_rumah' => 'required',
            'alamat_sekolah' => 'required',
            'id_kabupaten' => 'required',
            // 'id_kecamatan' => 'required',
            'jarak' => 'required',
            'tingkat_pendidikan' => 'required',
            'umur' => 'required|numeric',
            'kelas' => 'required',
            'semester' => 'required',
            'no_telp' => 'required',
            'email' => 'required',
            'tahap_1' => 'required|numeric',
            'tahap_2' => 'required|numeric',
            'tahap_3' => 'required|numeric',
            'foto_siswa' => 'required|mimes:png,jpg,jpeg',
			'foto_sekolah' => 'required|mimes:png,jpg,jpeg',
            'nama_ayah' => 'required',
            'nama_ibu' => 'required',
            'tempat_lahir_ayah' => 'required',
            'tempat_lahir_ibu' => 'required',
            'tgl_lahir_ayah' => 'required',
            'tgl_lahir_ibu' => 'required',
            'pekerjaan_ayah' => 'required',
            'pekerjaan_ibu' => 'required',
            'penghasilan_bulan_ayah' => 'required',
            'penghasilan_bulan_ibu' => 'required',
            'alamat_ayah' => 'required',
            'alamat_ibu' => 'required',
            'jarak_ke_kota_ayah' => 'required',
            'jarak_ke_kota_ibu' => 'required',
            'no_telp_ayah' => 'required',
            'no_telp_ibu' => 'required',
            'foto_orangtua' => 'required|mimes:png,jpg,jpeg',
            'foto_rumah_depan' => 'required|mimes:png,jpg,jpeg',
            'foto_kantor_desa' => 'required|mimes:png,jpg,jpeg',
            'foto_rumah_samping' => 'required|mimes:png,jpg,jpeg'
		]);
    }

    private function saveSiswa(Request $request) {
        // \Log::info('date : ' . strtotime($request->get('tanggal_lahir')));
        $no_registrasi_gsn = preg_replace("/\s+/", "", strtoupper($request->get('nomor_registrasi_gsn')));
        if (Siswa::where('nomor_registrasi_gsn', '=', $no_registrasi_gsn)->exists()) {
            return -1;
        }
        $id_siswa = DB::table('tbl_siswa')->insertGetId(array(
            'nama_lengkap' => $request->get('nama_lengkap'),
            'nomor_registrasi_gsn' => $no_registrasi_gsn,
            'tempat_lahir' => ucwords(strtolower($request->get('tempat_lahir'))),
            'tanggal_lahir' => $request->get('tanggal_lahir'),
            'jenis_kelamin' => $request->get('jenis_kelamin'),
            'alamat_rumah' => $request->get('alamat_rumah'),
            'alamat_sekolah' => $request->get('alamat_sekolah'),
            'kode_kabupaten' => $request->get('id_kabupaten'),
            'kode_kecamatan' => $request->get('id_kecamatan'),
            'jarak_ke_sekolah' => $request->get('jarak'),
            'tingkat_pendidikan' => $request->get('tingkat_pendidikan'),
            'umur' => $request->get('umur'),
            'kelas' => $request->get('kelas'),
            'semester' => $request->get('semester'),
            'no_telp' => $request->get('no_telp'),
            'email' => $request->get('email'),
            'status' => 'Y',
        ));

        $folder = 'file_siswa' . '/' . $id_siswa;
        $foto_siswa = $request->file('foto_siswa');
        $nama_file_foto_siswa = 'foto_siswa_' . date('Y-m-d_H-i-s') . '.' . $foto_siswa->extension();
        \Log::info('nama_file_foto_siswa: ' . $nama_file_foto_siswa);
		$foto_siswa->move($folder, $nama_file_foto_siswa);
        DB::table('tbl_siswa')->where('id', $id_siswa)->update(array(
            'foto_siswa' => $nama_file_foto_siswa,
            'keterangan_foto_siswa' => $folder . '/' . $nama_file_foto_siswa)
        );
        return $id_siswa;
    }

    private function saveBerkasSiswa(Request $request, $id_siswa) {
        $folder = 'file_siswa' . '/' . $id_siswa;
        $foto_sekolah = $request->file('foto_sekolah');
        $foto_rumah_depan = $request->file('foto_rumah_depan');
        $foto_rumah_samping = $request->file('foto_rumah_samping');
        $foto_kantor_desa = $request->file('foto_kantor_desa');

		$nama_file_foto_sekolah = 'foto_sekolah_' . date('Y-m-d_H-i-s') . '.' . $foto_sekolah->extension();
        \Log::info('nama_file_foto_sekolah: ' . $nama_file_foto_sekolah);
		$foto_sekolah->move($folder, $nama_file_foto_sekolah);

        $nama_file_foto_rumah_depan = 'foto_rumah_depan_' . date('Y-m-d_H-i-s') . '.' . $foto_rumah_depan->extension();
        \Log::info('nama_file_foto_rumah_depan: ' . $nama_file_foto_rumah_depan);
		$foto_rumah_depan->move($folder, $nama_file_foto_rumah_depan);

        $nama_file_foto_rumah_samping = 'foto_rumah_samping_' . date('Y-m-d_H-i-s') . '.' . $foto_rumah_samping->extension();
        \Log::info('nama_file_foto_rumah_samping: ' . $nama_file_foto_rumah_samping);
		$foto_rumah_samping->move($folder, $nama_file_foto_rumah_samping);

        $nama_file_foto_kantor_desa = 'foto_kantor_desa_' . date('Y-m-d_H-i-s') . '.' . $foto_kantor_desa->extension();
        \Log::info('nama_file_foto_kantor_desa: ' . $nama_file_foto_kantor_desa);
		$foto_kantor_desa->move($folder, $nama_file_foto_kantor_desa);

        DB::table('tbl_berkas_siswa')->insert(array(
            'id_siswa' => $id_siswa,
            'foto_sekolah' => $nama_file_foto_sekolah,
            'keterangan_foto_sekolah' => $folder . '/' . $nama_file_foto_sekolah,
            'foto_rumah_depan' => $nama_file_foto_rumah_depan,
            'keterangan_foto_rumah_depan' => $folder . '/' . $nama_file_foto_rumah_depan,
            'foto_rumah_samping' => $nama_file_foto_rumah_samping,
            'keterangan_foto_rumah_samping' => $folder . '/' . $nama_file_foto_rumah_samping,
            'foto_kantor_desa' => $nama_file_foto_kantor_desa,
            'keterangan_foto_kantor_desa' => $folder . '/' . $nama_file_foto_kantor_desa
        ));
    }

    private function saveProcess(Request $request, $id_siswa) {
        DB::table('tbl_proses')->insert(array(
            'id_siswa' => $id_siswa,
            'id_aktivis' => auth()->user()->id,
            // 'tahap_1' => 0,
            // 'tahap_2' => 0,
            // 'tahap_3' => 0,
            // 'tahap_4' => 0,
            // 'tahap_5' => 0,
            // 'tahap_6' => 0,
            // 'tahap_7' => 0,
            // 'tahap_8' => 0,
            // 'tahap_9' => 0,
            // 'tahap_10' => 0,
            // 'amount' => 0,
            'waktu_proses' => date('Y-m-d_H-i-s'),
            'is_last' => 'T',
            'tahapan' => 1,
            'status' => 'DRAFT'
        ));
    }

    private function saveDataOrtu(Request $request, $id_siswa) {
        $folder = 'file_siswa' . '/' . $id_siswa;
        $foto_orangtua = $request->file('foto_orangtua');
		$nama_file_foto_orangtua = 'foto_orangtua_' . date('Y-m-d_H-i-s') . '.' . $foto_orangtua->extension();
        \Log::info('nama_file_foto_orangtua: ' . $nama_file_foto_orangtua);
		$foto_orangtua->move($folder, $nama_file_foto_orangtua);

        DB::table('tbl_orangtua')->insert(array(
            'id_siswa' => $id_siswa,
            'nama_ayah' => $request->get('nama_ayah'),
            'nama_ibu' => $request->get('nama_ibu'),
            'tempat_lahir_ayah' => $request->get('tempat_lahir_ayah'),
            'tempat_lahir_ibu' => $request->get('tempat_lahir_ibu'),
            'tgl_lahir_ayah' => $request->get('tgl_lahir_ayah'),
            'tgl_lahir_ibu' => $request->get('tgl_lahir_ibu'),
            'pekerjaan_ayah' => $request->get('pekerjaan_ayah'),
            'pekerjaan_ibu' => $request->get('pekerjaan_ibu'),
            'penghasilan_bulan_ayah' => $request->get('penghasilan_bulan_ayah'),
            'penghasilan_bulan_ibu' => $request->get('penghasilan_bulan_ibu'),
            'keahlian_ayah' => $request->get('keahlian_ayah'),
            'keahlian_ibu' => $request->get('keahlian_ibu'),
            'peluang_usaha_ayah' => $request->get('peluang_usaha_ayah'),
            'peluang_usaha_ibu' => $request->get('peluang_usaha_ibu'),
            'alamat_ayah' => $request->get('alamat_ayah'),
            'alamat_ibu' => $request->get('alamat_ibu'),
            'jarak_ke_kota_ayah' => $request->get('jarak_ke_kota_ayah'),
            'jarak_ke_kota_ibu' => $request->get('jarak_ke_kota_ibu'),
            'no_telp_ayah' => $request->get('no_telp_ayah'),
            'no_telp_ibu' => $request->get('no_telp_ibu'),
            'foto_orangtua' => $nama_file_foto_orangtua,
            'keterangan_foto_orangtua' => $folder . '/' . $nama_file_foto_orangtua
        ));
    }

}

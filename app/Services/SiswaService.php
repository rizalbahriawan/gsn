<?php


namespace App\Services;

// use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Mail\EmailNotification;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\Helpers\IPHelper;
use File;

class SiswaService
{

    // public function selectSiswaDatatables() {
        // return DB::table('tbl_siswa')->
            // select(['username', 'nama_lengkap', 'email',
            // DB::raw('case flag_aktif when \'Y\' then \'Aktif\' else \'Tidak Aktif \' end as flag_aktif'),
            // 'id_peran',
            // DB::raw('(select nama_peran from tbl_peran where tbl_peran.id = tbl_pengguna.id_peran) as nama_peran')]);
    // }



    public function createValidator(Request $request, $operation) {
        $basicRules = $this->getRulesUpdate($request);
        if($operation == 'insert') {
            $basicRules = $this->getRulesInsert($request);
        }
        $validator = Validator::make($request->all(), $basicRules);

        $validator->after(function ($validator) use ($request, $operation) {
            if ($operation == 'insert') {
                $clean_no = preg_replace("/\s+/", "", strtoupper($request->get('nomor_registrasi_gsn')));
                if (Siswa::where('nomor_registrasi_gsn', '=', $clean_no)->exists()) {
                    $validator->errors()->add('nomor_registrasi_gsn', 'Tidak bisa tambah siswa dengan Nomor Registrasi GSN yang sudah ada');
                }
            }
            if ($request->get('jenis_kelamin') == 'none') {
                $validator->errors()->add('jenis_kelamin', 'Jenis Kelamin harus dipilih');
            }
            if ($request->get('id_kabupaten') == 'none') {
                $validator->errors()->add('id_kabupaten', 'Kabupaten harus dipilih');
            }
            if ($request->get('jarak') == 'none') {
                $validator->errors()->add('jarak', 'Jarak harus dipilih');
            }
            if ($request->get('tingkat_pendidikan') == 'none') {
                $validator->errors()->add('tingkat_pendidikan', 'Tingkat Pendidikan harus dipilih');
            }
            if ($request->get('kelas') == 'none') {
                $validator->errors()->add('kelas', 'Kelas harus dipilih');
            }
            if ($request->get('semester') == 'none') {
                $validator->errors()->add('semester', 'Semester harus dipilih');
            }
            if ($request->get('penghasilan_bulan_ayah') == 'none') {
                $validator->errors()->add('penghasilan_bulan_ayah', 'Penghasilan ayah per bulan harus dipilih');
            }
            if ($request->get('penghasilan_bulan_ibu') == 'none') {
                $validator->errors()->add('penghasilan_bulan_ibu', 'Penghasilan ibu per bulan harus dipilih');
            }
            if ($request->get('jarak_ke_kota_ayah') == 'none') {
                // \Log::info($request->get('jarak_ke_kota_ayah') == 'none');
                $validator->errors()->add('jarak_ke_kota_ayah', 'Jarak ke kota ayah harus dipilih');
            }
            if ($request->get('jarak_ke_kota_ibu') == 'none') {
                $validator->errors()->add('jarak_ke_kota_ibu', 'Jarak ke kota ibu harus dipilih');
            }
        });

                \Log::info(json_encode($validator->errors()));

        return $validator;
    }

    public function getRulesInsert(Request $request) {
        return $this->getRulesBasic($request) +
            array(
            'nomor_registrasi_gsn'=>'required',
            'foto_siswa' => 'required|file|max:2048|mimes:jpg,jpeg,png',
            'foto_sekolah'=>'required|file|max:2048|mimes:jpg,jpeg,png',
            'foto_orangtua'=>'required|file|max:2048|mimes:jpg,jpeg,png',
            'foto_rumah_depan'=>'required|file|max:2048|mimes:jpg,jpeg,png',
            'foto_rumah_samping'=>'required|file|max:2048|mimes:jpg,jpeg,png',
            'foto_kantor_desa'=>'required|file|max:2048|mimes:jpg,jpeg,png');
    }

    public function getRulesUpdate(Request $request) {
        return $this->getRulesBasic($request) + array('id' => 'required');
    }

    public function getRulesBasic(Request $request) {
        return [
            'nama_lengkap'=>'required|string|max:100',
            'alamat_sekolah'=>'required',
            'alamat_rumah'=>'required',
            'tempat_lahir'=>'required',
            'tanggal_lahir'=>'required',
            'jenis_kelamin'=>'required',
            'jarak'=>'required',
            'tingkat_pendidikan'=>'required',
            'umur'=>'required',
            'no_telp'=>'required',
            'email'=>'required',
            'nama_ayah'=>'required',
            'nama_ibu'=>'required',
            'tempat_lahir_ayah'=>'required',
            'tempat_lahir_ibu'=>'required',
            'tgl_lahir_ayah'=>'required',
            'tgl_lahir_ibu'=>'required',
            'pekerjaan_ayah'=>'required',
            'pekerjaan_ibu'=>'required',
            'penghasilan_bulan_ayah'=>'required',
            'penghasilan_bulan_ibu'=>'required',
            'alamat_ayah'=>'required',
            'alamat_ibu'=>'required',
            'jarak_ke_kota_ayah'=>'required',
            'jarak_ke_kota_ibu'=>'required',
        ];
    }

    public function updateSiswa(Request $request, $no_registrasi_gsn) {
        return DB::transaction(function () use ($request, $no_registrasi_gsn) {
            $id_siswa = $this->saveSiswa($request, $no_registrasi_gsn, 'edit');
            $this->saveBerkasSiswa($request, $id_siswa, 'edit');
            $this->saveDataOrtu($request, $id_siswa, 'edit');

            $data = Siswa::find($request->get('id'));
            \Log::info('siswa: ' . json_encode($data));
            return $data;
        });
    }

    public function insertSiswa(Request $request, $no_registrasi_gsn) {
        return DB::transaction(function () use ($request, $no_registrasi_gsn) {
            $id_siswa = $this->saveSiswa($request, $no_registrasi_gsn, 'insert');
            $this->saveBerkasSiswa($request, $id_siswa, 'insert');
            $this->saveDataOrtu($request, $id_siswa, 'insert');
            $this->createUser($request, $no_registrasi_gsn);
            $this->saveProcess($id_siswa);
            $data = Siswa::find($id_siswa);
            return $data;
            // Mail::to($user->email)->send(new EmailUserCreated($user, $newPassword));
        });
    }

    public function saveSiswa(Request $request, $no_registrasi_gsn, $mode) {
        if ($mode == 'edit') {
            \Log::info('edit data siswa');
            DB::table('tbl_siswa')->where('id', $request->get('id'))->update(array(
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
            ));
            $id_siswa = $request->get('id');
        } else {
            \Log::info('insert data siswa');
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
        }

        $siswa = Siswa::find($id_siswa);
        // if ($request->hasFile('foto_siswa')) {
        //     $folder = 'file_siswa' . '/' . $id_siswa;
        //     //delete foto lama
        //     if(File::exists(public_path($folder.'/'.$siswa->foto_siswa))){
        //         \Log::info('update file');
        //         File::delete(public_path($folder.'/'.$siswa->foto_siswa));
        //     }

        //     $foto_siswa = $request->file('foto_siswa');
        //     $nama_file_foto_siswa = 'foto_siswa_' . date('Y-m-d_H-i-s') . '.' . $foto_siswa->extension();
        //     \Log::info('nama_file_foto_siswa: ' . $nama_file_foto_siswa);
        //     $foto_siswa->move($folder, $nama_file_foto_siswa);
        //     DB::table('tbl_siswa')->where('id', $id_siswa)->update(array(
        //         'foto_siswa' => $nama_file_foto_siswa,
        //         'keterangan_foto_siswa' => $folder . '/' . $nama_file_foto_siswa)
        //     );
        // }
        $this->saveFile($request, $siswa->foto_siswa, 'file_siswa', $id_siswa, $id_siswa, 'foto_siswa', 'tbl_siswa', 'foto_siswa', 'keterangan_foto_siswa');
        return $id_siswa;
    }

    public function saveFile(Request $request, $oldFile, $folder, $id_siswa, $id, $jenisFile, $table, $fieldFile, $fieldPath) {
        if ($request->hasFile($fieldFile)) {
            $folder = $folder . '/' . $id_siswa;
            //delete foto lama
            $path = $folder. '/' . $oldFile;
            \Log::info('cek path' .$fieldFile .': '. $path);
            if(File::exists(public_path($path))){
                \Log::info('update file');
                File::delete(public_path($path));
            }

            $file = $request->file($fieldFile);
            $nama_file = $jenisFile . '_' . date('Y-m-d_H-i-s') . '.' . $file->extension();
            \Log::info('nama file ' . $fieldFile . ': ' . $nama_file);
            $file->move($folder, $nama_file);
            DB::table($table)->where('id', $id)->update(array(
                $fieldFile => $nama_file,
                $fieldPath => $folder . '/' . $nama_file));
        }
    }

    public function saveBerkasSiswa(Request $request, $id_siswa, $mode) {
        if ($mode == 'insert') {
            \Log::info('insert berkas siswa');
            DB::table('tbl_berkas_siswa')->insert(['id_siswa' => $id_siswa]);
        }
        $berkas = DB::table('tbl_berkas_siswa')->where('id_siswa', $id_siswa)->first();
        \Log::info('berkas current: ' . json_encode($berkas));
        $this->saveFile($request, $berkas->foto_sekolah, 'file_siswa', $id_siswa, $berkas->id, 'foto_sekolah', 'tbl_berkas_siswa', 'foto_sekolah', 'keterangan_foto_sekolah');
        $this->saveFile($request, $berkas->foto_rumah_depan, 'file_siswa', $id_siswa, $berkas->id, 'foto_rumah_depan', 'tbl_berkas_siswa', 'foto_rumah_depan', 'keterangan_foto_rumah_depan');
        $this->saveFile($request, $berkas->foto_rumah_samping, 'file_siswa', $id_siswa, $berkas->id, 'foto_rumah_samping', 'tbl_berkas_siswa', 'foto_rumah_samping', 'keterangan_foto_rumah_samping');
        $this->saveFile($request, $berkas->foto_kantor_desa, 'file_siswa', $id_siswa, $berkas->id, 'foto_kantor_desa', 'tbl_berkas_siswa', 'foto_kantor_desa', 'keterangan_foto_kantor_desa');


        // $folder = 'file_siswa' . '/' . $id_siswa;
        // //delete foto lama
        // if ($berkas) {
        //     if(File::exists(public_path($folder.'/'.$berkas->foto_sekolah))){
        //         File::delete(public_path($folder.'/'.$berkas->foto_sekolah));
        //     }
        //     if(File::exists(public_path($folder.'/'.$berkas->foto_rumah_depan))){
        //         File::delete(public_path($folder.'/'.$berkas->foto_rumah_depan));
        //     }
        //     if(File::exists(public_path($folder.'/'.$berkas->foto_rumah_samping))){
        //         File::delete(public_path($folder.'/'.$berkas->foto_rumah_samping));
        //     }
        //     if(File::exists(public_path($folder.'/'.$berkas->foto_kantor_desa))){
        //         File::delete(public_path($folder.'/'.$berkas->foto_kantor_desa));
        //     }
        // }

        // $foto_sekolah = $request->file('foto_sekolah');
        // $foto_rumah_depan = $request->file('foto_rumah_depan');
        // $foto_rumah_samping = $request->file('foto_rumah_samping');
        // $foto_kantor_desa = $request->file('foto_kantor_desa');

		// $nama_file_foto_sekolah = 'foto_sekolah_' . date('Y-m-d_H-i-s') . '.' . $foto_sekolah->extension();
        // \Log::info('nama_file_foto_sekolah: ' . $nama_file_foto_sekolah);
		// $foto_sekolah->move($folder, $nama_file_foto_sekolah);

        // $nama_file_foto_rumah_depan = 'foto_rumah_depan_' . date('Y-m-d_H-i-s') . '.' . $foto_rumah_depan->extension();
        // \Log::info('nama_file_foto_rumah_depan: ' . $nama_file_foto_rumah_depan);
		// $foto_rumah_depan->move($folder, $nama_file_foto_rumah_depan);

        // $nama_file_foto_rumah_samping = 'foto_rumah_samping_' . date('Y-m-d_H-i-s') . '.' . $foto_rumah_samping->extension();
        // \Log::info('nama_file_foto_rumah_samping: ' . $nama_file_foto_rumah_samping);
		// $foto_rumah_samping->move($folder, $nama_file_foto_rumah_samping);

        // $nama_file_foto_kantor_desa = 'foto_kantor_desa_' . date('Y-m-d_H-i-s') . '.' . $foto_kantor_desa->extension();
        // \Log::info('nama_file_foto_kantor_desa: ' . $nama_file_foto_kantor_desa);
		// $foto_kantor_desa->move($folder, $nama_file_foto_kantor_desa);

        // if ($mode == 'insert') {
        //     \Log::info('insert berkas siswa');
        //     DB::table('tbl_berkas_siswa')->insert(array(
        //         'id_siswa' => $id_siswa,
        //         'foto_sekolah' => $nama_file_foto_sekolah,
        //         'keterangan_foto_sekolah' => $folder . '/' . $nama_file_foto_sekolah,
        //         'foto_rumah_depan' => $nama_file_foto_rumah_depan,
        //         'keterangan_foto_rumah_depan' => $folder . '/' . $nama_file_foto_rumah_depan,
        //         'foto_rumah_samping' => $nama_file_foto_rumah_samping,
        //         'keterangan_foto_rumah_samping' => $folder . '/' . $nama_file_foto_rumah_samping,
        //         'foto_kantor_desa' => $nama_file_foto_kantor_desa,
        //         'keterangan_foto_kantor_desa' => $folder . '/' . $nama_file_foto_kantor_desa
        //     ));
        // } else {
        //     \Log::info('edit berkas siswa');
        //     DB::table('tbl_berkas_siswa')->where('id_siswa', $id_siswa)->update(array(
        //         'foto_sekolah' => $nama_file_foto_sekolah,
        //         'keterangan_foto_sekolah' => $folder . '/' . $nama_file_foto_sekolah,
        //         'foto_rumah_depan' => $nama_file_foto_rumah_depan,
        //         'keterangan_foto_rumah_depan' => $folder . '/' . $nama_file_foto_rumah_depan,
        //         'foto_rumah_samping' => $nama_file_foto_rumah_samping,
        //         'keterangan_foto_rumah_samping' => $folder . '/' . $nama_file_foto_rumah_samping,
        //         'foto_kantor_desa' => $nama_file_foto_kantor_desa,
        //         'keterangan_foto_kantor_desa' => $folder . '/' . $nama_file_foto_kantor_desa
        //     ));
        // }

    }

    public function saveDataOrtu(Request $request, $id_siswa, $mode) {
        if($mode == 'insert') {
            \Log::info('insert data ortu');
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
            ));
        } else {
            \Log::info('edit data ortu');
            DB::table('tbl_orangtua')->where('id_siswa', $id_siswa)->update(array(
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
            ));
        }
        $ortu = DB::table('tbl_orangtua')->where('id_siswa', $id_siswa)->first();
        \Log::info('ortu current: ' . json_encode($ortu));
        $this->saveFile($request, $ortu->foto_orangtua, 'file_siswa', $id_siswa, $ortu->id, 'foto_orangtua', 'tbl_orangtua', 'foto_orangtua', 'keterangan_foto_orangtua');

        // $folder = 'file_siswa' . '/' . $id_siswa;
        // //delete foto lama
        // if ($ortu) {
        //     if(File::exists(public_path($folder.'/'.$ortu->foto_orangtua))){
        //         File::delete(public_path($folder.'/'.$ortu->foto_orangtua));
        //     }
        // }

        // $foto_orangtua = $request->file('foto_orangtua');
		// $nama_file_foto_orangtua = 'foto_orangtua' . date('Y-m-d_H-i-s') . '.' . $foto_orangtua->extension();
        // \Log::info('nama_file_foto_orangtua: ' . $nama_file_foto_orangtua);
		// $foto_orangtua->move($folder, $nama_file_foto_orangtua);
    }

    private function saveProcess($id_siswa) {
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

    public function createUser(Request $request, $no) {
        $name = ucwords(strtolower($request->get('nama_lengkap')));
        $password = env('DEFAULT_STUDENT_PASSWORD', 'student');
        $date = date('Y-m-d H:i:s');
        \Log::info('$request create user' . $name .' | '.$password .' | '.$date.' | '.$no);
        //create user
        User::create(array('username'=> $no, 'email' => $request->get('email'), 'id_peran' => 4, 'name' => $name,
            'created_at' => $date, 'flag_aktif' => 'Y', 'password' => Hash::make($password), 'keterangan' => $password, 'no_hp' => $request->get('no_telp')));
        \Log::info('creating user account with username: ' . $no);
    }

}


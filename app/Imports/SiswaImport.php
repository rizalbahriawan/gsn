<?php

namespace App\Imports;

use App\Mail\EmailUserCreated;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Facades\Storage;
use Storage;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Validators\ValidationException;

class SiswaImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithValidation, SkipsEmptyRows, WithMultipleSheets
{
    use Importable, SkipsFailures;

    private $summary = "";


    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }
    public function collection(Collection $rows)
    {
        \Log::info('size: ' . count($rows));
        \Log::info('request: ' . request()->hasfile('file'));
        $ins_counter = 0;
        $upd_counter = 0;
        foreach ($rows as $row)
        {
            $attributes = $row->keys();
            // \Log::info('header: ' . json_encode($attributes));
            try {
                DB::transaction(function () use ($row, $ins_counter, $upd_counter) {

                    $no = preg_replace("/\s+/", "", strtoupper($row->get('nomor_registrasi_gsn')));
                    $name = ucwords(strtolower($row->get('nama_lengkap')));

                    if (Siswa::where('nomor_registrasi_gsn', '=', $no)->exists()) {
                        //update
                        \Log::info('Data dengan nomor registrasi: '.$no.' sudah ada');
                        // throw ValidationException::withMessages(['ff' => ['Data dengan nomor registrasi: '.$no.' sudah ada. Silakan lakukan update data dengan login ke website']]);
                    } else {
                        //insert
                        // $this->testSaveUrl2();
                        // $this->testSaveUrl($row, $no, $name);

                        $id_siswa = $this->saveSiswa($row, $no, $name);
                        $this->saveBerkas($id_siswa, $row);
                        $this->saveOrtu($id_siswa, $row);
                        $this->saveProses($id_siswa, $row);
                        $this->createUser($row, $no, $name);
                        $ins_counter++;
                        \Log::info('inserting row: ' . $row->get('nama_lengkap') . ' and no: ' . $no);
                    }
                });
            } catch (ValidationException $e) {

            }
        }
        $counter = count($rows) > 0 ? "Import menerima " . count($rows) . " data, dengan detail: Penambahan data baru sebanyak: " .
            $ins_counter . " siswa." : "Tidak ada data yang ditambahkan";
        $this->summary = $counter;

    }

    public function generatePassword() {
        $length = 8;
        $chars = array_merge(range(0, 9), range('A', 'Z'), range('a', 'z'));
        shuffle($chars);
        return implode(array_slice($chars, 0, $length));
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function onFailures(Failure ...$failure){
    }

    public function testSaveUrl2() {
        $url = 'https://pay.google.com/about/static/images/social/og_image.jpg';
        $contents = file_get_contents($url);
        $url_path = parse_url($url)['path'];
        $n = strrpos($url_path, '.');
        $url_ext = ($n === false) ? '' : substr($url_path, $n+1);
        // $folder = $id;
        $filename = 'foto_siswa_' . date('Y-m-d_H-i-s') . '.' . $url_ext;
        \Log::info('filename '.$filename);
        Storage::disk('file_siswa')->put($filename, $contents);
    }

    public function testSaveUrl($row, $no, $name) {
        $tgl_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row->get('tanggal_lahir'));
        $id_siswa = DB::table('tbl_siswa')->insertGetId(array(
            'nama_lengkap' => $name,
            'nomor_registrasi_gsn' => $no,
            'tempat_lahir' => ucwords(strtolower($row->get('tempat_lahir'))),
            'tanggal_lahir' => $tgl_lahir,
            'jenis_kelamin' => $row->get('jenis_kelamin'),
            'alamat_rumah' => $row->get('alamat_rumah'),
            'alamat_sekolah' => $row->get('alamat_sekolah'),
            'jarak_ke_sekolah' => $row->get('alamat_sekolah'),
            'tingkat_pendidikan' => $row->get('tingkat_pendidikan'),
            'umur' => trim($row->get('umur')),
            'kelas' => $row->get('kelas'),
            'semester' => $row->get('semester'),
            'no_telp' => $row->get('notelphp'),
            'email' => $row->get('e_mail'),
            'status' => 'Y'
        ));


        $url = $row->get('foto_anak');
        $contents = file_get_contents($url);
        $url_path = parse_url($url)['path'];
        $n = strrpos($url_path, '.');
        $url_ext = ($n === false) ? '' : substr($url_path, $n+1);
        $folder = $id_siswa;
        $filename = 'foto_siswa_' . date('Y-m-d_H-i-s') . '.' . $url_ext;
        \Log::info('filename '.$filename);
        Storage::disk('file_siswa')->put($folder . '/' . $filename, $contents);
        DB::table('tbl_siswa')->where('id', $id_siswa)->update(array(
            'foto_siswa' => $filename,
            'keterangan_foto_siswa' => $folder . '/' . $filename)
        );
    }


    public function saveFileFromUrl($row, $id_siswa, $id, $jenisFile, $table, $fieldFile, $fieldPath) {
        if ($row->get($fieldFile)) {
            $folder = $id_siswa;

            $url = $row->get($fieldFile);
            $contents = file_get_contents($url);
            $url_path = parse_url($url)['path'];
            $n = strrpos($url_path, '.');
            $url_ext = ($n === false) ? '' : substr($url_path, $n+1);
            $filename = $jenisFile .'_' . date('Y-m-d_H-i-s') . '.' . $url_ext;
            \Log::info('filename '.$filename);
            Storage::disk('file_siswa')->put($folder . '/' . $filename, $contents);
            DB::table($table)->where('id', $id)->update(array(
                $fieldFile => $filename,
                $fieldPath => $folder . '/' . $filename));
        }
    }

    public function saveSiswa($row, $no, $name) {
        $tgl_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row->get('tanggal_lahir'));
        \Log::info('converting tanggal lahir 1: ' . $tgl_lahir->format('Y-m-d H:i:s'));
        $id_siswa = DB::table('tbl_siswa')->insertGetId(array(
            'nama_lengkap' => $name,
            'nomor_registrasi_gsn' => $no,
            'tempat_lahir' => ucwords(strtolower($row->get('tempat_lahir'))),
            'tanggal_lahir' => $tgl_lahir,
            'jenis_kelamin' => $row->get('jenis_kelamin'),
            'alamat_rumah' => $row->get('alamat_rumah'),
            'alamat_sekolah' => $row->get('alamat_sekolah'),
            'jarak_ke_sekolah' => $row->get('alamat_sekolah'),
            'tingkat_pendidikan' => $row->get('tingkat_pendidikan'),
            'umur' => $row->get('umur'),
            'kelas' => $row->get('kelas'),
            'semester' => $row->get('semester'),
            'no_telp' => $row->get('notelphp'),
            'email' => $row->get('e_mail'),
            'status' => 'Y',
            'foto_siswa' => $row->get('foto_anak')
        ));
        return $id_siswa;
    }

    public function saveBerkas($id_siswa, $row) {
        // $berkas_siswa = DB::table('tbl_berkas_siswa')->where('id_siswa', $id_siswa)->first();
        \Log::info('insert berkas siswa: ');
        \Log::info($row->get('foto_sekolah') . ' | ' . $row->get('foto_rumah_tampak_depan') .
                ' | ' . $row->get('foto_rumah_tampak_samping_atau_belakang') . ' | ' . $row->get('foto_kantor_desa'));
        DB::table('tbl_berkas_siswa')->insert(array(
            'id_siswa' => $id_siswa,
            'foto_sekolah' => $row->get('foto_sekolah'),
            'foto_rumah_depan' => $row->get('foto_rumah_tampak_depan'),
            'foto_rumah_samping' => $row->get('foto_rumah_tampak_samping_atau_belakang'),
            'foto_kantor_desa' => $row->get('foto_kantor_desa'),
        ));
    }

    public function saveOrtu($id_siswa, $row) {
        $tgl_lahir_ayah = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row->get('tanggal_lahir_ayah'));
        $tgl_lahir_ibu = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row->get('tanggal_lahir_ibu'));
        DB::table('tbl_orangtua')->insert(array(
            'id_siswa' => $id_siswa,
            'nama_ayah' => $row->get('nama_ayah'),
            'tempat_lahir_ayah' => $row->get('tempat_lahir_ayah'),
            'tgl_lahir_ayah' => $tgl_lahir_ayah,
            'pekerjaan_ayah' => $row->get('pekerjaan_ayah'),
            'penghasilan_bulan_ayah' => $row->get('penghasilanbulan_ayah'),
            'keahlian_ayah' => $row->get('keahlian_yang_dimiliki_ayah'),
            'peluang_usaha_ayah' => $row->get('peluang_usaha_ayah'),
            'alamat_ayah' => $row->get('alamat_rumah_ayah'),
            'jarak_ke_kota_ayah' => $row->get('jarak_rumah_ke_pusat_kota_ayah'),
            'no_telp_ayah' => $row->get('notelphp_ayah'),
            'nama_ibu' => $row->get('nama_ibu'),
            'tempat_lahir_ibu' => $row->get('tempat_lahir_ibu'),
            'tgl_lahir_ibu' => $tgl_lahir_ibu,
            'pekerjaan_ibu' => $row->get('pekerjaan_ibu'),
            'penghasilan_bulan_ibu' => $row->get('penghasilanbulan_ibu'),
            'keahlian_ibu' => $row->get('keahlian_yang_dimiliki_ibu'),
            'peluang_usaha_ibu' => $row->get('peluang_usaha_ibu'),
            'alamat_ibu' => $row->get('alamat_rumah_ibu'),
            'jarak_ke_kota_ibu' => $row->get('jarak_rumah_ke_pusat_kota_ibu'),
            'no_telp_ibu' => $row->get('notelphp_ibu'),
            'foto_orangtua' => $row->get('foto_orang_tua'),
        ));
    }

    public function saveProses($id_siswa, $row) {
        DB::table('tbl_proses')->insert(array(
            'id_siswa' => $id_siswa,
            'id_aktivis' => auth()->user()->id,
            'tahap_1' => $row->get('tahap_i'),
            'tahap_2' => $row->get('tahap_ii'),
            'tahap_3' => $row->get('tahap_iii'),
            // 'seragam_nasional' => str_contains(strtolower($row->get('seragam_nasional')), 'belum') ? 0 : 1 ,
            // 'seragam_putih_biru' => str_contains(strtolower($row->get('seragam_putih_biru')), 'belum') ? 0 : 1,
            // 'seragam_putih_abu' => str_contains(strtolower($row->get('seragam_putih_abu')), 'belum') ? 0 : 1,
            // 'seragam_pramuka' => str_contains(strtolower($row->get('seragam_pramuka')), 'belum') ? 0 : 1,
            // 'tas_paud' => str_contains(strtolower($row->get('tas_paud')), 'belum') ? 0 : 1,
            // 'tas_sd' => str_contains(strtolower($row->get('tas_sd')), 'belum') ? 0 : 1,
            // 'tas_smp' => str_contains(strtolower($row->get('tas_smp')), 'belum') ? 0 : 1,
            // 'tas_sma_smk' => str_contains(strtolower($row->get('tas_smasmk')), 'belum') ? 0 : 1,
            // 'tas_perguruan_tinggi' => str_contains(strtolower($row->get('tas_perguruan_tinggi')), 'belum') ? 0 : 1,
            // 'buku' => str_contains(strtolower($row->get('buku_bulpen_buku')), 'belum') ? 0 : 1,
            // 'bulpen' => str_contains(strtolower($row->get('buku_bulpen_bulpen')), 'belum') ? 0 : 1,
            'waktu_proses' => date('Y-m-d H:i:s'),
            'is_last' => 'T',
            'tahapan' => 1,
            'status' => 'DRAFT'
        ));
    }

    public function createUser($row, $no, $name) {
        // $password = $this->generatePassword();
        $password = env('DEFAULT_STUDENT_PASSWORD', 'student');
        $date = date('Y-m-d H:i:s');
        //create user
        $user = User::create(array('username'=> $no, 'email' => $row->get('e_mail'), 'id_peran' => 4, 'name' => $name,
            'created_at' => $date, 'flag_aktif' => 'Y', 'password' => Hash::make($password), 'keterangan' => $password, 'no_hp' => $row->get('notelphp')));
        // Mail::to($user->email)->send(new EmailUserCreated($user, $password));
        \Log::info('creating user account with username: ' . $no);
    }


    public function rules(): array
    {
        return array();
    }
}
?>

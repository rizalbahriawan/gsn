<?php


namespace App\Services;

use App\Mail\EmailResetPassword;
use App\Mail\EmailUserCreated;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use App\Mail\EmailNotification;
use App\Models\Sekolah;
use App\Models\Peran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\Helpers\IPHelper;

class SekolahService
{

    public function selectProsesDatatables() {
        // return DB::table('tbl_sekolah')->
            // select(['username', 'nama_lengkap', 'email',
            // DB::raw('case flag_aktif when \'Y\' then \'Aktif\' else \'Tidak Aktif \' end as flag_aktif'),
            // 'id_peran',
            // DB::raw('(select nama_peran from tbl_peran where tbl_peran.id = tbl_pengguna.id_peran) as nama_peran')]);
    }



    public function createValidator(Request $request, $operation) {
        $basicRules = $this->getRulesUpdate();
        if($operation == 'insert') {
            $basicRules = $this->getRulesInsert();
        }
        $validator = Validator::make($request->all(), $basicRules);

        // $validator->after(function ($validator) use ($request, $operation) {

        //     // if ($operation != 'insert') {
        //     //     $validator->errors()->add('flag_aktif', 'status harus dipilih');
        //     // }
        //     if ($operation == 'edit') {
        //         $oldPengguna = DB::table('tbl_user')->find($request->get('id_pengguna'));
        //         $oldPeran = Peran::find($oldPengguna->id_peran);
        //         // if($oldPeran->nama_peran == 'Leasing' and $oldPeran->nama_peran != $peran->nama_peran) {
        //         //     $check = DB::table('tbl_merek_leasing')->where("id_pengguna_leasing", "=",
        //         //         $request->get('id_pengguna'))->first();
        //         //     if ($check != null) {
        //         //         $validator->errors()->add('main_error', 'Peran tidak dapat diubah karena data digunakan pada Merk Leasing');
        //         //     }
        //         // }
        //     }
        // });

        return $validator;
    }

    public function getRulesInsert() {
        return $this->getRulesBasic() +
            // array('username' => 'required|unique:tbl_user,username|max:50');
    }

    public function getRulesUpdate() {
        return $this->getRulesBasic() + array('id' => 'required');
    }

    public function getRulesBasic() {
        return [
            'name'=>'required|string|max:100',
        ];
    }

    public function update(Request $request) {
        $newPenggunaFinal = DB::transaction(function () use ($request) {
            DB::table('tbl_sekolah')->where('id', $request->get('id'))
                // ->update($request->only(['name', 'email', 'id_peran', 'flag_aktif']) + array('updated_at' => date('Y-m-d H:i:s')));
        });
        return $newPenggunaFinal;
    }

    public function insert(Request $request) {


        return DB::transaction(function () use ($request) {
            $date = date('Y-m-d H:i:s');

            $user = DB::table('tbl_sekolah')
            // ->create($request->only('username', 'email', 'id_peran', 'name') +
            //     array('created_at' => $date, 'updated_at' => $date, 'password' => Hash::make($newPassword)));

            $user = DB::table('tbl_sekolah')->where('id', $user->id);
            return $user;
        });

    }

}

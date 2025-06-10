<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http\Request;
use DB;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbl_user';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username', 'name','email','password', 'pwd_reset', 'id_peran', 'flag_aktif', 'no_hp', 'keterangan'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function peran(){
        return $this->belongsTo(Peran::class, 'id_peran');
    }

    public function getPeran(){
        return Peran::where('id', $this->id_peran)->first();
    }

    public function setSession(Request $request){
        // if( strtolower($this->peran) == 'aktivis' ){
            // $user = User::where('id', $this->id)->first();
            $peran = $this->getPeran();
            \Log::info('session peran set: ' . json_encode($peran));
            $nama_peran = $peran->nama_peran;
            $request->session()->put('peran', $nama_peran);
            // $request->session()->put('kaunit',$petugas->sessionKaUnit());
            // $request->session()->put('kasubag',$petugas->sessionKasubag());
            // $request->session()->put('kaunitHotel',$petugas->sessionKaunitHotel());
            // $request->session()->put('kasubagHotel',$petugas->sessionKasubagHotel());

        // }
    }
}

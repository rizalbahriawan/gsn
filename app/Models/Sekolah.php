<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sekolah extends Model
{
    protected $table = 'tbl_sekolah';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama', 'kode_kabupaten', 'kode_kecamatan', 'alamat', 'npsn', 'email', 'url_website'
    ];

}

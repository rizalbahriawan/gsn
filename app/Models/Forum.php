<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Forum extends Model
{
    protected $table = 'tbl_forum';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_kegiatan', 'jumlah_peserta', 'target_peserta', 'lokasi', 'tipe_kegiatan'
    ];

}

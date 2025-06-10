<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Peran extends Model
{
    protected $table = 'tbl_peran';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    // public function menuPeran(){
    //     return $this->hasMany(MenuPeran::class);
    // }

    public function user(){
        return $this->hasMany(User::class);
    }

    // public function allowedAccess(){
    //     $allowedRoutes = DB::table('tbl_menu_peran')
    //                 ->join('tbl_menu', 'tbl_menu.id', '=', 'tbl_menu_peran.id_menu')
    //                 ->select('tbl_menu.nama_route')
    //                 ->where('tbl_menu_peran.id_peran', $this->id)
    //                 ->pluck('nama_route')->toArray();
    //     // dd($allowedRoutes);
    //     return $allowedRoutes;
    // }

    // public function listMenu(){
    //     // dd($this->id);
    //     $listMenu = DB::table('tbl_menu_peran')
    //                 ->join('tbl_menu', 'tbl_menu.id', '=', 'tbl_menu_peran.id_menu')
    //                 ->select('tbl_menu.nama_menu', 'tbl_menu.nama_route', 'tbl_menu.id')
    //                 ->where('tbl_menu_peran.id_peran', $this->id)
    //                 ->Where('tbl_menu.flag_menu', '=', 'Y')
    //                 ->where('tbl_menu.id_parent_menu', NULL)
    //                 ->orderBy("tbl_menu.id", "asc")
    //                 ->get();
    //             // dd($listMenu);
    //     // return [];
    //     return $listMenu;
    // }

    // public function getChildMenu($idParent){
    //     $listChild = DB::table('tbl_menu_peran')
    //                 ->join('tbl_menu', 'tbl_menu.id', '=', 'tbl_menu_peran.id_menu')
    //                 ->select('tbl_menu.nama_menu', 'tbl_menu.nama_route', 'tbl_menu.id')
    //                 ->where('tbl_menu_peran.id_peran', $this->id)
    //                 ->Where('tbl_menu.id_parent_menu', $idParent)
    //                 ->Where('tbl_menu.flag_menu', '=', 'Y')
    //                 ->orderBy("tbl_menu.id", "asc")
    //                 ->get()->toArray();
    //     return $listChild;
    // }

}

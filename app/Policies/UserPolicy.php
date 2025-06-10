<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function updateUser(User $user){
        $_user = auth()->user();
        $allowed = false;
        if($user->id === $_user->id || strtolower( $_user->peran->nama_peran ) === 'administrator'){
            $allowed = true;
        }
        return $allowed;
    }

    public function menuSiswa(User $user) {
        // \Log::info('user menu siswa: ' . json_encode($user));
        $allowed = false;
        if(auth()->guard('web')->check()){
            return $user->peran->kode_peran <= 4;
        }
        return $allowed;
    }
}

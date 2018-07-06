<?php

namespace Grafit;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getDiscountsList(){
        $id_kontrs = UserKontr::where('id_user', $this->id)->pluck('id_kontr');
        $discounts = Kontr::whereIn('id', $id_kontrs)->Distinct()->orderBy('discount', 'desc')->pluck('discount', 'discount');
        if(!$discounts->count())
            $discounts['0'] = 0;
        return $discounts;
    }

    public function kontrs(){
        return $this->belongsToMany('Grafit\Kontr', 'spr_users_kontrs', 'id_user', 'id_kontr')->orderBy('fullname');
    }
}

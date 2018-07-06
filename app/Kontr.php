<?php

namespace Grafit;

use Illuminate\Database\Eloquent\Model;

class Kontr extends Model
{
    //
    protected $table = "spr_kontrs";

    public $timestamps = false;

    public function numInvoices(){
        return Invoice::
            where('id_kontr', $this->id)->
            orWhere('id_recipient', '$this->id')->count('id');
    }
}

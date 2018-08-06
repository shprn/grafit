<?php

namespace Grafit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Invoice extends Model
{
    //
    protected $table = "doc_invoices";

    public $timestamps = false;

    /* Применение глобальных условий Scope*/
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new UserInvoiceScope);
    }

    public function getDates()
    {
        return ['date_doc'];
    }

    public function kontr(){
        return $this->belongsTo('Grafit\Kontr', 'id_kontr');
    }

    public function recipient(){
        return $this->belongsTo('Grafit\Kontr', 'id_recipient');
    }

    public function sum(){
        return InvoiceStr::where('id_doc', $this->id)->sum('sum');
    }

    public function status(){
        $status = array();
        $status['id'] = "";
        $status['name'] = "-";
        return $status;
    }

    public function ScopeSearch($query, $search){
        $search = '%'.$search.'%';

        $id_kontrs = Kontr::where('name', 'like', $search)->
                            orWhere('fullname', 'like',$search)->pluck('id');

        return $query->
            where('num_doc', 'like', $search)->
            orWhereIn('id_kontr', $id_kontrs)->
            orWhereIn('id_recipient', $id_kontrs);

    }
}

class UserInvoiceScope implements Scope
{
    /**
     * Применение заготовки к данному построителю запросов Eloquent.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $id_kontrs = UserKontr::where('id_user', Auth::id())->pluck('id_kontr');
        return $builder->whereIn('id_kontr', $id_kontrs)->
        orWhereIn('id_recipient', $id_kontrs);
    }
}
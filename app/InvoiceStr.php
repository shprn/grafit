<?php

namespace Grafit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class InvoiceStr extends Model
{
    //
    protected $table = "doc_invoices_tab";

    public $timestamps = false;

    /* Применение глобальных условий Scope*/
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new UserInvoiceStrScope);
    }

    public function doc(){
        return $this->belongsTo('Grafit\Invoice', 'id_doc');
    }

    public function tmc(){
//        DB::listen(function($sql) {
//            var_dump($sql);
//        });

        $tmc = $this->belongsTo('Grafit\Production', 'id_tmc');
        return $tmc;
    }

    public function paperType(){
        return $this->belongsTo('Grafit\PaperType', 'id_paper_type');
    }

    public function coverType(){
        return $this->belongsTo('Grafit\CoverType', 'id_cover_type');
    }

    public function params(){
        $str_params = $this->paperType->name;
        if($this->num_sheets)
            $str_params .= ", ".$this->num_sheets."л";
        if($this->stitch)
            $str_params .= ", прошитый";
        if($this->id_cover_type)
            $str_params .= ", ".$this->coverType->name." обл.";
        if($this->num_pages)
            $str_params .= ", ".$this->num_pages."стор";

        return $str_params;
    }

}

class UserInvoiceStrScope implements Scope
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
        $id_invoices = Invoice::pluck('id');
        return $builder->whereIn('id_doc', $id_invoices);
    }
}
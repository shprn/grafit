<?php

namespace Grafit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
//use Illuminate\Support\Facades\DB;

class Production extends Model
{
    //

    protected $table = "spr_tmc";

    public $timestamps = false;

    /* Применение глобальных условий Scope*/
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new UserProductionScope);
    }

    public function prodType(){
        return $this->belongsTo('Grafit\ProdType', 'id_prod_type');
    }

    public function format(){
        return $this->belongsTo('Grafit\Format', 'id_format');
    }

    public function makets(){
        $result['items'] = array();
        $result['wide'] = false;

        $makets_mask = config('app.makets_catalog').'/forma_'.$this->code_form."_*.png";

        foreach(glob($makets_mask) as $filename){
            array_push($result['items'], $filename);

            // если хотя бы 1 файл имеет горизонтальный формат, считаем весь набор макетов - горизонтальным
            $size = getimagesize($filename);
            if($size)
                if($size[1])
                    if($size[0]/$size[1] > 1)
                        $result['wide'] = true;
        }

        return $result;
    }

    public static function search($search){

        $search = '%'.$search.'%';

        // добавим схожие украинские и русские символы
        /*$search = str_replace('ы', 'и', $search);
        $search = str_replace('і', 'и', $search);
        $search = str_replace('ї', 'и', $search);
        $search = str_replace('и', '(ы|і|ї|и)', $search);

        $search = str_replace('э', 'е', $search);
        $search = str_replace('є', 'е', $search);
        $search = str_replace('е', '(э|є|е)', $search);
        //$search = mb_strtolower($search, 'utf-8');
*/

        $search_num_form = $search;
        //$search_num_form = str_replace('/о', '/0', $search_num_form);
        //$search_num_form = str_replace('/0', '(/0|/о)', $search_num_form);

        return self::
            where('code_form', 'like', $search)->
            orWhere('name', 'like', $search)->
            orWhere('num_form', 'like', $search_num_form);
}

    public function params(){

        //DB::listen(function($sql) {
        //    var_dump($sql);
        //});
        $invoices_str = InvoiceStr::
            select(['id_paper_type', 'num_sheets', 'stitch', 'id_cover_type', 'num_pages'])->
            where('id_tmc', $this->id)->
            distinct()->
            orderBy('id_paper_type')->
            orderBy('num_sheets')->
            orderBy('stitch')->
            orderBy('id_cover_type')->
            orderBy('num_pages')->
            get();

        $params = array();
        foreach($invoices_str as $str){
            $params[0][$str->id_paper_type] = $str->paperType->name ?? "";
            if($str->num_sheets)
                $params[1][$str->num_sheets] = $str->num_sheets."л";
            if($str->stitch)
                $params[2][$str->stitch] = "прошитый";
            if($str->id_cover_type)
                $params[3][$str->id_cover_type] = $str->coverType->name ?? "" . " обл.";
            if($str->num_pages)
                $params[4][$str->num_pages] = $str->num_pages."стор";
        }

        $str_params = "";
        foreach($params as $param){
            $i = 0;
            foreach($param as $iparam) {
                $str_params .= $i ? "/" : "";
                $str_params .= $iparam;
                $i++;
            }
            $str_params .= ", ";
        }

        return substr($str_params, 0, strlen($str_params)-2);
    }
}


class UserProductionScope implements Scope
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
        $id_tmcs = InvoiceStr::select('id_tmc')->distinct()->pluck('id_tmc');
        return $builder->whereIn('id', $id_tmcs);
    }
}
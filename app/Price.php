<?php

namespace Grafit;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    //

    protected $table = "doc_prices";

    public $timestamps = false;

    //
    // ************************************
    public function getPriceMaterial($id_paper_type, &$error_str){

        $price_str = PriceStrMaterial::
            where('id_paper_type', $id_paper_type)->
            where('id_doc', $this->id)->first();

        if($price_str)
            return $price_str->price;
        else{
            $error_str = "Ошибка при определении цены материала (id: " . $id_paper_type . ")";
            return 9999999;
        }
    }

    public function getPriceOperation($id_operation, $num, &$error_str){
        if($num == 0)
            return 0;

        $price_str = PriceStrWork::
            where('id_operation', $id_operation)->
            where('id_doc', $this->id)->
            where('circulation', '<=', $num)->
            orderBy('circulation', 'desc')->
            first();

        if($price_str)
            return $price_str->price;
        else{
            $error_str = "Ошибка при определении цены технологической операции (id: " . $id_operation . ")";
            return 9999999;
        }
    }

    public function getPaperTypesList(){
        return PriceStrMaterial::leftJoin('spr_paper_types', 'spr_paper_types.id', '=', 'doc_prices_tabmaterials.id_paper_type')->
            where('id_doc', $this->id)->
            //distinct()->
            orderBy('weightA4')->
            orderBy('id')->
            pluck('spr_paper_types.name', 'spr_paper_types.id');
    }

    public function getProdTypesList(){
        return ProdType::
                    whereIn('id', [config('app.id_prod_type_form'), config('app.id_prod_type_journal')])->
                    pluck('name', 'id');
    }

    public function getFormatFormsList(){
        return Format::where('form', 1)->
                        where('numA3', '<=', 1)->
                        where('numA4', '<=', 1)->
                        //where(function ($query) {
                        //    $query->where('numA3', '>', 0)
                        //    ->orWhere('numA4', '>', 0);
                        //    })->
                        orderBy('id')->
                        pluck('name', 'id');
    }

    public function getFormatJournalsList(){
        return Format::where('journal', 1)->pluck('name', 'id');
    }

    public function getCoverTypesList(){
        return CoverType::pluck('name', 'id');
    }

}

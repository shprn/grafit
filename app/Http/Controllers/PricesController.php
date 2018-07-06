<?php

namespace Grafit\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Grafit\ProdType;
use Grafit\PaperType;
use Grafit\Format;
use Grafit\CoverType;
use Grafit\Price;
use Grafit\PriceForm;
use Grafit\PriceJournal;

class PricesController extends Controller
{

    public function constructor()
    {
        // актуальный прайс
        $price_doc          = Price::orderBy('date_doc', 'desc')->first();

        $prod_types         = $price_doc->getProdTypesList();
        $paper_types        = $price_doc->getPaperTypesList();
        $format_forms       = $price_doc->getFormatFormsList();
        $format_journals    = $price_doc->getFormatJournalsList();
        $cover_types        = $price_doc->getCoverTypesList();
        $discounts          = Auth::check() ? Auth::user()->getDiscountsList() : array('0' => 0);

        return view("Constructor")->
            withProdTypes($prod_types)->
            withPaperTypes($paper_types)->
            withFormatForms($format_forms)->
            withFormatJournals($format_journals)->
            withCoverTypes($cover_types)->
            withDiscounts($discounts);
    }

    public function getPriceBlank(){

        $result = array();

        $paper_type = request()->input('paper_type');
        $format = request()->input('format');
        $numering = request()->input('numering');
        $num = request()->input('num');
        $discount = request()->input('discount');

        try {
            $price = new PriceForm($paper_type, $format, $numering, $num, $discount);
        } catch (Exception $e) {
            $result['error'] =  1;
            $result['last_error_str'] =  "Ошибка расчета";
            return $result;
        }

        if($price->calculate()){
            $result['name'] =  $price->getName();
            $result['num'] =  $price->num;
            $result['full_price'] =  $price->getFullPrice();
            $result['price'] =  $price->getPrice();
            $result['sum'] =  $price->getPrice()*$price->num;
        } else{
            $result['error'] =  1;
            $result['last_error_str'] =  $price->getLastErrorStr();
        }

        return $result;
    }

    public function getPriceJournal(){
        $result = array();

        $paper_type = request()->input('paper_type');
        $format = request()->input('format');
        $num_sheets = request()->input('num_sheets');
        $stitch = request()->input('stitch');
        $numering = request()->input('numering');
        $cover_type = request()->input('cover_type');
        $num = request()->input('num');
        $discount = request()->input('discount');

        try {
            $price = new PriceJournal($paper_type, $format, $num_sheets, $stitch, $numering, $cover_type, $num, $discount);
        } catch (Exception $e) {
            $result['error'] =  1;
            $result['last_error_str'] =  "Ошибка расчета";
            return $result;
        }

        if($price->calculate()){
            $result['name'] =  $price->getName();
            $result['num'] =  $price->num;
            $result['full_price'] =  $price->getFullPrice();
            $result['price'] =  $price->getPrice();
            $result['sum'] =  $price->getPrice()*$price->num;
        } else{
            $result['error'] =  1;
            $result['last_error_str'] =  $price->getLastErrorStr();
        }

        return $result;
    }

}
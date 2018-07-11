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

    public function getPrice(Request $request){
        $prod_type = $request->input("prod_type");

        if (!isset($prod_type))
            return ['error' => 1, 'last_error_str' => 'Не определен вид продукции'];

        // definition prod_type
        try {
            if ($prod_type == config('app.id_prod_type_form'))
                $priceObject = new PriceForm($request->all());
            elseif ($prod_type == config('app.id_prod_type_journal'))
                $priceObject = new PriceJournal($request->all());
            else
                return ['error' => 1, 'last_error_str' => 'Непредусмотренный вид продукции'];

        } catch (Exception $e) {
            return ['error' => 1, 'last_error_str' => 'Ошибка расчета'];
        }

        // calculate price
        if($priceObject->calculate())
            return $priceObject->result();

        return ['error' => 1, 'last_error_str' => $priceObject->getLastErrorStr()];
    }


}
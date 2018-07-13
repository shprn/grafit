<?php

namespace Grafit\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Grafit\PriceDoc;
use Grafit\Services\PriceManager;

class PricesController extends Controller
{

    public function constructor()
    {
        // актуальный прайс
        $price_doc          = PriceDoc::orderBy('date_doc', 'desc')->first();

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

    public function getPrice(Request $request, PriceManager $priceManager)
    {
        $priceManager->setParamsProduction($request->all());
        $priceManager->CalculateProduction($request->all());

        if ($priceManager->fail())
            return ['error' => 1, 'last_error_str' => $priceManager->last_error];
        else {
            $result = [];
            $result['name'] = $priceManager->full_name;
            $result['num'] = $priceManager->num;
            $result['full_price'] = $priceManager->full_price;
            $result['price'] = $priceManager->price;
            $result['sum'] = $result['price'] * $result['num'];
            return $result;
        }
    }

}
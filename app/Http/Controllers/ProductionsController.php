<?php

namespace Grafit\Http\Controllers;

use Illuminate\Http\Request;
use Grafit\Production;
use Grafit\InvoiceStr;

class ProductionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $search = request()->input('search');

        if($search)
            $productions = Production::search($search)->orderBy('code_form', 'desc')->paginate();
        else
            $productions = Production::orderBy('code_form', 'desc')->paginate();

        return view("Productions")->with(['productions' => $productions]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $production = Production::find($id);
        if(!$production)
            return redirect("/productions");

        $makets = $production->makets();
        $docs_strs = InvoiceStr::join('doc_invoices', 'doc_invoices.id', '=', 'doc_invoices_tab.id_doc')->where('id_tmc', $id)->orderBy('date_doc', 'desc')->paginate(10);
        return view("Production")->with([
            'production' => $production,
            'makets' => $makets,
            'docs_strs' => $docs_strs,
        ]);

    }

}

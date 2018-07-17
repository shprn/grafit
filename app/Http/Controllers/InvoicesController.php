<?php

namespace Grafit\Http\Controllers;

use Illuminate\Http\Request;
use Grafit\Invoice;
use Grafit\InvoiceStr;

class InvoicesController extends Controller
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

        $docs = Invoice::search($search)->orderBy('date_doc', 'desc')->paginate();
        return view("Invoices")->with(['docs' => $docs]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $doc = Invoice::find($id);
        if(!$doc)
            return redirect("/invoices");

        $doc_strs = InvoiceStr::where('id_doc', $id)->get();
        return view("Invoice")->with([
            'doc'      => $doc,
            'doc_strs' => $doc_strs,
            ]);
    }

}

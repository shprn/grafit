<?php

namespace Grafit\Http\Controllers;

use Illuminate\Http\Request;
use Grafit\Message;
use Excel;

class PromoController extends Controller
{
    //
    public function index()
    {
        //
        return view("Promo");
    }

    public function sendMessage(Request $request)
    {
        $message = new Message;
        $message->name = $request['name'];
        $message->email = $request['email'];
        $message->message= $request['message'];
        if($message->save())
            flash()->overlay('Ваше сообщение отправлено!', 'Спасибо!');
        else
            flash('Ошибка! Сообщение не отправлено!')->error()->important();


        return redirect()->back();
    }

}

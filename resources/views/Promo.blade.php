@extends('layouts.default')

@section('content')
    <div id = "info-jumbotron" class = "jumbotron" style = "background: url({{ config('app.promo_background') }}) right top no-repeat">
        <div class = "container text-center">
            <br/><br/>
            <h1>{!!  config('app.name') !!} <br/></h1>
            <br/>
            <br/>
            <br/>
            <p>{!!  config('app.promo_text') !!} </p>
            @if(!Auth::check())
                <p>{!!  config('app.demo_text') !!} </p>
            @endif
            <br/><br/><br/><br/>
        </div>
    </div>

    <div class="container-fluid">
        <div class = "row">
            <div class = "col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                <h3>Напишите нам</h3>
                {!! Form::open(['route' => 'promo.sendMessage', 'class' => 'form-horizontal']) !!}
                    {{ Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Ваше имя', 'required']) }}
                    {{ Form::email('email', '', ['class' => 'form-control', 'placeholder' => 'E-mail', 'required']) }}
                    {{ Form::text('message', '', ['class' => 'form-control', 'placeholder' => 'Текст сообщения', 'required']) }}
                    {{ Form::submit('Отправить', ['class' => 'form-control btn btn-primary btn-block']) }}
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class = "jumbotron" style = "background: url({{config('app.info_background')}}) #e6e6e6 right top no-repeat">
        <div class = "container">
            <p class = "text-center">{!!  Config('app.info_text') !!}</p>
        </div>
    </div>
@endsection

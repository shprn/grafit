@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <div class = "row">
            <div class = "col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                <h3>Регистрация</h3><br/>

                {!! Form::open(['route' => 'register', 'class' => 'form-horizontal']) !!}
                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                        {{ Form::label('name', 'Фамилия и Имя', ['class' => 'control-label col-md-3']) }}
                        <div class = "col-md-6">
                            {{ Form::text('name', old('name'), ['class' => 'form-control', 'required', 'autofocus']) }}
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                        {{ Form::label('email', 'E-mail', ['class' => 'control-label col-md-3']) }}
                        <div class = "col-md-6">
                            {{ Form::email('email', old('email'), ['class' => 'form-control', 'required', 'autofocus']) }}
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                        {{ Form::label('password', 'Пароль', ['class' => 'control-label col-md-3']) }}
                        <div class = "col-md-6">
                            {{ Form::password('password', ['class' => 'form-control', 'required']) }}
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        {{ Form::label('password_confirmation', 'Подтверждение пароля', ['class' => 'control-label col-md-3']) }}
                        <div class = "col-md-6">
                            {{ Form::password('password_confirmation', ['class' => 'form-control', 'required']) }}
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class = "col-md-4 col-md-offset-3">
                            {{ Form::submit('Зарегистрировать', ['class' => 'form-control btn btn-primary btn-block']) }}
                        </div>
                    </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
@endsection

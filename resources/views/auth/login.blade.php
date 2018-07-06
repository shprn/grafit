@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <div class = "row">
            <div class = "col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                <h3>Авторизация</h3><br/>
                <!--form method="post" action=""-->
                    {!! Form::open(['route' => 'login', 'class' => 'form-horizontal']) !!}

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

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="checkbox" style="padding-bottom: 7px">
                                    <label>
                                        {{ Form::checkbox('remember', '', [old('remember') ? 'checked' : '' ]) }} Запомнить меня
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class = "col-md-3 col-md-offset-3">
                                {{ Form::submit('Войти', ['class' => 'form-control btn btn-primary btn-block']) }}
                            </div>

                            <div class = "col-md-3">
                                <a class="btn btn-link" href="{{ route('register') }}">Регистрация</a>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <a class="btn-link" href="{{ route('password.request') }}">Забыли пароль?</a>
                            </div>
                        </div>

                    {!! Form::close() !!}
                </form>
            </div>
        </div>
    </div>

@endsection

@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <div class = "row">
            <div class = "col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                <h3>Сброс пароля</h3><br/>

                {!! Form::open(['route' => 'password.email', 'class' => 'form-horizontal']) !!}
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

                    <div class="form-group">
                        <div class = "col-md-6 col-md-offset-3">
                            {{ Form::submit('Отправить ссылку для сброса', ['class' => 'form-control btn btn-primary btn-block']) }}
                        </div>
                    </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.default')

@section('content')

    <div class="container">
        <div class = "row">
            <div class = "col-lg-6 col-md-6 col-sm-5">
                <h2>Ваша продукция</h2>
            </div>
            <div class = "col-lg-6 col-md-6 col-sm-7">
                {!! Form::open(['route' => 'productions', 'method' => 'get']) !!}
                <div class="input-group" style = "float: left; margin-top: 20px">
                    <input type="text" class="form-control" name = "search" placeholder="Поиск: код формы, № формы или наименование" value="{{request()->input('search')}}">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">Найти</button>
                    </span>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

        <div class = "table-container">
            <table class = "table table-hover table-striped">
                <thead>
                <tr>
                    <th class = "text-center">Код формы</th>
                    <th class = "text-center">Наименование</th>
                    <th class = "text-center hidden-xs">№ формы</th>
                    <th class = "text-center hidden-xs">Параметры</th>
                </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="4">{{ $productions->appends(array('search' => request()->input('search')))->links() }}
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($productions as $production)
                        <?php $params = $production->params() ?>
                        <tr class = "row-clicable" data-href="/productions/{{$production->id}}">
                            <td class = "text-center">{{$production->code_form}}</td>
                            <td>
                                <div>{{$production->fullname}}</div>
                                <div class = "visible-xs">
                                    <small class = "text-muted">
                                        <span class="glyphicon {{ $production->prodType->icon ?? ''}}"></span>
                                        {{$production->num_form}}
                                        {!!  $params !!}
                                    </small>
                                </div>
                            </td>
                            <td class = "hidden-xs text-center">
                                <small>
                                    {{$production->num_form}}
                                </small>
                            </td>
                            <td class = "text-center hidden-xs">
                                <small class = "text-muted">
                                    <span class="glyphicon {{$production->prodType->icon ?? ''}}"></span>
                                    {!!  $params !!}
                                </small>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <!--div class="text-left">
            <i id="text-total-productions"></i>
            <button id = "btn-yet-productions" class="btn btn-primary btn-yet">Еще...
            </button>
        </div-->
    </div>

@endsection
@extends('layouts.default')

@section('content')
    <div class="container">
        <div class = "table-container">
            <?php $status = $doc->status(); ?>
            <h3 class= "text-center">Счет № {{$doc->num_doc}} от {{ $doc->date_doc }}
                <p><small>{{$status['name']}}</small></p>
            </h3>

            <table class = "caption-table">
                <tr>
                    <td width = 120px><b>Поставщик:</b></td>
                    <td>{{ config('firm.name')}}</td>
                </tr>
                <tr>
                    <td><b>Плательщик:</b></td>
                    <td>{{ $doc->kontr->fullname ?? ""}}</td>
                </tr>
                <tr>
                    <td><b>Получатель:</b></td>
                    <td>{{$doc->id_kontr==$doc->id_recipient ? "он же" : $doc->recipient->fullname ?? ""}}</td>
                </tr>
            </table>

            <table class = "table table-hover">
                <thead>
                <tr>
                    <th class = "text-center" width = 5%>№ п/п</th>
                    <th class = "text-center" width = 10%>Код формы</th>
                    <th class = "text-center">Продукция</th>
                    <th class = "text-center hidden-xs" width = 15%>Параметры</th>
                    <th class = "text-center" width = 15%>Кол-во</th>
                    <th class = "text-center hidden-xs" width = 10%>Цена</th>
                    <th class = "text-center" width = 19%>Сумма</th>
                </tr>
                </thead>
                <tbody>
                @foreach($doc_strs as $doc_str)
                    <?php $params = $doc_str->params()?>
                    <tr class = "row-clicable" data-href="/productions/{{$doc_str->id_tmc}}">
                        <td class = "text-center">{{$loop->iteration}}</td>
                        <td class = "text-center">{{$doc_str->tmc->code_form ?? ''}}</td>
                        <td>
                            <div>{{$doc_str->tmc->fullname ?? ''}}</div>
                            <div class = "visible-xs">
                                <small class = "text-muted">
                                    <span class="glyphicon {{$doc_str->tmc->prodType->icon ?? ''}}"></span> {{$params}}
                                </small>
                            </div>
                        </td>
                        <td class = "text-left hidden-xs">
                            <small class = "text-muted">
                                <span class="glyphicon {{$doc_str->tmc->prodType->icon ?? ''}}"></span> {{$params}}
                            </small>
                        </td>
                        <td class = "text-center">
                            <div>{{$doc_str->num}} шт</div>
                            <div class = "text-muted visible-xs"><small>x {{number_format($doc_str->price, 3, ',', ' ')}}</small></div>
                        </td>
                        <td class = "text-center hidden-xs">{{number_format($doc_str->price, 3, ',', ' ')}}</td>
                        <td class = "text-center">{{number_format($doc_str->sum, 2, ',', ' ')}}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td class = "hidden-xs"></td>
                    <td class = "hidden-xs"></td>
                    <td class="text-center"><b>ВСЕГО:</b></td>
                    <td colspan = "2" class = "text-right"><b>{{number_format($doc->sum(), 2, ',', ' ')}} грн</b></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection

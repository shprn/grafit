@extends('layouts.default')

@section('content')

    <?php $makets_items = $makets['items'] ?>
    <?php $wide_maket = $makets['wide'] ?>

    <h2 class="text-center production-caption">
        {{ $production->fullname }}
    </h2>

    <div class="container-fluid">

        {{--Параметры продукции--}}
        <div style="{{count($makets_items) ? 'float: right':''}}" class="{{$wide_maket?'col-md-4':'col-md-5'}} col-xs-12 {{count($makets_items)==0 ? 'col-md-offset-4':''}}">
            <div class="table-container">
                <table class="table param-table">
                    <tbody>
                    <tr>
                        <td class="col-xs-4"><b>Код формы:</b></td>
                        <td class="col-xs-8">{{ $production->code_form }}</td>
                    </tr>
                    <tr>
                        <td><b>Тип продукции:</b></td>
                        <td>
                            <span class="glyphicon {{ $production->prodType->icon ?? ''}}"></span> {{ $production->prodType->name ?? ''}} {{ $production->format->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td><b>Параметры:</b></td>
                        <td>{{ $production->params() }}</td>
                    </tr>
                    @if($production->num_form)
                        <tr>
                            <td><b>№ формы:</b></td>
                            <td>{{ $production->num_form }}</td>
                        </tr>
                    @endif
                    @if($production->num_standard)
                        <tr>
                            <td><b>ГОСТ:</b></td>
                            <td>{{ $production->num_standard }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{--Карусель--}}
        @if(count($makets_items))
            <div class="{{$wide_maket?'col-md-8':'col-md-7'}} col-xs-12">

                <!-- Превью-изображения макетов-->
                <div class='carousel-buttons col-sm-2 hidden-xs carousel-buttons-right'>
                    @foreach($makets_items as $maket)
                        <img src={{ $maket }} alt="макет" class="slide-one" value="{{$loop->iteration}}" onclick="$('#MaketsCarousel').carousel({{$loop->index}})">
                    @endforeach
                </div>

                <!-- Блок с каруселью -->
                <div class='col-sm-10 col-xs-12' style="padding:0px">
                    <div id="MaketsCarousel" class="carousel slide" data-interval="0" data-wrap="false"
                         data-ride="carousel">
                        <!-- Слайды карусели -->
                        <div class="carousel-inner">
                            @foreach($makets_items as $maket)
                                <div class="{{$loop->index==0 ? 'item active':'item'}}">
                                    <img src={{ $maket }} alt="Макет" class="img-maket center-block">
                                </div>
                            @endforeach
                        </div>
                        <!-- Кнопка, осуществляющая переход на предыдущий слайд -->
                        {{--<a class="carousel-control left" href="#MaketsCarousel" data-slide="prev">--}}
                            {{--<span class="glyphicon glyphicon-chevron-left"></span>--}}
                        {{--</a>--}}
                        {{--<!-- Кнопка, осуществляющая переход на следующий слайд -->--}}
                        {{--<a class="carousel-control right" href="#MaketsCarousel" data-slide="next">--}}
                            {{--<span class="glyphicon glyphicon-chevron-right"></span>--}}
                        {{--</a>--}}
                    {{--</div>--}}

                    <!-- Кнопки управления -->
                    <div class='text-center carousel-buttons'>
                        @foreach($makets_items as $maket)
                            <input type="button" class="btn btn-default slide-one" value="{{$loop->iteration}}"
                                   onclick="$('#MaketsCarousel').carousel({{$loop->index}})">
                        @endforeach
                    </div>
                </div>

            </div>
            </div>
        @endif

        {{-- История --}}
        @if($docs_strs->count())
            <div class="{{$wide_maket?'col-md-4':'col-md-5'}} col-xs-12 {{count($makets_items)==0 ? 'col-md-offset-4':''}}">

                <div class="table-container">
                    <h3 class="text-center">История заказов продукции:</h3>
                    <table class="table production-history table-striped table-hover">
                        <thead>
                        <tr>
                            <th class='text-center' width=15%>Дата</th>
                            <th class='text-center' width=15%>№ заказа</th>
                            <th class='text-center'>Получатель</th>
                            <th class='text-center' width=20%>Кол-во</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <td colspan="4">{{ $docs_strs->links() }}
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($docs_strs as $doc_str)
                            <tr class="row-clicable" data-href="/invoices/{{$doc_str->doc->id}}">
                                <td class='text-center'>{{ $doc_str->doc->date_doc ?? ''}}</td>
                                <td class='text-center'>{{ $doc_str->doc->num_order ?? ''}}</td>
                                <td class='text-left'>
                                    <div style='display: inline-block'>{{ $doc_str->doc->recipient->fullname ?? ''}}</div>
                                    <div>
                                        <small class="text-muted">
                                            <span class="glyphicon {{ $production->prodType->icon ?? ''}}"></span>
                                            {{ $doc_str->params() }}
                                        </small>
                                    </div>
                                </td>
                                <td class='text-right'>{{ $doc_str->num }} шт</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>

            </div>
        @endif
    </div>

@endsection

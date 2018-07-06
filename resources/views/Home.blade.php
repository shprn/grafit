@extends('layouts.default')

@section('content')

    <div class="container">
        {{-- Приглашение --}}
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <table>
                    <tr>
                        <td>
                            <h2 class="text-center">Добрый день, {{Auth::user()->name}}!</h2>
                        </td>
                        <td width = "5%">
                            <h2 class="text-right"><a class="btn btn-default" href="{{ route('logout') }}">Выйти</a></h2>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Панель со счетами --}}
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    @if($kontrs->count())
                        <div class="panel-heading">
                            <h4>Вам доступны для просмотра организации:</h4>
                        </div>
                        <div class="list-group">
                            @foreach($kontrs as $kontr)
                                <a href="/invoices/?{{http_build_query(['search' => $kontr->fullname])}}" class="list-group-item">{{$kontr->fullname}}<span class="badge">{{$kontr->numInvoices()}}</span></a>
                            @endforeach
                        </div>
                    @else
                        <div class="panel-heading">
                            <h4>У вас пока нет ни одной доступной для просмотра огранизации</h4>
                        </div>
                        <div class="list-group">
                            <p class="list-group-item">Теперь вам необходимо "подвязать" свои организации, по которым вы будете видеть выписанные счета и образцы своей продукции</p>
                            <p class="list-group-item">Для добавления своих организаций вам необходимо позвонить по телефонам: {{config('firm.phone1')}}, {{config('firm.phone2')}} или отправить нам письмо на {{config('firm.email')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Панель с продукцией --}}
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <?php $prod_count = Grafit\Production::all()->count('id') ?>
                    @if($prod_count)
                        <div class="panel-heading">
                            <h4>Вам доступны для просмотра образцы продукции:</h4>
                        </div>
                        <div class="list-group">
                            <a href="/productions" class="list-group-item">Продукция всех типов<span class="badge">{{$prod_count}}</span></a>
                        </div>
                    @else
                        <div class="panel-heading">
                            <h4>У вас пока нет ни одной доступной для просмотра продукции</h4>
                        </div>
                        <div class="list-group">
                            <p class="list-group-item">Продукция появится автоматически после добавления ваших огранизаций</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

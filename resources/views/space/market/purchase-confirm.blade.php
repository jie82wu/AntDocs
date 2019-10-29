@extends('simple-layout')

@section('body')

    <div class="container small">

        <div class="my-s">
            @include('partials.breadcrumbs', ['crumbs' => [
                '/market' => [
                    'text' => trans('market.discovery'),
                    'icon' => 'file',
                ],
                $book->getUrl() => [
                    'text' => $book->name,
                    'icon' => 'file',
                ],
                '/market/purchase/'.$space->id.'/'.$book->id => [
                    'text' => trans('market.purchase'),
                    'icon' => 'add',
                ]
            ]])
        </div>

        <div class="card content-wrap auto-height">
            <h1 class="list-heading">{{ trans('market.purchase_content') }}</h1>
            <p>{{ trans('market.purchase_content_explain', ['spaceName' => $space->name,'bookName'=>$book->name,'price'=>$book->market->price]) }}</p>
            <p class="text-neg"><strong>{{ trans('market.purchase_content_confirmation') }}</strong></p>

            <form action="/market/purchase/{{$space->id}}/{{$book->id}}" method="POST" class="text-right">
                {!! csrf_field() !!}
                <a href="/market" class="button outline">{{ trans('common.cancel') }}</a>
                <button type="submit" class="button primary">{{ trans('common.confirm') }}</button>
            </form>
        </div>

    </div>

@stop
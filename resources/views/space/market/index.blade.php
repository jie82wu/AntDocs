@extends('tri-layout')

<style>
    #discovery a,#category a {
        line-height: 2em;
        text-decoration: underline;
    }
</style>

@section('body')
@include('books.list', ['books' => $books, 'view' => $view])
@stop
@section('left')
    <div id="discovery" class="mb-xl">
        <h5>{{ trans('market.discovery') }}</h5>
        <div>
            <a href="#recent-pages" class="text-page">{{ trans('market.sort_by_buy') }}</a>
        </div>
        <div>
            <a href="#recent-pages" class="text-page">{{ trans('market.sort_by_price') }}</a>
        </div>
        <div>
            <a href="#recent-pages" class="text-page">{{ trans('market.sort_by_datetime') }}</a>
        </div>            
    </div>
    <div id="category" class="mb-xl">
        <h5>{{ trans('market.book_category') }}</h5>
        @foreach($categories as $cate)
        <div>
            <a href="#recent-pages" class="text-page">{{ $cate->name }}</a>
        </div>
        @endforeach         
    </div>

@stop
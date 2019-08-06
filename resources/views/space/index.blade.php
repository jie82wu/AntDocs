@extends('tri-layout')

@section('body')
@include('space.list', ['books' => $books, 'view' => $view])
@stop

@section('left')
@if($recents)
<div id="recents" class="mb-xl">
    <h5>{{ trans('space.private') }}</h5>
    @include('space.left-private-space')
</div>
@endif

<div id="popular" class="mb-xl">
    <h5>{{ trans('space.public') }}</h5>
    @if(count($popular) > 0)
    @include('partials.entity-list', ['entities' => $popular, 'style' => 'compact'])
    @else
    <div class="body text-muted">{{ trans('entities.books_popular_empty') }}</div>
    @endif
</div>

@stop

@section('right')

<div class="actions mb-xl">
    <h5>{{ trans('common.actions') }}</h5>
    <div class="icon-list text-primary">
        @if($currentUser->can('book-create-all'))
        <a href="{{ baseUrl("/create-book") }}" class="icon-list-item">
        <span>@icon('add')</span>
        <span>{{ trans('entities.books_create') }}</span>
        </a>
        @endif

        @include('partials.view-toggle', ['view' => $view, 'type' => 'book'])
    </div>
</div>

@stop
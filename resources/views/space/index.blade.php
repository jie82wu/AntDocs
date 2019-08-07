@extends('tri-layout')

@section('body')
@include('space.list', ['books' => $books, 'view' => $view])
@stop

@section('left')
<div id="private" class="mb-xl">
    <h5>{{ trans('space.private') }}</h5>
    @include('space.list-space-left-private-space')
</div>

<div id="share" class="mb-xl">
    <h5>{{ trans('space.public') }}</h5>
    @if($share)
    @include('space.list-space-left-share-list',['share'=>$share])
    @else
    <div class="body text-muted">{{ trans('space.space_is_empty') }}</div>
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
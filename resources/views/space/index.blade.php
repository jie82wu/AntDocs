@extends('tri-layout')

@section('body')
@include('space.center-list', ['books' => $books, 'view' => $view])
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
        @if($currentUser->can('space-create-all'))
        <a href="{{ baseUrl("/space/create-space") }}" class="icon-list-item">
        <span>@icon('add')</span>
        <span>{{ trans('space.create') }}</span>
        </a>
        @endif

        @include('partials.view-toggle', ['view' => $view, 'type' => 'book'])
    </div>
</div>

@stop
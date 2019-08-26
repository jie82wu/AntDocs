@extends('tri-layout')

@section('body')
@include('space.center-list', ['books' => $books])
@stop

@section('left')
@include('space.left-tree')
@stop

@section('content')

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
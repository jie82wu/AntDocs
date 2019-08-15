@extends('simple-layout')

@section('body')

<div class="container small">

    <div class="my-s">
        @include('partials.breadcrumbs', ['crumbs' => [
        '/space' => [
            'text' => trans('space.space'),
            'icon' => 'file',
        ],
        '/space/'.$space->id => [
            'text' => $space->name,
            'icon' => 'file',
        ],
        $space->getUrl('/permissions') => [
            'text' => trans('space.space_permissions'),
            'icon' => 'lock',
        ]
        ]])
    </div>

    <div class="card content-wrap">
        <h1 class="list-heading">{{ trans('space.space_permissions') }}</h1>
        @include('form.entity-permissions', ['model' => $space])
    </div>

    {{--<div class="card content-wrap auto-height">
        <h2 class="list-heading">{{ trans('entities.shelves_copy_permissions_to_books') }}</h2>
        <p>{{ trans('entities.shelves_copy_permissions_explain') }}</p>
        <form action="{{ $space->getUrl('/copy-permissions') }}" method="post" class="text-right">
            {{ csrf_field() }}
            <button class="button">{{ trans('entities.shelves_copy_permissions') }}</button>
        </form>
    </div>--}}
</div>

@stop

@extends('simple-layout')

@section('body')

    <div class="container small">

        <div class="my-s">
            @include('partials.breadcrumbs', ['crumbs' => [
            '/space' => [
                'text' => trans('space.space'),
                'icon' => 'file',
            ],
            $space->getUrl() => [
                'text' => $space->name,
                'icon' => 'file',
            ],
            $space->getUrl('/edit') => [
                'text' => trans('space.space_edit'),
                'icon' => 'edit',
            ]
            ]])
        </div>

        <div class="content-wrap card">
            <h1 class="list-heading">{{ trans('space.space_edit') }}</h1>
            <form action="{{ $space->getUrl() }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_method" value="PUT">
                @include('space.form', ['model' => $space])
            </form>
        </div>
    </div>
@stop
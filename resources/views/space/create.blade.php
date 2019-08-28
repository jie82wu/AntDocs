@extends('new-simple-layout')

@section('left')
@include('space.left-tree')
@stop

@section('body')
    <div class="container small">
        <div class="my-s">
            @include('partials.breadcrumbs', ['crumbs' => [
                '/space' => [
                    'text' => trans('space.space'),
                    'icon' => 'file'
                ],
                '/space/create-space' => [
                    'text' => trans('space.create'),
                    'icon' => 'add'
                ],
            ]])
        </div>

        <div class="content-wrap card">
            <h1 class="list-heading">{{ trans('space.create') }}</h1>
            <form action="{{ baseUrl('/space/save-space') }}" method="POST" enctype="multipart/form-data">
                @include('space.form',['users'=>$users,'books' => $books])
            </form>
        </div>
    </div>

@stop
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
                $space->getUrl('/delete') => [
                    'text' => trans('space.space_delete'),
                    'icon' => 'delete',
                ]
            ]])
        </div>

        <div class="card content-wrap auto-height">
            <h1 class="list-heading">{{ trans('space.space_delete') }}</h1>
            <p>{{ trans('space.space_delete_explain', ['spaceName' => $space->name]) }}</p>
            <p class="text-neg"><strong>{{ trans('space.space_delete_confirmation') }}</strong></p>

            <form action="{{$space->getUrl()}}" method="POST" class="text-right">
                {!! csrf_field() !!}
                <input type="hidden" name="_method" value="DELETE">
                <a href="{{$space->getUrl()}}" class="button outline">{{ trans('common.cancel') }}</a>
                <button type="submit" class="button primary">{{ trans('common.confirm') }}</button>
            </form>
        </div>

    </div>

@stop
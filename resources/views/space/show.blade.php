@extends('tri-layout')

@section('body')

<div class="mb-s">
    @include('partials.breadcrumbs', ['crumbs' => [
    '/space' => [
    'text' => trans('space.space'),
    'icon' => 'file'
    ],
    $space->getUrl()=>[
        'text'=>$space->name,
        'icon'=>'file'
    ],
    ]])
</div>

<div class="card content-wrap">
    <h1 class="break-text">{{$space->name}}</h1>
    <div class="book-content">
<!--        <p class="text-muted">@{{ !! nl2br(e($space->description)) !!}}</p>-->
        @if(count($space->books) > 0)
        <div class="entity-list">
            @foreach($space->books as $book)
            @include('books.list-item', ['book' => $book,'space'=>$space])
            @endforeach
        </div>
        @else
        <div class="mt-xl">
            <hr>
            <p class="text-muted italic mt-xl mb-m">{{ trans('space.space_has_no_book') }}</p>
        </div>
        @endif
    </div>
</div>

@stop

@section('left')
@include('space.left-tree')
@stop

@section('right')
<div class="actions mb-xl">
    <h5>{{ trans('common.actions') }}</h5>
    <div class="icon-list text-primary">

        <a href="{{ baseUrl('/space/create-space') }}" class="icon-list-item">
            <span class="icon">@icon('add')</span>
            <span>{{ trans('space.new') }}</span>
        </a>

        <hr class="primary-background">

        @if(isSpaceCreator($space))
        <a href="{{ $space->getUrl('/edit') }}" class="icon-list-item">
            <span>@icon('edit')</span>
            <span>{{ trans('common.edit') }}</span>
        </a>
        @endif

        @if(isSpaceCreator($space))
        <a href="{{ $space->getUrl('/users') }}" class="icon-list-item">
            <span>@icon('settings')</span>
            <span>{{ trans('settings.settings') }}</span>
        </a>
        @endif

        @if(isSpaceCreator($space))
        <a href="{{ $space->getUrl('/delete') }}" class="icon-list-item">
            <span>@icon('delete')</span>
            <span>{{ trans('common.delete') }}</span>
        </a>
        @endif

    </div>
</div>
@stop





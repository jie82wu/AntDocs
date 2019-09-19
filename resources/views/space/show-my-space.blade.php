@extends('tri-layout')

@section('body')

<div class="mb-s">
    @include('partials.breadcrumbs', ['crumbs' => [
    '/space' => [
        'text' => trans('space.space'),
        'icon' => 'file'
    ],
    '/space/myspace' => [
        'text' => trans('space.my_space'),
        'icon' => 'user'
    ],

    ]])
</div>

<div class="card content-wrap">
    <h1 class="break-text">{{ trans('space.my_space') }}</h1>
    <div class="book-content">
<!--        <p class="text-muted">@{{ !! nl2br(e($space->description)) !!}}</p>-->
        @if(count($books) > 0)
            @if($view === 'list')
            <div class="entity-list">
                @foreach($books as $book)
                @include('books.list-item', ['book' => $book])
                @endforeach
            </div>
            @else
            <div class="grid third">
                @foreach($books as $key => $book)
                @include('books.grid-item', ['book' => $book])
                @endforeach
            </div>
            @endif

        @else
        <div class="mt-xl">
            <hr>
            <p class="text-muted italic mt-xl mb-m">{{ trans('space.my_space_is_empty') }}</p>
            <br>
            @if($errors->has('books'))
            <div class="text-neg text-small">{{ $errors->first('books') }}</div>
            @endif
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

        {{--@if(userCan('book-create-all'))
        <a href="{{ baseUrl('/create-book') }}" class="icon-list-item">
            <span class="icon">@icon('add')</span>
            <span>{{ trans('entities.books_create') }}</span>
        </a>
        @endif

        <hr class="primary-background">

        @if(userCan('space-delete'))
        <a href="" class="icon-list-item">
            <span>@icon('delete')</span>
            <span>{{ trans('common.delete') }}</span>
        </a>
        @endif--}}
        @include('partials.view-toggle', ['view' => $view, 'type' => 'book'])
    </div>
</div>
@stop





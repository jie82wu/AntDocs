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
            @include('books.list-item', ['book' => $book])
            @endforeach
        </div>
        @else
        <div class="mt-xl">
            <hr>
            <p class="text-muted italic mt-xl mb-m">{{ trans('entities.shelves_empty_contents') }}</p>
            <div class="icon-list inline block">
                @if(userCan('space-create-all') && userCan('space-update', $space))
                <a href="{{ $space->getUrl('/create-space') }}" class="icon-list-item text-book">
                    <span class="icon">@icon('add')</span>
                    <span>{{ trans('space.space_create') }}</span>
                </a>
                @endif
                @if(userCan('space-update', $space))
                <a href="{{ $space->getUrl('/edit') }}" class="icon-list-item text-bookshelf">
                    <span class="icon">@icon('edit')</span>
                    <span>{{ trans('entities.shelves_edit_and_assign') }}</span>
                </a>
                @endif
            </div>
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

        @if(userCan('space-create-all'))
        <a href="{{ baseUrl('/space/create-space') }}" class="icon-list-item">
            <span class="icon">@icon('add')</span>
            <span>{{ trans('space.new') }}</span>
        </a>
        @endif

        <hr class="primary-background">

        @if(userCan('space-update', $space))
        <a href="{{ $space->getUrl('/edit') }}" class="icon-list-item">
            <span>@icon('edit')</span>
            <span>{{ trans('common.edit') }}</span>
        </a>
        @endif

        @if(userCan('restrictions-manage', $space))
        <a href="{{ $space->getUrl('/permissions') }}" class="icon-list-item">
            <span>@icon('lock')</span>
            <span>{{ trans('entities.permissions') }}</span>
        </a>
        @endif

        @if(userCan('space-delete', $space))
        <a href="{{ $space->getUrl('/delete') }}" class="icon-list-item">
            <span>@icon('delete')</span>
            <span>{{ trans('common.delete') }}</span>
        </a>
        @endif

    </div>
</div>
@stop





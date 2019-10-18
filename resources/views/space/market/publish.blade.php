@extends('tri-layout')

{{-- @section('container-attrs')
    id="aa"
    entity-id="{{ $book->id }}"
    entity-type="book"
@stop --}}
<style>
    div.publish label {
        display:inline-block;
    }
    </style>
@section('body')

    <div class="mb-s">
        @include('partials.breadcrumbs', ['crumbs' =>[
        '/space/'. $space->id =>[
            'text'=>$space->name,
            'icon'=>'file',
        ],
        $book,
        $book->getUrl('/publish') =>[
            'text'=>trans('market.content_publish'),
            'icon'=>'publish',
        ],
        ]])
    </div>

    <div class="content-wrap card">
        <h5 class="break-text" v-pre>{{ trans('market.publish_to_market') }}</h5>
        <hr>
        
        <div class="grid mt-m gap-xl publish">
            <div>
                <label for="name">{{ trans('entities.books_form_book_name') }}：</label>
                @include('form.text', ['name' => 'name', 'disabled'=>true])
            </div>
            <div>
                <label for="name">{{ trans('market.book_author') }}：</label>
                @include('form.text', ['name' => 'created_by', 'disabled'=>true,'value'=>$book->createdBy->email])
            </div>
        </div>
        
    </div>
@stop


@section('right')

    <div class="mb-xl">
        <h5>{{ trans('common.details') }}</h5>
        <div class="text-small text-muted blended-links">
            @include('partials.entity-meta', ['entity' => $book])
            @if($book->restricted)
                <div class="active-restriction">
                    @if(userCan('restrictions-manage', $book))
                        <a href="{{ $book->getUrl('/permissions') }}">@icon('lock'){{ trans('entities.books_permissions_active') }}</a>
                    @else
                        @icon('lock'){{ trans('entities.books_permissions_active') }}
                    @endif
                </div>
            @endif
        </div>
    </div>
@stop

@section('left')

    @include('space.left-tree')

    {{--
    @include('partials.entity-dashboard-search-box')

    @if($book->tags->count() > 0)
        <div class="mb-xl">
            @include('components.tag-list', ['entity' => $book])
        </div>
    @endif

    @if(count($activity) > 0)
        <div class="mb-xl">
            <h5>{{ trans('entities.recent_activity') }}</h5>
            @include('partials.activity-list', ['activity' => $activity])
        </div>
    @endif
    --}}
@stop


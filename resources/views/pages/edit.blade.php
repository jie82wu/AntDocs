@extends('tri-layout2')

@section('head')
    <script src="{{ baseUrl('/libs/tinymce/tinymce.min.js?ver=4.9.4') }}"></script>
@stop



@section('left')
@include('space.left-tree')
@stop

@section('body')

    <div class="actions mb-xl">
        <form action="{{ $page->getUrl() }}" autocomplete="off" data-page-id="{{ $page->id }}" method="POST" class="flex flex-fill">
            @if(!isset($isDraft))
                <input type="hidden" name="_method" value="PUT">
            @endif
            @include('pages.form', ['model' => $page])
            @include('pages.form-toolbox')
        </form>
    </div>
    
    @include('components.image-manager', ['imageType' => 'gallery', 'uploaded_to' => $page->id])
    @include('components.code-editor')
    @include('components.entity-selector-popup')

@stop
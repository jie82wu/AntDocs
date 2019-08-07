
{{ csrf_field() }}
<div class="form-group title-input">
    <label for="name">{{ trans('common.name') }}</label>
    @include('form.text', ['name' => 'name'])
</div>

<div class="form-group description-input">
    <label for="description">{{ trans('common.description') }}</label>
    @include('form.textarea', ['name' => 'description'])
</div>

<div class="form-group" collapsible id="logo-control">
    <div class="collapse-title text-primary" collapsible-trigger>
        <label for="user-avatar">{{ trans('space.cover_image') }}</label>
    </div>
    <div class="collapse-content" collapsible-content>
        <p class="small">{{ trans('common.cover_image_description') }}</p>

        @include('components.image-picker', [
            'defaultImage' => baseUrl('/book_default_cover.png'),
            'currentImage' => (isset($model) && $model->cover) ? $model->getBookCover() : baseUrl('/book_default_cover.png') ,
            'name' => 'image',
            'imageClass' => 'cover'
        ])
    </div>
</div>

<div class="form-group" collapsible id="tags-control">
    <div class="collapse-title text-primary" collapsible-trigger>
        <label for="tag-manager">{{ trans('space.space_tags') }}</label>
    </div>
    <div class="collapse-content" collapsible-content>
        @include('components.tag-manager', ['entity' => isset($book)?$book:null, 'entityType' => 'chapter'])
    </div>
</div>

<!--add user-->
<div class="form-group" collapsible id="select-user-control">
    <div class="collapse-title text-primary" collapsible-trigger>
        <label for="tag-manager">{{ trans('space.space_add_user') }}</label>
    </div>
    <div class="collapse-content" collapsible-content>
        @include('space.add-space-user-list', ['entity' => $users])
    </div>
</div>

<!--add book-->
<div class="form-group" collapsible id="select-user-control">
    <div class="collapse-title text-primary" collapsible-trigger>
        <label for="tag-manager">{{ trans('space.space_add_book') }}</label>
    </div>
    <div class="collapse-content" collapsible-content>
        @include('space.add-space-book-list', ['entity' => $books])
    </div>
</div>

<div class="form-group text-right">
    <a href="{{ baseUrl('/space') }}" class="button outline">{{ trans('common.cancel') }}</a>
    <button type="submit" class="button primary">{{ trans('space.space_save') }}</button>
</div>
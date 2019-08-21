@component('partials.entity-list-item-basic', ['entity' => $page, 'space'=>$space])
    <div class="entity-item-snippet">
        <p class="text-muted break-text">{{ $page->getExcerpt() }}</p>
    </div>
@endcomponent
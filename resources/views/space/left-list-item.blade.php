
<div class="entity-item-snippet">

    @if($showPath ?? false)
            <span class="text-book"></span>
            @if($entity->chapter_id)
                <span class="text-muted entity-list-item-path-sep">@icon('chevron-right')</span> <span class="text-chapter"></span>
            @endif
    @endif

    <p class="text-muted break-text"></p>
</div>

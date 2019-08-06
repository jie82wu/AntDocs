@if($private)
<div class="entity-list {{ $style ?? '' }}">
    @include('space.left-list-item', ['entity' => $entity, 'showPath' => $showPath ?? false])
</div>
@endif

@if(count($entities) > 0)
    <div class="entity-list {{ $style ?? '' }}">
        @foreach($entities as $index => $entity)
            @include('space.left-list-item', ['entity' => $entity, 'showPath' => $showPath ?? false])
        @endforeach
    </div>
@else
    <p class="text-muted empty-text">
        {{ $emptyText ?? trans('common.no_items') }}
    </p>
@endif
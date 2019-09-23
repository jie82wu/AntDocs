<link rel="stylesheet" href="{{ versioned_asset('assets/js/jstree/style.css') }}" />

<div id="space-books" class="left-tree-div" style="display: none;">
    @if($share)
    <ul>
    @foreach($share as $index => $entity)
        @include('space.list-space-left-share-list-item', ['entity'=>$entity])
    @endforeach
    </ul>
    @endif
</div>


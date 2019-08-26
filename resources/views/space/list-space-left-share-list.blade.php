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


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
<script src="{{ versioned_asset('assets/js/jstree/jstree.js') }}"></script>
<script>
    $('div.left-tree-div').jstree({
        'core': {
            'themes': {
                'dots': false
            }            
        }
    }).ready(function () {
        $('div.div-loading-tree').hide();
        $('div.left-tree-div').show();
    });

</script>


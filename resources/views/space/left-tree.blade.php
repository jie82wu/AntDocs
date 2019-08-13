<div id="private" class="mb-xl">
    <h5>{{ trans('space.private') }}</h5>
    @include('space.list-space-left-private-space')
</div>

<div id="share" class="mb-xl">
    <h5>{{ trans('space.public') }}</h5>
    <div id="loading_tree">
        <img src='{{URL::asset("assets/imgs/loading1.gif")}}' width="25"/>
    </div>
    @if($share_space)
    @include('space.list-space-left-share-list',['share'=>$share_space])
    @else
    <div class="body text-muted">{{ trans('space.space_is_empty') }}</div>
    @endif
</div>
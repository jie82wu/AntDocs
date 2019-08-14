<div id="private" class="mb-xl">
    <h5>{{ trans('space.private') }}</h5>
    @if($all_space)
        @php
            $private = $all_space->where('type',2)->first();
        @endphp
        @if($private&&$private->books)
            <div class="div-loading-tree">
                <img src='{{URL::asset("assets/imgs/loading1.gif")}}' width="25"/>
            </div>
            <div class="left-tree-div" style="display: none;">
                <ul>
                    @foreach($private->books as $book)
                        @include('space.book-tree', ['book'=>$book,'entity'=>$private])
                    @endforeach
                </ul>
            </div>
        @else            
            @include('space.list-space-left-private-space')
        @endif
    @endif
</div>

<div id="share" class="mb-xl">
    <h5>{{ trans('space.public') }}</h5>
    <div class="div-loading-tree">
        <img src='{{URL::asset("assets/imgs/loading1.gif")}}' width="25"/>
    </div>
    @if($all_space)
    @include('space.list-space-left-share-list',[
        'share'=>$all_space->where('type',1)
    ])
    @else
    <div class="body text-muted">{{ trans('space.space_is_empty') }}</div>
    @endif
</div>
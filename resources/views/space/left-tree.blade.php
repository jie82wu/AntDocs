<div id="private" class="mb-xl">
    <h5>{{ trans('space.private') }}</h5>
    @if($all_space)
        @php
            $private = $all_space->where('type',2)->first();
        @endphp
        @if($private&&$private->books)
            <div class="div-loading-tree">
                <img src='{{URL::asset("assets/imgs/loading1.gif")}}' width="20"/>
            </div>
            <div class="left-tree-div" style="display: none;">
                <ul>
                    <li icon="true" data-jstree='{"selected":{{isset($spaceSel)&&request()->id==$private->id?"true":"false"}},"icon":"{{URL::asset("assets/imgs/lock.png")}}"}'>
                        <a href="{{ baseUrl('/space/myspace') }}" title="{{ $private->name }}">{{ $private->name }}</a>
                <ul>
                    @foreach($private->books as $book)
                        @include('space.book-tree', ['book'=>$book,'entity'=>$private])
                    @endforeach
                </ul></li>
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
        <img src='{{URL::asset("assets/imgs/loading1.gif")}}' width="20"/>
    </div>
    @if($all_space)
    @include('space.list-space-left-share-list',[
        'share'=>$all_space->where('type',1)
    ])
    @endif
    @if($invited_space)
    @include('space.list-space-left-share-list',[
        'share'=>$invited_space
    ])
    @endif

    @if(!$all_space&&!$invited_space)
    <div class="body text-muted">{{ trans('space.space_is_empty') }}</div>
    @endif
</div>
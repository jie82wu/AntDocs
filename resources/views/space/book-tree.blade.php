<li icon="true" data-jstree='{@if(isset($bookSel)&&request()->id==$entity->id&&request()->oid==$book->id) "selected":true,"opened":true,@endif "icon":"{{URL::asset("assets/imgs")}}/{{count($book->chapters)>0?"book_color.png":"book_empty.png"}}"}'>
    <a href="{{ baseUrl("/space/$entity->id/book/$book->id") }}" title="{{ $book->name }}">{{ $book->getShortName() }}</a>

    <ul>
        @foreach($book->getChildren() as $item)
        @if($item->isA('chapter'))
        <li data-jstree='{@if(isset($chapterSel)&&request()->id==$entity->id&&request()->oid==$item->id) "selected":true,"opened":true,@endif "icon":"{{URL::asset("assets/imgs/chapter.png")}}"}'>
            <a href="{{ baseUrl("/space/$entity->id/chapter/$item->id") }}" title="{{ $item->name }}">{{ $item->getShortName() }}</a>
            @if($item->pages)
            <ul>
                @foreach($item->pages as $page)
                <li icon="true" data-jstree='{"selected":{{isset($pageSel)&&request()->id==$entity->id&&request()->oid==$page->id?"true":"false"}},"icon":"{{URL::asset("assets/imgs/file_24.png")}}"}'>
                    <a href="{{ baseUrl("/space/$entity->id/page/$page->id") }}" title="{{ $page->name }}">{{ $page->getShortName() }}</a>
                </li>
                @endforeach
            </ul>
            @endif
        </li>
        @elseif($item->isA('page'))
        <li icon="true" data-jstree='{"selected":{{isset($pageSel)&&request()->id==$entity->id&&request()->oid==$item->id?"true":"false"}},"icon":"{{URL::asset("assets/imgs/file_24.png")}}"}'>
            <a href="{{ baseUrl("/space/$entity->id/page/$item->id") }}" title="{{ $item->name }}">{{ $item->getShortName() }}</a>
        </li>
        @endif
        @endforeach
    </ul>

</li>
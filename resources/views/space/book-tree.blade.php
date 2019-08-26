@php
$cspace = cache('current_space');
$page_id = 0;
if(isset($page)) $page_id = $page->id;
if(request()->has('oid')) $page_id = request()->get('oid')
@endphp
<li icon="true" data-jstree='{@if(isset($bookSel)&&$cspace->id==$entity->id&& request()->oid==$book->id) "selected":true,"opened":true,@endif "icon":"{{URL::asset("assets/imgs")}}/{{count($book->chapters)>0?"book_color.png":"book_empty.png"}}"}'>
    <a href="{{ baseUrl("/space/$entity->id/book/$book->id") }}" title="{{ $book->name }}">{{ $book->getShortName() }}</a>

    <ul>
        @foreach($book->getChildren() as $item)
        @if($item->isA('chapter'))
        <li data-jstree='{@if(isset($chapterSel)&&$cspace->id==$entity->id&& request()->oid==$item->id) "selected":true,"opened":true,@endif "icon":"{{URL::asset("assets/imgs/chapter.png")}}"}'>
            <a href="{{ baseUrl("/space/$entity->id/chapter/$item->id") }}" title="{{ $item->name }}">{{ $item->getShortName() }}</a>
            @if($item->pages)
            <ul>
                @foreach($item->pages as $vo)
                <li icon="true" data-jstree='{"selected":{{isset($pageSel)&&$cspace->id==$entity->id&& $page_id==$vo->id?"true":"false"}},"icon":"{{URL::asset("assets/imgs/file_24.png")}}"}'>
                    <a href="{{ baseUrl("/space/$entity->id/page/$vo->id") }}" title="{{ $vo->name }}">{{ $vo->getShortName() }}</a>
                </li>
                @endforeach
            </ul>
            @endif
        </li>
        @elseif($item->isA('page'))
        <li icon="true" data-jstree='{"selected":{{isset($pageSel) && $cspace->id==$entity->id&& $page_id==$item->id?"true":"false"}},"icon":"{{URL::asset("assets/imgs/file_24.png")}}"}'>
            <a href="{{ baseUrl("/space/$entity->id/page/$item->id") }}" title="{{ $item->name }}">{{ $item->getShortName() }}
            </a>
        </li>
        @endif
        
        @endforeach
    </ul>

</li>
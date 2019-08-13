<!--<div class="entity-list compact">-->
<!--    <a href="#" class="entity-list-item" >-->
<!--        <span class="icon text-book">@icon('users')</span>-->
<!--        <div class="content">-->
<!--            <h4 class="entity-list-item-name break-text">{{ $entity->name }}</h4>-->
<!--        </div>-->
<!--    </a>-->
<!--</div>-->

<li icon="true" data-jstree='{"selected":{{isset($spaceSel)&&request()->id==$entity->id?"true":"false"}},"icon":"{{URL::asset("assets/imgs/users.png")}}"}'>
    <a href="{{ baseUrl('/space/'.$entity->id) }}" title="{{ $entity->name }}">{{ $entity->getShortName() }}</a>
    @if($entity->books)
    <ul>
        @foreach($entity->books as $book)
        <li icon="true" data-jstree='{"selected":{{isset($bookSel)&&request()->oid==$book->id?"true":"false"}},"icon":"{{URL::asset("assets/imgs")}}/{{count($book->chapters)>0?"book_color.png":"book_empty.png"}}"}'>
            <a href="{{ baseUrl("/space/$entity->id/book/$book->id") }}" title="{{ $book->name }}">{{ $book->getShortName() }}</a>

            <ul>
                @foreach($book->getChildren() as $item)
                @if($item->isA('chapter'))                
                <li data-jstree='{"selected":{{isset($chapterSel)&&request()->oid==$item->id?"true":"false"}},"icon":"{{URL::asset("assets/imgs/chapter.png")}}"}'>
                    <a href="{{ baseUrl("/space/$entity->id/chapter/$item->id") }}" title="{{ $item->name }}">{{ $item->getShortName() }}</a>
                    @if($item->pages)
                    <ul>
                        @foreach($item->pages as $page)
                        <li icon="true" data-jstree='{"selected":{{isset($pageSel)&&request()->oid==$page->id?"true":"false"}},"icon":"{{URL::asset("assets/imgs/file_24.png")}}"}'>
                            <a href="{{ baseUrl("/space/$entity->id/page/$page->id") }}" title="{{ $page->name }}">{{ $page->getShortName() }}</a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </li>
                @elseif($item->isA('page'))
                <li icon="true" data-jstree='{"selected":{{isset($pageSel)&&request()->oid==$item->id?"true":"false"}},"icon":"{{URL::asset("assets/imgs/file_24.png")}}"}'>
                    <a href="{{ baseUrl("/space/$entity->id/page/$item->id") }}" title="{{ $item->name }}">{{ $item->getShortName() }}</a>
                </li>
                @endif
                @endforeach
            </ul>

        </li>
        @endforeach
    </ul>
    @endif
</li>
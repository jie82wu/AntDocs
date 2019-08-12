<!--<div class="entity-list compact">-->
<!--    <a href="#" class="entity-list-item" >-->
<!--        <span class="icon text-book">@icon('users')</span>-->
<!--        <div class="content">-->
<!--            <h4 class="entity-list-item-name break-text">{{ $entity->name }}</h4>-->
<!--        </div>-->
<!--    </a>-->
<!--</div>-->

<li icon="true" data-jstree='{"selected":{{isset($spaceSel)&&request()->id==$entity->id?"true":"false"}},"icon":"{{URL::asset("assets/imgs/users.png")}}"}'>
    <a href="{{ baseUrl('/space/'.$entity->id) }}" title="{{ $entity->name }}">{{ str_limit($entity->name,12) }}</a>
    @if($entity->books)
    <ul>
        @foreach($entity->books as $book)
        <li icon="true" data-jstree='{"selected":{{isset($bookSel)&&request()->oid==$book->id?"true":"false"}},"icon":"{{URL::asset("assets/imgs")}}/{{count($book->chapters)>0?"book.png":"book_empty.png"}}"}'>
            <a href="{{ baseUrl("/space/$entity->id/book/$book->id") }}" title="{{ $book->name }}">{{ str_limit($book->name, 12) }}</a>
            @if($book->chapters)
            <ul>
                @foreach($book->chapters as $chapter)
                <li data-jstree='{"selected":{{isset($chapterSel)&&request()->oid==$chapter->id?"true":"false"}},"icon":false}'>
                    <a href="{{ baseUrl("/space/$entity->id/chapter/$chapter->id") }}" title="{{ $chapter->name }}">{{ str_limit($chapter->name, 12) }}</a>
                    @if($chapter->pages)
                    <ul>
                        @foreach($chapter->pages as $page)
                        <li icon="true" data-jstree='{"selected":{{isset($pageSel)&&request()->oid==$page->id?"true":"false"}},"icon":"{{URL::asset("assets/imgs/file_24.png")}}"}'>
                            <a href="{{ baseUrl("/space/$entity->id/page/$page->id") }}" title="{{ $page->name }}">{{ str_limit($page->name, 12) }}</a>
                        </li>
                        @endforeach
<!--                        <li data-jstree='{"icon":"{{URL::asset("assets/imgs/file_reading.png")}}"}'>-->
<!--                            <a href="#">page 2</a>-->
<!--                        </li>-->
                    </ul>
                    @endif
                </li>
                @endforeach
            </ul>
            @endif
        </li>
        @endforeach
    </ul>
    @endif
</li>
<!--<div class="entity-list compact">-->
<!--    <a href="#" class="entity-list-item" >-->
<!--        <span class="icon text-book">@icon('users')</span>-->
<!--        <div class="content">-->
<!--            <h4 class="entity-list-item-name break-text">{{ $entity->name }}</h4>-->
<!--        </div>-->
<!--    </a>-->
<!--</div>-->

<li icon="true" data-jstree='{"icon":"{{URL::asset("assets/imgs/users.png")}}"}'>
    <a href="{{ baseUrl('/space/'.$entity->id) }}">{{ $entity->name }}</a>
    @if($entity->books)
    <ul>
        @foreach($entity->books as $book)
        <li icon="true" data-jstree='{"icon":"{{URL::asset("assets/imgs")}}/{{count($book->chapters)>0?"book.png":"book_empty.png"}}"}'>
            <a href="{{ baseUrl("/space/$entity->id/book/$book->id") }}">{{ $book->name }}</a>
            @if($book->chapters)
            <ul>
                @foreach($book->chapters as $chapter)
                <li data-jstree='{"icon":false}'>
                    <a href="{{ baseUrl("/space/$entity->id/chapter/$chapter->id") }}">{{ $chapter->name }}</a>
                    @if($chapter->pages)
                    <ul>
                        @foreach($chapter->pages as $page)
                        <li icon="true" data-jstree='{"selected" : false,"icon":"{{URL::asset("assets/imgs/file_24.png")}}"}'>
                            <a href="{{ baseUrl("/space/$entity->id/page/$page->id") }}">{{ $page->name }}</a>
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

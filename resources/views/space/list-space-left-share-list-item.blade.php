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
        @foreach($entity->books as $share_item_book)
            @include('space.book-tree', ['left_book'=>$share_item_book])
        @endforeach
    </ul>
    @endif
</li>
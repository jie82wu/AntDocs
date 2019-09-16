
<li icon="true" data-jstree='{"selected":{{isset($spaceSel)&&getSpace()->id==$entity->id?"true":"false"}},"icon":"{{URL::asset("assets/imgs/users.png")}}"}'>
    <a href="{{ baseUrl('/space/'.$entity->id) }}" title="{{ $entity->name }}">{{ $entity->getShortName() }}</a>
    @if($entity->books)
    <ul>
        @foreach($entity->books as $share_item_book)
            @include('space.book-tree', ['left_book'=>$share_item_book])
        @endforeach
    </ul>
    @endif
</li>
<table class="table">
    <tr>
        <th class="text-center" colspan="3">搜索区</th>
<!--        <th>         {{ trans('space.cover_image') }}   </th>-->
<!--        <th>         {{ trans('space.book_name') }}   </th>-->
<!--        <th>         {{ trans('space.short_description') }}   </th>-->
    </tr>
    @foreach($entity as $item)
    <tr>
        <td class="text-center" style="line-height: 0;width:100px;" >
            @include('space.space-checkbox-com',['id'=>$item->id,'name'=>'books[]','checked'=>isset($bids)&&in_array($item->id,$bids)])
        </td>
        <td width="20">
            <img style="width:150px;" src="{{ $item->getBookCover()}}" alt="{{ $item->name }}">
        </td>
        <td align="left" >
            <h4 class="entity-list-item-name break-text">{{ $item->name }}</h4>
            <div class="entity-item-snippet">
                <p class="text-muted break-text mb-s">{{ $item->getExcerpt() }}</p>
            </div>
        </td>
    </tr>
    @endforeach
</table>
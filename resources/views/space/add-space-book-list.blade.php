<style>
    td{padding:0 !important;}
</style>
<table class="table">
<!--    <tr>-->
<!--        <th class="text-center" colspan="3">搜索区</th>-->
<!--        <th>         {{ trans('space.cover_image') }}   </th>-->
<!--        <th>         {{ trans('space.book_name') }}   </th>-->
<!--        <th>         {{ trans('space.short_description') }}   </th>-->
<!--    </tr>-->
    @foreach($entity as $item)
    <tr>
        <td class="text-center" style="line-height: 0;width:100px;" >
            @include('space.space-checkbox-com',['id'=>$item->id,'name'=>'books[]','checked'=>isset($bids)&&in_array($item->id,$bids)])
        </td>
        <td width="130" style="overflow:hidden;">
            <img style="width:100px;height:3.5rem;" src="{{ $item->getBookCover()}}" alt="{{ $item->name }}">
        </td>
        <td align="left" >
            <h5 class="entity-list-item-name">{{ $item->name }} <span class="text-muted break-text mb-s"></span></h5>
            <div class="entity-item-snippet">
                <p class="text-muted break-text mb-s">{{ $item->getExcerpt() }}</p>
            </div>
        </td>
    </tr>
    @endforeach
</table>
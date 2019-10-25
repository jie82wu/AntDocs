<a href="{{$book->getUrl()}}" class="grid-card"  data-entity-type="book" data-entity-id="{{$book->id}}">
    <div class="bg-book featured-image-container-wrap">
        <div class="featured-image-container" @if($book->cover) style="background-image: url('{{ $book->getBookCover() }}')"@endif>
        </div>
        @icon('book')
    </div>
    <div class="grid-card-content">
        <h2>{{$book->getShortName(35)}}</h2>
        <p class="text-muted"><span>by {{$book->createdBy->name}}，{{ $book->created_at->diffForHumans() }}</span></p>
    </div>
    <div class="grid-card-footer">
        <span style="line-height: 2em;"><b style="color:darkred;font-size: 1.2em;">{{$book->market->price}}</b> 蚂蚁币 <button style="padding:1px 5px;float:right;" class="button outline" type="button">{{ trans('market.purchase') }}</button></span>
    </div>
</a>
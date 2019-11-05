<div class="grid-card"  data-entity-type="book" data-entity-id="{{$book->id}}">
    <a href="{{$book->getUrl()}}" style="text-decoration: none">
        <div class="bg-book featured-image-container-wrap">
            <div class="featured-image-container" @if($book->cover) style="background-image: url('{{ $book->getBookCover() }}')"@endif>
            </div>
            @icon('book')
        </div>
        <div class="grid-card-content">
            <h2>{{$book->getShortName(35)}}</h2>
            <p class="text-muted"><span>by {{$book->createdBy->name}}，{{ $book->created_at->diffForHumans() }}</span></p>
        </div>
    </a>
    <div class="grid-card-footer" style="padding-bottom:24px;padding-top: 0;">
        <span style="line-height: 2em;"><b style="color:darkred;font-size: 1.2em;">{{$book->market->price}}</b> 蚂蚁币 
            <button book-id="{{$book->id}}" space-picker-select style="padding:1px 5px;float:right;" class="button outline">{{ trans('market.purchase') }}</button>
        </span>
    </div>
</div>
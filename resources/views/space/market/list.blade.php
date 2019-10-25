
<div class="content-wrap mt-m card">
    <div class="grid half v-center no-row-gap">
        <h1 class="list-heading">{{ trans('market.discovery') }}</h1>
        <div style="margin-top: 20px;padding-left: 10em;">
            <form class="search-box flexible" method="get">
                <input type="text" name="term" value="{{request()->get('term')}}" placeholder="{{ trans('market.search_market') }}">
                <button style="right: 8px;left:auto;" type="submit">@icon('search')</button>
            </form>
        </div>
    </div>
    <hr>
    @if(count($books) > 0)
            <div class="grid third">
                @foreach($books as $key => $book)
                    @include('space.market.grid-item', ['book' => $book])
                @endforeach
            </div>
            <div>
                {!! $books->render() !!}
            </div>
    @else
    <p class="text-muted">{{ trans('common.no_items') }}</p>
    @endif
</div>
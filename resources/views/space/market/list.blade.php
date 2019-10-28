
<div class="content-wrap mt-m card">
    <div class="grid half v-center no-row-gap">
        <h1 class="list-heading">{{ trans('market.discovery') }}</h1>
        <div style="margin-top: 20px;">
            <form class="search-box flexible" method="get">
                <input type="text" style="width:70%;display: inline-block;" name="term" value="{{request()->get('term')}}" placeholder="{{ trans('market.search_market') }}">
                <button type="submit">@icon('search')</button>
                <a href="/market" style="float:right;margin:0;" class="button outline">{{ trans('common.search_clear') }}</a>
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
    @include('space.entity-selector-popup', ['entityTypes' => 'page'])

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
    <script>
        $('[space-picker-select]').on('click',function (e) {
            $('#space-selector-wrap div[overlay]').show();
        });

    </script>    
    @else
    <p class="text-muted">{{ trans('common.no_items') }}</p>
    @endif
</div>

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
                <input type="hidden" name="book_id" id="book_id">
                <input type="hidden" name="space_id" id="space_id">
                {!! $books->render() !!}
            </div>
    @include('space.entity-selector-popup', ['entityTypes' => 'page'])

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
    <script>
        $('[space-picker-select]').on('click',function (e) {
            $('#space-selector-wrap div[overlay]').show();
            $('#book_id').val($(this).attr('book-id'));
        });
        $('[entity-list-item]').on('click',function (e) {
            $(this).addClass('select-item-background').siblings().removeClass('select-item-background')
            $('#space_id').val($(this).attr('data-entity-id'));
        })
        $('[select-button]').on('click',function (e) {
            var space_id = $('#space_id').val()
            var book_id  = $('#book_id').val()
            if (space_id=='' || book_id=='')
                return
            window.location.href = '/market/purchase/'+$('#space_id').val()+'/'+$('#book_id').val()
        })
    </script>    
    @else
    <p class="text-muted">{{ trans('common.no_items') }}</p>
    @endif
</div>
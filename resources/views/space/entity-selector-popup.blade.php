<div id="space-selector-wrap">
    <div overlay entity-selector-popup>
        <div class="popup-body small">
            <div class="popup-header primary-background">
                <div class="popup-title">{{ trans('space.save_to_space') }}</div>
                <button type="button" class="popup-header-close">x</button>
            </div>
            
            <div class="form-group entity-selector-container">
                <div space-selector class="entity-selector">
                    {{--<input type="text" placeholder="{{ trans('common.search') }}" entity-selector-search>
                    <div class="text-center loading" entity-selector-loading>@include('partials.loading-icon')</div>--}}
                    <div space-selector-results>
                        <div class="entity-list">
                            @foreach($allSpace as $item)
                            <span class="book entity-list-item" entity-list-item data-entity-id="{{$item->id}}">
                                <span>@icon('book')</span>
                                <div class="content">
                                    <h4 class="entity-list-item-name break-text">{{$item->name}}</h4>
                                </div>
                            </span><hr>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="popup-footer">
                <button type="button" select-button class="button entity-link-selector-confirm primary corner-button">{{ trans('common.select') }}</button>
            </div>
        </div>
    </div>
</div>
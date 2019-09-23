
<div class="left-tree-div" style="display: none;">
    <ul>
        <li icon="true" data-jstree='{"selected":{{!(cache()->has(cacheKey()))||isset($spaceSel)&&isset($private)&&request()->id==$private->id?"true":"false"}},"icon":"{{URL::asset("assets/imgs/lock.png")}}"}'>
            <a href="{{ baseUrl('/space/myspace') }}" title="{{ trans('space.my_space') }}">{{ trans('space.my_space') }}</a>
    <ul>
    
</div>


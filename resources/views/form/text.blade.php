<input type="text" id="{{ $name }}" name="{{ $name }}"
       @if($errors->has($name)) class="text-neg" @endif
       @if(isset($placeholder)) placeholder="{{$placeholder}}" @endif
       @if(isset($tabindex)) tabindex="{{$tabindex}}" @endif
       @if(isset($disabled)&&$disabled) disabled @endif
       value="{{ isset($value) ?$value: ((old($name) ?: (isset($model)?$model->$name:'') ) ?: request()->get($name)) }}">
@if($errors->has($name))
    <div class="text-neg text-small">{{ $errors->first($name) }}</div>
@elseif(request()->has('email')&&isset($extra))
    <div class="text-pos text-small">{{ trans($extra) }}</div>
@endif
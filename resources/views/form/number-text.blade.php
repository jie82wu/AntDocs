<input type="number" min="0" id="{{ $name }}" name="{{ $name }}" style="width:100px;"
       @if($errors->has($name)) class="text-neg" @endif
       @if(isset($placeholder)) placeholder="{{$placeholder}}" @endif
       @if(isset($tabindex)) tabindex="{{$tabindex}}" @endif
       @if(isset($disabled)&&$disabled) disabled @endif
       value="{{ isset($value) ?$value: 1 }}">
@if($errors->has($name))
    <div class="text-neg text-small" style="display: inline;">{{ $errors->first($name) }}</div>
@elseif(request()->has('email')&&isset($extra))
    <div class="text-pos text-small">{{ trans($extra) }}</div>
@endif
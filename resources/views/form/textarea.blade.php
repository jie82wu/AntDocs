<textarea id="{{ $name }}" name="{{ $name }}" rows="5"
          @if($errors->has($name)) class="text-neg" @endif>@if(isset($value) || isset($model) || old($name)){{ isset($value)?$value:(old($name) ? old($name) : $model->$name) }}@endif</textarea>
@if($errors->has($name))
    <div class="text-neg text-small">{{ $errors->first($name) }}</div>
@endif
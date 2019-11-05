
<select id="{{ $name }}" name="{{ $name }}">
    @foreach($options as $option)
        <option value="{{$option->name}}" @if(isset($value)&&$option->name==$value) selected @endif @if($errors->has($name)) class="text-neg" @endif>
            {{ $option->name }}
        </option>
    @endforeach
</select>

@if($errors->has($name))
    <div class="text-neg text-small">{{ $errors->first($name) }}</div>
@endif
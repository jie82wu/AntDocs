<label class="toggle-switch  text-neg ">
    <input type="checkbox" name="{{ $name }}" value="{{ $id }}" @if($checked) checked="checked" @endif>
    <span class="custom-checkbox text-primary">@icon('check')</span>
    <span class="label">{{$label or ''}}</span>
</label>
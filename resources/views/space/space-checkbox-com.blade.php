<label class="toggle-switch  text-neg ">
    @if(session()->get('select_user_id')==$id && !$checked)
    <input type="checkbox" name="{{ $name }}" value="{{ $id }}" checked="checked" >
    <span class="custom-checkbox">@icon('check')</span>
    @else
    <input type="checkbox" name="{{ $name }}" value="{{ $id }}" @if($checked) checked="checked" @endif>
    <span class="custom-checkbox text-primary">@icon('check')</span>
    @endif
    <span class="label">{{$label or ''}}</span>
</label>
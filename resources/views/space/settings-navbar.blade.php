<div class="active-link-list">
    <a href="{{ baseUrl('/space/'.$space->id.'/users')}}" @if($selected == 'users') class="active" @endif>@icon('users'){{ trans('settings.users') }}</a>
    <a href="{{ baseUrl('/space/'.$space->id.'/roles') }}" @if($selected == 'roles') class="active" @endif>@icon('lock-open'){{ trans('settings.roles') }}</a>
</div>
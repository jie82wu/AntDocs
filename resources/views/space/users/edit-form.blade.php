
<div class="pt-m">
    <div class="grid mt-m gap-xl" style="margin-top: 0px !important;">
        <div>
            <img class="avatar med" style="width:50px;height:50px;" src="{{ $user->getAvatar(20)}}" alt="{{ $user->name }}">
            &emsp;<h4 style="display: inline-block;">{{$user->name}}<br><label class="text-muted">{{ $user->email }}</label></h4>
        </div>
    </div>
</div>

@if($authMethod === 'ldap' && userCan('users-manage'))
    <div class="grid half gap-xl v-center">
        <div>
            <label class="setting-list-label">{{ trans('settings.users_external_auth_id') }}</label>
            <p class="small">{{ trans('settings.users_external_auth_id_desc') }}</p>
        </div>
        <div>
            @include('form.text', ['name' => 'external_auth_id'])
        </div>
    </div>
@endif


<div>
    <label for="role" class="setting-list-label">{{ trans('settings.users_role') }}</label>
    <p class="small">{{ trans('settings.users_role_desc') }}</p>
    <div class="mt-m">
        @include('form.role-checkboxes', ['name' => 'roles', 'roles' => $roles])
    </div>
</div>


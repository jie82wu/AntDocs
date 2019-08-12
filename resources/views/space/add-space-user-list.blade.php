<table class="table">
    <tr><th colspan="4" class="text-center">搜索区</th></tr>
    <tr>
        <th class="text-center"></th>
        <th>
            {{ trans('auth.name') }}/{{ trans('auth.email') }}
        </th>
        <th>{{ trans('settings.role_user_roles') }}</th>
    </tr>
    @foreach($users as $user)
    <tr>
        <td class="text-center" style="line-height: 0;width:100px;">
            @include('space.space-checkbox-com',['id'=>$user->id,'name'=>'users[]','checked'=>isset($uids)&&in_array($user->id,$uids)])
        </td>
        <td valign="middle">
            <img class="avatar med" style="width:25px;height:25px;" src="{{ $user->getAvatar(20)}}" alt="{{ $user->name }}">
            &emsp;<span style="display: inline-block;">{{ $user->name }}<br><label class="text-muted">{{ $user->email }}</label></span>
        </td>
        <td>
            @foreach($user->roles as $index => $role)
            <small>{{$role->display_name}}@if($index !== count($user->roles) -1),@endif</small>
            @endforeach
        </td>
    </tr>
    @endforeach
</table>
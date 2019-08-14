<table class="table">
<!--    <tr><th colspan="4" class="text-center">搜索区</th></tr>-->
    <tr>
        <th class="text-center"></th>
        <th class="text-center">是否作为管理者</th>
        <th>
            {{ trans('auth.name') }}/{{ trans('auth.email') }}
        </th>
        <th>{{ trans('settings.role_user_roles') }}</th>
    </tr>
    @foreach($users as $key=>$user)
    @if ($user->id != user()->id)
    <tr>
        <td class="text-center" style="line-height: 0;width:100px;">
            @include('space.space-checkbox-com',['id'=>$user->id,'name'=>'users['.$key.'][user_id]','checked'=>isset($uids)&&in_array($user->id,$uids)])
        </td>
        <td class="text-center" style="line-height: 0;">
            @include('space.space-checkbox-com',['id'=>1,'name'=>'users['.$key.'][is_admin]','checked'=>isset($aids)&&in_array($user->id,$aids)])
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
    @endif
    @endforeach
</table>
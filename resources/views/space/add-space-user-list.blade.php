<table class="table">
<!--    <tr><th colspan="4" class="text-center">搜索区</th></tr>-->
    <tr>
<!--        <th class="text-center"></th>-->
        <th>
            {{ trans('auth.name') }}/{{ trans('auth.email') }}
        </th>
        <th>{{ trans('space.space_roles') }}</th>
        <th>{{ trans('common.actions') }}</th>
    </tr>
    @foreach($users as $key=>$user)
    <tr>
        {{--<td class="text-center" style="line-height: 0;width:100px;">
            @include('space.space-checkbox-com',['id'=>$user->id,'name'=>'users['.$key.'][user_id]','checked'=>isset($uids)&&in_array($user->id,$uids)])
        </td>--}}
        <td valign="middle">
            <img class="avatar med" style="width:25px;height:25px;" src="{{ $user->getAvatar(20)}}" alt="{{ $user->name }}">&emsp;
            @if($user->id != $space->created_by)
            <a href="{{ baseUrl("/space/{$space->id}/users/{$user->id}") }}">
            <span>{{ $user->name }}<br><label class="text-muted">{{ $user->email }}</label></span>
            </a>
            @else
            <a href="#"><span>{{ $user->name }}<br><label class="text-muted">{{ $user->email }}</label></span></a>
            @endif
        </td>
        <td>
            @if($user->id != $space->created_by)
            @foreach($user->roles as $index => $role)
            <small><a href="{{ baseUrl("/space/{$space->id}/roles/{$role->id}") }}">{{$role->display_name}} </a>@if($index !== count($user->roles) -1),@endif</small>
            @endforeach
            @else
            <small>{{ trans('space.creator') }}</small>
            @endif
        </td>
        <td>
            @if($user->id != $space->created_by)
            <small><a href="{{ baseUrl("/space/{$space->id}/user/{$user->id}/delete") }}" >{{ trans('common.delete') }}</a></small>
            @endif
        </td>
    </tr>
    @endforeach
</table>
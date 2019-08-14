@extends('tri-layout')

@section('body')

<div class="content-wrap mt-m card">
    <div class="grid half v-center no-row-gap">
        <h1 class="list-heading">{{ trans('common.message') }}</h1>
    </div>
    <table class="table">
        <tr>
            <th><a href="#" permissions-table-toggle-all="" class="text-small text-primary" style=""></a></th>
            <th>{{ trans('message.message_content') }}</th>
            <th>{{ trans('common.actions') }}</th>
        </tr>
        @foreach($messages as $message)
        <tr>
            <td>
                {{--@include('space.space-checkbox-com',['id'=>1,'name'=>'users[]','checked'=>isset($uids)&&in_array($user->id,$uids)])--}}
            </td>
            <td>
                {{ trans($message->content_key, ['userName'=>$message->fromUser->name, 'spaceName'=>$message->space()->name]) }}
            </td>
            <td >
                <a href="{{ baseUrl('/message/'.$message->id.'/status/1')}}">{{ trans('message.allow') }}</a>
                <a href="{{ baseUrl('/message/'.$message->id.'/status/2')}}">{{ trans('message.reject') }}</a>
                <a href="{{ baseUrl('/message/'.$message->id.'/status/3')}}">{{ trans('message.omit') }}</a>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@stop

@section('left')

{{--@include('partials.entity-dashboard-search-box')--}}

@if(isset($activity))
<div class="mb-xl">
    <h5>{{ trans('entities.recent_activity') }}</h5>
    @include('partials.activity-list', ['activity' => $activity])
</div>
@endif
@stop
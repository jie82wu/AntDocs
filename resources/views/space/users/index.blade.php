@extends('simple-layout')

@section('body')
    <div class="container small">

        <div class="py-m">
            @include('space.settings-navbar', ['selected' => 'users'])
        </div>

        <div class="card content-wrap">

            <div class="grid right-focus v-center">
                <h1 class="list-heading">{{ trans('space.space_users') }}</h1>

            <div class="text-right">
                    {{--<div class="block inline mr-s">
                        <form method="get" action="{{ baseUrl("/settings/users") }}">
                            @foreach(collect($listDetails)->except('search') as $name => $val)
                                <input type="hidden" name="{{ $name }}" value="{{ $val }}">
                            @endforeach
                            <input type="text" name="search" placeholder="{{ trans('settings.users_search') }}" @if($listDetails['search']) value="{{$listDetails['search']}}" @endif>
                        </form>
                    </div>--}}
                        <a href="{{ baseUrl("/space/{$space->id}/users/create") }}" style="margin-top: 0;" class="outline button">{{ trans('settings.users_add_new') }}</a>
                </div>
            </div>

            {{--TODO - Add last login--}}
            <form action="{{ baseUrl("/space/{$space->id}/users") }}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
                <input type="hidden" name="_method" value="PUT">          
                @include('space.add-space-user-list')
                <div class="form-group text-right">
                    <button type="submit" class="button primary">{{ trans('space.space_save_users') }}</button>
                </div>
            </form>
            

            <div>
                {{ $users->links() }}
            </div>
        </div>

    </div>

@stop

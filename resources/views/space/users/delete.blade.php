@extends('simple-layout')

@section('body')
    <div class="container small">

        <div class="py-m">
            @include('settings.navbar', ['selected' => 'users'])
        </div>

        <div class="card content-wrap auto-height">
            <h1 class="list-heading">{{ trans('settings.users_delete') }}</h1>

            <p>{{ trans('space.space_remove_user_explain', ['userName' => $user->name,'spaceName' => $space->name]) }}</p>

            <div class="grid half">
                <p class="text-neg"><strong>{{ trans('settings.users_delete_confirm') }}</strong></p>
                <div>
                    <form action="" method="POST" class="text-right">
                        {!! csrf_field() !!}

                        <input type="hidden" name="_method" value="DELETE">
                        <a href="{{ baseUrl("/space/{$space->id}/users") }}" class="button outline">{{ trans('common.cancel') }}</a>
                        <button type="submit" class="button primary">{{ trans('common.confirm') }}</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@stop

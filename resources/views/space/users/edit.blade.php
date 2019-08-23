@extends('simple-layout')

@section('body')
    <div class="container small">

        <div class="py-m">
            @include('space.settings-navbar', ['selected' => 'users'])
        </div>

        <div class="card content-wrap">
            <h1 class="list-heading">{{ $user->id === $currentUser->id ? trans('settings.users_edit_profile') : trans('settings.users_edit') }}</h1>
            <form action="{{ baseUrl("/space/{$space->id}/users/{$user->id}") }}" method="post" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <input type="hidden" name="_method" value="PUT">

                <div class="setting-list">
                    @include('space.users.edit-form', ['model' => $user, 'authMethod' => $authMethod])

                    {{--<div class="grid half gap-xl">
                        <div>
                            <label for="user-avatar" class="setting-list-label">{{ trans('settings.users_avatar') }}</label>
                            <p class="small"></p>
                        </div>
                        <div>
                            @include('components.image-picker', [
                                'resizeHeight' => '512',
                                'resizeWidth' => '512',
                                'showRemove' => false,
                                'defaultImage' => baseUrl('/user_avatar.png'),
                                'currentImage' => $user->getAvatar(80),
                                'currentId' => $user->image_id,
                                'name' => 'profile_image',
                                'imageClass' => 'avatar large'
                            ])
                        </div>
                    </div>--}}

                </div>

                <div class="text-right">
                    <a href="{{  baseUrl("/space/{$space->id}/users") }}" class="button outline">{{ trans('common.cancel') }}</a>
                    <button class="button primary" type="submit">{{ trans('common.save') }}</button>
                </div>
            </form>
        </div>

        @if($currentUser->id === $user->id && count($activeSocialDrivers) > 0)
            <div class="card content-wrap auto-height">
                <h2 class="list-heading">{{ trans('settings.users_social_accounts') }}</h2>
                <p class="text-muted">{{ trans('settings.users_social_accounts_info') }}</p>
                <div class="container">
                    <div class="grid third">
                        @foreach($activeSocialDrivers as $driver => $enabled)
                            <div class="text-center mb-m">
                                <div>@icon('auth/'. $driver, ['style' => 'width: 56px;height: 56px;'])</div>
                                <div>
                                    @if($user->hasSocialAccount($driver))
                                        <a href="{{ baseUrl("/login/service/{$driver}/detach") }}" class="button small outline">{{ trans('settings.users_social_disconnect') }}</a>
                                    @else
                                        <a href="{{ baseUrl("/login/service/{$driver}") }}" class="button small outline">{{ trans('settings.users_social_connect') }}</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

@stop

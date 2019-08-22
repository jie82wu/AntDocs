@extends('simple-layout')

@section('body')

    <div class="container small">
        <div class="py-m">
            @include('space.settings-navbar', ['selected' => 'roles'])
        </div>

        <form action="{{ baseUrl("/space/{$space->id}/roles/{$role->id}") }}" method="POST">
            <input type="hidden" name="_method" value="PUT">
            @include('space.roles.form', ['model' => $role, 'title' => trans('space.space_role_edit'), 'icon' => 'edit'])
        </form>
    </div>

@stop

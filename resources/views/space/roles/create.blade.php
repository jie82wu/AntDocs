@extends('simple-layout')

@section('body')

    <div class="container small">

        <div class="py-m">
            @include('space.settings-navbar', ['selected' => 'roles'])
        </div>

        <form action="{{ baseUrl("/space/".$space->id."/roles/new") }}" method="POST">
            @include('space.roles.form', ['title' => trans('space.create_space_roles')])
        </form>
    </div>

@stop

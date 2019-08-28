@extends('base')

@section('body-class', 'tri-layout')
@section('content')

<div class="tri-layout-container" tri-layout @yield('container-attrs') >

    <div class="tri-layout-left print-hidden pt-m" id="sidebar">
        <div class="tri-layout-left-contents">
            @yield('left')
        </div>
    </div>
    
    <div class="@yield('body-wrap-classes') tri-layout-middle" style="padding-top: 0;">
        <div class="tri-layout-middle-contents">
            @yield('body')
        </div>
    </div>

</div>

@stop

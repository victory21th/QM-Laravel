@extends('store.layout')

@section('custom-styles')
<style>
    body {
        color: {{ $setting->color }};
        background: {{ $setting->background }};
    }
</style>
@stop

@section('header')
@stop

@section('body')
<input type="hidden" id="token" value="{{ $token }}"/>
<div class="tv-containter text-center">
    <div class="header-100 font-size-sm">
        <span>
            <img src="/assets/img/logo.png"/>
        </span>
        &nbsp;
        <span>Last : <span id="js-span-last"></span></span>
        &nbsp;&nbsp;&nbsp;
        <span>Waiting Time : <span id="js-span-waiting-time"></span>min</span>
    </div>
    
    <div class="text-center header-300 font-size-lg" id="js-div-tv">
        <div id="js-div-success" class="hide">
            <div>
                Current : <span id="js-span-current"></span>
            </div>
            
            <div>
                Counter : <span id="js-span-counter"></span>
            </div>
        </div>
        
        <div id="js-div-failed" class="hide">
            
        </div>
    </div>
</div>

<div class="slider-container" id="js-div-slider">
    @foreach ($sliders as $slider)
        <div style="background: url({{ HTTP_SLIDER_PATH.$slider->url }}); background-size: cover; width: 100%; height: 620px;"></div>
    @endforeach
</div>
@stop

@section('footer')
@stop

@section('custom-scripts')
    @include('js.store.tv.index')
@stop

@stop

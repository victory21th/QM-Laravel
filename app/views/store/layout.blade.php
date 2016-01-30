@extends('main')

@section('styles')
    {{ HTML::style('/assets/css/style_store.css') }}
@stop

@yield('content')

@stop
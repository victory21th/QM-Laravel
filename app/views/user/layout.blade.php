@extends('main')

@section('styles')
    {{ HTML::style('/assets/css/style_user.css') }}
@stop

@section('header')
    <header class="header">
        <div class="container">
            <div class="pull-left">
                <a href="/">
                    <img src="/assets/img/logo.png" style="margin-top: 14px;"/>
                </a>
            </div>
            <div class="pull-right">
                <ul class="nav nav-pills nav-top">
                    <?php if (!isset($pageNo)) { $pageNo = 0; } ?>
                        <li class="{{ ($pageNo == 1) ? 'active' : ''}}"><a href="{{ URL::route('user.store.search') }}">Search</a></li>
                    @if (Session::has('user_id'))
                        <li class="{{ ($pageNo == 2) ? 'active' : ''}}"><a href="{{ URL::route('user.queue.status') }}">Status</a></li>
                        <li><a href="{{ URL::route('user.auth.doLogout') }}">Sign Out</a></li>
                    @else
                        <li class="{{ ($pageNo == 98) ? 'active' : ''}}"><a href="{{ URL::route('user.auth.login') }}">Sign in</a></li>
                        <li class="{{ ($pageNo == 99) ? 'active' : ''}}"><a href="{{ URL::route('user.auth.signup') }}">Register</a></li>
                    @endif
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </header>
@stop

    @yield('content')

@section('footer')
<div class="footer-container">
    <div class="container footer-menu">
        <div class="row color-white">
            <div class="col-sm-3">
                <p class="text-uppercase margin-bottom-20"><b>Company Info</b></p>
                <ul>
                    <li><a href="#">About us</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Contact &amp; Support</a></li>
                </ul>
            </div>
            <div class="col-sm-3 color-white">
                <p class="text-uppercase margin-bottom-20"><b>HOW IT WORKS</b></p>
                <ul>
                    <li><a href="#">How it works?</a></li>
                    <li><a href="#">Media solutions</a></li>
                    <li><a href="#">Partnerships</a></li>
                </ul>
            </div>
            <div class="col-sm-3 color-white">
                <p class="text-uppercase margin-bottom-20"><b>FOLLOW US</b></p>
                <ul>
                    <li><a href="#"><i class="fa fa-facebook" style="width: 18px;"></i>&nbsp;Facebook</a></li>
                    <li><a href="#"><i class="fa fa-twitter" style="width: 18px;"></i>&nbsp;Twitter</a></li>
                    <li><a href="#"><i class="fa fa-google-plus" style="width: 18px;"></i>&nbsp;Google Plus</a></li>
                </ul>            
            </div>
            <div class="col-sm-3 color-white">
                <p class="text-uppercase margin-bottom-20"><b>NEWS LETTERS</b></p>
                <input type="text" class="form-control" placeholder="Email"/>
                
            </div>                                    
        </div>
    </div>
    <div class="page-footer" style="background: #222;">
    	<div class="page-footer-inner">
    		 &copy; Copyright 2015 | All Rights Reserved | Powered by Finternet-Group
    	</div>
    	<div class="page-footer-tools">
    		<span class="go-top">
    		<i class="fa fa-angle-up"></i>
    		</span>
    	</div>
    </div>
</div>
@stop

@section('scripts')
    {{ HTML::script('/assets/js/alert.js') }}
    {{ HTML::script('/assets/js/bootbox.js') }}
@stop

@stop
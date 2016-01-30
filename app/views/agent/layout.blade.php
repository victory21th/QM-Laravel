@extends('main')

@section('styles')
    {{ HTML::style('/assets/css/style_agent.css') }}
@stop

@section('header')
    
<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner">
		<!-- BEGIN LOGO -->
		<div class="page-logo" style="padding-top: 12px;">
			<a href="#" style="text-decoration: none;">
			    <span class="logo-title">{{ SITE_NAME }} Agent</span>
			</a>
		</div>
		<!-- END LOGO -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		
		<!-- BEGIN TOP NAVIGATION MENU -->
		<div class="top-menu">
            <?php if (Session::has('agent_id')) {?>
    			<ul class="nav navbar-nav pull-right">
    				<li class="dropdown dropdown-quick-sidebar-toggler">
    					<a href="{{ URL::route('agent.auth.logout') }}" class="dropdown-toggle" id="js-a-signout">
    					    <i class="icon-logout"></i> Sign Out
    					</a>
    				</li>
    			</ul>
            <?php }?>
		</div>
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END HEADER INNER -->
</div>

<div class="clearfix"></div>    
@stop

@section('body')
<?php if (!isset($pageNo)) { $pageNo = 0; } ?>
<div class="page-container">
    <div class="page-sidebar-wrapper">
    	<div class="page-sidebar navbar-collapse collapse">
    		<ul class="page-sidebar-menu" data-auto-scroll="true" data-slide-speed="200">
    			<li class="sidebar-toggler-wrapper">
    				<div class="sidebar-toggler"></div>
    			</li>
    
    			<li class="start <?php echo ($pageNo == 1) ? "active" : "";?>">
    				<a href="{{ URL::route('agent.process') }}">
    				    <i class="fa fa-tachometer"></i>
    				    <span class="title">Queue Process</span>
    				</a>
    			</li>

    			<li class="last <?php echo ($pageNo == 2) ? "active" : "";?>">
    				<a href="{{ URL::route('agent.account') }}">
    				    <i class="fa fa-user"></i>
    				    <span class="title">My Account</span>
    				</a>
    			</li>
    		</ul>
        </div>
    </div>
    
    <div class="page-content-wrapper">
    	<div class="page-content">            
            @yield('breadcrumb')
            @yield('content')
        </div>
    </div>
</div>    			    			

@stop



@section('footer')
    <div class="page-footer footer-background">
    	<div class="page-footer-inner">
    		 &copy; Copyright 2015 | All Rights Reserved | Powered by Finternet-Group
    	</div>
    	<div class="page-footer-tools">
    		<span class="go-top">
    		<i class="fa fa-angle-up"></i>
    		</span>
    	</div>
    </div>
@stop

@section('scripts')
    {{ HTML::script('/assets/js/alert.js') }}
    {{ HTML::script('/assets/js/bootbox.js') }}
@stop

@stop
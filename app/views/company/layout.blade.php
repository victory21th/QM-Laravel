@extends('main')

@section('styles')
    {{ HTML::style('/assets/css/style_company.css') }}
@stop

@section('header')
<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner">
		<!-- BEGIN LOGO -->
		<div class="page-logo" style="padding-top: 12px;">
			<a href="/company/dashboard" style="text-decoration: none;">
			    <span class="logo-title">{{ SITE_NAME }} Company</span>
			</a>
		</div>
		<!-- END LOGO -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		
		<!-- BEGIN TOP NAVIGATION MENU -->
		<div class="top-menu">
            <?php if (Session::has('company_id')) {?>
    			<ul class="nav navbar-nav pull-right">
    				<li class="dropdown dropdown-quick-sidebar-toggler">
    					<a href="{{ URL::route('company.auth.logout') }}" class="dropdown-toggle">
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
    				<a href="{{ URL::route('company.dashboard') }}">
    				    <i class="icon-bar-chart"></i>
    				    <span class="title">Dashboard</span>
    				</a>
    			</li>
    			
    			<li class="<?php echo ($pageNo == 9) ? "active" : "";?>">
    				<a href="{{ URL::route('company.dashboard.agent') }}">
    				    <i class="fa fa-bar-chart-o"></i>
    				    <span class="title">Agents Statistic</span>
    				</a>
    			</li>
    			
    			<li class="<?php echo ($pageNo == 2) ? "active" : "";?>">
    				<a href="{{ URL::route('company.store') }}">
    				    <i class="fa fa-building"></i>
    				    <span class="title">Store Management</span>
    				</a>
    			</li>
    			
    			<li class="<?php echo ($pageNo == 3) ? "active" : "";?>">
    				<a href="{{ URL::route('company.agent') }}">
    				    <i class="fa fa-users"></i>
    				    <span class="title">Agent Management</span>
    				</a>
    			</li>
    			
    			<li class="<?php echo ($pageNo == 4) ? "active" : "";?>">
    				<a href="{{ URL::route('company.video') }}">
    				    <i class="fa fa-file-movie-o"></i>
    				    <span class="title">Video Management</span>
    				</a>
    			</li>
    			
    			<li class="<?php echo ($pageNo == 8) ? "active" : "";?>">
    				<a href="{{ URL::route('company.slider') }}">
    				    <i class="fa fa-image"></i>
    				    <span class="title">Slider Management</span>
    				</a>
    			</li>
    			
    			<li class="<?php echo ($pageNo == 5) ? "active" : "";?>">
    				<a href="{{ URL::route('company.setting') }}">
    				    <i class="icon-settings"></i>
    				    <span class="title">Settings</span>
    				</a>
    			</li>
    			
    			<li class="<?php echo ($pageNo == 6) ? "active" : "";?>">
    				<a href="{{ URL::route('company.ticket-type') }}">
    				    <i class="fa fa-ticket"></i>
    				    <span class="title">Ticket Type</span>
    				</a>
    			</li>
    			
    			<li class="last <?php echo ($pageNo == 7) ? "active" : "";?>">
    				<a href="{{ URL::route('company.account') }}">
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
    @include('js.common')
@stop

@stop
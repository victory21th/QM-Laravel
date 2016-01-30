@extends('company.layout')

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">Dashboard</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Dashboard</span>
					<i class="fa fa-angle-right"></i>
				</li>
			</ul>
			
		</div>
	</div>    
@stop

@section('custom-styles')
    {{ HTML::style('/assets/css/datepicker.css') }}    
@stop

@section('content')
    <h2>Dashboard</h2>
    <div class="row">
        <div class="col-sm-12">
            <form class="form-horizontal" method="post" action="{{ URL::route('company.dashboard') }}">
                <div class="form-group">
                    <label class="col-sm-2 col-sm-offset-1 control-label">Search Date</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control text-center readonly-white" name="startDate" id="startDate" placeholder="Start Date" readonly value="{{ $startDate }}">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control text-center readonly-white" name="endDate" id="endDate" placeholder="End Date" readonly value="{{ $endDate }}">
                    </div>
                    <div class="col-sm-1">
                        &nbsp;
                    </div>                                                
                    <div class="col-sm-3">
                        <select class="form-control" id="period">
                            <option value="0">Select Period</option>
                            <option value="3">Last 3 days</option>
                            <option value="7">Last 1 week</option>
                            <option value="30">Last 1 month</option>
                            <option value="60">Last 2 months</option>
                            <option value="90">Last 3 months</option>
                            <option value="180">Last 6 months</option>
                            <option value="365">Last 1 year</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 col-sm-offset-1 control-label">Store</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="storeId">
                            <option value="" {{ ($storeId == '') ? 'selected' : '' }}>All Stores</option>
                            @foreach ($stores as $store)
                            <option value="{{ $store->id }}" {{ ($storeId == $store->id) ? 'selected' : '' }}>{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4 text-right">
                        <button class="btn btn-primary" onclick="return onValidate();">Search</button>
                    </div>
                </div>                
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-sm-offset-2">
            <div class="dashboard-stat green">
				<div class="visual">
					<i class="icon-speedometer"></i>
				</div>
				<div class="details">
					<div class="number">
						 {{ $average_time_per_ticket }}										
					 </div>
					<div class="desc">
						 Average time per ticket (min)
					</div>
				</div>
			</div>
        </div>
        <div class="col-sm-4">
            <div class="dashboard-stat green">
				<div class="visual">
					<i class="fa fa-bar-chart-o"></i>
				</div>
				<div class="details">
					<div class="number">
						 {{ $average_tickets_per_day }}										
					 </div>
					<div class="desc">
						 Average tickets per day
					</div>
				</div>
			</div>
        </div>    
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            <hr/>
        </div>    
        <div id="container1" class="chart-container col-sm-12"></div>
        <div class="col-sm-12">
            <hr/>
        </div>
        <div id="container2" class="chart-container col-sm-12"></div>
        <div class="col-sm-12">
            <hr/>
        </div>        
        <div id="container3" class="chart-container col-sm-12"></div>            
    </div>
@stop

@section('custom-scripts')
    {{ HTML::script('/assets/js/bootstrap-datepicker.js') }}
    {{ HTML::script('/assets/highcharts/highcharts.js') }}
    {{ HTML::script('/assets/highcharts/modules/exporting.js') }}    
    @include('js.company.dashboard.index')
@stop

@stop

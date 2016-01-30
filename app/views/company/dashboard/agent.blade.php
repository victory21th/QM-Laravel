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
				<li>
					<span>Agents</span>
				</li>
			</ul>
			
		</div>
	</div>    
@stop

@section('custom-styles')
    {{ HTML::style('/assets/css/datepicker.css') }}    
@stop

@section('content')
    <h2>Agents Statistic</h2>
    <div class="row">
        <div class="col-sm-12">
            <form class="form-horizontal" method="post" action="{{ URL::route('company.dashboard.agent') }}">
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
                <hr/>
                <div class="portlet box green">
                    <div class="portlet-title">
                		<div class="caption">
                			<i class="fa fa-navicon"></i> Agents Statistic
                		</div>
                	</div>
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="js-tbl-data">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Ticket Closed</th>
                                    <th>Online Time</th>
                                    <th>Average Ticket Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($agents as $key => $value)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $value->name }}</td>
                                        <td>{{ $value->email }}</td>
                                        <td>{{ $value->phone }}</td>
                                        <td>{{ $value->cnt }}</td>
                                        <td>
                                            {{ floor($value->activeTime / 3600).' Hour   '.floor(($value->activeTime % 3600) / 60).' Min   '.floor(($value->activeTime % 3600) % 60).' Sec' }}
                                        </td>
                                        <td>
                                            @if ($value->cnt ==0)
                                                {{ '0 Min   0 Sec' }}
                                            @else
                                                {{ floor(round($value->activeTime / $value->cnt) / 60).' Min   '.(round($value->activeTime / $value->cnt) % 60).' Sec' }}    
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
            </form>
        </div>
    </div>
@stop

@section('custom-scripts')
    {{ HTML::script('/assets/js/bootstrap-datepicker.js') }}
    @include('js.company.dashboard.agent')
@stop

@stop

@extends('company.layout')

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">Setting</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Setting</span>
					<i class="fa fa-angle-right"></i>
				</li>
			</ul>
			
		</div>
	</div>    
@stop

@section('custom-styles')
    {{ HTML::style('/assets/css/bootstrap-colorpicker.min.css') }}
    {{ HTML::style('/assets/css/bootstrap-timepicker.min.css') }}
@stop

@section('content')

@if ($errors->has())
<div class="alert alert-danger alert-dismissibl fade in">
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    @foreach ($errors->all() as $error)
		<p>{{ $error }}</p>		
	@endforeach
</div>
@endif

<div class="portlet box green">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-pencil-square-o"></i> Setting
		</div>
	</div>
	<div class="portlet-body">
        <form class="form-horizontal" role="form" method="post" action="{{ URL::route('company.setting.store') }}" enctype="multipart/form-data">
            <input type="hidden" name="setting_id" value="{{ $setting->id }}">
            @foreach ([
                'waiting_time' => 'Waiting Time (Second)',
                'logo'         => 'Logo',
                'color'        => 'Color',
                'background'   => 'Background',
                'start_time'   => 'Start Time',
                'end_time'     => 'End Time',
            ] as $key => $value)
            <div class="form-group">
                <label class="col-sm-2 control-label">{{ Form::label($key, $value) }}</label>
                <div class="col-sm-10">
                    @if ($key === 'color' || $key === 'background')
                        <div class="input-group" id="js-div-<?php echo $key;?>">
                            {{ Form::text($key, $setting->{$key}, ['class' => 'form-control readonly', 'readonly' => true]) }}
                            <span class="input-group-addon"><i></i></span>
                        </div>                    
                    @elseif ($key === 'start_time' || $key === 'end_time')
                        <div class="input-group">
                            {{ Form::text($key, $setting->{$key}, ['class' => 'form-control readonly', 'readonly' => true]) }}
                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                        </div>
                    @elseif ($key === 'logo')
                        {{ Form::file($key, ['class' => 'form-control']) }}
                        <div class="text-center margin-top-xs">
                            <img src="{{ HTTP_LOGO_PATH.$setting->logo }}" style="height: 50px;"/>
                        </div>
                    @else
                        {{ Form::text($key, $setting->{$key}, ['class' => 'form-control']) }}
                    @endif
                </div>
            </div>
            @endforeach

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center">
                    <button class="btn btn-success">
                        <span class="glyphicon glyphicon-ok-circle"></span> Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@section('custom-scripts')
    {{ HTML::script('/assets/js/bootstrap-colorpicker.min.js') }}
    {{ HTML::script('/assets/js/bootstrap-timepicker.min.js') }}
    @include('js.company.setting.index')
@stop

@stop

@extends('admin.layout')

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">City Management</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>City</span>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span>Edit</span>
				</li>
			</ul>
			
		</div>
	</div>    
@stop

@section('content')

@if ($errors->has())
<div class="alert alert-danger alert-dismissibl fade in">
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    @foreach ($errors->all() as $error)
		{{ $error }}		
	@endforeach
</div>
@endif

<div class="portlet box green">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-pencil-square-o"></i> Edit City
		</div>
	</div>
	<div class="portlet-body">
        <form class="form-horizontal" role="form" method="post" action="{{ URL::route('admin.city.store') }}">
            <input type="hidden" name="city_id" value="{{ $city->id }}">
            @foreach ([
                'name' => 'Name',
                'created_at' => 'Created At',
                'updated_at' => 'Updated At',
            ] as $key => $value)
            <div class="form-group">
                <label class="col-sm-2 control-label">{{ Form::label($key, $value) }}</label>
                <div class="col-sm-10">
                    @if ($key === 'created_at' || $key === 'updated_at')
                        <p class="form-control-static">{{ $city->{$key} }}</p>
                    @else
                    <input type="text" class="form-control" name="{{ $key }}" value="{{ $city->{$key} }}">
                    @endif
                </div>
            </div>
            @endforeach
          
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center">
                    <button class="btn btn-success">
                        <span class="glyphicon glyphicon-ok-circle"></span> Save
                    </button>
                    <a href="{{ URL::route('admin.city') }}" class="btn btn-primary">
                        <span class="glyphicon glyphicon-share-alt"></span> Back
                    </a>
                </div>
            </div>
          </form>
    </div>
</div>
@stop

@stop

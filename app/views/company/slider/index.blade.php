@extends('company.layout')

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">Slider Management</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Slider</span>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span>List</span>
				</li>
			</ul>
			
		</div>
	</div>    
@stop

@section('content')
<?php if (isset($alert)) { ?>
<div class="alert alert-<?php echo $alert['type'];?> alert-dismissibl fade in">
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <p>
        <?php echo $alert['msg'];?>
    </p>
</div>
<?php } ?>
                    
<div class="portlet box green">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-navicon"></i> Slider List
		</div>
		<div class="actions">
		    <a href="{{ URL::route('company.slider.create') }}" class="btn btn-default btn-sm">
		        <span class="glyphicon glyphicon-plus"></span>&nbsp;Create
		    </a>								    
	    </div>		
	</div>
    <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover" id="js-tbl-data">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Created At</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sliders as $key => $value)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td><img src="{{ HTTP_SLIDER_PATH.$value->url }}" style="height: 70px;"/></td>
                        <td>{{ substr($value->created_at, 0, 10) }}</td>
                        <td>
                            <a href="{{ URL::route('company.slider.delete', $value->id) }}" class="btn btn-sm btn-danger" id="js-a-delete">
                                <span class="glyphicon glyphicon-trash"></span> Delete
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pull-right">{{ $sliders->links() }}</div>
    </div>
</div>    
@stop

@stop

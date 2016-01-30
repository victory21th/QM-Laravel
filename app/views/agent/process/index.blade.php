@extends('agent.layout')

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">Queue Processing</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Queue Processing</span>
					<i class="fa fa-angle-right"></i>
				</li>
			</ul>
			
		</div>
	</div>    
@stop

@section('content')
    
    <div class="portlet box green">
        <div class="portlet-title">
    		<div class="caption">
    			<i class="fa fa-pencil-square-o"></i> My Account
    		</div>
    	</div>
    	<div class="portlet-body">
            <input type="hidden" name="process_id" id="process_id" value="{{ Session::has('process_id') ? Session::get('process_id') : '' }}"/>
            
            <div class="row">
                <div class="col-sm-4 col-sm-offset-1 {{ isset($currentQueueNo) ? '' : 'hidden' }}" id="js-div-current-queue-no">
                    <div class="alert alert-info" role="alert">
                        Current Queue No : <span class="font-bold" id="js-span-current-queue-no">{{ isset($currentQueueNo) ? $currentQueueNo : '' }}</span>
                    </div>            
                </div>
                
                <div class="col-sm-4 col-sm-offset-2 {{ isset($lastQueueNo) ? '' : 'hidden' }}" id="js-div-last-queue-no">
                    <div class="alert alert-info" role="alert">
                        Last Queue No : <span class="font-bold" id="js-span-last-queue-no">{{ isset($lastQueueNo) ? $lastQueueNo : '' }}</span>
                    </div>                
                </div>
                
                <div class="col-sm-6 col-sm-offset-3 {{ $is_active ? 'hidden' : '' }}" id="js-div-status">
                    <div class="alert alert-danger" role="alert">
                        <span id="js-span-status">Your status is DEACTIVE</span>
                    </div>
                </div>
                
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1">
                            <button class="btn btn-success btn-block btn-lg" id="js-btn-next">
                                Take a next Customer
                            </button>
                        </div>
                    </div>
    
                    <div class="row margin-top-normal {{ $is_active ? '' : 'hidden' }}" id="js-div-stop">
                        <div class="col-sm-10 col-sm-offset-1 text-center">
                            <button class="btn btn-default btn-block btn-lg" id="js-btn-stop">
                                Stop Work
                            </button>
                        </div>
                    </div>
                    
                    <div class="row margin-top-normal">
                        <div class="col-sm-10 col-sm-offset-1">
                            <div class="alert alert-success" role="alert">
                                <p>Mostly used Ticket Reason</p>
                                <?php $i = 1; ?>
                                @foreach ($mostTickets as $item)
                                    <p>{{ ($i++).". ".$item->name." - ".$item->cnt }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-sm-offset-1">
                    <div class="">
                        <div class="alert alert-success" role="alert">
                            Amount of Queue : {{ $amountOfQueue }}
                        </div>
                    </div>
                    <div class="">
                        <div class="alert alert-success" role="alert">
                            Average ticket management time : {{ floor($averageTicketTime / 60).' Min   '.($averageTicketTime % 60).' Sec' }}
                        </div>
                    </div>
                    <div class="">
                        <div class="alert alert-success" role="alert">
                            Average your ticket management time : {{ floor($averageYourTicketTime / 60).' Min   '.($averageYourTicketTime % 60).' Sec' }}
                        </div>
                    </div>
                    <div class="">
                        <div class="alert alert-success" role="alert">
                            Tickets Managed : {{ $ticketsManaged }}
                        </div>
                    </div>
                    <div class="">
                        <div class="alert alert-success" role="alert">
                            Active Time : {{ floor($activeTime / 60).' Min   '.($activeTime % 60).' Sec' }}
                        </div>
                    </div>                
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="modal fade" id="modal-ticket-types" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?php echo SITE_NAME;?></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="js-input-is-next" value="1"/>
                    <div class="row">
                    @foreach ($ticketTypes as $ticketType)
                        <div class="col-sm-4 col-sm-offset-1">
                            <button class="btn btn-default btn-block margin-top-sm" id="js-btn-ticket-type" data-id="{{ $ticketType->id }}">{{ $ticketType->name }}</button>
                        </div>
                        <div class="col-sm-1"></div>
                    @endforeach
                        <div class="col-sm-4 col-sm-offset-1">
                            <button class="btn btn-info btn-block margin-top-sm" id="js-btn-ticket-type" data-id="">Skip</button>
                        </div>
                        <div class="col-sm-1"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="js-btn-submit">Submit</button>
                </div>
            </div>
        </div>
    </div>    
        
@stop

@section('custom-scripts')
    @include('js.agent.process.index')
@stop

@stop

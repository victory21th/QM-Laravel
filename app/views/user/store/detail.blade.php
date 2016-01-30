@extends('user.layout')

@section('body')
<main class="bs-docs-masthead" role="main">
    <div class="container">
        <div class="row">
            <div class="pull-left detail-navi">
                <a href="/store/search" class="color-green">&laquo;&nbsp;Back to Search Page</a>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</main>
<div class="detail-main">
    <div class="container detail-header">
        <div class="row">
            <div class="text-uppercase col-sm-5 color-gray-light">Store Name</div>
            <div class="text-uppercase col-sm-2 color-gray-light">Company Name</div>
            <div class="text-uppercase col-sm-2 color-gray-light">Waiting Time</div>
            <div class="text-uppercase col-sm-2 color-gray-light">Next Number</div>
        </div>
    </div>
    <div class="container detail-body">
        <div class="padding-top-sm padding-bottom-xs detail-body-header">
            <div class="col-sm-5">{{ $store->name }}</div>
            <div class="col-sm-2">{{ $store->company->name }}</div>
            <div class="col-sm-2">
                @if (count($store->activeAgent) == 0)
                    <span class="label label-danger font-size-full">---</span>
                @else
                    {{ ceil((($store->status->last_queue_no - $store->status->current_queue_no) / count($store->activeAgent) )) * ($store->company->setting->waiting_time / 60) }}min
                @endif
            </div>
            <div class="col-sm-1">{{ $store->status->last_queue_no }}</div>
            <div class="col-sm-2">
                @if ($queueNo)
                    <button class="btn btn-danger pull-right">{{ $queueNo }}</button>
                @else
                    <button class="btn btn-success pull-right" id="js-btn-apply" data-id="{{ $store->id }}">Apply</button>
                @endif
            </div>
            <div class="clearfix"></div>
        </div>
        
        <div class="padding-top-sm padding-bottom-xs">
            <div class="col-sm-8" style="border-right: 1px solid #EEE;">
                <h3 class="color-green">Store Details : </h3>
                <hr/>
                <div class="padding-bottom-sm detail-body-description">
                    {{ $store->description }}
                </div>
                <div class="row padding-top-sm padding-bottom-sm">
                    <div class="col-sm-5 col-sm-offset-1">
                        <b>Category :</b>&nbsp;&nbsp;&nbsp;
                        {{ $store->company->category->name }}
                    </div>
                    <div class="col-sm-5">
                        <b>City :</b>&nbsp;&nbsp;&nbsp;
                        {{ $store->company->city->name }}
                    </div>                    
                </div>
            </div>
            <div class="col-sm-4">
                <div class="margin-top-normal">
                    <div class="margin-bottom-sm">
                        <div class="col-sm-3 text-center">
                            <img src="{{ HTTP_LOGO_PATH.$store->company->setting->logo }}" class="img-circle" style="width: 100%; margin-top: 5px;">
                        </div>
                        <div class="col-sm-9">
                            <h4><b>{{ $store->name }}</b></h4>
                            <p class="color-gray-light" style="font-size: 11px;">Member since : {{ $store->created_at }}</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <hr>
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-1 control-label"><span class="glyphicon glyphicon-map-marker"></span></label>
                            <label class="col-sm-5 control-label" style="font-weight: normal; text-align: left;">Address : </label>
                            <div class="col-sm-5">
                                <p class="form-control-static text-right"><b>{{ $store->address }}</b></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-1 control-label"><span class="glyphicon glyphicon-user"></span></label>
                            <label class="col-sm-7 control-label" style="font-weight: normal; text-align: left;">Total Customers Served : </label>
                            <div class="col-sm-3">
                                <p class="form-control-static text-right"><b>{{ $servedCount }}</b></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-1 control-label"><span class="glyphicon glyphicon-stats"></span></label>
                            <label class="col-sm-7 control-label" style="font-weight: normal; text-align: left;">Waiting Time : </label>
                            <div class="col-sm-3">
                                <p class="form-control-static text-right">
                                    <b>
                                        @if (count($store->activeAgent) == 0)
                                            ---
                                        @else
                                            {{ ceil((($store->status->last_queue_no - $store->status->current_queue_no) / count($store->activeAgent) )) * ($store->company->setting->waiting_time / 60) }}min
                                        @endif  
                                    </b>
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label"><span class="glyphicon glyphicon-send"></span></label>
                            <label class="col-sm-5 control-label" style="font-weight: normal; text-align: left;">Current Number : </label>
                            <div class="col-sm-5">
                                <p class="form-control-static text-right"><b>{{ $store->status->current_queue_no }}</b></p>
                            </div>
                        </div>
                        <div class="form-group color-gray-dark">
                            <label class="col-sm-1 control-label"><span class="glyphicon glyphicon-briefcase"></span></label>
                            <label class="col-sm-5 control-label" style="font-weight: normal; text-align: left;">Next Number : </label>
                            <div class="col-sm-5">
                                <p class="form-control-static text-right"><b>{{ $store->status->last_queue_no }}</b></p>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
@stop

@section('custom-scripts')
    @include('js.user.store.detail')
@stop

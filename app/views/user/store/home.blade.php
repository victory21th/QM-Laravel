@extends('user.layout')
@section('custom-styles')
    <link rel="stylesheet" href="/assets/plugin_slider/css/layerslider.css">
@stop

@section('body')
<div class="layer_slider">
    <div id="layerslider-container-fw" style="position:relative;">
        <div id="layerslider" style="width: 100%; height: 450px; margin: 0px auto;">
            <div class="ls-layer" style="slidedirection: right;">
                <img src="/assets/img/slider01.jpg" class="ls-bg" alt="Slide background">
            </div>
            <div class="ls-layer" style="slidedirection: right;">
                <img src="/assets/img/slider02.jpg" class="ls-bg" alt="Slide background">
            </div>
            <div class="ls-layer" style="slidedirection: right;">
                <img src="/assets/img/slider03.jpg" class="ls-bg" alt="Slide background">                                
            </div>
        </div>
    </div>
</div>
<div>
    <div class="container">
        <div class="row text-center margin-top-normal">
            <h1>How It Works?</h1>
        </div>
        
        <div class="row">
            <div class="col-sm-2 col-sm-offset-3 color-gray-dark">
                <div class="step-item">
                    <h3 class="color-gray-dark"><b>Step 1</b></h3>
                    <p>Search Stores</p>
                </div>
            </div>
            <div class="col-sm-2 color-gray-dark">
                <div class="step-item">
                    <h3 class="color-gray-dark"><b>Step 2</b></h3>
                    <p>Estimate Time</p>
                </div>
            </div>
            <div class="col-sm-2 color-gray-dark">
                <div class="step-item">
                    <h3 class="color-gray-dark"><b>Step 3</b></h3>
                    <p>Choose Store</p>
                </div>
            </div>
        </div>
        
        <div class="row text-center margin-top-normal margin-bottom-normal">
            <h1>There is already {{ $total }} places where you can use our app</h1>
        </div>
    </div>
    <?php if (!isset($category)) { $category = 0; } ?>
    <div class="container">
        <div class="row margin-top-30">
            <ul class="nav nav-tabs nav-justified ul-project-category" role="tablist">
                <li class="{{ ($category == 0) ? 'active' : '' }}">
                    <a href="/">Latest Projects</a>
                </li>
                @foreach ($categories as $item)
                <li class="{{ ($category == $item->id) ? 'active' : '' }}">
                    <a href="/home/{{ $item->id }}">{{ $item->name }}</a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>    
</div>
<div class="gray-container">    
    <div class="container">
        <div class="row">
            <table class="table table-store-list" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Store Name</th>
                        <th>Address</th>
                        <th>Company</th>
                        <th class="text-center">Estimated Waiting</th>
                        <th class="text-center">Current</th>
                        <th class="text-center">Last</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stores as $item)
                    <tr>
                        <td><a href="/store/detail/{{ $item->id }}">{{ $item->store_name }}</a></td>
                        <td>{{ $item->address }}</td>
                        <td><a href="/store/search?company={{ $item->company_name }}">{{ $item->company_name }}</a></td>
                        <td class="text-center">
                            @if ($item->cnt == '')
                                <span class="label label-danger font-size-full">---</span>
                            @else
                                {{ ceil((($item->last_queue_no - $item->current_queue_no) / $item->cnt)) * ($item->waiting_time / 60) }}min
                            @endif
                        </td>
                        <td class="text-center">{{ $item->current_queue_no }}</td>
                        <td class="text-center">{{ $item->last_queue_no }}</td>
                        <td class="text-center">
                            @if (!$item->queue_no)
                                <button class="btn btn-success btn-sm" id="js-btn-apply" data-id="{{ $item->id }}">Apply</button>
                            @else
                                <button class="btn btn-danger btn-sm">{{ $item->queue_no }}</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @if (count($stores) == 0)
                    <tr>
                        <td colspan="7" class="text-center">There is no stores</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <div class="pull-right">{{ $stores->links() }}</div>
        </div>
        <div class="row padding-bottom-normal padding-top-normal">
            <div class="col-sm-6" style="background-image: url('/assets/img/home01.jpg'); background-size: cover; padding: 0px;">
                <div class="home-desc-part1">
                    <h1 class="color-white text-center" style="margin-top: 0px; padding-top: 30px;">For Consumers</h1>
                    <div class="home-desc-step">
                        <div class="pull-left home-desc-step-icon">
                            <span class="glyphicon glyphicon-chevron-right color-white"></span>
                        </div>
                        <div class="pull-left home-desc-step-text">
                            <span class="color-gray-dark"><b>Connect with all relevant service providers</b></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="home-desc-step">
                        <div class="pull-left home-desc-step-icon">
                            <span class="glyphicon glyphicon-chevron-right color-white"></span>
                        </div>
                        <div class="pull-left home-desc-step-text">
                            <span class="color-gray-dark"><b>Save money compare service providers</b></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="home-desc-step">
                        <div class="pull-left home-desc-step-icon">
                            <span class="glyphicon glyphicon-chevron-right color-white"></span>
                        </div>
                        <div class="pull-left home-desc-step-text">
                            <span class="color-gray-dark"><b>Get proposals within hours</b></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>                                        
                </div>
            </div>
            <div class="col-sm-6" style="background-image: url('/assets/img/home02.jpg'); background-size: cover; padding: 0px;">
                <div class="home-desc-part2">
                    <h1 class="color-blue text-center" style="margin-top: 0px; padding-top: 30px;">For businesses</h1>
                    <div class="home-desc-step">
                        <div class="pull-left home-desc-step-icon">
                            <span class="glyphicon glyphicon-chevron-right color-white"></span>
                        </div>
                        <div class="pull-left home-desc-step-text">
                            <span class="color-gray-dark"><b>Join the world largest queue platform</b></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="home-desc-step">
                        <div class="pull-left home-desc-step-icon">
                            <span class="glyphicon glyphicon-chevron-right color-white"></span>
                        </div>
                        <div class="pull-left home-desc-step-text">
                            <span class="color-gray-dark"><b>Step through the door of amazing opportunies</b></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="home-desc-step">
                        <div class="pull-left home-desc-step-icon">
                            <span class="glyphicon glyphicon-chevron-right color-white"></span>
                        </div>
                        <div class="pull-left home-desc-step-text">
                            <span class="color-gray-dark"><b>New jobs everyday</b></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>                                        
                </div>
            </div>
        </div>    
    </div>
</div>
@stop

@section('custom-scripts')
    <script type="text/javascript" src="/assets/plugin_slider/jQuery/jquery-easing-1.3.min.js"></script>
    <script type="text/javascript" src="/assets/plugin_slider/jQuery/jquery-transit-modified.min.js"></script>
    <script type="text/javascript" src="/assets/plugin_slider/js/layerslider.transitions.min.js"></script>
    <script type="text/javascript" src="/assets/plugin_slider/js/layerslider.kreaturamedia.jquery.min.js"></script>
    @include('js.user.store.home')
@stop

@extends('user.layout')

@section('custom-styles')
    <link rel="stylesheet" href="/assets/css/bootstrap-slider.css">
@stop

@section('body')
<main class="bs-docs-masthead" role="main">
    <div class="search-container-image">
        <div class="search-container-color">
            <div class="container">
                <div class="row" style="background-color: rgba(255, 255, 255, 0.9); padding: 30px; border-radius: 3px;">
                    <form class="form-horizontal" method="get" action="{{ URL::route('user.store.search') }}">
                        <div class="col-sm-2">
                            <div class="form-group search-container-field">
                                <label class="color-blue">Store Name</label>
                                <input type="text" class="form-control" name="store" value="{{ $store_name }}">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group search-container-field">
                                <label class="color-blue">Company Name</label>
                                <input type="text" class="form-control" name="company" value="{{ $company_name }}">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group search-container-field">
                                <label class="color-blue">Category</label>
                                <select class="form-control" name="category">
                                    <option value=""></option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ ($category->id == $category_id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group search-container-field">
                                <label class="color-blue">City</label>
                                <select class="form-control" name="city">
                                    <option value=""></option>
                                    @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" {{ ($city->id == $city_id) ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>                                
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group search-container-field">
                                <label class="color-blue">Waiting Time (min)</label>
                                <div>
                                    <input type="checkbox" name="is_all" id="is_all" {{ ($is_all) ? 'checked' : '' }}/> All &nbsp;&nbsp;&nbsp;
                                    <input id="js-slider-waiting-time" data-slider-id='js-slider-waiting-time-slider' type="text" data-slider-min="0" data-slider-max="40" data-slider-step="2" data-slider-value="[{{ $waiting_time_min }},{{ $waiting_time_max }}]"/>
                                </div>
                                <div id="js-div-range-waiting-min">
                                    {{ $waiting_time_min }} : {{ $waiting_time_max }} min
                                </div>
                                
                                <input type="hidden" id="js-waiting-time-min" name="min" value="{{ $waiting_time_min }}"/>
                                <input type="hidden" id="js-waiting-time-max" name="max" value="{{ $waiting_time_max }}"/>                                
                            </div>
                        </div>                        
                        <div class="col-sm-1 text-center">
                            <div class="form-group">
                                <button class="btn btn-success btn-block" style="margin-top: 25px;">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
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
            <div class="pull-right">{{ $stores->appends(Request::input())->links() }}</div>
        </div>
    </div>
</div>
@stop

@section('custom-scripts')
    <script type="text/javascript" src="/assets/js/bootstrap-slider.js"></script>
    @include('js.user.store.search')
@stop

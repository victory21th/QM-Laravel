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
<div class="detail-main padding-top-normal">
    <div class="container detail-body" style="border: 1px solid #CFC;">
        <div class="row text-center">
            <h2>My Queue For Today</h2>
        </div>
        <div class="row">
            <table class="table table-store-list" style="width: 100%; margin-bottom: 0px;">
                <thead>
                    <tr>
                        <th>Store Name</th>
                        <th>Address</th>
                        <th>Company</th>
                        <th class="text-center">Estimated Waiting</th>
                        <th class="text-center">Current</th>
                        <th class="text-center">My Queue No</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($queues as $queue)
                    <tr>
                        <td><a href="/store/detail/{{ $queue->store->id }}">{{ $queue->store->name }}</a></td>
                        <td>{{ $queue->store->address }}</td>
                        <td>{{ $queue->store->company->name }}</td>
                        <td class="text-center">
                            @if (count($queue->store->activeAgent) == 0)
                                <span class="label label-danger font-size-full">---</span>
                            @elseif (($queue->queue_no - $queue->store->status->current_queue_no) < 0 )
                                <span class="label label-info font-size-full">Passed</span>
                            @else
                                {{ ceil((($queue->queue_no - $queue->store->status->current_queue_no) / count($queue->store->activeAgent) )) * ($queue->store->company->setting->waiting_time / 60) }}min
                            @endif
                        </td>
                        <td class="text-center">{{ $queue->store->status->current_queue_no }}</td>
                        <td class="text-center">{{ $queue->queue_no }}</td>
                    </tr>
                    @endforeach
                    @if (count($queues) == 0)
                    <tr>
                        <td colspan="6" class="text-center">There is no queues</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@stop

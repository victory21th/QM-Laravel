<?php namespace Batch;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session;
use Process as ProcessModel;

class ProcessController extends \BaseController {
    
    public function end() {
        $processes = ProcessModel::whereRaw("end_time IS NULL AND DATE_ADD(created_at, INTERVAL 2 HOUR) < NOW()")->get();
        foreach ($processes as $process) {
            $waitingTime = $process->agent->store->company->setting->waiting_time;
            $endTime = date("H:i:s", strtotime("$waitingTime seconds", strtotime($process->created_at)));
            $process->end_time = $endTime;
            $process->save();
        }
    }
}

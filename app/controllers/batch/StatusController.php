<?php namespace Batch;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session;
use Store as StoreModel;

class StatusController extends \BaseController {
    
    public function reset() {        
        $stores = StoreModel::all();
        foreach ($stores as $store) {
            $status = $store->status;
            $startNo = rand(1, 100);
            $status->current_queue_no = $startNo;
            $status->last_queue_no = $startNo;
            $status->save();
        }
    }
}

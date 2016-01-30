<?php namespace User;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response;
use \User\Models\Queue as QueueModel, Status as StatusModel;

class QueueController extends \BaseController {
    
    public function apply() {
        if (Session::has('user_id')) {
            if (Input::has('storeId')) {
                $storeId = Input::get('storeId');
                $userId = Session::get('user_id');
                $status = StatusModel::where('store_id', $storeId)->first();

                $myQueue = QueueModel::where('store_id', $storeId)
                                     ->where('user_id', $userId)
                                     ->where('queue_no', '>', $status->current_queue_no)
                                     ->whereRaw('DATE(created_at) = DATE(NOW())')
                                     ->count();
                                
                if ($myQueue == 0) {
                    $queueNo = $status->last_queue_no;
                    $status->last_queue_no = $queueNo + 1;
                    $status->save();
                    
                    $queue = new QueueModel;
                    $queue->store_id = $storeId;
                    $queue->user_id = $userId;
                    $queue->queue_no = $queueNo + 1;
                    $queue->save();
                    
                    return Response::json(['result' => 'success', 'msg' => 'You have been successfully apply on this store']);                
                } else {
                    return Response::json(['result' => 'failed', 'msg' => 'You have already apply on this store', 'code' => 'CD00']);
                }
            } else {
                return Response::json(['result' => 'failed', 'msg' => 'Invalid Request', 'code' => 'CD00']);
            }
        } else {
            return Response::json(['result' => 'failed', 'msg' => 'You must login for apply', 'code' => 'CD01']);            
        }       
    }
    
    public function status() {
        if (Session::has('user_id')) {
            $param['pageNo'] = 2;
            $param['queues'] = QueueModel::whereRaw('DATE(created_at) = DATE(NOW()) AND user_id = '.Session::get('user_id'))->get();
            return View::make('user.queue.status')->with($param);
        } else {
            return View::make('user.auth.login');
        }     
    }
}

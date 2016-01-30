<?php namespace Api;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response;
use \User\Models\Queue as QueueModel, Status as StatusModel;

class QueueController extends \BaseController {
    
    public function apply() {
        if (Input::has('store_id') && Input::has('user_id')) {
            
            $storeId = Input::get('store_id');
            $userId = Input::get('user_id');
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
                
                return Response::json(['result' => 'success', 'msg' => 'You have been successfully apply on this store', 'queue_no' => $queueNo + 1]);                
            } else {
                return Response::json(['result' => 'failed', 'msg' => 'You have already apply on this store']);
            }
                      

        } else {
            return Response::json(['result' => 'failed', 'msg' => 'Invalid Request', ]);            
        }       
    }
    
    public function status() {
        if (Input::has('user_id')) {
            $queues = QueueModel::whereRaw('DATE(created_at) = DATE(NOW()) AND user_id = '.Input::get('user_id'))->get();
            $stores = [];
            foreach ($queues as $queue) {
                if (count($queue->store->activeAgent) == 0) {
                    $time = '---';
                } elseif (($queue->queue_no - $queue->store->status->current_queue_no) < 0 ) {
                    $time = 'Passed';
                } else {
                    $time = ceil((($queue->queue_no - $queue->store->status->current_queue_no) / count($queue->store->activeAgent) )) * ($queue->store->company->setting->waiting_time / 60).'min';
                }

                $stores[] = ['store_id' => $queue->store->id,
                             'store_name' => $queue->store->name,
                             'company_name' => $queue->store->company->name,
                             'address' => $queue->store->address,
                             'estimated_waiting' => $time,
                             'current' => $queue->store->status->current_queue_no,
                             'mine' => $queue->queue_no, ];
            }
            return Response::json(['result' => 'success', 'msg' => '', 'stores' => $stores, ]);
        } else {
            return Response::json(['result' => 'failed', 'msg' => 'Invalid Request', ]);
        }
    }
}

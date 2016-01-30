<?php namespace Api;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response;
use Store as StoreModel, Status as StatusModel;

class TabletController extends \BaseController {
    
    public function login() {
        if (Input::has('email') && Input::has('password')) {
            $email = Input::get('email');
            $password = Input::get('password');
            
            $store = StoreModel::whereRaw('email = ? and secure_key = md5(concat(salt, ?))', array($email, $password))->get();
            
            if (count($store) != 0) {
                $store = $store[0];
                return Response::json( ['store_id' => $store->id, 
                                        'result' => 'success', 
                                        'msg' => '',
                                        'name' => $store->name,
                                        'address' => $store->address,
                                        'phone' => $store->phone,
                                        'email' => $store->email,
                                        'logo' => HTTP_PATH."assets/img/logo.png",
                                 ]);
            } else {
                return Response::json( ['result' => 'failed', 'msg' => 'Email and Password is incorrect', ]);
            }
        } else {
            return Response::json( ['result' => 'failed', 'msg' => 'Invalid Request', ]);            
        }
    }
    
    public function apply() {
        if (Input::has('store_id')) {
            $storeId = Input::get('store_id');
            $status = StatusModel::where('store_id', $storeId)->first();
            $queueNo = $status->last_queue_no;
            $status->last_queue_no = $queueNo + 1;
            $status->save();
            
            $store = StoreModel::find($storeId);
            return Response::json(['result' => 'success',
                                   'msg' => '',
                                   'queue_no' => $queueNo + 1,
                                   'created_at' => $status->updated_at->format('Y-m-d H:i:s'),
                                   'logo' => HTTP_PATH."/assets/img/logo.png",
                                  ]);
        } else {
            return Response::json(['result' => 'failed', 'msg' => 'Invalid Request', ]);
        }
    }
}

<?php namespace Store;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response;
use Store as StoreModel, Agent as AgentModel, Company as CompanyModel, CompanySetting as CompanySettingModel, Slider as SliderModel;

class StatusController extends \BaseController {
   
    public function tv($token) {
        $store = StoreModel::where('token', $token)->get();
        if (count($store) == 0) {
            return View::make('store.tv.invalid');
        } else {
            $param['token'] = $token;
            
            $store = StoreModel::where('token', $token)->first();
            $param['sliders'] = SliderModel::where('company_id', $store->company_id)->get();
            $param['setting'] = CompanySettingModel::where('company_id', $store->company_id)->first();
            return View::make('store.tv.index')->with($param);
        }
    }
    
    public function asyncStatus() {
        $token = Input::get('token');
        $store = StoreModel::where('token', $token)->first();
        
        $agents = AgentModel::where('store_id', $store->id)->where('is_active', true)->get();
        
        $current = $store->status->current_queue_no;
        $last = $store->status->last_queue_no;
        $counter = count($agents);
        if ($counter == 0) {
            return Response::json(['result' => 'failed', 
                                   'msg'    => 'There is no active Agent',
                                   'last'   => $last,
                                    ]);
        } else {
            $companySetting = CompanySettingModel::where('company_id', $store->company_id)->first();
            $waitingTime = ceil((($last - $current) / $counter )) * $companySetting->waiting_time;
            return Response::json(['result'      => 'success',
                                   'current'     => $current,
                                   'last'        => $last,
                                   'counter'     => $counter,
                                   'waitingTime' => round($waitingTime / 60, 1), 
                                    ]);
        }
    }
}

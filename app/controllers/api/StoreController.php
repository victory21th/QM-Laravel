<?php namespace Api;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, DB, Response;
use Store as StoreModel, \User\Models\Queue as QueueModel;

class StoreController extends \BaseController {
        
    public function search() {        
        $storeName = Input::has('store_name') ? Input::get('store_name') : '';
        $companyName = Input::has('company_name') ? Input::get('company_name') : '';
        $categoryId = Input::has('category_id') ? Input::get('category_id') : '';
        $cityId = Input::has('city_id') ? Input::get('city_id') : '';
        $min = Input::has('min') ? Input::get('min') : WAITING_TIME_MIN;
        $max = Input::has('max') ? Input::get('max') : WAITING_TIME_MAX;
        $isAll = Input::has('is_all') ? Input::get('is_all') : 'Y';
        $userId = Input::has('user_id') ? Input::get('user_id') : 0;
        
        $prefix = DB::getTablePrefix();
        $result = DB::table('store')
            ->join('company', 'store.company_id', '=', 'company.id')
            ->leftJoin(DB::raw('(SELECT * FROM '.$prefix.'queue WHERE user_id = '.$userId.' AND DATE(created_at) = DATE(NOW()) GROUP BY store_id) AS '.$prefix.'queues'), 'store.id', '=', 'queues.store_id')
            ->join('status', 'store.id', '=', 'status.store_id')
            ->leftJoin(DB::raw('(SELECT count(*) as cnt, store_id FROM '.$prefix.'agent WHERE is_active = 1 GROUP BY store_id) AS '.$prefix.'agt'), 'agt.store_id', '=', 'store.id')
            ->join('company_setting', 'company_setting.company_id', '=', 'company.id')
            ->join('category', 'category.id', '=', 'company.category_id')
            ->join('city', 'city.id', '=', 'company.city_id')
            ->select('store.id', 'store.name as store_name', 'store.address as address', 'company.name as company_name', 'company_setting.waiting_time as estimated_waiting',
                            'status.current_queue_no as current', 'status.last_queue_no as last', 'agt.cnt', 'queues.queue_no');

        if ($storeName != '') {
            $result = $result->where('store.name', 'like', '%'.$storeName.'%');
        }
        
        if ($companyName != '') {
            $result = $result->where('company.name', 'like', '%'.$companyName.'%');
        }
        
        if ($categoryId != '') {
            $result = $result->where('category.id', '=', $categoryId);
        }
        
        if ($cityId != '') {
            $result = $result->where('city.id', '=', $cityId);
        }
        
        if ($isAll == 'N') {
            if ($min != '') {
                $result = $result->whereRaw($prefix.'agt.cnt > 0')
                                 ->whereRaw('(('.$prefix.'status.last_queue_no - '.$prefix.'status.current_queue_no'.') * '
                                            .$prefix.'company_setting.waiting_time / 60) / ('.$prefix.'agt.cnt) >= '.$min);            
            }
            
            if ($max != '') {
                $result = $result->whereRaw($prefix.'agt.cnt > 0')
                                 ->whereRaw('(('.$prefix.'status.last_queue_no - '.$prefix.'status.current_queue_no'.') * '
                                            .$prefix.'company_setting.waiting_time / 60) / ('.$prefix.'agt.cnt) <= '.$max);
            }
        }
        
        $stores = $result->get();
        
        
        
        for ($i = 0; $i < count($stores); $i++) {
            $store = $stores[$i];                           
            $stores[$i]->estimated_waiting = (count($store->cnt) == '') ? '---' : ceil((($store->last - $store->current) / $store->cnt)) * ($store->estimated_waiting / 60).'min';
        }
        
        return Response::json(['result' => 'success', 'msg' => '', 'stores' => $stores, ]);
    }
    
    public function detail() {
        if (Input::has('store_id') && Input::has('user_id')) {
            $storeId = Input::get('store_id');
            $userId = Input::get('user_id');
            $store = StoreModel::find($storeId);
            
            $queueNo = QueueModel::where('user_id', $userId)
                           ->where('store_id', $storeId)
                           ->whereRaw('DATE(created_at) = DATE(NOW())')
                           ->orderBy('created_at', 'DESC')
                           ->get();
            
            if (count($queueNo) > 0) {
                $queueNo = $queueNo[0]->queue_no;
            } else {
                $queueNo = null;
            }
            
            return Response::json(['result'            => 'success', 
                                   'msg'               => '', 
                                   'store_name'        => $store->name,
                                   'company_name'      => $store->company->name,
                                   'company_logo'      => HTTP_LOGO_PATH.$store->company->setting->logo,
                                   'company_created_at'=> $store->company->created_at->format('Y-m-d H:i:s'),
                                   'estimated_waiting' => (count($store->activeAgent) == 0) ? '---' : ceil((($store->status->last_queue_no - $store->status->current_queue_no) / count($store->activeAgent) )) * ($store->company->setting->waiting_time / 60).'min',
                                   'next_number'       => $store->status->last_queue_no,
                                   'description'       => str_replace( 'src="/assets', 'src="'.HTTP_PATH.'/assets', $store->description),
                                   'category_name'     => $store->company->category->name,
                                   'city_name'         => $store->company->city->name,
                                   'address'           => $store->address,
                                   'total_customers_served' => QueueModel::where('store_id', $storeId)->count(),
                                   'current_number'    => $store->status->current_queue_no,
                                   'queue_no'          => $queueNo,
                            ]);
        } else {
            return Response::json(['result' => 'failed', 'msg' => 'Invalid Request', ]);
        }
    }
    
}

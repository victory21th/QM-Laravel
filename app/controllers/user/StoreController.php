<?php namespace User;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, DB;
use City as CityModel, User as UserModel, Store as StoreModel, Category as CategoryModel, Company as CompanyModel, \User\Models\Queue as QueueModel;

class StoreController extends \BaseController {
        
    public function home($id = 0) {
        $param['pageNo'] = 0;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        $userId = Session::has('user_id') ? Session::get('user_id') : 0;
        
        $prefix = DB::getTablePrefix();
        
        $result = DB::table('store')
            ->join('company', 'store.company_id', '=', 'company.id')
            ->leftJoin(DB::raw('(SELECT * FROM '.$prefix.'queue WHERE user_id = '.$userId.' AND DATE(created_at) = DATE(NOW()) GROUP BY store_id) AS '.$prefix.'queues'), 'store.id', '=', 'queues.store_id')
            ->join('status', 'store.id', '=', 'status.store_id')
            ->leftJoin(DB::raw('(SELECT count(*) as cnt, store_id FROM '.$prefix.'agent WHERE is_active = 1 GROUP BY store_id) AS '.$prefix.'agt'), 'agt.store_id', '=', 'store.id')
            ->join('company_setting', 'company_setting.company_id', '=', 'company.id')
            ->join('category', 'category.id', '=', 'company.category_id')
            ->join('city', 'city.id', '=', 'company.city_id')
            ->select('store.id', 'store.name as store_name', 'store.address as address', 'company.name as company_name', 'company_setting.waiting_time', 
                     'status.current_queue_no', 'status.last_queue_no', 'agt.cnt', 'queues.queue_no');       
                
        if ($id == 0) {
            $param['stores'] = $result->paginate(PAGINATION_SIZE);
        } else {
            $companyIds = array();
            $companyIds[] = 0;
            $companies = CompanyModel::where('category_id', $id)->get();
            foreach ($companies as $company) {
                $companyIds[] = $company->id;
            }
            $param['stores'] = $result->whereIn('store.company_id', $companyIds)->paginate(PAGINATION_SIZE);
        }
        $param['total'] = StoreModel::count();
        $param['category'] = $id;
        $param['categories'] = CategoryModel::all();
        return View::make('user.store.home')->with($param);
    }
    
    public function search() {
        $param['pageNo'] = 1;        
        
        $param['store_name'] = Input::has('store') ? Input::get('store') : '';
        $param['company_name'] = Input::has('company') ? Input::get('company') : '';
        $param['category_id'] = Input::has('category') ? Input::get('category') : '';
        $param['city_id'] = Input::has('city') ? Input::get('city') : '';
        $param['waiting_time_min'] = Input::has('min') ? Input::get('min') : WAITING_TIME_MIN;
        $param['waiting_time_max'] = Input::has('max') ? Input::get('max') : WAITING_TIME_MAX;
        $param['is_all'] = Input::has('is_all') ? true : !Input::has('min');
        $userId = Session::has('user_id') ? Session::get('user_id') : 0;
        
        $prefix = DB::getTablePrefix();
        
        $result = DB::table('store')
            ->join('company', 'store.company_id', '=', 'company.id')
            ->leftJoin(DB::raw('(SELECT * FROM '.$prefix.'queue WHERE user_id = '.$userId.' AND DATE(created_at) = DATE(NOW()) GROUP BY store_id) AS '.$prefix.'queues'), 'store.id', '=', 'queues.store_id')
            ->join('status', 'store.id', '=', 'status.store_id')
            ->leftJoin(DB::raw('(SELECT count(*) as cnt, store_id FROM '.$prefix.'agent WHERE is_active = 1 GROUP BY store_id) AS '.$prefix.'agt'), 'agt.store_id', '=', 'store.id')
            ->join('company_setting', 'company_setting.company_id', '=', 'company.id')
            ->join('category', 'category.id', '=', 'company.category_id')
            ->join('city', 'city.id', '=', 'company.city_id')
            ->select('store.id', 'store.name as store_name', 'store.address as address', 'company.name as company_name', 'company_setting.waiting_time', 
                     'status.current_queue_no', 'status.last_queue_no', 'agt.cnt', 'queues.queue_no');
        
        if ($param['store_name'] != '') {
            $result = $result->where('store.name', 'like', '%'.$param['store_name'].'%');
        }
        
        if ($param['company_name'] != '') {
            $result = $result->where('company.name', 'like', '%'.$param['company_name'].'%');
        }
        
        if ($param['category_id'] != '') {
            $result = $result->where('category.id', '=', $param['category_id']);
        }
        
        if ($param['city_id'] != '') {
            $result = $result->where('city.id', '=', $param['city_id']);
        }        
        if (!$param['is_all']) {
            $result = $result->whereRaw($prefix.'agt.cnt > 0')
                             ->whereRaw('(('.$prefix.'status.last_queue_no - '.$prefix.'status.current_queue_no'.') * '
                             .$prefix.'company_setting.waiting_time / 60) / ('.$prefix.'agt.cnt)'
                             .' BETWEEN '.$param['waiting_time_min'].' AND '.$param['waiting_time_max']);            
        }

        $stores = $result->paginate(PAGINATION_SIZE);

        $param['stores'] = $stores;
        $param['categories'] = CategoryModel::all();
        $param['cities'] = CityModel::all();
        
        return View::make('user.store.search')->with($param);
    }
    
    public function detail($id) {
        $param['store'] = StoreModel::find($id);
        $param['servedCount'] = QueueModel::where('store_id', $id)->count();
        
        $userId = Session::has('user_id') ? Session::get('user_id') : 0;
        
        $queueNo = QueueModel::where('user_id', $userId)
                        ->where('store_id', $id)
                        ->whereRaw('DATE(created_at) = DATE(NOW())')
                        ->orderBy('created_at', 'DESC')
                        ->get();
        
        if (count($queueNo) > 0) {
            $queueNo = $queueNo[0]->queue_no;
        } else {
            $queueNo = null;
        }

        $param['queueNo'] = $queueNo;
        
        return View::make('user.store.detail')->with($param);
    }
}

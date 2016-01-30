<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Store as StoreModel, Status as StatusModel;

class StoreController extends \BaseController {
    
    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('company_id')) {
                return Redirect::route('company.auth.login');
            }
        });
    }    
        
    public function index() {
        $param['stores'] = StoreModel::where('company_id', Session::get('company_id') )->paginate(10);
        $param['pageNo'] = 2;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('company.store.index')->with($param);
    }
    
    public function create() {
        $param['pageNo'] = 2;
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('company.store.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 2;
        $param['store'] = StoreModel::find($id);
        
        return View::make('company.store.edit')->with($param);
    }
    
    public function store() {
        
        $rules = ['name' => 'required',
                  'address' => 'required',
                  'postal_code' => 'required',
                  'email' => 'required|email',
                  'phone' => 'required',
                 ];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $password = Input::get('password');
            
            if (Input::has('store_id')) {
                $id = Input::get('store_id');
                $store = StoreModel::find($id);
                
                if ($password !== '') {
                    $store->secure_key = md5($store->salt.$password);
                }
            } else {
                $store = new StoreModel;
                
                if ($password === '') {
                    $alert['msg'] = 'You have to enter password';
                    $alert['type'] = 'danger';
                    return Redirect::route('company.store.create')->with('alert', $alert);
                }
                $store->salt = str_random(8);
                $store->token = str_random(8);
                $store->secure_key = md5($store->salt.$password);
                $store->company_id = Session::get('company_id');
            }       
            
            $store->name = Input::get('name');
            $store->address = Input::get('address');
            $store->postal_code = Input::get('postal_code');
            $store->email = Input::get('email');
            $store->phone = Input::get('phone');
            $store->description = Input::get('description');
            $store->save();
            
            if (!Input::has('store_id')) {
                $status = new StatusModel;
                $status->store_id = $store->id;
                $startNo = rand(1, 100);
                $status->current_queue_no = $startNo;
                $status->last_queue_no = $startNo;
                $status->save();
            }
            
            $alert['msg'] = 'Store has been saved successfully';
            $alert['type'] = 'success';
              
            return Redirect::route('company.store')->with('alert', $alert);
        }
    }
    
    public function delete($id) {
        try {
            StoreModel::find($id)->delete();
            
            $alert['msg'] = 'Store has been deleted successfully';
            $alert['type'] = 'success';            
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Store has been already used';
            $alert['type'] = 'danger';            
        }

        return Redirect::route('company.store')->with('alert', $alert);
    }
}

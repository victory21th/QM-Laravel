<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Agent as AgentModel, Store as StoreModel;

class AgentController extends \BaseController {
    
    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('company_id')) {
                return Redirect::route('company.auth.login');
            }
        });
    }    
        
    public function index() {
        $storeIds = [];
        $storeIds[] = 0;
        
        $stores = StoreModel::where('company_id', Session::get('company_id'))->get();
        foreach ($stores as $store) {
            $storeIds[] = $store->id;
        }
        
        $param['agents'] = AgentModel::whereIn('store_id', $storeIds)->paginate(10);
        $param['pageNo'] = 3;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('company.agent.index')->with($param);
    }
    
    public function create() {
        $param['pageNo'] = 3;
        $param['stores'] = StoreModel::where('company_id', Session::get('company_id'))->get();
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('company.agent.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 3;
        $param['agent'] = AgentModel::find($id);
        $param['stores'] = StoreModel::where('company_id', Session::get('company_id'))->get();
        
        return View::make('company.agent.edit')->with($param);
    }
    
    public function store() {
        
        $rules = ['name' => 'required',
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
            
            if (Input::has('agent_id')) {
                $id = Input::get('agent_id');
                $agent = AgentModel::find($id);
                
                if ($password !== '') {
                    $agent->secure_key = md5($agent->salt.$password);
                }
            } else {
                $agent = new AgentModel;
                
                if ($password === '') {
                    $alert['msg'] = 'You have to enter password';
                    $alert['type'] = 'danger';
                    return Redirect::route('company.agent.create')->with('alert', $alert);
                }
                $agent->salt = str_random(8);
                $agent->token = str_random(8);
                $agent->secure_key = md5($agent->salt.$password);
            }       
            
            $agent->name = Input::get('name');
            $agent->email = Input::get('email');
            $agent->phone = Input::get('phone');
            $agent->store_id = Input::get('store_id');
            $agent->save();
            
            $alert['msg'] = 'Agent has been saved successfully';
            $alert['type'] = 'success';
              
            return Redirect::route('company.agent')->with('alert', $alert);
        }
    }
    
    public function delete($id) {
        try {
            AgentModel::find($id)->delete();
            
            $alert['msg'] = 'Agent has been deleted successfully';
            $alert['type'] = 'success';            
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Agent has been already used';
            $alert['type'] = 'danger';            
        }

        return Redirect::route('company.agent')->with('alert', $alert);
    }
}

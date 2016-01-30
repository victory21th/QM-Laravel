<?php namespace Agent;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Agent as AgentModel;

class AccountController extends \BaseController {
    
    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('agent_id')) {
                return Redirect::route('agent.auth.login');
            }
        });
    }

    public function index() {
        $agent = AgentModel::find(Session::get('agent_id'));
        
        $param['pageNo'] = 2;
        $param['agent'] = $agent;
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('agent.account.index')->with($param);
    }
   
    public function store() {
        
        $rules = ['name'          => 'required',
                  'email'         => 'required|email',
                  'phone'         => 'required',
                 ];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $agent = AgentModel::find(Session::get('agent_id'));
            
            $password = Input::get('password');
            if ($password !== '') {
                $agent->secure_key = md5($agent->salt.$password);
            }
            
            $agent->name = Input::get('name');
            $agent->email = Input::get('email');
            $agent->phone = Input::get('phone');
            $agent->save();            

            $alert['msg'] = 'Account has been saved successfully';
            $alert['type'] = 'success';
              
            return Redirect::route('agent.account')->with('alert', $alert);
        }
    }
}

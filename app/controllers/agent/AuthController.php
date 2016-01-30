<?php namespace Agent;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Agent as AgentModel, Process as ProcessModel;

class AuthController extends \BaseController {
    
    public function index() {
        if (Session::has('agent_id')) {
            return Redirect::route('agent.process');
        } else {
            return Redirect::route('agent.auth.login');
        }
    }
    
    public function login() {
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
            return View::make('agent.auth.login')->with($param);
        } else {
            return View::make('agent.auth.login');
        }
    }
    
    public function doLogin() {
        $email = Input::get('email');
        $password = Input::get('password');
        
        $agent = AgentModel::whereRaw('email = ? and secure_key = md5(concat(salt, ?))', array($email, $password))->get();
        
        if (count($agent) != 0) {
            Session::set('agent_id', $agent[0]->id);
            $agent[0]->is_active = false;
            $agent[0]->save();
            return Redirect::route('agent.process');
        } else {
            $alert['msg'] = 'Invalid Email and Password';
            $alert['type'] = 'danger';
            return Redirect::route('agent.auth.login')->with('alert', $alert);
        }
    }
    
    public function logout() {
        
        if (Session::has('process_id')) {
            $process = ProcessModel::find(Session::get('process_id'));
            $process->end_time = date('H:i:s');
            $process->save();
            Session::forget('process_id');
        }
        
        $agent = AgentModel::find(Session::get('agent_id'));
        $agent->is_active = false;
        $agent->save();

        Session::forget('agent_id');
        return Redirect::route('agent.auth.login');
    }
}

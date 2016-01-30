<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Company as CompanyModel;

class AuthController extends \BaseController {
    
    public function index() {
        if (Session::has('company_id')) {
            return Redirect::route('company.dashboard');
        } else {
            return Redirect::route('company.auth.login');
        }
    }
    
    public function login() {
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
            return View::make('company.auth.login')->with($param);
        } else {
            return View::make('company.auth.login');
        }
    }
    
    public function doLogin() {
        $email = Input::get('email');
        $password = Input::get('password');
        
        $company = CompanyModel::whereRaw('email = ? and secure_key = md5(concat(salt, ?))', array($email, $password))->get();
        
        if (count($company) != 0) {
            Session::set('company_id', $company[0]->id);
            return Redirect::route('company.dashboard');
        } else {
            $alert['msg'] = 'Invalid Email and Password';
            $alert['type'] = 'danger';
            return Redirect::route('company.auth.login')->with('alert', $alert);
        }
    }
    
    public function logout() {
        Session::forget('company_id');
        return Redirect::route('company.auth.login');
    }
}

<?php namespace User;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use City as CityModel, User as UserModel;

class AuthController extends \BaseController {
        
    public function login() {
        $param['pageNo'] = 98;
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('user.auth.login')->with($param);        
    }
    
    public function signup() {
        $param['pageNo'] = 99;
        $param['cities'] = CityModel::all();
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;   
        }
        
        return View::make('user.auth.signup')->with($param);
    }
    
    public function doSignup() {
        $rules = ['email'      => 'required|email|unique:user',
                  'password'   => 'required|confirmed',
                  'password_confirmation' => 'required',
                  'phone'      => 'required',
                  'name'       => 'required',
                  'city_id'    => 'required',
                ];
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $user = new UserModel;
            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->phone = Input::get('phone');
            $user->city_id = Input::get('city_id');
            $user->address = Input::get('address');
            $user->salt = str_random(8);
            $user->secure_key = md5($user->salt.Input::get('password'));
            $user->save();
            
            $alert['msg'] = 'User has been signed up successfully';
            $alert['type'] = 'success';
            
            return Redirect::route('user.auth.signup')->with('alert', $alert);            
        }
    }
    
    public function doLogin() {
        $email = Input::get('email');
        $password = Input::get('password');
        
        $user = UserModel::whereRaw('email = ? and secure_key = md5(concat(salt, ?))', array($email, $password))->get();
        
        if (count($user) != 0) {
            Session::set('user_id', $user[0]->id);
            return Redirect::route('user.home');
        } else {
            $alert['msg'] = 'Email & Password is incorrect';
            $alert['type'] = 'danger';
            return Redirect::route('user.auth.login')->with('alert', $alert);
        }
    }
    
    public function doLogout() {
        Session::forget('user_id');
        return Redirect::route('user.home');
    }
}

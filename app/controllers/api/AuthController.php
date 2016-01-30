<?php namespace Api;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response;
use User as UserModel;

class AuthController extends \BaseController {
    
    public function login() {
        if (Input::has('email') && Input::has('password')) {
            $email = Input::get('email');
            $password = Input::get('password');
            
            $user = UserModel::whereRaw('email = ? and secure_key = md5(concat(salt, ?))', array($email, $password))->get();
            
            if (count($user) != 0) {
                return Response::json( ['user_id' => $user[0]->id, 'result' => 'success', 'msg' => '', ]);
            } else {
                return Response::json( ['result' => 'failed', 'msg' => 'Email and Password is incorrect', ]);
            }
        } else {
            return Response::json( ['result' => 'failed', 'msg' => 'Invalid Request', ]);            
        }
    }
    
    public function signup() {
        if (Input::has('name') || Input::has('email') || Input::has('phone') || Input::has('city_id') || Input::has('address') || Input::has('password')) {
            $user = new UserModel;
            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->phone = Input::get('phone');
            $user->city_id = Input::get('city_id');
            $user->address = Input::get('address');
            $user->salt = str_random(8);
            $user->secure_key = md5($user->salt.Input::get('password'));
            $user->save();
            return Response::json( ['result' => 'success', 'msg' => '', ]);
        } else {
            return Response::json( ['result' => 'failed', 'msg' => 'Invalid Request', ]);
        }
    }
    
    public function profile() {
        if (Input::has('user_id')) {
            $user = UserModel::find(Input::get('user_id'));
            return Response::json( ['result' => 'success', 'msg' => '', 'user' => $user]);
        } else {
            return Response::json( ['result' => 'failed', 'msg' => 'Invalid Request', ]);
        }        
    }
    
    public function update() {
        if (Input::has('user_id') && Input::has('email') && Input::has('name')
            && Input::has('phone') && Input::has('city_id') && Input::has('address')) {
            
            $user = UserModel::find(Input::get('user_id'));
            $user->email = Input::get('email');
            $user->name = Input::get('name');
            $user->phone = Input::get('phone');
            $user->city_id = Input::get('city_id');
            $user->address = Input::get('address');
            
            if (Input::get('password') != '') {
                $user->secure_key = md5($user->salt.Input::get('password'));                
            }
            $user->save();
            return Response::json( ['result' => 'success', 'msg' => '', ]);
        } else {
            return Response::json( ['result' => 'failed', 'msg' => 'Invalid Request', ]);
        }
    }
    
}

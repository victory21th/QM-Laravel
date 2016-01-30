<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, File;
use User as UserModel, City as CityModel;

class UserController extends \BaseController {
    
    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('admin_id')) {
                return Redirect::route('admin.auth.login');
            }
        });
    }    
    
    public function index() {
        $param['users'] = UserModel::paginate(10);
        $param['pageNo'] = 3;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('admin.user.index')->with($param);
    }
    
    public function create() {
        $param['pageNo'] = 3;
        $param['cities'] = CityModel::all();
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('admin.user.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 3;
        $param['user'] = UserModel::find($id);
        $param['cities'] = CityModel::all();        
        
        return View::make('admin.user.edit')->with($param);
    }
    
    public function store() {
        
        $rules = ['name'       => 'required',
                  'email' => 'required|email',
                  'phone' => 'required',
                  'city_id' => 'required',
                 ];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $password = Input::get('password');
            
            if (Input::has('user_id')) {
                $id = Input::get('user_id');
                $user = UserModel::find($id);
                
                if ($password !== '') {
                    $user->secure_key = md5($user->salt.$password);
                }
            } else {
                $user = new UserModel;
                
                if ($password === '') {
                    $alert['msg'] = 'You have to enter password';
                    $alert['type'] = 'danger';
                    return Redirect::route('admin.user.create')->with('alert', $alert);
                }
                $user->salt = str_random(8);
                $user->secure_key = md5($user->salt.$password);
            }       
            
            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->phone = Input::get('phone');
            $user->city_id = Input::get('city_id');
            $user->address = Input::get('address');
            $user->save();

            $alert['msg'] = 'User has been saved successfully';
            $alert['type'] = 'success';
              
            return Redirect::route('admin.user')->with('alert', $alert);
        }
    }
    
    public function delete($id) {
        try {            
            UserModel::find($id)->delete();
             
            $alert['msg'] = 'User has been deleted successfully';
            $alert['type'] = 'success';
        } catch(\Exception $ex) {
            $alert['msg'] = 'This User has been already used';
            $alert['type'] = 'danger';
        }
        
        return Redirect::route('admin.user')->with('alert', $alert);
    }
}

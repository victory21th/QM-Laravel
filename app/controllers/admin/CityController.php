<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use City as CityModel;

class CityController extends \BaseController {

    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('admin_id')) {
                return Redirect::route('admin.auth.login');
            }
        });
    }
    
    public function index() {
        $param['cities'] = CityModel::paginate(10);
        $param['pageNo'] = 5;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('admin.city.index')->with($param);
    }
    
    public function create() {
        $param['pageNo'] = 5;
        return View::make('admin.city.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 5;
        $param['city'] = CityModel::find($id);
        
        return View::make('admin.city.edit')->with($param);
    }
    
    public function store() {
        
        $rules = ['name' => 'required'];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (Input::has('city_id')) {
                $id = Input::get('city_id');
                $city = CityModel::find($id);
            } else {
                $city = new CityModel;                
            }            
            $city->name = Input::get('name');
            $city->save();
            
            $alert['msg'] = 'City has been saved successfully';
            $alert['type'] = 'success';            
              
            return Redirect::route('admin.city')->with('alert', $alert);            
        }
    }
    
    public function delete($id) {
        try {
            CityModel::find($id)->delete();
            
            $alert['msg'] = 'City has been deleted successfully';
            $alert['type'] = 'success';            
        } catch(\Exception $ex) {
            $alert['msg'] = 'This City has been already used';
            $alert['type'] = 'danger';
        }

        return Redirect::route('admin.city')->with('alert', $alert);
    }
}

<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use CompanySetting as SettingModel;

class SettingController extends \BaseController {
    
    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('company_id')) {
                return Redirect::route('company.auth.login');
            }
        });
    }    
        
    public function index() {        
        $setting = SettingModel::where('company_id', Session::get('company_id') );
        
        $setting = $setting->first();
        
        $param['pageNo'] = 5;
        $param['setting'] = $setting;
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('company.setting.index')->with($param);
    }
   
    public function store() {
        
        $rules = ['waiting_time'  => 'required|numeric',
                  'color'         => 'required',
                  'background'    => 'required',
                  'start_time'    => 'required',
                  'end_time'      => 'required',
                 ];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            
            $setting = SettingModel::find(Input::get('setting_id'));
            
            $setting->waiting_time = Input::get('waiting_time');
            $setting->color = Input::get('color');
            $setting->background = Input::get('background');
            $setting->start_time = Input::get('start_time');
            $setting->end_time = Input::get('end_time');
            if (Input::hasFile('logo')) {
                $filename = str_random(24).".".Input::file('logo')->getClientOriginalExtension();
                Input::file('logo')->move(ABS_LOGO_PATH, $filename);
                $setting->logo = $filename;
            }            
            $setting->save();
            
            $alert['msg'] = 'Setting has been saved successfully';
            $alert['type'] = 'success';
              
            return Redirect::route('company.setting')->with('alert', $alert);
        }
    }
}

<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Slider as SliderModel;

class SliderController extends \BaseController {
    
    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('company_id')) {
                return Redirect::route('company.auth.login');
            }            
        });
    }

    public function index() {                
        $param['sliders'] = SliderModel::where('company_id', Session::get('company_id'))->paginate(10);
        $param['pageNo'] = 8;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('company.slider.index')->with($param);
    }
    
    public function create() {
        $param['pageNo'] = 8;
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('company.slider.create')->with($param);
    }
    
    public function store() {
        if (Input::hasFile('slider')) {
            $filename = str_random(24).".".Input::file('slider')->getClientOriginalExtension();
            Input::file('slider')->move(ABS_SLIDER_PATH, $filename);
            $slider = new SliderModel;
            $slider->company_id = Session::get('company_id');
            $slider->url = $filename;
            $slider->save();
            
            $alert['msg'] = 'Slider has been saved successfully';
            $alert['type'] = 'success';
            return Redirect::route('company.slider')->with('alert', $alert);
        } else {
            $alert['msg'] = 'Please select the file to upload';
            $alert['type'] = 'danger';
            return Redirect::route('company.slider.create')->with('alert', $alert);
        }
    }
    
    public function delete($id) {
        try {
            SliderModel::find($id)->delete();
            
            $alert['msg'] = 'Slider has been deleted successfully';
            $alert['type'] = 'success';
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Slider has been already used';
            $alert['type'] = 'danger';            
        }

        return Redirect::route('company.slider')->with('alert', $alert);
    }
}

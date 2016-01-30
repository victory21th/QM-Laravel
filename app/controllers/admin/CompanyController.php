<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, File;
use Company as CompanyModel, City as CityModel, Category as CategoryModel, CompanySetting as SettingModel;

class CompanyController extends \BaseController {
    
    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('admin_id')) {
                return Redirect::route('admin.auth.login');
            }
        });
    }    
    
    public function index() {
        $param['companies'] = CompanyModel::paginate(10);
        $param['pageNo'] = 2;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('admin.company.index')->with($param);
    }
    
    public function create() {
        $param['pageNo'] = 2;
        $param['cities'] = CityModel::all();
        $param['categories'] = CategoryModel::all();
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('admin.company.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 2;
        $param['company'] = CompanyModel::find($id);
        $param['cities'] = CityModel::all();
        $param['categories'] = CategoryModel::all();        
        
        return View::make('admin.company.edit')->with($param);
    }
    
    public function store() {
        
        $rules = ['name' => 'required',
                  'vat_number' => 'required',
                  'address' => 'required',
                  'postal_code' => 'required',
                  'email' => 'required|email',
                  'phone' => 'required',
                  'category_id' => 'required',
                  'city_id' => 'required',
                 ];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $password = Input::get('password');
            
            if (Input::has('company_id')) {
                $id = Input::get('company_id');
                $company = CompanyModel::find($id);
                
                if ($password !== '') {
                    $company->secure_key = md5($company->salt.$password);
                }
            } else {
                $company = new CompanyModel;
                
                if ($password === '') {
                    $alert['msg'] = 'You have to enter password';
                    $alert['type'] = 'danger';
                    return Redirect::route('admin.company.create')->with('alert', $alert);
                }
                $company->salt = str_random(8);
                $company->token = str_random(8);
                $company->secure_key = md5($company->salt.$password);
            }       
            
            $company->name = Input::get('name');
            $company->vat_number = Input::get('vat_number');
            $company->address = Input::get('address');
            $company->postal_code = Input::get('postal_code');
            $company->email = Input::get('email');
            $company->phone = Input::get('phone');
            $company->category_id = Input::get('category_id');
            $company->city_id = Input::get('city_id');
            $company->save();
            
            if (!Input::has('company_id')) {
                $setting = new SettingModel;
                $setting->company_id = $company->id;
                $setting->waiting_time = WAITING_TIME;
                $setting->logo = LOGO;
                $setting->color = COLOR;
                $setting->background = BACKGROUND;
                $setting->start_time = START_TIME;
                $setting->end_time = END_TIME;
                $setting->save();
                
                File::makeDirectory(public_path()."/assets/img/stores/".$company->id, 0775, true, true);               
            }

            $alert['msg'] = 'Company has been saved successfully';
            $alert['type'] = 'success';
              
            return Redirect::route('admin.company')->with('alert', $alert);
        }
    }
    
    public function delete($id) {
        try {
            // SettingModel::where('company_id', $id)->delete();
            
            CompanyModel::find($id)->delete();
             
            $alert['msg'] = 'Company has been deleted successfully';
            $alert['type'] = 'success';
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Company has been already used';
            $alert['type'] = 'danger';
        }
        
        return Redirect::route('admin.company')->with('alert', $alert);
    }
}

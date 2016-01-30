<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Company as CompanyModel, City as CityModel, Category as CategoryModel;

class AccountController extends \BaseController {
    
    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('company_id')) {
                return Redirect::route('company.auth.login');
            }
        });
    }
        
    public function index() {        
        $company = CompanyModel::find(Session::get('company_id'));
        
        $param['pageNo'] = 7;
        $param['company'] = $company;
        $param['cities'] = CityModel::all();
        $param['categories'] = CategoryModel::all();
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('company.account.index')->with($param);
    }
   
    public function store() {
        
        $rules = ['name'          => 'required',
                  'vat_number'    => 'required',
                  'address'       => 'required',
                  'postal_code'   => 'required',
                  'email'         => 'required|email',
                  'phone'         => 'required',
                  'category_id'   => 'required',
                  'city_id'       => 'required',
                 ];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $company = CompanyModel::find(Session::get('company_id'));
            
            $password = Input::get('password');
            if ($password !== '') {
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

            $alert['msg'] = 'Account has been saved successfully';
            $alert['type'] = 'success';
              
            return Redirect::route('company.account')->with('alert', $alert);
        }
    }
}

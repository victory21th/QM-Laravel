<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;

class DashboardController extends \BaseController {
    
    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('admin_id')) {
                return Redirect::route('admin.auth.login');
            }
        });
    }   
    
    public function index() {
        $param['pageNo'] = 1;
        return View::make('admin.dashboard.index')->with($param);
    }    
}

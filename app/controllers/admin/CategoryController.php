<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Category as CategoryModel;

class CategoryController extends \BaseController {
    
    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('admin_id')) {
                return Redirect::route('admin.auth.login');
            }
        });
    }
    
    public function index() {
        $param['categories'] = CategoryModel::paginate(10);
        $param['pageNo'] = 4;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('admin.category.index')->with($param);
    }
    
    public function create() {
        $param['pageNo'] = 4;
        return View::make('admin.category.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 4;
        $param['category'] = CategoryModel::find($id);
        
        return View::make('admin.category.edit')->with($param);
    }
    
    public function store() {
        
        $rules = ['name' => 'required'];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (Input::has('category_id')) {
                $id = Input::get('category_id');
                $category = CategoryModel::find($id);
            } else {
                $category = new CategoryModel;                
            }
            $category->name = Input::get('name');
            $category->save();
            
            $alert['msg'] = 'Category has been saved successfully';
            $alert['type'] = 'success';
              
            return Redirect::route('admin.category')->with('alert', $alert);            
        }
    }
    
    public function delete($id) {
        try {
            CategoryModel::find($id)->delete();
            
            $alert['msg'] = 'Category has been deleted successfully';
            $alert['type'] = 'success';
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Category has been already used';
            $alert['type'] = 'danger';
        }
        
        return Redirect::route('admin.category')->with('alert', $alert);
    }
}

<?php namespace Api;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response;
use Category as CategoryModel;

class CategoryController extends \BaseController {
    public function index() {
        return Response::json( ['categories' => CategoryModel::all(), 
                                'result' => 'success',
                                'msg' => '', ]);
    }
}

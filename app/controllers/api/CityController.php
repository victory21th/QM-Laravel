<?php namespace Api;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response;
use City as CityModel;

class CityController extends \BaseController {
    public function index() {
        return Response::json( ['cities' => CityModel::all(),
                                'result' => 'success',
                                'msg' => '', ]);        
    }
}

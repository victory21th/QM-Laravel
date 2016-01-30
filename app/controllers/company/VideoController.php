<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Video as VideoModel;

class VideoController extends \BaseController {
    
    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('company_id')) {
                return Redirect::route('company.auth.login');
            }            
        });
    }

    public function index() {                
        $param['videos'] = VideoModel::where('company_id', Session::get('company_id') )->paginate(10);
        $param['pageNo'] = 4;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('company.video.index')->with($param);
    }
    
    public function create() {
        $param['pageNo'] = 4;
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('company.video.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 4;
        $param['video'] = VideoModel::find($id);
        
        return View::make('company.video.edit')->with($param);
    }
    
    public function store() {
        $rules = ['name'      => 'required',
                  'url'       => 'required',
                  'duration'  => 'required|numeric',
                 ];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {            
            if (Input::has('video_id')) {
                $id = Input::get('video_id');
                $video = VideoModel::find($id);
            } else {
                $video = new VideoModel;
            }       
            
            $video->company_id = Session::get('company_id');
            $video->name = Input::get('name');
            $video->url = Input::get('url');
            $video->duration = Input::get('duration');
            $video->save();
            
            $alert['msg'] = 'Video has been saved successfully';
            $alert['type'] = 'success';
              
            return Redirect::route('company.video')->with('alert', $alert);
        }
    }
    
    public function delete($id) {
        try {
            VideoModel::find($id)->delete();
            
            $alert['msg'] = 'Video has been deleted successfully';
            $alert['type'] = 'success';            
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Video has been already used';
            $alert['type'] = 'danger';            
        }

        return Redirect::route('company.video')->with('alert', $alert);
    }
}

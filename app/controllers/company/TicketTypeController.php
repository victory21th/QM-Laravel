<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use TicketType as TicketTypeModel;

class TicketTypeController extends \BaseController {
    
    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('company_id')) {
                return Redirect::route('company.auth.login');
            }
        });
    }    
        
    public function index() {
        $param['ticketTypes'] = TicketTypeModel::where('company_id', Session::get('company_id') )->paginate(10);
        $param['pageNo'] = 6;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('company.ticket-type.index')->with($param);
    }
    
    public function create() {
        $param['pageNo'] = 6;
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('company.ticket-type.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 6;
        $param['ticketType'] = TicketTypeModel::find($id);
        
        return View::make('company.ticket-type.edit')->with($param);
    }
    
    public function store() {
        $rules = ['name'      => 'required'];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {            
            if (Input::has('ticket_type_id')) {
                $id = Input::get('ticket_type_id');
                $ticketType = TicketTypeModel::find($id);
            } else {
                $ticketType = new TicketTypeModel;
            }       
            
            $ticketType->company_id = Session::get('company_id');
            $ticketType->name = Input::get('name');
            $ticketType->save();
            
            $alert['msg'] = 'Ticket Type has been saved successfully';
            $alert['type'] = 'success';
              
            return Redirect::route('company.ticket-type')->with('alert', $alert);
        }
    }
    
    public function delete($id) {
        try {
            TicketTypeModel::find($id)->delete();
            
            $alert['msg'] = 'Ticket Type has been deleted successfully';
            $alert['type'] = 'success';            
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Ticket Type has been already used';
            $alert['type'] = 'danger';
        }

        return Redirect::route('company.ticket-type')->with('alert', $alert);
    }
}

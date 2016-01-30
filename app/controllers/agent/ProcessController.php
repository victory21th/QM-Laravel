<?php namespace Agent;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, DB;
use Agent as AgentModel, Status as StatusModel, TicketType as TicketTypeModel, Process as ProcessModel, Store as StoreModel;

class ProcessController extends \BaseController {
        
    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('agent_id')) {
                return Redirect::route('agent.auth.login');
            }
        });
    }
        
    public function index() {
        $param['pageNo'] = 1;
        $agent = AgentModel::find(Session::get('agent_id'));
        $param['is_active'] = $agent->is_active;
        $param['ticketTypes'] = TicketTypeModel::where('company_id', $agent->store->company->id)->get();
        if (Session::has('process_id')) {
            $process = ProcessModel::find(Session::get('process_id'));
            $param['currentQueueNo'] = $process->queue_no;
            $param['lastQueueNo'] = $process->agent->store->status->last_queue_no;
        }
        
        // Get Store ID
        $storeId = $agent->store_id;
        $status = StatusModel::where('store_id', $storeId)->first();
        
        $agents = AgentModel::where('store_id', $storeId)
                            ->where('is_active', TRUE);
        $param['amountOfQueue'] = $status->last_queue_no - $status->current_queue_no;
        
        $tickets = ProcessModel::where('agent_id', $agent->id)
                               ->whereRaw('DATE(created_at) = DATE(NOW())')
                               ->where('end_time', '!=', '');
        $param['ticketsManaged'] = $tickets->count();
        
        $processTickets = $tickets->get();
        $activeTime = 0;
        foreach ($processTickets as $item) {
            $activeTime += strtotime($item->end_time) - strtotime($item->start_time);
        }
        $param['activeTime'] = $activeTime;
        
        $param['averageYourTicketTime'] = ($tickets->count() == 0) ? 0 : round($activeTime / $tickets->count());
        
        
        $agentIds = [];
        $agentIds[] = 0;
        
        $agents = AgentModel::where('store_id', $storeId)->get();
        foreach ($agents as $agent) {
            $agentIds[] = $agent->id;
        }        
        
        $tickets = ProcessModel::whereIn('agent_id', $agentIds)
                               ->whereRaw('DATE(created_at) = DATE(NOW())')
                               ->where('end_time', '!=', '');
        $processTickets = $tickets->get();
        $activeTime = 0;
        foreach ($processTickets as $item) {
            $activeTime += strtotime($item->end_time) - strtotime($item->start_time);
        }        
        $param['averageTicketTime'] = ($tickets->count() == 0) ? 0 : round($activeTime / $tickets->count());

        $prefix = DB::getTablePrefix();
        
        $sqlType = "SELECT id, name
                  FROM ".$prefix."ticket_type
                 WHERE company_id = ".$agent->store->company->id."
                 UNION ALL
                SELECT 0 as id, 'Skip' as name";        
        
        $sql = "
                SELECT COUNT(*) AS cnt, IFNULL(ticket_type, 0) AS type
                  FROM ".$prefix."process t1, ".$prefix."agent t2, ".$prefix."store t3
                 WHERE t1.agent_id = t2.id
                   AND t2.store_id = t3.id
                   AND t1.end_time != ''
                   AND t3.company_id = ".$agent->store->company->id."
                 GROUP BY ticket_type";
        
        $sql = "SELECT t1.name, IFNULL(t2.cnt, 0) AS cnt
                  FROM ($sqlType) t1
                  LEFT JOIN ($sql) t2
                    ON t1.id = t2.type
                 ORDER BY IFNULL(t2.cnt, 0) DESC";
        $param['mostTickets'] = DB::select($sql);
        
        return View::make('agent.process.index')->with($param);
    }
    
    public function asyncNext() {
        $agent = AgentModel::find(Session::get('agent_id'));
        $agent->is_active = true;
        $agent->save();
        $status = StatusModel::where('store_id', $agent->store_id)->first();
        
        if (Session::has('process_id')) {
            $process = ProcessModel::find(Session::get('process_id'));
            $process->end_time = date('H:i:s');
            if (Input::has('ticket_type') && Input::get('ticket_type') != '') {
                $process->ticket_type = Input::get('ticket_type');
            }
            $process->save();
            Session::forget('process_id');
        }
        
        if (Input::get('is_next') == '1') {
            if ($status->current_queue_no + 1 <= $status->last_queue_no) {
                $status->current_queue_no = $status->current_queue_no + 1;
                $status->save();
            
                $process = new ProcessModel;
                $process->agent_id = Session::get('agent_id');
                $process->queue_no = $status->current_queue_no;
                $process->start_time = date('H:i:s');
                $process->save();
                Session::set('process_id', $process->id);
                return Response::json(['result' => 'success',
                                       'currentQueueNo' => $status->current_queue_no,
                                       'lastQueueNo' => $status->last_queue_no,
                                       'processId' => $process->id, ]);
            } else {
                $agent->is_active = false;
                $agent->save();                
                return Response::json(['result' => 'failed', 'msg' => 'The queue is empty']);
            }
        } else {
            $agent->is_active = false;
            $agent->save();
            return Response::json(['result' => 'failed', 'msg' => 'Your status is DEACTIVE']);
        }

        
    }
}

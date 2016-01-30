<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, DB;
use Store as StoreModel, \User\Models\Queue as QueueModel, Process as ProcessModel;
class DashboardController extends \BaseController {
        
    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('company_id')) {
                return Redirect::route('company.auth.login');
            }
        });
    }
        
    public function index() {
        $param['pageNo'] = 1;
        
        $startDate = Input::has('startDate') ? Input::get('startDate') : '';
        $endDate   = Input::has('endDate')   ? Input::get('endDate')   : '';
        if ($startDate == '' || $endDate == '') {
            $endDate = date('Y-m-d');
            $startDate = substr($endDate, 0, 8)."01";
        }
        
        $storeId   = Input::has('storeId')   ? Input::get('storeId')   : '';
        $companyId = Session::get('company_id');
        
        $prefix = DB::getTablePrefix();
        $sql = "
                SELECT t1.start_time, t1.end_time
                  FROM ".$prefix."process t1, ".$prefix."agent t2, ".$prefix."store t3
                 WHERE t1.agent_id = t2.id
                   AND t2.store_id = t3.id
                   AND t1.created_at >= '".$startDate." 00:00:00'
                   AND t1.created_at <= '".$endDate." 23:59:59'
                   AND t1.end_time != ''
                   AND t3.company_id = $companyId";
        if ($storeId != '') {
            $sql.= " AND t2.store_id = $storeId";
        }
        
        $processes = DB::select($sql);
        
        $average_time_per_ticket = 0;
        foreach ($processes as $item) {
            $average_time_per_ticket += strtotime($item->end_time) - strtotime($item->start_time);
        }
        if (count($processes) == 0) {
            $param['average_time_per_ticket'] = '---';
        } else {
            $param['average_time_per_ticket'] = round($average_time_per_ticket / count($processes) / 60, 2);
        }
        
        
        $sql = "
                SELECT COUNT(*) AS cnt
                  FROM ".$prefix."queue t1, ".$prefix."store t2
                 WHERE t1.store_id = t2.id
                   AND (DATE(t1.created_at) BETWEEN '".$startDate."' AND '".$endDate."')
                   AND t2.company_id = $companyId";
        if ($storeId != '') {
            $sql.= " AND t1.store_id = $storeId";
        }
        $queues = DB::select($sql);
        $param['average_tickets_per_day'] = $queues[0]->cnt;
        
        // daily statistics
        $sql = "
                SELECT COUNT(*) AS cnt, DATE(t1.created_at) AS dt
                  FROM ".$prefix."queue t1, ".$prefix."store t2
                 WHERE t1.store_id = t2.id
                   AND (DATE(t1.created_at) BETWEEN '".$startDate."' AND '".$endDate."')
                   AND t2.company_id = $companyId";
        if ($storeId != '') {
            $sql.= " AND t1.store_id = $storeId";
        }
        $sql.= " GROUP BY DATE(created_at)";
        
        $sql = "SELECT t1.*, YEAR(dt) as y, MONTH(dt)-1 as m, DATE_FORMAT(dt,'%e') as d
                  FROM ($sql) t1";
        $sqlPeriod = $this->allPeriod($startDate, $endDate);

        $sql = "SELECT t1.*, IFNULL(t2.cnt, 0) AS cnt, YEAR(t1.dt) as y, MONTH(t1.dt)-1 as m, DATE_FORMAT(t1.dt,'%e') as d
                  FROM ($sqlPeriod) t1
                  LEFT JOIN ($sql) t2
                    ON t1.dt = t2.dt
                 ORDER BY t1.dt ASC";
        $param['daily_statistics'] = DB::select($sql);
        
        // hourly statistics
        $sql = "
                SELECT COUNT(*) AS cnt, HOUR(t1.created_at) AS hr
                  FROM ".$prefix."queue t1, ".$prefix."store t2
                 WHERE t1.store_id = t2.id
                   AND (DATE(t1.created_at) BETWEEN '".$startDate."' AND '".$endDate."')
                   AND t2.company_id = $companyId";
        if ($storeId != '') {
            $sql.= " AND t1.store_id = $storeId";
        }
        $sql.= " GROUP BY HOUR(created_at)";
        $sqlHour = $this->allHour();
        $sql = "SELECT t1.*, IFNULL(t2.cnt, 0) AS cnt
                  FROM ($sqlHour) t1
                  LEFT JOIN ($sql) t2
                    ON t1.hr = t2.hr
                 ORDER BY t1.hr ASC";
        $param['hourly_statistics'] = DB::select($sql);
        
        // ticket reason
        $sql = "
                SELECT COUNT(*) AS cnt, IFNULL(ticket_type, 0) AS type
                  FROM ".$prefix."process t1, ".$prefix."agent t2, ".$prefix."store t3 
                 WHERE t1.agent_id = t2.id
                   AND t2.store_id = t3.id
                   AND (DATE(t1.created_at) BETWEEN '".$startDate."' AND '".$endDate."')
                   AND t3.company_id = $companyId";
        if ($storeId != '') {
            $sql.= " AND t2.store_id = $storeId";
        }
        $sql.= " GROUP BY ticket_type";
        $sqlReason = $this->allReason();
        $sql = "SELECT t1.*, IFNULL(t2.cnt, 0) AS cnt
                  FROM ($sqlReason) t1
                  LEFT JOIN ($sql) t2
                    ON t1.id = t2.type
                 ORDER BY t1.id ASC";
        $param['reason_statistics'] = DB::select($sql);
        
        $param['startDate'] = $startDate;
        $param['endDate'] = $endDate;
        $param['storeId'] = $storeId;
        $param['stores'] = StoreModel::where('company_id', Session::get('company_id'))->get();
        
        return View::make('company.dashboard.index')->with($param);
    }
    
    public function agent() {
        $param['pageNo'] = 9;
        
        $startDate = Input::has('startDate') ? Input::get('startDate') : '';
        $endDate   = Input::has('endDate')   ? Input::get('endDate')   : '';
        if ($startDate == '' || $endDate == '') {
            $endDate = date('Y-m-d');
            $startDate = substr($endDate, 0, 8)."01";
        }
        $storeId   = Input::has('storeId')   ? Input::get('storeId')   : '';
        $companyId = Session::get('company_id');
        $prefix = DB::getTablePrefix();
        
        $sqlGroup = "SELECT agent_id, COUNT(*) AS cnt, SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time))) AS activeTime
                       FROM ".$prefix."process
                      WHERE end_time != ''
                        AND (DATE(created_at) BETWEEN DATE('$startDate') AND DATE('$endDate'))
                      GROUP BY agent_id";
        
        $sql = "SELECT t1.id, t1.name, t1.email, t1.phone
                  FROM ".$prefix."agent t1, ".$prefix."store t2
                 WHERE t1.store_id = t2.id
                   AND t2.company_id = $companyId";
        if ($storeId != '') {
            $sql.= " AND t1.store_id = $storeId";
        }
        
        $sql = "SELECT t1.*, IFNULL(t2.cnt, 0) AS cnt, IFNULL(t2.activeTime, 0) as activeTime 
                  FROM ($sql) t1
                  LEFT JOIN ($sqlGroup) t2 ON t1.id = t2.agent_id";
        
        $param['agents'] = DB::select($sql);
        
        $param['storeId'] = $storeId;
        $param['startDate'] = $startDate;
        $param['endDate'] = $endDate;
        $param['stores'] = StoreModel::where('company_id', Session::get('company_id'))->get();
        return View::make('company.dashboard.agent')->with($param);
    }
    
    public function allPeriod($startDate, $endDate) {
        $sql = "select datediff( '$endDate', '$startDate' ) as days";
        $days = DB::select($sql);
        $days = $days[0]->days;

        $dateSql = "";
        $days++;
        for ($i = 0; $i < $days; $i++) {
            $dateSql.="SELECT DATE_ADD('$startDate', INTERVAL $i day) AS dt";
            if( $i != $days - 1  ){
                $dateSql.=" UNION ALL ";
            }
        }
        return $dateSql;
    }

    public function allHour() {
        $hourSql = "";
        for ($i = 0; $i < 24; $i++) {
            $hourSql.= "SELECT $i AS hr";
            if ($i != 23) {
                $hourSql.= " UNION ALL ";
            }
        }
        return $hourSql;
    }
    
    public function allReason() {
        $companyId = Session::has('company_id');
        $prefix = DB::getTablePrefix();

        $sql = "SELECT id, name
                  FROM ".$prefix."ticket_type
                 WHERE company_id = ".$companyId."
                 UNION ALL
                SELECT 0 as id, 'Skip' as name";
        return $sql;
    }
}

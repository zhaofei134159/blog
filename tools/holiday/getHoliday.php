<?php
/*
* 获取传递当天是否是节假日
*/
include_once './common.php';

# 常量数据
const LOG_FILE_NAME = 'getHolidayLog';

$param = $_REQUEST;
if (empty($param)) {
    $data = file_get_contents("php://input");
    $param = json_decode($data, true);
	journalRecord('获取当前日期：', ' param:'.json_encode($param), LOG_FILE_NAME);
}else{
    ksort($param);
    $data = json_encode($param);
	journalRecord('获取当前日期：', ' param:'.json_encode($param), LOG_FILE_NAME);
}

class getHoliday extends common{

    public function __construct(){
        $parentData = parent::__construct();
    }

    public function getToday($search_date=''){
    	if(empty($search_date)){
    		$search_date = date('Y-m-d');
    	}
    	$holidayls = $this->mysqlLink->doSql("SELECT * FROM zf_festival_and_holiday WHERE `date`='{$search_date}'");

    	$result = array();
    	if(!empty($holidayls)){
    		$result['date'] = $holidayls['0']['date'];
    		$result['type'] = ($holidayls['0']['type']==1)?'上班':'休假';
    		$result['msg'] = $holidayls['0']['holiday'];
    	}
    	return json_encode($result);
    }
}

$holiday = new getHoliday();
$json = $holiday->getToday($param['date']);
echo $json;
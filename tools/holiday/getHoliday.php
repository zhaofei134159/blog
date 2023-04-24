<?php
/*
* 获取传递当天是否是节假日
*/
include_once './common.php';
include_once './conf/day.fun.php';

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
    	vaR_dump($search_date);
    	vaR_dump($holidayls);
    }
}

$holiday = new getHoliday();
$html = $holiday->getToday($param['date']);

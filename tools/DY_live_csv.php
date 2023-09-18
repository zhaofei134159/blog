<?php
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
define('S_PATH', dirname(__FILE__));
ini_set("display_errors", "On");//打开错误提示
ini_set("error_reporting",E_ALL);//显示所有错误

include_once S_PATH.'/conf/core.fun.php';
include_once S_PATH.'/class/database.php';
include_once S_PATH.'/class/secretkey.php';
include_once S_PATH.'/class/MySql.php';  # mysql


$param = $_REQUEST;
if (empty($param)) {
    $data = file_get_contents("php://input");
    $param = json_decode($data, true);
}else{
    ksort($param);
    $data = json_encode($param);
}
ksort($param);

$startDateTime = $param['startDateTime'];
if(empty($startDateTime)){
	exit('请输入开始日期时间！');
}

# 数据库配置
$db_conf = array(
    'host' => $db['default']['hostname'],
    'port' => '3306',
    'user' => $db['default']['username'],
    'passwd' => $db['default']['password'],
    'dbname' => $db['default']['database'],
);

# mysql
$mysql = new MMysql($db_conf);

$sql = "SELECT accound_title, room_title, live_start_time, username, user_cnt FROM dy_live_data WHERE live_start_time>='{$startDateTime}' order by live_start_time";
$contentarr = $mysql->doSql($sql);

$titlearr = array('账号', '场次', '开播时间', '用户', '用户评论数');
export_csv($contentarr, $titlearr, 'liveCsv');
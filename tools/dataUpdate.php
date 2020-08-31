<?php
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
define('S_PATH', dirname(__FILE__));
error_reporting(E_ALL);

include_once S_PATH.'/class/database.php';  
include_once S_PATH.'/class/secretkey.php';  
include_once S_PATH.'/class/MySql.php';  # mysql


# 有几个脚本执行
$num = exec("ps aux | grep 'getJLInfo.php' | grep -v grep | wc -l");
if($num>1){
  exit(date('Y-m-d').' 已经有脚本了');
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


# 修改数据
$sql = "SELECT * from zf_famou_work_info where work_id=9 and id!=341";
$res = $mysql->doSql($sql);

foreach($res as $key=>$val){
	var_dump($val['id']);

	$content = explode(' <br> ',$val['content']);
	unset($content[0]);
	$contentStr = implode(' <br> ',$content);

	$updateSql = "update zf_famou_work_info set content='".$contentStr."' where id=".$val['id'];
	$mysql->doSql($updateSql);
}
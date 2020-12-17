<?php
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
define('S_PATH', dirname(__FILE__));
error_reporting(E_ALL);

include_once S_PATH.'/class/database.php';  
include_once S_PATH.'/class/MySql.php';  # mysql

$num = exec("ps aux | grep 'nowProcessing.php' | grep -v grep | wc -l");
if($num>1){
  exit(date('Y-m-d').' 已经有脚本了');
}

$res = array();
while($fp = exec('ps -aux')){
	$res[] = $fp;
}

var_dump($res);


<?php
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
define('S_PATH', dirname(__FILE__));
error_reporting(E_ALL);

include_once S_PATH.'/class/database.php';  
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

$sql = "SELECT * FROM zf_bladesoul";
$res = $mysql->doSql($sql);
var_dump($res);die;

$file =

$picFile = upload_img($file,'picToWord');

# 获取百度的 access_token
$result = $this->getBdAccessToken();
$resultArr = json_decode($result,true);
if(!isset($resultArr['access_token']) || empty($resultArr['access_token'])){
	$callback = array('errorMsg'=>'token获取错误','errorNo'=>'101');
	exit(json_encode($callback));
}

# 获取百度图文识别后的返回
$token = $resultArr['access_token'];
$url = 'https://aip.baidubce.com/rest/2.0/ocr/v1/webimage?access_token=';
$wordRes = $this->getBdPicToWord($url,$token,$picFile);
$wordResArr = json_decode($wordRes,true);
if(empty($wordResArr['words_result_num'])){
	$url = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general_basic?access_token=';
	$wordRes = $this->getBdPicToWord($url,$token,$picFile);
	$wordResArr = json_decode($wordRes,true);
}

if(empty($wordResArr['words_result_num'])){
	$callback = array('errorMsg'=>'未识别出文字','errorNo'=>'109');
	exit(json_encode($callback));
}

@unlink($picFile);

$callback = array('errorMsg'=>'','errorNo'=>'0','seccuss'=>$wordResArr);
exit(json_encode($callback));
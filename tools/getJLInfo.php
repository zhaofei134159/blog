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

# 路径
$path = '../../python/';

# 百度api
$picToWordAppkey = $config['picToWordAppkey']; 
$picToWordSecretkey = $config['picToWordSecretkey']; 


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

foreach($res as $key=>$val){
	$file = $path.$val['src'];
	baiduAPI($file);
	die;
}

function baiduAPI($file){
	$picFile = $file;

	# 获取百度的 access_token
	$result = getBdAccessToken();
	$resultArr = json_decode($result,true);
	if(!isset($resultArr['access_token']) || empty($resultArr['access_token'])){
		$callback = array('errorMsg'=>'token获取错误','errorNo'=>'101');
		exit(json_encode($callback));
	}

	# 获取百度图文识别后的返回
	$token = $resultArr['access_token'];
	$url = 'https://aip.baidubce.com/rest/2.0/ocr/v1/webimage?access_token=';
	$wordRes = getBdPicToWord($url,$token,$picFile);
	$wordResArr = json_decode($wordRes,true);
	// if(empty($wordResArr['words_result_num'])){
	// 	$url = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general_basic?access_token=';
	// 	$wordRes = getBdPicToWord($url,$token,$picFile);
	// 	$wordResArr = json_decode($wordRes,true);
	// }

	if(empty($wordResArr['words_result_num'])){
		$callback = array('errorMsg'=>'未识别出文字','errorNo'=>'109');
		exit(json_encode($callback));
	}


	$callback = array('errorMsg'=>'','errorNo'=>'0','seccuss'=>$wordResArr);
	exit(json_encode($callback));
}

function getBdAccessToken(){
	
	global $picToWordAppkey,$picToWordSecretkey;

	$url = 'https://aip.baidubce.com/oauth/2.0/token';
    $post_data['grant_type'] = 'client_credentials';
    $post_data['client_id'] = $picToWordAppkey;
    $post_data['client_secret'] = $picToWordSecretkey;
    $o = "";
    foreach ( $post_data as $k => $v ) 
    {
    	$o.= "$k=" . urlencode( $v ). "&" ;
    }
    $post_data = substr($o,0,-1);
    $res = request($url, $post_data);

    return $res;
}


function getBdPicToWord($url,$token,$file){

	$url = $url . $token;
	$img = file_get_contents($file);
	$img = base64_encode($img);
	$bodys = array(
	    'image' => $img
	);
	$res = request($url, $bodys);

	return $res;
}


/**
* 发起http post请求(REST API), 并获取REST请求的结果
* @param string $url
* @param string $param
* @return - http response body if succeeds, else false.
*/
function request($url = '', $param = array())
{
	if (empty($url)) {
		return false;
	}

	$postUrl = $url;
	$curlPost = $param;
	// 初始化curl
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $postUrl);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	// 要求结果为字符串且输出到屏幕上
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	// post提交方式
	if(!empty($curlPost)){
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
	}
	// 运行curl
	$data = curl_exec($curl);
	curl_close($curl);

	return $data;
}

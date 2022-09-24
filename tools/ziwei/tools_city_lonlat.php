<?php
/*
* 获取各个城市的经纬度
*/
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
define('S_PATH', dirname(__FILE__));
error_reporting(E_ALL);

include_once S_PATH.'/../class/database.php';
include_once S_PATH.'/../class/MySql.php';  # mysql

$text = "./basicData/longitude_and_latitude.txt";

# 数据库配置
$db_conf = array(
    'host' => $db['fortune']['hostname'],
    'port' => '3306',
    'user' => $db['fortune']['username'],
    'passwd' => $db['fortune']['password'],
    'dbname' => $db['fortune']['database'],
);

# mysql
$mysql = new MMysql($db_conf);


$sql = "SELECT * FROM ziwei_city_district";
$res = $mysql->doSql($sql);
$allCity = array();
foreach($res as $key=>$val){
	$allCity[$val['id']] = $val['district'];
}

$content = file_get_contents($text);
$arr = explode('<br>',$content);

$cityLongLat = array();
foreach($arr as $key=>$val){
	$cont = trim($val);
	if(strpos($cont,'(') !== false){
		preg_match('/(.*):((.*),(.*))/',$cont,$arr);
		$city = str_replace('经纬度','',$arr['1']);
		$city = str_replace('市区','',$city);
		$city = str_replace('市市中心','',$city);
		$cont3 = trim($arr['3'],'(');
		$cont4 = str_replace('</p>','',$arr['4']);
		$cont4 = str_replace(')','',$cont4);
		$cityLongLat[$key]['city'] = $city;
		$cityLongLat[$key]['longitude'] = $cont3;
		$cityLongLat[$key]['latitude'] = $cont4;
	}

	if(strpos($cont,'北纬') !== false && strpos($cont,'东经') !== false){
		preg_match('/(.*) (.*) 北纬(.*) 东经(.*)/',$cont,$arr);
		$city = $arr['2'];
		$longitude = $arr['4'];
		$latitude = $arr['3'];
		$cityLongLat[$key]['city'] = $city;
		$cityLongLat[$key]['longitude'] = $longitude;
		$cityLongLat[$key]['latitude'] = $latitude;
	}
}

# 组合
foreach($allCity as $id=>$district){
	foreach($cityLongLat as $key=>$val){
		if(strpos($val['city'], $district) !== false || strpos($district, $val['city']) !== false){
			var_dump($id);

			$updatesql = 'update ziwei_city_district set `longitude`="'.$val['longitude'].'",`latitude`="'.$val['latitude'].'" where id='.$id;
			$mysql->doSql($updatesql);
		}
	}
}
// echo $content;die;
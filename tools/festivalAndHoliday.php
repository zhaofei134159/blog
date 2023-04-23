<?php
/*
* 获取国家法定节假日
*/
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
define('S_PATH', dirname(__FILE__));
error_reporting(E_ALL);

include_once S_PATH.'/class/database.php';  
include_once S_PATH.'/class/MySql.php';  # mysql
include_once S_PATH.'/conf/core.fun.php';

# 有几个脚本执行
$num = exec("ps aux | grep 'festivalAndHoliday.php' | grep -v grep | wc -l");
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

$dateY = date('Y');
$sql = "SELECT * FROM zf_festival_and_holiday WHERE year='{$dateY}'";
$res = $mysql->doSql($sql);
if(!empty($res)){
    exit('今年已经有节假日数据了，无需抓取！');
}

# 获取每年国务院公布的节假日
$url = 'http://sousuo.gov.cn/s.htm?t=paper&advance=false&n=&codeYear=&codeCode=&searchfield=title&sort=&q=%E8%8A%82%E5%81%87%E6%97%A5';
$html = httpRequest($url);
preg_match_all('/<li class="res-list"([\s\S]*?)<\/li>/s',$html,$pHandel);

$holidayData = array();
foreach($pHandel[0] as $key=>$val){
	$newstr = trim(str_replace(array("\r","\n"),"",strip_tags($val)));
	preg_match('/国务院办公厅关于(.*)年部分节假日安排的通知(.*)/is', $newstr, $matches);
	preg_match('/<a href="(.*)" target="_blank">/', $val, $matches2);

	# 判断如果year大于等于当前年 且链接不为空 则抓取详情数据
	if (!empty($matches['1']) && $matches['1']>=date('Y') && !empty($matches2['1'])) {
		echo $matches2['1'];
		$detail = httpRequest($matches2['1']);
		preg_match_all('/<p([\s\S]*?)<\/p>/s',$detail,$detailHandel);
		foreach($detailHandel[0] as $key=>&$value){
			if(preg_match('/(共[1-9]天)/s',$value,$tempHandel)==1){
				$value = strip_tags($value);
				$value = explode("。",$value);
				$holidayName = explode("、",explode("：",$value[0])[0])[1];
				// $start = preg_replace("/(年|月)/", "-",explode("日至",explode("、",explode("：",$value[0])[1])[0])[0]);
				$start = preg_replace("/(年|月)/", "-",explode("日至",explode("日放假",explode("、",explode("：",$value[0])[1])[0])[0])[0]);
				if(strlen($start)<7){
					$start = date("Y")."-".$start;
				}
				$start = strtotime($start);
				$length = (int)preg_replace("/(共|天)/","",$tempHandel[0]);
				for($i=0; $i<$length; $i++){
					$item=[];
					$item["holidayName"] = $holidayName;
					$item["type"] = "休假";
					$item["date"] = date("Y-m-d",$start+$i*86400+1);
					$holidayData[] = $item;
				}
				if(count($value)==3){
					$value[1] = explode("、",$value[1]);
					foreach($value[1] as $ke=>&$val){
						$val = date("Y")."-".explode("日（",str_replace("月", "-",$val))[0];
						$date = date('Y-m-d', strtotime($val));

						$item = [];
						$item["holidayName"] = $holidayName."补班";
						$item["type"] = "正常上班";
						$item["date"] = $date;
						$holidayData[] = $item;
					}
				}
			}else{
				// echo 2;
				// var_dump($value);
			}
		}
	}
}

if (!empty($holidayData)) {
	foreach($holidayData as $key=>$val){
		$insert = array();
		$insert['year'] = date('Y', $val['date']);
		$insert['date'] = $val['date'];
		$insert['type'] = ($val['type']=='休假')?2:1;
		$insert['holiday'] = $val['holidayName'];
		$mysql->insert('zf_festival_and_holiday',$insert);
	}
}
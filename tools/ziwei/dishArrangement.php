<?php
/*
* 紫微斗数排盘
* 1. 公元纪年对应的天干（公元纪年的尾数）
* 4 5 6 7 8 9 0 1 2 3
* 甲乙丙丁戊己庚辛壬癸
*/
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
define('S_PATH', dirname(__FILE__));
error_reporting(E_ALL);

include_once S_PATH.'../class/database.php';
include_once S_PATH.'../class/MySql.php';  # mysql

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

# 确定农历的生辰
$date = '2022-07-02 05:30';
$lunarTime = lunar_calendar_time($date); 
var_dump($lunarTime);

# 计算农历时间
/*
* 中国
* 北京时间 != 真太阳时
* 需要换算：以东经120°为基准，每减少1°减去4分钟，每增加1°增加4分钟
*/
function lunar_calendar_time ($date) {
	
}


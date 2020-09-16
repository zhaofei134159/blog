<?php
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
define('S_PATH', dirname(__FILE__));
error_reporting(E_ALL);

include_once S_PATH.'/class/database.php';  
include_once S_PATH.'/class/WebSocket.php'; # socket
include_once S_PATH.'/class/MySql.php';  # mysql
include_once S_PATH.'/class/phpanalysis/phpanalysis.class.php'; # php分词

# 有几个脚本执行
$num = exec("ps aux | grep 'Webserver.php' | grep -v grep | wc -l");
if($num>1){
  exit(date('Y-m-d').' 已经有脚本了');
}


# 敏感词
$sensitiveWords = array('妈的','sb','我靠','傻逼','md','cnm','草你妈','nmb','你妈逼');


# 数据库配置
$db_conf = array(
    'host' => $db['default']['hostname'],
    'port' => '3306',
    'user' => $db['default']['username'],
    'passwd' => $db['default']['password'],
    'dbname' => $db['default']['database'],
);


# socket配置
$addr = '104.243.18.161';
$port = '8000';
$callback = 'WSevent';//回调函数的函数名
$log = true;

# mysql
$mysql = new MMysql($db_conf);


# 分词
PhpAnalysis::$loadInit = false;
$participle = new PhpAnalysis('utf-8', 'utf-8', true);


# socket
$socket = new WebSocket($addr,$port,$callback,$log);
$socket->start();


function WSevent($type,$usermsg){
    global $socket;
    if('in'==$type){
      $socket->log('客户进入id:'.$usermsg['userid']);

    }elseif('out'==$type){
      $socket->log('客户退出id:'.$usermsg['userid']);

    }elseif('msg'==$type){
      $socket->log($usermsg['userid'].'消息:'.$usermsg['msg']);
    }
}

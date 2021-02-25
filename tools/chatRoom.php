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
$num = exec("ps aux | grep 'chatRoom.php' | grep -v grep | wc -l");
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
      error_log(date('Y-m-d H:i:s')."\t 客户进入id:".$usermsg['userid'].PHP_EOL,3,S_PATH."/log/chatRoomLog.log");

    }elseif('out'==$type){
      $socket->log('客户退出id:'.$usermsg['userid']);
      error_log(date('Y-m-d H:i:s')."\t 客户退出id:".$usermsg['userid'].PHP_EOL,3,S_PATH."/log/chatRoomLog.log");

    }elseif('msg'==$type){
      $socket->log($usermsg['userid'].'消息:'.$usermsg['msg']);
      error_log(date('Y-m-d H:i:s')."\t ".$usermsg['userid']."消息:".$usermsg['msg'].PHP_EOL,3,S_PATH."/log/chatRoomLog.log");
        
      # 存放数据库
      message_analysis($usermsg['userid'],$usermsg['msg'],$type,$usermsg['sign']);
    }

}


# 语言解析
function message_analysis($userid,$usermsg,$type,$sign){
  global $socket;
  global $mysql;
  # 返回数据
  $resultData = array();
  if($type=='msg'){
      if(empty($usermsg)||$usermsg==false){
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid." 发送数据为空".PHP_EOL,3,S_PATH."/log/chatRoomLog.log");
          return '0';
      }
      if($usermsg=='ping'){
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  心跳验证".PHP_EOL,3,S_PATH."/log/chatRoomLog.log");
          $resultData['flog'] = 0;
          $resultData['msg'] =  '心跳验证';
          $resultData['result'] = array();
          $socket->allweite(json_encode($resultData));
          return '0';
      }
      
      # 
      $usermsgJson = json_decode($usermsg,true);

      if(empty($usermsgJson)){
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid." 真实用户".$usermsgJson['userId']." json数据为空".PHP_EOL,3,S_PATH."/log/chatRoomLog.log");
          $resultData['flog'] = 1;
          $resultData['msg'] = 'json数据为空';
          $resultData['result'] = array();
          $socket->allweite(json_encode($resultData));
          return '1';
      }
      
      # 用户信息
      $userinfo = getUserInfo($usermsgJson['userId']);
      if(empty($userinfo)){
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." 用户信息为空".PHP_EOL,3,S_PATH."/log/chatRoomLog.log");
          $resultData['flog'] = 2;
          $resultData['msg'] = '用户信息为空';
          $resultData['result'] = array();
          $socket->allweite(json_encode($resultData));
          return '2';
      }

      # 退出
      if($usermsgJson['type']=='out'){
          $socket->log('客户退出id:'.$userid);

          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid." 真实用户".$usermsgJson['userId']." 退出".PHP_EOL,3,S_PATH."/log/chatRoomLog.log");
          $resultData['flog'] = -1;
          $resultData['msg'] = '退出';
          $resultData['result'] = array();
          $socket->allweite(json_encode($resultData));

          $socket->close($sign);
          return '1';
      }

      /*


      # 是否有交流关联记录 若无 则新增
      $relationId = userRelation($userinfo['id'],$usermsgJson['toUserId']);
      if(empty($relationId)){
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." 交流关联记录错误".PHP_EOL,3,S_PATH."/log/chatRoomLog.log");
          $resultData['flog'] = 3;
          $resultData['msg'] = '交流记录错误';
          $resultData['result'] = array();
          $socket->allweite(json_encode($resultData));
          return '3';
      }
      error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." 聊天记录返回 relationId：".$relationId.PHP_EOL,3,S_PATH."/log/chatRoomLog.log");

      # 交流记录
      $messageLog = userMessage($relationId,$userinfo['id'],$usermsgJson['toUserId'],$usermsgJson['content'],$usermsgJson['type']);

      if(empty($messageLog)){
        error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." messageLog为空".PHP_EOL,3,S_PATH."/log/chatRoomLog.log");
        $resultData['flog'] = 4;
        $resultData['msg'] = '无聊天数据';
        $resultData['result'] = array();
        $socket->allweite(json_encode($resultData));
        return '4';
      }


      if($usermsgJson['type']=='record'){
        foreach($messageLog as $key=>$val){
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." messageLog: ".json_encode($val).PHP_EOL,3,S_PATH."/log/chatRoomLog.log");
          if($val['msg_type']=='work'){
              $val['content'] = json_decode($val['content'],'true');
          }
          $resultData['flog'] = 5;
          $resultData['msg'] = '接收数据返回';
          $resultData['result'] = $val;
          $socket->allweite(json_encode($resultData));
        }
        return '5';
      }else if($usermsgJson['toUserId']==84){
        foreach($messageLog as $key=>$val){
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." messageLog: ".json_encode($val).PHP_EOL,3,S_PATH."/log/chatRoomLog.log");
          if($val['msg_type']=='work'){
              $val['content'] = json_decode($val['content'],'true');
          }
          $resultData['flog'] = 5;
          $resultData['msg'] = '接收数据返回';
          $resultData['result'] = $val;
          $socket->allweite(json_encode($resultData));
        }
        return '5';
      }else{
        error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." messageLog: ".json_encode($messageLog).PHP_EOL,3,S_PATH."/log/chatRoomLog.log");
        $resultData['flog'] = 5;
        $resultData['msg'] = '接收数据返回';
        $resultData['result'] = $messageLog;
        $socket->allweite(json_encode($resultData));
        return '5';
      }
      */
  } 
  
}
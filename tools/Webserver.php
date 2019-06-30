<?php
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");

include './class/WebSocket.php';
include './class/MySql.php';

$db_conf = array(
    'host' => '127.0.0.1',
    'port' => '3306',
    'user' => 'root',
    'passwd' => 'zhaofei',
    'dbname' => 'blog',
);
$addr = '104.243.18.161';
$port = '8282';
$callback = 'WSevent';//回调函数的函数名
$log = true;

# mysql
$mysql = new MMysql($db_conf);

# socket
$socket = new WebSocket($addr,$port,$callback,$log);
$socket->start();


function WSevent($type,$usermsg){
    global $socket;
    if('in'==$type){
      $socket->log('客户进入id:'.$usermsg['userid']);
      error_log(date('Y-m-d H:i:s')."\t  客户进入id:".$usermsg['userid'].PHP_EOL,3,"./log/webServer.log");

    }elseif('out'==$type){
      $socket->log('客户退出id:'.$usermsg['userid']);
      error_log(date('Y-m-d H:i:s')."\t  客户退出id:".$usermsg['userid'].PHP_EOL,3,"./log/webServer.log");

    }elseif('msg'==$type){
      $socket->log($usermsg['userid'].'消息:'.$usermsg['msg']);
      error_log(date('Y-m-d H:i:s')."\t ".$usermsg['userid']." 消息: ".$usermsg['msg'].PHP_EOL,3,"./log/webServer.log");

      # 存放数据库
      message_analysis($usermsg['userid'],$usermsg['msg'],$type);

    }
}


# 语言解析
function message_analysis($userid,$usermsg,$type){
  global $socket;
  global $mysql;
  # 返回数据
  $resultData = array();


  if($type=='msg'){
      if(empty($usermsg)||$usermsg==false){
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid." 发送数据为空".PHP_EOL,3,"./log/webServer.log");
          return '0';
      }

      if($usermsg=='ping'){
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  心跳验证".PHP_EOL,3,"./log/webServer.log");
          return '0';
      }

      $usermsgJson = json_decode($usermsg,true);

      if(empty($usermsgJson)){
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid." 真实用户".$usermsgJson['userId']." json数据为空".PHP_EOL,3,"./log/webServer.log");
          $resultData['flog'] = 1;
          $resultData['msg'] = 'json数据为空';
          $resultData['result'] = array();
          $socket->allweite(json_encode($resultData));
          return '1';
      }

      # 用户信息
      $userinfo = getUserInfo($usermsgJson['userId']);
      if(empty($userinfo)){
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." 用户信息为空".PHP_EOL,3,"./log/webServer.log");
          $resultData['flog'] = 2;
          $resultData['msg'] = '用户信息为空';
          $resultData['result'] = array();
          $socket->allweite(json_encode($resultData));
          return '2';
      }

      # 是否有交流关联记录 若无 则新增
      $relationId = userRelation($userinfo['id'],$usermsgJson['toUserId']);
      if(empty($relationId)){
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." 交流关联记录错误".PHP_EOL,3,"./log/webServer.log");
          $resultData['flog'] = 3;
          $resultData['msg'] = '交流记录错误';
          $resultData['result'] = array();
          $socket->allweite(json_encode($resultData));
          return '3';
      }
      error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." 聊天记录返回 relationId：".$relationId.PHP_EOL,3,"./log/webServer.log");

      # 交流记录
      $messageLog = userMessage($relationId,$userinfo['id'],$usermsgJson['toUserId'],$usermsgJson['content'],$usermsgJson['type']);

      if(empty($messageLog)){
        error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." messageLog为空".PHP_EOL,3,"./log/webServer.log");
        $resultData['flog'] = 4;
        $resultData['msg'] = '无聊天数据';
        $resultData['result'] = array();
        $socket->allweite(json_encode($resultData));
        return '4';
      }


      if($usermsgJson['type']=='record'){
        foreach($messageLog as $key=>$val){
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." messageLog: ".json_encode($val).PHP_EOL,3,"./log/webServer.log");
          $resultData['flog'] = 5;
          $resultData['msg'] = '接收数据返回';
          $resultData['result'] = $val;
          $socket->allweite(json_encode($resultData));
        }
        return '5';
      }else{
        error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." messageLog: ".json_encode($messageLog).PHP_EOL,3,"./log/webServer.log");
        $resultData['flog'] = 5;
        $resultData['msg'] = '接收数据返回';
        $resultData['result'] = $messageLog;
        $socket->allweite(json_encode($resultData));
        return '5';
      }
  } 
  

  // $redis = new redis();  
  // $redis->connect('127.0.0.1', 6379);  
  // $chat = array();
  // // !$redis->exists('chat')
  // if($redis->hLen('chat')<=0){
  //   $chat[] = $json;
  // }else{
  //   // $cun = $redis->get('chat');
  //   $cun = $redis->hGetAll('chat');
  //   $socket->log($usermsg['userid'].'消息:'.$usermsg['msg']);
  //   $chat = json_decode($cun,true);
  //   $redis->del('chat');
  //   $chat[] = $json;
  // }
  // $chat = json_encode($chat);  
  // // $redis->set('chat',$chat);
  // $redis->hSet('chat',1,$chat);

  // $socket->allweite($msg);
}


# 获取用户ID
function getUserInfo($openid){
    global $mysql;

    $sql = "SELECT * FROM zf_user WHERE weixin_openid='{$openid}'";
    $res = $mysql->doSql($sql);

    if(empty($res)){
      return array();
    }
    return $res['0'];
}

/*
* 用户交流记录
* 两个用户ID 因为是交流  所以每个人都可能是发送者
* 返回交流ID
*/
function userRelation($userid,$touserid){
    global $mysql;

    $sql = "SELECT * from zf_user_relation where (userid={$userid} or msg_userid={$userid}) and (userid={$touserid} or msg_userid={$touserid})";
    $result = $mysql->doSql($sql);

    if(empty($result)){
      $insert = array();
      $insert['userid'] = $userid;
      $insert['msg_userid'] = $touserid;
      $insert['ctime'] = time();
      $mysql->insert('zf_user_relation',$insert);

      $sql = "SELECT * from zf_user_relation where (userid={$userid} or msg_userid={$userid}) and (userid={$touserid} or msg_userid={$touserid})";
      $result = $mysql->doSql($sql);
    }
   
    $relaId = $result['0']['id'];

    return $relaId;
} 

/*
* 交流信息
* ralaId：交流ID  content：内容  type：类型
* 聊天记录
*/
function userMessage($ralaId,$userid,$touserid,$content,$type){
    global $mysql;

    if(!empty($content)){
      #　图片
      $typeContent = $content;
      if($type=='image'){


      }else if($type=='text'){
        $insert = array();
        $insert['rela_id'] = $ralaId;
        $insert['userid'] = $userid;
        $insert['touserid'] = $touserid;
        $insert['content'] = $typeContent;
        $insert['msg_type'] = $type;
        $insert['msg_time'] = time();
        $mysql->insert('zf_message',$insert);
      }
    }


    if($type=='record'){
      # 查询聊天记录
      $sql = "SELECT m.*, us.headimg AS usHeadimg, us.nikename as usNikename, us.name as usName, tous.headimg AS tousHeadimg, tous.nikename as tousNikename, tous.name as tousName FROM zf_message m LEFT JOIN zf_user us ON m.userid = us.id LEFT JOIN zf_user tous ON m.touserid = tous.idWHERE 1  m.rela_id={$ralaId} and m.msg_status != 2 order by id desc limit 10";
      $result = $mysql->doSql($sql);
      $result = array_reverse($result);
      return $result;
    }else{
      # 查询聊天记录
      // $sql = "SELECT * from zf_message where rela_id={$ralaId} and msg_status!=2 order by id desc limit 1";
      $sql = "SELECT m.*, us.headimg AS usHeadimg, us.nikename as usNikename, us.name as usName, tous.headimg AS tousHeadimg, tous.nikename as tousNikename, tous.name as tousName FROM zf_message m LEFT JOIN zf_user us ON m.userid = us.id LEFT JOIN zf_user tous ON m.touserid = tous.idWHERE 1  m.rela_id={$ralaId} and m.msg_status != 2 order by id desc limit 1";
      $result = $mysql->doSql($sql);
      return $result['0'];
    }

}

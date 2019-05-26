<?php
date_default_timezone_set("PRC");

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


  if($type=='msg'){
      if(empty($usermsg)){
          error_log(date('Y-m-d H:i:s')."\t ".$userid." 消息为空".PHP_EOL,3,"./log/webServer.log");
          return ;
      }
      $usermsgJson = json_decode($usermsg,true);

      $userinfo = getUserInfo($usermsgJson['userId']);
      if(empty($userinfo)){
          error_log(date('Y-m-d H:i:s')."\t ".$userid." 用户信息为空".PHP_EOL,3,"./log/webServer.log");
          return ;
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

  $socket->allweite($msg);
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
 

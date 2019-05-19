<?php
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
    }elseif('out'==$type){
      $socket->log('客户退出id:'.$usermsg['userid']);
    }elseif('msg'==$type){
      $socket->log($usermsg['userid'].'消息:'.$usermsg['msg']);
      roboot($usermsg['userid'],$usermsg['msg']);
      message_analysis($usermsg['userid'],$usermsg['msg'],$type);
    }
}


# 语言解析
function message_analysis($userid,$usermsg,$type){
  global $socket;
  global $mysql;

  $sql = "select * from zf_user_relation";
  $res = $mysql->doSql($sql);
  var_dump($res);die;

  if($type=='in'){

  }else if($type=='msg'){

  }else if($type=='out'){

  }

  $json = json_decode($msg,true);
  // $show = json_encode($json);
  if(empty($msg)){
      return ;
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
 

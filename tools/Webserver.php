<?php
include '/data/html/blog/tools/class/WebSocket.php';
 
$addr = '172.93.37.18';
$port = '8282';
$callback = 'WSevent';//回调函数的函数名
$log = true;


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
    }
}
 
function roboot($sign,$msg){
  global $socket;
  $json = json_decode($msg,true);
  echo $sign;
  var_dump($json);
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
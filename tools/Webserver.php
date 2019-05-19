<?php
include './class/WebSocket.php';
include './class/MY_Model.php';
include './class/Zf_user_model.php';
 
$addr = '104.243.18.161';
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
      message_analysis($usermsg['userid'],$usermsg['msg'],$type);
    }
}
 

# 语言解析
function message_analysis($userid,$usermsg,$type){
  global $socket;
  $this->load->model('Zf_user_model');

  $blogs = $this->Zf_user_model->get_list('is_del=0','*','',20,0);


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
 

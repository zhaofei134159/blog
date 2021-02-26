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

    }elseif('out'==$type){
      $socket->log('客户退出id:'.$usermsg['userid']);

    }elseif('msg'==$type){
      $socket->log($usermsg['userid'].'消息:'.$usermsg['msg']);

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
          return '0';
      }
      if($usermsg=='ping'){
          $resultData['flog'] = 0;
          $resultData['msg'] =  '心跳验证';
          $resultData['result'] = array();
          $socket->allweite(json_encode($resultData));
          return '0';
      }
      # 
      $usermsgJson = json_decode($usermsg,true);

      if(empty($usermsgJson)){
          $resultData['flog'] = 1;
          $resultData['msg'] = 'json数据为空';
          $resultData['result'] = array();
          $socket->allweite(json_encode($resultData));
          return '1';
      }
      
      # 用户信息
      $userinfo = getUserInfo($usermsgJson['userid']);
      if(empty($userinfo)){
          $result = userMessage($userinfo['id'],'','record');
          
          $resultData['flog'] = 2;
          $resultData['msg'] = '用户信息为空';
          $resultData['result'] = $result;
          $socket->allweite(json_encode($resultData));
          return '2';
      }

      # 记录聊天内容内容
      if(!empty($usermsgJson['msg'])){
          $result = array();

          # 进入聊天室
          if($usermsgJson['type']=='start'){
              userMessage($userinfo['id'],'进入聊天室','in');
              $result = userMessage($userinfo['id'],'','record');
          } 
          
          # 退出聊天室
          if($usermsgJson['type']=='end'){
              $result = userMessage($userinfo['id'],'退出聊天室','out');
          }

          # 聊天内容记录
          if($usermsgJson['type']=='msg'){
              $result = userMessage($userinfo['id'],$usermsgJson['msg'],'text');
          }


          $resultData['flog'] = 3;
          $resultData['msg'] = '聊天记录';
          $resultData['result'] = $result;
          $socket->allweite(json_encode($resultData));
      }


  }
}

# 获取用户信息
function getUserInfo($userid){
    global $mysql;

    $sql = "SELECT * FROM zf_user WHERE id='{$userid}'";
    $res = $mysql->doSql($sql);

    if(empty($res)){
      return array();
    }
    return $res['0'];
}


/*
* 交流信息
* content：内容  type：类型
* 聊天记录
*/
function userMessage($userid,$content,$type){
    global $mysql;

    # 存数据
    if(!empty($content)){
        if(in_array($type,array('in','out'))){

          $insert = array();
          $insert['userid'] = $userid;
          $insert['content'] = $content;
          $insert['msg_type'] = $type;
          $insert['msg_time'] = time();
          $mysql->insert('zf_chatroom_message',$insert);

        }else if($type=='text'){
          # 是否存在，敏感词
          $callback = sensitiveWordsSearch($content);
          $setWord = $callback['setWord'];
          $typeContent = $callback['search'];
          
          $insert = array();
          $insert['userid'] = $userid;
          $insert['content'] = $typeContent;
          $insert['msg_type'] = $type;
          $insert['msg_time'] = time();
          $mysql->insert('zf_chatroom_message',$insert);
        }
    }


    # 返回聊天记录
    if($type=='record'){
      # 查询聊天记录
      $result = userMessageList('limit 10');
      $result = array_reverse($result);

      return $result;

    }else{

      # 查询聊天记录
      $result = userMessageList('limit 1');
      $result = array_reverse($result);

      return $result;
    }

}

# 用户消息
function userMessageList($limit){
    global $mysql;

    $sql = "SELECT m.*, us.headimg AS usHeadimg, us.nikename as usNikename, us.name as usName, tous.headimg AS tousHeadimg, tous.nikename as tousNikename, tous.name as tousName FROM zf_chatroom_message m LEFT JOIN zf_user us ON m.userid = us.id LEFT JOIN zf_user tous ON m.touserid = tous.id WHERE 1  and m.msg_status != 2 order by id desc {$limit}";
    $result = $mysql->doSql($sql);

    return $result;
}

/*
* 是否存在敏感词
*/
function sensitiveWordsSearch($search){
    global $sensitiveWords;

    $setWord = 0;
    foreach($sensitiveWords as $key=>$word){
        if(strpos(strtolower($search),$word) !== false){
          $search = str_ireplace($word,'***',$search);
          $setWord = 1;
        }
    }

    $callback = array('setWord'=>$setWord,'search'=>$search);

    return $callback;
}
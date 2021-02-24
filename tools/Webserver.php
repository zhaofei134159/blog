<?php
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
define('S_PATH', dirname(__FILE__));
error_reporting(E_ALL);

include_once S_PATH.'/class/database.php';  
include_once S_PATH.'/class/WebSocket.php'; # socket
include_once S_PATH.'/class/MySql.php';  # mysql
include_once S_PATH.'/class/phpanalysis/phpanalysis.class.php'; # php分词

// error_log(date('Y-m-d H:i:s')." 开始".PHP_EOL,3,S_PATH."/log/webServer.log");

# 有几个脚本执行
$num = exec("ps aux | grep 'Webserver.php' | grep -v grep | wc -l");
if($num>1){
  // error_log(date('Y-m-d H:i:s')." 已经有脚本了".PHP_EOL,3,S_PATH."/log/webServer.log");
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
$port = '8282';
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
      error_log(date('Y-m-d H:i:s')."\t  客户进入id:".$usermsg['userid'].PHP_EOL,3,S_PATH."/log/webServer.log");

    }elseif('out'==$type){
      $socket->log('客户退出id:'.$usermsg['userid']);
      error_log(date('Y-m-d H:i:s')."\t  客户退出id:".$usermsg['userid'].PHP_EOL,3,S_PATH."/log/webServer.log");

    }elseif('msg'==$type){
      $socket->log($usermsg['userid'].'消息:'.$usermsg['msg']);
      error_log(date('Y-m-d H:i:s')."\t ".$usermsg['userid']." 消息: ".$usermsg['msg'].PHP_EOL,3,S_PATH."/log/webServer.log");

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
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid." 发送数据为空".PHP_EOL,3,S_PATH."/log/webServer.log");
          return '0';
      }

      if($usermsg=='ping'){
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  心跳验证".PHP_EOL,3,S_PATH."/log/webServer.log");
          $resultData['flog'] = 0;
          $resultData['msg'] =  '心跳验证';
          $resultData['result'] = array();
          $socket->allweite(json_encode($resultData));
          return '0';
      }

      $usermsgJson = json_decode($usermsg,true);

      # 退出
      if($usermsgJson['type']=='out'){
          $socket->log('客户退出id:'.$userid);

          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid." 真实用户".$usermsgJson['userId']." 退出".PHP_EOL,3,S_PATH."/log/webServer.log");
          $resultData['flog'] = -1;
          $resultData['msg'] = '退出';
          $resultData['result'] = array();
          $socket->allweite(json_encode($resultData));

          $socket->close($sign);
          return '1';
      }
      
      if(empty($usermsgJson)){
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid." 真实用户".$usermsgJson['userId']." json数据为空".PHP_EOL,3,S_PATH."/log/webServer.log");
          $resultData['flog'] = 1;
          $resultData['msg'] = 'json数据为空';
          $resultData['result'] = array();
          $socket->allweite(json_encode($resultData));
          return '1';
      }

      # 用户信息
      $userinfo = getUserInfo($usermsgJson['userId']);
      if(empty($userinfo)){
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." 用户信息为空".PHP_EOL,3,S_PATH."/log/webServer.log");
          $resultData['flog'] = 2;
          $resultData['msg'] = '用户信息为空';
          $resultData['result'] = array();
          $socket->allweite(json_encode($resultData));
          return '2';
      }

      # 是否有交流关联记录 若无 则新增
      $relationId = userRelation($userinfo['id'],$usermsgJson['toUserId']);
      if(empty($relationId)){
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." 交流关联记录错误".PHP_EOL,3,S_PATH."/log/webServer.log");
          $resultData['flog'] = 3;
          $resultData['msg'] = '交流记录错误';
          $resultData['result'] = array();
          $socket->allweite(json_encode($resultData));
          return '3';
      }
      error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." 聊天记录返回 relationId：".$relationId.PHP_EOL,3,S_PATH."/log/webServer.log");

      # 交流记录
      $messageLog = userMessage($relationId,$userinfo['id'],$usermsgJson['toUserId'],$usermsgJson['content'],$usermsgJson['type']);

      if(empty($messageLog)){
        error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." messageLog为空".PHP_EOL,3,S_PATH."/log/webServer.log");
        $resultData['flog'] = 4;
        $resultData['msg'] = '无聊天数据';
        $resultData['result'] = array();
        $socket->allweite(json_encode($resultData));
        return '4';
      }


      if($usermsgJson['type']=='record'){
        foreach($messageLog as $key=>$val){
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." messageLog: ".json_encode($val).PHP_EOL,3,S_PATH."/log/webServer.log");
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
          error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." messageLog: ".json_encode($val).PHP_EOL,3,S_PATH."/log/webServer.log");
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
        error_log(date('Y-m-d H:i:s')."\t 消息用户：".$userid."  真实用户".$usermsgJson['userId']." messageLog: ".json_encode($messageLog).PHP_EOL,3,S_PATH."/log/webServer.log");
        $resultData['flog'] = 5;
        $resultData['msg'] = '接收数据返回';
        $resultData['result'] = $messageLog;
        $socket->allweite(json_encode($resultData));
        return '5';
      }
  } 
  
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

    # 存数据
    if(!empty($content)){
      #　图片
      $typeContent = $content;
      if($type=='image'){

        $insert = array();
        $insert['rela_id'] = $ralaId;
        $insert['userid'] = $userid;
        $insert['touserid'] = $touserid;
        $insert['content'] = $typeContent;
        $insert['msg_type'] = $type;
        $insert['msg_time'] = time();
        $mysql->insert('zf_message',$insert);

      }else if($type=='text'&&$touserid!=84){
        # 是否存在，敏感词
        $callback = sensitiveWordsSearch($search);
        $setWord = $callback['setWord'];
        $typeContent = $callback['search'];
        
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


    # 返回聊天记录
    if($type=='record'){
      # 查询聊天记录
      $result = userMessageList($ralaId,'limit 10');
      $result = array_reverse($result);

      # 如果是和管理员说话  并且之前没有聊天记录
      if($touserid==84&&empty($result)){
        $sql = "SELECT * FROM zf_user where id={$userid} limit 1";
        $zf_user = $mysql->doSql($sql);

        $content = '你好, 亲爱的 '.$zf_user[0]['nikename']."\n 可以输入关键字，来搜索文章。\n 例如: php、mysql、python ";
        $insert = array();
        $insert['rela_id'] = $ralaId;
        $insert['userid'] = 84;
        $insert['touserid'] = $userid;
        $insert['content'] = $content;
        $insert['msg_type'] = 'text';
        $insert['msg_time'] = time();
        $mysql->insert('zf_message',$insert);
        
        $result = userMessageList($ralaId,'limit 1');
      }

      return $result;
    }else if($touserid==84){
      $workNum = 1;
      if($type=='text'){
        # 系统管理员
        # 通过输入的文字 查询文章
        $workNum = UserSearchArticles($typeContent,$ralaId,$userid,$touserid);
      }

      # 查询聊天记录
      $result = userMessageList($ralaId,'limit '.$workNum);
      $result = array_reverse($result);

      return $result;
    }else{
      # 查询聊天记录
      $result = userMessageList($ralaId,'limit 1');
      $result = array_reverse($result);
      return $result['0'];
    }

}

# 用户消息
function userMessageList($ralaId,$limit){
    global $mysql;

    // $sql = "SELECT * from zf_message where rela_id={$ralaId} and msg_status!=2 order by id desc limit 1";
    $sql = "SELECT m.*, us.headimg AS usHeadimg, us.nikename as usNikename, us.name as usName, tous.headimg AS tousHeadimg, tous.nikename as tousNikename, tous.name as tousName FROM zf_message m LEFT JOIN zf_user us ON m.userid = us.id LEFT JOIN zf_user tous ON m.touserid = tous.id WHERE 1 and m.rela_id={$ralaId} and m.msg_status != 2 order by id desc {$limit}";
    $result = $mysql->doSql($sql);

    return $result;
}


/*
* 用户搜索文章
* $search 用户输入的文字
* return 
*/
function UserSearchArticles($search,$ralaId,$userid,$touserid='84'){
    global $mysql;
    global $sensitiveWords;
    global $participle;

    # 是否存在敏感词
    $callback = sensitiveWordsSearch($search);
    $setWord = $callback['setWord'];
    $search = $callback['search'];

    # 存在敏感词
    if($setWord==1){
        $insert = array();
        $insert['rela_id'] = $ralaId;
        $insert['userid'] = $userid;
        $insert['touserid'] = $touserid;
        $insert['content'] = $search;
        $insert['msg_type'] = 'text';
        $insert['msg_time'] = time();
        $mysql->insert('zf_message',$insert);

        $insert = array();
        $insert['rela_id'] = $ralaId;
        $insert['userid'] = $touserid;
        $insert['touserid'] = $userid;
        $insert['content'] = "维护网络文明，人人有则 \n 请注意您的言语。";
        $insert['msg_type'] = 'text';
        $insert['msg_time'] = time();
        $mysql->insert('zf_message',$insert);

        return 2;
    }

    # 先把用户搜索的存库
    $insert = array();
    $insert['rela_id'] = $ralaId;
    $insert['userid'] = $userid;
    $insert['touserid'] = $touserid;
    $insert['content'] = $search;
    $insert['msg_type'] = 'text';
    $insert['msg_time'] = time();
    $mysql->insert('zf_message',$insert);


    # php  分词  查询文章
    //载入词典
    $participle->LoadDict();
        
    //执行分词
    $participle->SetSource($search);
    $participle->differMax = true;
    $participle->unitWord = true;
    $participle->StartAnalysis(true);
    
    $keyword = $participle->GetFinallyKeywords();
    $keywordArr = explode(',',$keyword);

    # 拼接sql
    $work_sql = "SELECT id,title from zf_work  where 1 and blog_id=1 and ";
    $workSqlArr = array();
    foreach($keywordArr as $wd){
      $workSqlArr[] = " (title like '%{$wd}%' or `desc` like '%{$wd}%') ";
    }
    $work_sql .= implode(' or ',$workSqlArr);
    // $work_sql .= " limit 5";
    $works = $mysql->doSql($work_sql);

    if(empty($works)){
      $insert = array();
      $insert['rela_id'] = $ralaId;
      $insert['userid'] = $touserid;
      $insert['touserid'] = $userid;
      $insert['content'] = "未检索到您希望看到的文章\n 我会提醒主人更新的。";
      $insert['msg_type'] = 'text';
      $insert['msg_time'] = time();
      $mysql->insert('zf_message',$insert);

      return 2;
    }

    foreach($works as $work){
      $insert = array();
      $insert['rela_id'] = $ralaId;
      $insert['userid'] = $touserid;
      $insert['touserid'] = $userid;
      $insert['content'] = json_encode($work);
      $insert['msg_type'] = 'work';
      $insert['msg_time'] = time();
      $mysql->insert('zf_message',$insert);
    }

    return count($works)+1;
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

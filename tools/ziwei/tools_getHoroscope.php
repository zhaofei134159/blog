<?php
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
define('S_PATH', dirname(__FILE__));
error_reporting(E_ALL);

include_once S_PATH.'/../class/database.php';  
include_once S_PATH.'/../class/MySql.php';  # mysql
include_once S_PATH.'/../conf/core.fun.php';  # 公共方法
include_once S_PATH.'/../conf/simple_html_dom.php';  # 解析html

$num = exec("ps aux | grep 'getHoroscope.php' | grep -v grep | wc -l");
if($num>1){
  exit(date('Y-m-d').' 已经有脚本了');
}

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

# 抓取命盘
// $date = '1990-1-27';
// $hour = '23';
// $sex = '1'; # 1为男  2为女
// getHoroscope($date, $hour, $sex);
// die;

# 抓取星耀
// getStarlight();
// die;


# 抓取星耀 - 宫
getStarPalace();
die;
function getStarPalace(){
  $url = 'https://mp.weixin.qq.com/s/B9tI3vIJ_CmRAdWYvucd7Q';
  $html = httpRequest($url);
  // echo $html;
  
  $preg = '/id="js_content"(.*)<\/div>/x';
  preg_match_all($preg, $html, $result);
  // var_dump($result);die;
  // echo $result['1']['0'];die;

  if(!empty($result['1']['0'])){
    $preg1 = '/<section(.*)<\/section>/Ux';
    preg_match_all($preg1, $result['1']['0'], $result1);
    foreach($result1['1'] as $key=>$val){
      $preg2 = '/<span(.*);">(.*)<\/span>/Ux';
      preg_match_all($preg2, $val, $result2);
      
      $str = '';
      if(!empty($result2['2']['1']) && strlen($result2['2']['1'])<=3){
        var_dump($result2['2']['1']);
        $str = $result2['2']['1'];
      }else{
        var_dump($result2);
      }
    }

    // var_dump($data);die;
    // starInsert($data);
  }
}

# 星耀
function getStarlight(){
  global $mysql;
  $addr = 'https://www.ziwei.my';


  # 抓取每个星的信息
  $starsql = "SELECT * from ziwei_starlight_info where id in(1,2,3,4,5,6,7,8,9,10,11,12,13,14)";
  $starData = $mysql->doSql($starsql);
  // var_dump($starData);

  foreach($starData as $key=>$val){
    $url = $val['url'];
    $html = httpRequest($url);

    /*
    $preg = '/class="su-service">(.*)<\/div>/x';
    preg_match_all($preg, $html, $result);
    // var_dump($result);die;

    if(!empty($result['1']['0'])){
      $preg1 = '/(.*)">(.*)<\/div>/x';

      $data = array();
      $data['id'] = $val['id'];
      $data['name'] = $val['name'];

      foreach($result['1'] as $rkey=>$rval){
        if(strpos($rval,'名言') !== false){
          preg_match_all($preg1, $rval, $result1);
          $data['known_saying'] = $result1['2']['0']; # 名言
        }
        if(strpos($rval,'外貌') !== false){
          preg_match_all($preg1, $rval, $result2);
          $data['appearance'] = $result2['2']['0']; # 外貌
        }
        if(strpos($rval,'个性') !== false){
          preg_match_all($preg1, $rval, $result3);
          $data['personality'] = $result3['2']['0']; # 个性
        }
        if(strpos($rval,'优点') !== false){
          preg_match_all($preg1, $rval, $result3);
          $data['advantage'] = $result3['2']['0']; # 优点
        }
        if(strpos($rval,'缺点') !== false){
          preg_match_all($preg1, $rval, $result3);
          $data['shortcoming'] = $result3['2']['0']; # 缺点
        }
        if(strpos($rval,'身体象征部位') !== false){
          preg_match_all($preg1, $rval, $result3);
          $data['body_parts'] = $result3['2']['0']; # 身体部位
        }
      }
      // var_dump($data);die;
      starInsert($data);
    }
    */

    /*
    $preg = '/<p>(.*)<\/p>/x';
    preg_match_all($preg, $html, $result);
    // var_dump($result);die;

    if(!empty($result['1']['0'])){
      $preg1 = '/(.*)>：(.*)。/x';

      $data = array();
      $data['id'] = $val['id'];
      $data['name'] = $val['name'];

      foreach($result['1'] as $rkey=>$rval){
        if(strpos($rval,'形体') !== false){
          preg_match_all($preg1, $rval, $result2);
          $data['appearance'] = $result2['2']['0']; # 外貌
        }
        if(strpos($rval,'身体') !== false){
          preg_match_all($preg1, $rval, $result3);
          $data['body_parts'] = $result3['2']['0']; # 身体部位
        }
      }
      // var_dump($data);die;
      starInsert($data);
    }
    */

    $preg = '/<\/strong>，(.*)<\/p>/Umx';
    preg_match_all($preg, $html, $result);
    // var_dump($result);die;

    if(!empty($result['1']['0'])){

      $data = array();
      $data['id'] = $val['id'];
      $data['name'] = $val['name'];
      $data['info'] = $result['1']['0'];
      starInsert($data);
    }

  }



  // $addr = 'https://www.ziwei.my';
  // $url = $addr.'/zi-wei-dou-shu-portfolio/zi-wei-dou-shu-star-type-classification/';
  // $html = httpRequest($url);

  /*
  $preg = '/<li>(.*)<\/li>/';
  preg_match_all($preg, $html, $result);
  // var_dump($result);
  # 甲级星
  foreach($result['1'] as $key=>$val){
    if($key < 5){
      $preg1 = '/<strong>(.*)<\/strong>/x';
      preg_match_all($preg1, $val, $result1);

      $starArr = explode("、",$val);
      foreach($starArr as $skey=>$sval){
        $preg2 = '/<a (.*)href="(.*)">(.*)<\/a>/x';
        preg_match_all($preg2, $sval, $result2);

        $data = array();
        $data['name'] = $result2['3']['0'];
        $data['grade'] = 1;
        $data['galaxy'] = $result1['1']['0'];
        $data['url'] = $addr.$result2['2']['0'];
        // var_dump($data);
        // starInsert($data);
      }
    }
  }
  */

  /*
  $preg = '/<p>(.*)<\/p>/';
  preg_match_all($preg, $html, $result);

  # 乙级星
  foreach($result['1'] as $key=>$val){
    if($key >= 8 && $key <= 8){
      $starArr = explode("、",$val);

      foreach($starArr as $skey=>$sval){
        $preg2 = '/<a (.*)href="(.*)">(.*)<\/a>/x';
        preg_match_all($preg2, $sval, $result2);

        $data = array();
        # 7为乙级星
        if($key == 7){
          $data['grade'] = 2;
        }
        # 8为丙级星
        if($key == 8){
          $data['grade'] = 3;
        }
        # 9为丁级星
        if($key == 9){
          $data['grade'] = 4;
        }
        # 10为戊级星
        if($key == 10){
          $data['grade'] = 5;
        }

        $data['name'] = $result2['3']['0'];
        $data['url'] = $addr.$result2['2']['0'];
        var_dump($result2);
        var_dump($data);
        // starInsert($data);
      }
    }
  }
  */
  /*
  # 其他星
  $preg = '/<li>(.*)<\/li>/';
  preg_match_all($preg, $html, $result);
  foreach($result['1'] as $key=>$val){
    if($key>=7 && $key<=18){
      $starArr = explode("、",$val);

      foreach($starArr as $skey=>$sval){
        $preg2 = '/<a (.*)href="(.*)">(.*)<\/a>/x';
        preg_match_all($preg2, $sval, $result2);

        if(!empty($result2[0])){
          $data = array();
          $data['name'] = $result2['3']['0'];
          $data['url'] = $addr.$result2['2']['0'];
          starInsert($data);
        }
      }
    }
  }
  */

}

# 命盘
function getHoroscope($date, $hour, $sex){
  $url = 'https://m.k366.com/cm/pp/ziwei.asp';

  $dataArr = array();
  $dataArr['birthday'] = $date;
  $dataArr['datetype'] = '0';
  $dataArr['datetext'] = '新历:'.$date;
  $dataArr['hour'] = $hour;
  $dataArr['sex'] = $sex;
  $data = http_build_query($dataArr);
  $html = httpRequest($url,$data);

  $dome = str_get_html($html);
  $table = $dome->find("div[class=box-result]", -1)->children;
  $tr = $table[0]->children;

  $gongData = array();
  foreach($tr as $trk=>$trv){
    foreach($trv->children as $tdk=>$tdv){
      if($tdv->tag != 'td'){
        continue;
      }
      # 每一个td 都是一个宫
      # 1,3,4行全部为宫, 2行0,2为宫
      if($trk == 2 && $tdk == 1){
        continue;
      }

      # 宫
      $gongInfo = $tdv->children[0]->children;
      foreach($gongInfo as $gkey=>$gval){
        foreach($gval->children as $gk=>$gv){
          if($gv->tag == '<br>'){
            continue;
          }

          # 是否包含 禄、科等一些字
          if(strpos($gv->innertext,'<br>') !== false){
            $gongData = $gv->innertext;
          }
          die;
        }
      }
    }
  }
  var_dump($tr);
}

# 星耀insert
function starInsert($data){
  global $mysql;

  $tagsql = "SELECT * FROM ziwei_starlight_info where name='{$data['name']}' limit 1";
  $tagData = $mysql->doSql($tagsql);
  if(empty($tagData)){
    $mysql->insert('ziwei_starlight_info',$data);
  }else if(!empty($data['id'])){
    $id = $data['id'];
    unset($data['id']);
    unset($data['name']);

    $mysql->updateSql('ziwei_starlight_info',$data,'id='.$id);
  }
}

// $status = get_used_status();

// $insert = array();
// $insert['ip'] = $db_conf['host'];
// $insert['cpu_usage'] = $status['cpu_usage'];
// $insert['mem_usage'] = $status['mem_usage'];
// $insert['hd_avail'] = $status['hd_avail'];
// $insert['hd_usage'] = $status['hd_usage'];
// $insert['tast_running'] = $status['tast_running'];
// $insert['detection_time'] = $status['detection_time'];
// $insert['createtime'] = date('Y-m-d H:i:s');
// $mysql->insert('server_info_desc',$insert);

// unset($status);
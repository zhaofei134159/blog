<?php
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
define('S_PATH', dirname(__FILE__));
error_reporting(E_ALL);

include S_PATH.'/class/MySql.php';  # mysql


$num = exec("ps aux | grep 'serverInfoDesc.php' | grep -v grep | wc -l");
if($num>1){
  exit(date('Y-m-d').' 已经有脚本了');
}


# 数据库配置
$db_conf = array(
    'host' => '104.243.18.161',
    'port' => '3306',
    'user' => 'root',
    'passwd' => 'zhaofei',
    'dbname' => 'blog',
);

# mysql
$mysql = new MMysql($db_conf);
  
  
function get_used_status(){
  $fp = popen('top -b -n 2 | grep -E "^(%Cpu|KiB Mem|Tasks)"',"r");//获取某一时刻系统cpu和内存使用情况
  $rs = "";
  while(!feof($fp)){
    $rs .= fread($fp,1024);
  }
  pclose($fp);

  $sys_info = explode("\n",$rs);
  $cpu_info = explode(",",$sys_info[4]);  //CPU占有量  数组
  $tast_info = explode(",",$sys_info[3]);//进程 数组
  $mem_info = explode(",",$sys_info[5]); //内存占有量 数组

  //正在运行的进程数
  $tast_running = trim(trim($tast_info[1],'running'));
   
  //CPU占有量
  $cpu_usage = trim(trim($cpu_info[0],'%Cpu(s): '),'%us');  //百分比
   

  //内存占有量
  $mem_total = trim(trim($mem_info[0],'KiB Mem: '),' total'); 
  $mem_used = trim($mem_info[1],' used');
  $mem_usage = 0;
  if($mem_total!=0){
    $mem_usage = round(100*intval($mem_used)/intval($mem_total),2);  //百分比
  }
   
   
  $fp = popen('df -lh | grep -E "^(/)"',"r");
  $rs = fread($fp,1024);
  pclose($fp);

  $rs = preg_replace("/\s{2,}/",' ',$rs);  //把多个空格换成 “_”
  $hd = explode(" ",$rs);
  $hd_avail = trim($hd[3],'G'); //磁盘可用空间大小 单位G
  $hd_usage = trim($hd[4],'%'); //挂载点 百分比
  //print_r($hd);
     
   
  //检测时间
  $fp = popen("date +'%Y-%m-%d %H:%M'","r");
  $rs = fread($fp,1024);
  pclose($fp);
  $detection_time = trim($rs);
   
  return array('cpu_usage'=>$cpu_usage,'mem_usage'=>$mem_usage,'hd_avail'=>$hd_avail,'hd_usage'=>$hd_usage,'tast_running'=>$tast_running,'detection_time'=>$detection_time);
}

$status = get_used_status();

$insert = array();
$insert['ip'] = $db_conf['host'];
$insert['cpu_usage'] = $status['cpu_usage'];
$insert['mem_usage'] = $status['mem_usage'];
$insert['hd_avail'] = $status['hd_avail'];
$insert['hd_usage'] = $status['hd_usage'];
$insert['tast_running'] = $status['tast_running'];
$insert['detection_time'] = $status['detection_time'];
$insert['createtime'] = date('Y-m-d H:i:s');
$mysql->insert('server_info_desc',$insert);

unset($status);

?>

<?php
include S_PATH.'/class/MySql.php';  # mysql

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
  $fp = popen('top -b -n 2 | grep -E "^(Cpu|Mem|Tasks)"',"r");//获取某一时刻系统cpu和内存使用情况
  $rs = "";
  while(!feof($fp)){
    $rs .= fread($fp,1024);
  }
  pclose($fp);

  $sys_info = explode("\n",$rs);
  $tast_info = explode(",",$sys_info[3]);//进程 数组
  $cpu_info = explode(",",$sys_info[4]);  //CPU占有量  数组
  $mem_info = explode(",",$sys_info[5]); //内存占有量 数组
  //正在运行的进程数
  $tast_running = trim(trim($tast_info[1],'running'));
   
  //CPU占有量
  $cpu_usage = trim(trim($cpu_info[0],'Cpu(s): '),'%us');  //百分比
   

  //内存占有量
  $mem_total = trim(trim($mem_info[0],'Mem: '),'k total'); 
  $mem_used = trim($mem_info[1],'k used');
  $mem_usage = round(100*intval($mem_used)/intval($mem_total),2);  //百分比
   
   
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
  
 //echo date("Y-m-d H:i:s",time())."<br>";
  
 $status = get_used_status();
 var_dump($status);
  
 $sql = "insert into performance(ip,cpu_usage,mem_usage,hd_avail,hd_usage,tast_running,detection_time) ";
 $sql .= " value('".MONITORED_IP."','".$status['cpu_usage']."','".$status['mem_usage']."','".$status['hd_avail']."','".$status['hd_usage']."','".$status['tast_running']."','".$status['detection_time']."')";
 $query = mysql_query($sql) or die("SQL 语句执行失败!");
 unset($status);
  
 //echo date("Y-m-d H:i:s",time())."<br>";
  
?>

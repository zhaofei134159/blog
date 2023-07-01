<?php
/*
* 公共方法
* 验证参数是否正确
*/
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
ini_set("display_errors", "On");//打开错误提示
ini_set("error_reporting",E_ALL);//显示所有错误

include_once './../login.php';
include_once S_PATH.'/tools/class/database.php';
include_once S_PATH.'/tools/class/MySql.php';  # mysql
include_once S_PATH.'/tools/holiday/conf/day.fun.php';

# 数据库配置
$db_conf = array(
    'host' => $db['default']['hostname'],
    'port' => '3306',
    'user' => $db['default']['username'],
    'passwd' => $db['default']['password'],
    'dbname' => $db['default']['database'],
);

# mysql
class common{
	public $dbConf = [];
	public $mysqlLink = [];

	public function __construct(){
		global $db_conf;
		$this->dbConf = $db_conf;
		$this->DB_link();
	}

	public function DB_link(){
		try{
			$this->mysqlLink = new MMysql($this->dbConf);
		}catch (Exception $e){
			$this->mysqlLink->close();
			$this->mysqlLink = new MMysql($this->dbConf);
		}
	}
}
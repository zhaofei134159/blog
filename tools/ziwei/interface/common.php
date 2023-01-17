<?php
/*
* 公共方法
* 验证参数是否正确
*/
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
define('S_PATH', dirname(__FILE__));
error_reporting(E_ALL);

include_once S_PATH.'/../../class/database.php';
include_once S_PATH.'/../../class/MySql.php';  # mysql

# 数据库配置
$db_conf = array(
    'host' => $db['fortune']['hostname'],
    'port' => '3306',
    'user' => $db['fortune']['username'],
    'passwd' => $db['fortune']['password'],
    'dbname' => $db['fortune']['database'],
);

# mysql
class common{
	public $dbConf = [];
	public $mysqlLink = [];

	public function __construct(){
		global $db_conf;
		$this->dbConf = $db_conf;
		// $this->DB_link();
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
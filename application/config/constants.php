<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */



define('WEB_SITE',		'http://agentmanage.doxue.com');
define('WEB_SITE_NAME',		'都学网-代理商管理平台');
define('WEB_SITE_DESC',		'都学网-代理商管理平台');

define('COPYRIGHT',		'都学网'); //版权
define('TEAM',		'都学网');   //支持团队
define('COMPANY',		'都学网');  //公司
define('SUPPORT_URL',		'http://www.doxue.com/');  //技术支持
define('OFFICIAL_URL',		'http://www.doxue.com/'); //官网

define('AGENT_MAX_DISCOUNT',		0.78); //最高优惠折扣
define('AGENT_MIN_DISCOUNT',		0.99);   //最低优惠折扣起点

define('MASTER_RESOURCE_PATH',		'/resource/master/');
define('CFG_USER_REG_ACTIVE',		'1');                                                      //用户注册是否开启邮件激活验证 1 开启 0 关闭
define('CFG_ORDER_OPERATE_SENEMAIL',		'0');                                              //操作订单是否发送邮件 1 发送 0 不发送
define('PRO_ROOT_PATH', str_replace("\\","/",dirname(dirname(dirname(__FILE__)))).'/');    //项目的根目录
define('API_PATH', PRO_ROOT_PATH.'api/');                                                  //应用程序接口路径
define('CONTROLLERS_PATH', PRO_ROOT_PATH.'application/controllers/');                      //控制器路径




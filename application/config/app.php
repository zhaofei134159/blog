<?php
	//smtp的配置
  	$config['smtpserver'] = 'smtp.163.com';//SMTP服务器
	$config['smtpserverport'] = 25;//SMTP服务器端口
	$config['smtpusermail'] = "blogfamily@163.com";//SMTP服务器的用户邮箱
	$config['smtpuser'] = "blogfamily@163.com";//SMTP服务器的用户帐号
	$config['smtppass'] = "zf134159";//SMTP服务器的用户密码
	// $config['smtppass'] = "zhaofei134159";//SMTP服务器的用户密码
	$config['mailtype'] = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件

	//微博登陆
	$config['weibo_appkey'] = "592824770";
	$config['weibo_appsecret'] = "171f27008870d14ef169376bea74c74c";
	$config['weibo_login'] = "http://blog.myfeiyou.com/home/login/weibo_web";

	//微博登陆
	$config['qq_appkey'] = "101390259";
	$config['qq_appsecret'] = "c166d5355d23ccd0f30e55529ae411a0";
	$config['qq_login'] = "http://blog.myfeiyou.com/home/login/qq_web";

	//github登录
	$config['cliend_id'] = '11b1110e779590c616fe';
	$config['cliend_secret'] = 'ea9e3db091199805a5ea527e8e72368276a2607c';
	$config['cliend_login'] = 'http://blog.myfeiyou.com/home/login/github_web';

	//百度
	# 图文识别的appKey
	$config['picToWordAppId'] = '11521585';
	$config['picToWordAppkey'] = '9PZah23T4Yaa1pePDzCdCzwR';
	$config['picToWordSecretkey'] = 'ehvwNTa1Y3VbTjXEEfbEF57eeRX2s2uj';

	# 语音处理
	$config['voiceAppId'] = '20349172';
	$config['voiceAppkey'] = 'GY3XkTZKNwElpcTknlWUSo0A';
	$config['voiceSecretkey'] = 'N51MrcfuKMrhGhF3F9Du8EgMt4GZgmdn';

	//腾讯
	$config['SecretId'] = 'AKIDC8nAI83YBW2zfWuIxEExOYREuHOL6k26 ';
	$config['SecretKey'] = 'A4kBLG6fx81kfJirjJgmgMltjIbqpeCS';


	//代理的导航
 	$config['user_type'] = array(
 		2=>'博主',
 		3=>'游客',
 		4=>'微信小程序',
  	);




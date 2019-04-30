<?php
	//smtp的配置
  	$config['smtpserver'] = 'smtp.163.com';//SMTP服务器
	$config['smtpserverport'] = 25;//SMTP服务器端口
	$config['smtpusermail'] = "blogfamily@163.com";//SMTP服务器的用户邮箱
	$config['smtpuser'] = "blogfamily@163.com";//SMTP服务器的用户帐号
	// $config['smtppass'] = "zf134159";//SMTP服务器的用户密码
	$config['smtppass'] = "zhaofei134159";//SMTP服务器的用户密码
	$config['mailtype'] = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件

	//微博登陆
	$config['weibo_appkey'] = "592824770";
	$config['weibo_appsecret'] = "171f27008870d14ef169376bea74c74c";
	$config['weibo_login'] = "http://blog.myfeiyou.com/home/login/weibo_web";

	//微博登陆
	$config['qq_appkey'] = "101390259";
	$config['qq_appsecret'] = "c166d5355d23ccd0f30e55529ae411a0";
	$config['qq_login'] = "http://blog.myfeiyou.com/home/login/qq_web";

	//代理的导航
 	$config['user_type'] = array(
 		2=>'博主',
 		3=>'游客',
  	);




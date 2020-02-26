<?php 

echo '开始时间:'.date('H:i:s',time());
//进程数
$work_number=6;
 
//
$worker=[];
 
//模拟地址
$curl=[
	'https://blog.csdn.net/feiwutudou',
	'https://wiki.swoole.com/wiki/page/215.html',
	'http://fanyi.baidu.com/?aldtype=16047#en/zh/manager',
	'http://wanguo.net/Salecar/index.html',
	'http://o.ngking.com/themes/mskin/login/login.jsp',
	'https://blog.csdn.net/marksinoberg/article/details/77816991'
];
 
 
//创建进程
for ($i=0; $i < $work_number; $i++) { 
	//创建多线程
	$pro=new swoole_process(function(swoole_process $work) use($i,$curl){
		$work->write($curl[$i].'开始');
		//获取html文件
		$content=curldeta($curl[$i]);
		//写入管道
		$work->write($curl[$i].'结束');
	},true);
	$pro_id=$pro->start();
	$worker[$pro_id]=$pro;
}
//读取管道内容
foreach ($worker as $v) {
 	var_dump($v->read());
}
 
//模拟爬虫
function curldeta($curl_arr)
{	//file_get_contents
	var_dump($curl_arr."\t 222");
	sleep(2);
	var_dump($curl_arr."\t 333");
}
 
//进程回收
swoole_process::wait();
 
echo '结束时间:'.date('H:i:s',time());
 
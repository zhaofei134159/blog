<?php
/*
* 中文字符分割
*/
function mbStrSplit ($string, $len=1) {
	$start = 0;
	$strlen = mb_strlen($string);
	while ($strlen) {
		$array[] = mb_substr($string,$start,$len,"utf8");
		$string = mb_substr($string, $len, $strlen,"utf8");
		$strlen = mb_strlen($string);
	}
	return $array;
}

// 公共json 输出方法
function outputJson ($data, $type = true) {
 	// 设置响应头为JSON类型  
    header('Content-Type: application/json');  
    // 将数据编码为JSON字符串  
    $jsonData = json_encode($data);  

    if($type){
    	exit($jsonData);
    }

    // 输出JSON数据  
    return $jsonData;  
}
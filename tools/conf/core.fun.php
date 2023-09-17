<?php 
# curl
function httpRequest($url, $post_data='', $header=0, $timeout=30)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, $header);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	//post
	if($post_data)
	{
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	}
	$output = curl_exec($ch);
	curl_close($ch);

	return $output;
}

# 时间戳 毫秒级
function getMicrotime(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

# 保留中英文字符
function match_chinese($chars, $encoding='utf8')
{
    $pattern =($encoding=='utf8')?'/[\x{4e00}-\x{9fa5}a-zA-Z0-9]/u':'/[\x80-\xFF]/';//如需调整过滤内容可修改这行
    preg_match_all($pattern,$chars,$result);
    $temp =join('',$result[0]);
    return $temp;
}
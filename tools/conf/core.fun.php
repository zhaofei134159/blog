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


/**
 * 导出数据到CSV文件
 * @param array $data  数据
 * @param array $title_arr 标题
 * @param string $file_name CSV文件名
 */
function export_csv(&$data, $title_arr, $file_name = 'csv',$extMod = '') {
    if($extMod == ''){
        $file_name = urlencode($file_name).'_'.sgmdate('Y-m-dHis');
    }
   
    ini_set("max_execution_time", "3600");
    $csv_data = '';
    /** 标题 */
    $nums = count($title_arr);
    for ($i = 0; $i < $nums - 1; ++$i) {
        $csv_data .= '"' . iconv('utf-8','GBK',$title_arr[$i]) . '",';
    }
    if ($nums > 0) {
        $csv_data .= '"' . iconv('utf-8','GBK',$title_arr[$nums - 1]) . "\"\r\n";
    }

    foreach ($data as $k => $row) {
        $row_values = array_values($row);
        for ($i = 0; $i < $nums - 1; ++$i) {    
            $row_values[$i] = str_replace("\"", "\"\"", iconv('utf-8','GBK',$row_values[$i]));
            $csv_data .= '"' . $row_values[$i] . '",';
        }

        $csv_data .= '"' . str_replace("\"", "\"\"", iconv('utf-8','GBK',$row_values[$nums - 1])) . "\"\r\n";
        unset($data[$k]);
    }

    // $csv_data = mb_convert_encoding($csv_data, "cp936", "UTF-8");

    $file_name = empty($file_name) ? date('Y-m-d-H-i-s', time()) : $file_name;
    if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE")) { // 解决IE浏览器输出中文名乱码的bug
        $file_name = urlencode($file_name);
        $file_name = str_replace('+', '%20', $file_name);
    }
    $file_name = $file_name . '.csv';
    header("Content-type:text/csv;");
    header("Content-Disposition:attachment;filename=" . $file_name);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    echo $csv_data;
}

<?php 


# 记录日志
function journalRecord($msg,$data,$file){
    list($usec, $sec) = explode(" ", microtime());
    $haomiao = (float)$usec + (float)$sec;

	error_log(date('Y-m-d H:i:s ',time()).'，毫秒级:'.$haomiao.' '.$msg.', data:'.$data.PHP_EOL,3,S_PATH.'/public/public/data/'.$file.'.txt');
}

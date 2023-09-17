<?php
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
define('S_PATH', dirname(__FILE__));
error_reporting(E_ALL);

include_once S_PATH.'/class/database.php';  
include_once S_PATH.'/class/secretkey.php';  
include_once S_PATH.'/class/MySql.php';  # mysql


# 有几个脚本执行
$num = exec("ps aux | grep 'DY_live_data.php' | grep -v grep | wc -l");
if($num>1){
  exit(date('Y-m-d').' 已经有脚本了');
}

# 路径
// $path = '../../python/';

// # 数据库配置
// $db_conf = array(
//     'host' => $db['default']['hostname'],
//     'port' => '3306',
//     'user' => $db['default']['username'],
//     'passwd' => $db['default']['password'],
//     'dbname' => $db['default']['database'],
// );

// # mysql
// $mysql = new MMysql($db_conf);


$date = date('Y-m-d', strtotime('-1 day'));
$listData = DYLiveDataList($date);
echo $DY_live_data_list;die;

function DYLiveDataList($date){
unlink('./DY_live_data_list.txt');
// 1.拉取google数据.
$curlRequest = <<<EOF
curl 'https://union.bytedance.com/ark/api/data/pugna_component/data/v2/faction/anchor_detail/room_list_with_tag?anchorID=88677582480&liveID=1&beginDate={$date}&endDate={$date}&page=1&size=5&msToken=q4JKyPbIVgqzMa48TKZdZwx_F9cqY4JIerz_OoBwZx8Szck95dYp2M00RAGijNIRL2kyqN5V8mUCwWbAflhtGA3KD-pjrq_5zFKOyQzajweEpLmIpJGk&X-Bogus=DFSzswVLKtLdU2t2tPsNr5ppgiu2&_signature=_02B4Z6wo00001LxcPFAAAIDBSSPWzsjJKni8XDjAAEoWjMT5qqvLZLwR-GbS3djUzWM4OtSMbyTZn1Fl9ZZ0UmPPfa0bVTTTiSnOwq3S4F2qoPFoeg.p5esvAoZ97gvW2YuhzWqHXvZXWebo35' \
  -H 'authority: union.bytedance.com' \
  -H 'accept: application/json, text/plain, */*' \
  -H 'accept-language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6' \
  -H 'content-type: application/json;charset=utf-8' \
  -H 'cookie: passport_csrf_token=23be172c4f439eadff8b16514c7b1776; passport_csrf_token_default=23be172c4f439eadff8b16514c7b1776; s_v_web_id=verify_lmn3o6p1_nWZb20Cz_i0YD_4gAD_B19D_Tw6BTKb2tXHY; csrf_session_id=9e28417361f560b3257178824864fc2a; msToken=j5f4xKQU6CDIrEUg5qYiIDJLo_p26FqJhnPpgqUDTfa42vT5U1kf5lv80CAt7dTJ1ASDlHhjOGZRvhvuI6AEb8y3zLjheKDfJPGHou2J4ju9Y3HgkJ-1; ttcid=2edc221727a04119afe8f7bcdaa27bc650; passport_csrf_token_wap_state=16844bfe3gASoVCgoVPZIDRkY2ZiMmYwZWYwZDNkMTU2MDg0YWQ3NjQ5YTllNmZmoU6goVYBoUkAoUQAoUHRBvqhTQChSLN1bmlvbi5ieXRlZGFuY2UuY29toVIColBM0QTcpkFDVElPTqChTNl1aHR0cHM6Ly91bmlvbi5ieXRlZGFuY2UuY29tL29wZW4_Y29kZT1kNmE2YThlM2Q5MDdhMTgzNHJuVEpIYzlzSDFmYTJPa3RwYW4mc3RhdGU9ZmRiMWE4N2YtNmI0Yy00N2U5LTlmNGEtNWE5NGEyOTMyZDIzoVTZIDM4OTViM2U4YjYxZjk2NWY2NDZjYmUzNzk5ZWFlNmIwoVcAoUYAolNBAKFVwg%253D%253D; odin_tt=22280ce9990fd791e3a1f2515c0d121f7bf011693a8d671238810abd82b0351b336ba8bffe6aa1075d2130d01daf7a30fd6ba52613085d30a03861057cf29b5f; n_mh=EPIf2WY6hm-Orm6jOEVhhsjDia8IHuqSLsKFWl1Tf8c; passport_auth_status=e6e654947cd2c291090e20e13db2ad90%2C; passport_auth_status_ss=e6e654947cd2c291090e20e13db2ad90%2C; sid_guard=c392a957727b0a3151f00cd0ba8066e3%7C1694933602%7C2592000%7CTue%2C+17-Oct-2023+06%3A53%3A22+GMT; uid_tt=8a46f6aa451414ac8171c4f2115463f4; uid_tt_ss=8a46f6aa451414ac8171c4f2115463f4; sid_tt=c392a957727b0a3151f00cd0ba8066e3; sessionid=c392a957727b0a3151f00cd0ba8066e3; sessionid_ss=c392a957727b0a3151f00cd0ba8066e3; sid_ucp_v1=1.0.0-KGRjZTE5NDdiMjJjMDZmZjcxOTU4NzUzYmFmOWIzNGY0YmI0YTBkMWIKHgi4y7DYwo3pBRDixJqoBhj6DSAMMI7kipYGOAhAJhoCaGwiIGMzOTJhOTU3NzI3YjBhMzE1MWYwMGNkMGJhODA2NmUz; ssid_ucp_v1=1.0.0-KGRjZTE5NDdiMjJjMDZmZjcxOTU4NzUzYmFmOWIzNGY0YmI0YTBkMWIKHgi4y7DYwo3pBRDixJqoBhj6DSAMMI7kipYGOAhAJhoCaGwiIGMzOTJhOTU3NzI3YjBhMzE1MWYwMGNkMGJhODA2NmUz; store-region=cn-bj; store-region-src=uid; msToken=q4JKyPbIVgqzMa48TKZdZwx_F9cqY4JIerz_OoBwZx8Szck95dYp2M00RAGijNIRL2kyqN5V8mUCwWbAflhtGA3KD-pjrq_5zFKOyQzajweEpLmIpJGk; node_sid=9724e82790919d0eee8ef283eb8af5d9; ttwid=1%7C14elGxHRaK_8zO4Ji78lfNmtqyb1zZxM79XEQ7gnvVM%7C1694933605%7C8953873506c7ed038203a8fdb4adab5e022aed10d7ae5ca52fc2109953718191; tt_scid=8SUzuY.9YRY.6kYT3DEjT-pDJtx9heGSAbUDlk0Sq9aPr.swDrxkfDNs7aseYgvC8505' \
  -H 'referer: https://union.bytedance.com/open/portal/anchor/list/anchorDetail?anchorID=88677582480&appId=3000&tab=live_record' \
  -H 'sec-ch-ua: "Chromium";v="116", "Not)A;Brand";v="24", "Microsoft Edge";v="116"' \
  -H 'sec-ch-ua-mobile: ?0' \
  -H 'sec-ch-ua-platform: "Windows"' \
  -H 'sec-fetch-dest: empty' \
  -H 'sec-fetch-mode: cors' \
  -H 'sec-fetch-site: same-origin' \
  -H 'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36 Edg/116.0.1938.81' \
  -H 'x-appid: 3000' \
  -H 'x-csrf-token: undefined' \
  -H 'x-language: zh' \
  -H 'x-secsdk-csrf-token: 0001000000017e2cf95569a8c0725c6d49e0397f330377a287b76e1a46e97e5f5fdc507551cb17859d21f86162f4' \
  --compressed >> DY_live_data_list.txt
EOF;
  $startTime = getMicrotime();
  exec($curlRequest);
  $endTime_1 = getMicrotime();
  $dataString = file_get_contents('./DY_live_data_list.txt');

  return $dataString;
}
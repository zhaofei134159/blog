<?php
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
define('S_PATH', dirname(__FILE__));
error_reporting(E_ALL);

include_once S_PATH.'/conf/core.fun.php';
include_once S_PATH.'/class/database.php';
include_once S_PATH.'/class/secretkey.php';  
include_once S_PATH.'/class/MySql.php';  # mysql


# 有几个脚本执行
$num = exec("ps aux | grep 'DY_live_data.php' | grep -v grep | wc -l");
if($num>1){
  exit(date('Y-m-d').' 已经有脚本了');
}

# 用户名称
$nickname1Ls = array('霖雨弹幕小机器人欢迎提问', 'S的苏打水MmMm', 'LIUJINWEN');
$nickname2Ls = array('棋牌小九', '小辣椒', '木木酱');


# 数据库配置
$db_conf = array(
    'host' => $db['default']['hostname'],
    'port' => '3306',
    'user' => $db['default']['username'],
    'passwd' => $db['default']['password'],
    'dbname' => $db['default']['database'],
);

# mysql
$mysql = new MMysql($db_conf);


$date = date('Y-m-d', strtotime('-1 day'));
# 麻将
$listDataJson = DYLiveDataList1($date);
$listData = json_decode($listDataJson, true);
$listJson = $listData['data']['data_string'];

$listArr = array();
if(!empty($listJson)){
	$listArr = json_decode($listJson, true);
}
if(empty($listArr)){
	exit('未找到直播场次，需要查看接口token是否失效');
}
foreach($listArr['data']['series'] as $lsKey=>$lsVal){

	$roomid = $lsVal['roomID'];
	$roomTitle = $lsVal['roomTitle'];
	$liveStartTime = $lsVal['liveStartTime'];

	$interactJson = DYLiveDataInteract1($roomid);

	$interactData = json_decode($interactJson, true);
	$interactDataJson = $interactData['data']['data_string'];

	$interactArr = array();
	if(!empty($interactDataJson)){
		$interactArr = json_decode($interactDataJson, true);
	}
	if(empty($interactArr)){
		exit('未找到直播场次中互动数据，需要查看接口token是否失效');
	}


	foreach($interactArr['data']['series'] as $intKey=>$intVal){
    $nickname = match_chinese($intVal['nickname']);
		if(in_array($intVal['order'], array('1', '2')) && in_array($nickname, $nickname1Ls)){
			$insert = array();
      $insert['accound_title'] = '麻将';
			$insert['room_title'] = $roomTitle;
			$insert['live_start_time'] = $liveStartTime;
			$insert['username'] = $nickname;
			$insert['user_cnt'] = $intVal['cnt'];
			// var_dump($insert);

			$mysql->insert('dy_live_data', $insert);
		}
	}
}

# 斗地主
$listDataJson = DYLiveDataList2($date);
$listData = json_decode($listDataJson, true);
$listJson = $listData['data']['data_string'];

$listArr = array();
if(!empty($listJson)){
  $listArr = json_decode($listJson, true);
}
if(empty($listArr)){
  exit('未找到直播场次，需要查看接口token是否失效');
}
foreach($listArr['data']['series'] as $lsKey=>$lsVal){

  $roomid = $lsVal['roomID'];
  $roomTitle = $lsVal['roomTitle'];
  $liveStartTime = $lsVal['liveStartTime'];

  $interactJson = DYLiveDataInteract2($roomid);

  $interactData = json_decode($interactJson, true);
  $interactDataJson = $interactData['data']['data_string'];

  $interactArr = array();
  if(!empty($interactDataJson)){
    $interactArr = json_decode($interactDataJson, true);
  }
  if(empty($interactArr)){
    exit('未找到直播场次中互动数据，需要查看接口token是否失效');
  }


  foreach($interactArr['data']['series'] as $intKey=>$intVal){
    $nickname = match_chinese($intVal['nickname']);
    if(in_array($intVal['order'], array('1', '2')) && in_array($nickname, $nickname2Ls)){
      $insert = array();
      $insert['accound_title'] = '斗地主';
      $insert['room_title'] = $roomTitle;
      $insert['live_start_time'] = $liveStartTime;
      $insert['username'] = $nickname;
      $insert['user_cnt'] = $intVal['cnt'];
      // var_dump($insert);

      $mysql->insert('dy_live_data', $insert);
    }
  }
}

function DYLiveDataList1($date){
unlink('./DY_live_data_list1.txt');
// 1.拉取DY数据.
$curlRequest = <<<EOF
  curl 'https://union.bytedance.com/ark/api/data/pugna_component/data/v2/faction/anchor_detail/room_list_with_tag?anchorID=88677582480&liveID=1&beginDate={$date}&endDate={$date}&page=1&size=5&msToken=7GjU5CZ7XXyV22pLeh28arPq-I0tsd21Sp8kS3igYxQJXf9gg-Es6DD5_8OsNPV1kw9r8UbgGCjPCxbACtDfCfHSF65TAZzt8jgm27r53F7paOp5xQv6&X-Bogus=DFSzswVusSVdU2-ctPsCPe9WX7no&_signature=_02B4Z6wo0000182yVgwAAIDDTbCsTEQZTxfNslKAAJZzPvUQmm8Sb2U7.pzVoni5.NwUWosfB3zkKvnKeqdqdqpXYfDj1Dlkz5y-ql0nyq4H4uQnfaK4Mlo.JKQXVOQmDDjlePR9NCS8dOxr56' \
  -H 'authority: union.bytedance.com' \
  -H 'accept: application/json, text/plain, */*' \
  -H 'accept-language: zh-CN,zh;q=0.9' \
  -H 'content-type: application/json;charset=utf-8' \
  -H 'cookie: s_v_web_id=verify_lmn7bwt9_sFBW7FHq_SIw6_4KHB_Ba5f_UMeMMOzr1I22; csrf_session_id=9e28417361f560b3257178824864fc2a; passport_csrf_token=5183deb729c64b5a06ca605261c27617; passport_csrf_token_default=5183deb729c64b5a06ca605261c27617; msToken=Ai0xhZAv0_wpd0S7On-EYpN23GafXdx_v76Gw-h23aM7DiZ8Cwphn2V7o4rcaD_8ctfmjtjj-hIEI1qvm1cLVG7kjXcpmBjX4hOh0DJJaKsf3Bfqw_ol; ttcid=0c3f4dbd16e8466592b34494931da83e16; passport_csrf_token_wap_state=bc529d473gASoVCgoVPZIGQ5NzY2MjkzZTYyYjE0MGY0OWJmODkzZTRhZTE3YzE4oU6goVYBoUkAoUQAoUHRBvqhTQChSLN1bmlvbi5ieXRlZGFuY2UuY29toVIColBM0QTcpkFDVElPTqChTNl2aHR0cHM6Ly91bmlvbi5ieXRlZGFuY2UuY29tL29wZW4vP2NvZGU9ZDZhNmE4ZTNkOTA3YTE4M21jbEV5ZGVTdFlIVVZSbm1Keko2JnN0YXRlPTQzNzkyMDIzLThhNTYtNDlmYy04NGNlLWQ1NmE5ZTcxODk2YqFU2SA2Mzg5MzIzMTk0NTA1MGI1MjEwZWZiYWMwY2I1Mzk4ZqFXAKFGAKJTQQChVcI%253D; odin_tt=5afc688f8e87a0fd70cf51496a374ac1e183195d3b2e1292f570690574902641f62d269ef0059e059d3f7fa39dbefb3dc050f121694230aef96e6457f56cb1ed; n_mh=EPIf2WY6hm-Orm6jOEVhhsjDia8IHuqSLsKFWl1Tf8c; passport_auth_status=23946e20458b63c2c23d0c9d173cb006%2C; passport_auth_status_ss=23946e20458b63c2c23d0c9d173cb006%2C; sid_guard=e8ab8317684a26ef5406c8cf32ab0030%7C1694939791%7C2592000%7CTue%2C+17-Oct-2023+08%3A36%3A31+GMT; uid_tt=7245099a8c5fad344d69f429e229a3ca; uid_tt_ss=7245099a8c5fad344d69f429e229a3ca; sid_tt=e8ab8317684a26ef5406c8cf32ab0030; sessionid=e8ab8317684a26ef5406c8cf32ab0030; sessionid_ss=e8ab8317684a26ef5406c8cf32ab0030; sid_ucp_v1=1.0.0-KDhlM2VjMjBjNjRhMWE5MzBmZjRlOGY2MmU2ZWY5MGY1NTY1ZWI1NWUKHgi4y7DYwo3pBRCP9ZqoBhj6DSAMMI7kipYGOAhAJhoCaGwiIGU4YWI4MzE3Njg0YTI2ZWY1NDA2YzhjZjMyYWIwMDMw; ssid_ucp_v1=1.0.0-KDhlM2VjMjBjNjRhMWE5MzBmZjRlOGY2MmU2ZWY5MGY1NTY1ZWI1NWUKHgi4y7DYwo3pBRCP9ZqoBhj6DSAMMI7kipYGOAhAJhoCaGwiIGU4YWI4MzE3Njg0YTI2ZWY1NDA2YzhjZjMyYWIwMDMw; store-region=cn-bj; store-region-src=uid; msToken=7GjU5CZ7XXyV22pLeh28arPq-I0tsd21Sp8kS3igYxQJXf9gg-Es6DD5_8OsNPV1kw9r8UbgGCjPCxbACtDfCfHSF65TAZzt8jgm27r53F7paOp5xQv6; ttwid=1%7C14elGxHRaK_8zO4Ji78lfNmtqyb1zZxM79XEQ7gnvVM%7C1694939793%7Ceea3a903a88fcf155e97337fa0cb796addc24d679f06c243c28e2d33bf82255d; node_sid=603673ea082194c6b0ae681e67db9250; tt_scid=y2-1gd2K-qbWPwzREcJJW9Vu.IFh9eAlfekztadFgSMdVElnwK4zn2E9AnyTCyCd257b' \
  -H 'referer: https://union.bytedance.com/open/portal/anchor/list/anchorDetail?anchorID=88677582480&appId=3000&tab=live_record' \
  -H 'sec-ch-ua: "Chromium";v="106", "Google Chrome";v="106", "Not;A=Brand";v="99"' \
  -H 'sec-ch-ua-mobile: ?0' \
  -H 'sec-ch-ua-platform: "Windows"' \
  -H 'sec-fetch-dest: empty' \
  -H 'sec-fetch-mode: cors' \
  -H 'sec-fetch-site: same-origin' \
  -H 'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36' \
  -H 'x-appid: 3000' \
  -H 'x-csrf-token: undefined' \
  -H 'x-language: zh' \
  -H 'x-secsdk-csrf-token: 000100000001a09bef908d356206067f0de5021ad9dd14e0ea4fa902e1cbbf54517971ad12ce1785a2c2f3e46a1e' \
  --compressed >> DY_live_data_list1.txt
EOF;
  $startTime = getMicrotime();
  exec($curlRequest);
  $endTime_1 = getMicrotime();
  $dataString = file_get_contents('./DY_live_data_list1.txt');

  return $dataString;
}

function DYLiveDataList2($date){
unlink('./DY_live_data_list2.txt');
// 1.拉取DY数据.
$curlRequest = <<<EOF
  curl 'https://union.bytedance.com/ark/api/data/pugna_component/data/v2/faction/anchor_detail/room_list_with_tag?anchorID=4213790313556647&liveID=1&beginDate={$date}&endDate={$date}&page=1&size=5&msToken=whHx0-xK2MmE44QA0jbMZVYE0u7oQiztzf8MIK_4iAZL4vIt0TVV6rVbCWM9wXVHWj0ZBv_iu956BzThjIjGNHrYaP7C3aUCnVMXEnqantpjNzLX5aCQ&X-Bogus=DFSzswVu3vfdUWkdtPn0VF9WX7JK&_signature=_02B4Z6wo00001HYemqgAAIDA9hxg6jisa6B2Hp4AAHiX3TmJxMSuiz0c62O6xZDwqMD5b7mS1WEj2QQJfFM0G6VP1uP5Z0zrLUr206Vm7eHP6jOYqplNkCGZ76okHvkXBtWYdYUrmgy571Sj81' \
  -H 'authority: union.bytedance.com' \
  -H 'sec-ch-ua: ";Not A Brand";v="99", "Chromium";v="94"' \
  -H 'x-language: zh' \
  -H 'x-csrf-token: undefined' \
  -H 'x-secsdk-csrf-token: 0001000000016f63066b501f1b21015fb034fd55f6314b5c18004ab742cbe6c99ee7ba10adaf1785ff41ed707c81' \
  -H 'user-agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 Core/1.94.200.400 QQBrowser/11.8.5310.400' \
  -H 'content-type: application/json;charset=utf-8' \
  -H 'accept: application/json, text/plain, */*' \
  -H 'sec-ch-ua-mobile: ?0' \
  -H 'x-appid: 3000' \
  -H 'sec-ch-ua-platform: "Windows"' \
  -H 'sec-fetch-site: same-origin' \
  -H 'sec-fetch-mode: cors' \
  -H 'sec-fetch-dest: empty' \
  -H 'referer: https://union.bytedance.com/open/portal/anchor/list/anchorDetail?anchorID=4213790313556647&appId=3000&tab=live_record' \
  -H 'accept-language: zh-CN,zh;q=0.9' \
  -H 'cookie: store-region-src=uid; passport_csrf_token=273394d19beee2abdc4b39e93beb8cbd; passport_csrf_token_default=273394d19beee2abdc4b39e93beb8cbd; s_v_web_id=verify_lmovwiry_lvMoaX0v_w7T0_4sPh_Bzra_PvAFCeB3E7bb; csrf_session_id=9e28417361f560b3257178824864fc2a; x-jupiter-uuid=16950414318842516; passport_csrf_token_wap_state=c4b07af63gASoVCgoVPZIDEwYWY2NDA2ZDNhNDBiYjZjNTFkOTEwMDI3ZTBkMzJjoU6goVYBoUkAoUQAoUHRBvqhTQChSLN1bmlvbi5ieXRlZGFuY2UuY29toVIColBM0QTcpkFDVElPTqChTNl1aHR0cHM6Ly91bmlvbi5ieXRlZGFuY2UuY29tL29wZW4_Y29kZT01YWNmN2Y3NTMyZTkwODVibFY5TTRNbEpsMnRNMVExTzhMaEgmc3RhdGU9OGY0Mjg4NDUtZTNhZC00ZGRmLWJlN2ItZTIyYWJlNDFmMDg5oVTZIDM4ZTM4MDAwYzdiOTQxNDAzZTE2OTZkNTA2YmYwYzBioVcAoUYAolNBAKFVwg%253D%253D; odin_tt=45b5689085e09ac22d0b9b3a73545d317d5346a2be63529c797ec32c0916523de8023c4a9c8a9dd37599c4a63772bb3f9bec1dcecfc450567908d5a5eb178507; n_mh=vcEaS7AMXidzvkifDt83PW243ktCyJ7qJ7RqIjQZIn4; passport_auth_status=550bc854aab0245dd01e919294ba0cb5%2C; passport_auth_status_ss=550bc854aab0245dd01e919294ba0cb5%2C; sid_guard=acc3e8aaa9789bea6dffe38a6e7192b8%7C1695041491%7C2592000%7CWed%2C+18-Oct-2023+12%3A51%3A31+GMT; uid_tt=f9ed4cc639349ead303cdb2719b2114f; uid_tt_ss=f9ed4cc639349ead303cdb2719b2114f; sid_tt=acc3e8aaa9789bea6dffe38a6e7192b8; sessionid=acc3e8aaa9789bea6dffe38a6e7192b8; sessionid_ss=acc3e8aaa9789bea6dffe38a6e7192b8; sid_ucp_v1=1.0.0-KDgzNWJlZjFjZTc0M2I3YzU1NTk5YTJhNGIxODFlZjRjZjE3NmM5MDcKHgjL-tDj643OBRDTj6GoBhj6DSAMMNmQr48GOAhAJhoCaGwiIGFjYzNlOGFhYTk3ODliZWE2ZGZmZTM4YTZlNzE5MmI4; ssid_ucp_v1=1.0.0-KDgzNWJlZjFjZTc0M2I3YzU1NTk5YTJhNGIxODFlZjRjZjE3NmM5MDcKHgjL-tDj643OBRDTj6GoBhj6DSAMMNmQr48GOAhAJhoCaGwiIGFjYzNlOGFhYTk3ODliZWE2ZGZmZTM4YTZlNzE5MmI4; store-region=cn-bj; ttwid=1%7CGPDn279159WKRmyxT_M6nusZoNMMAtPG95kCfViW7hI%7C1695041493%7Ca0fe7a628ff66a9941e033191942b1f8bfc3f5199bcbb7fac899d1b3d7a81f96; msToken=whHx0-xK2MmE44QA0jbMZVYE0u7oQiztzf8MIK_4iAZL4vIt0TVV6rVbCWM9wXVHWj0ZBv_iu956BzThjIjGNHrYaP7C3aUCnVMXEnqantpjNzLX5aCQ; node_sid=3e08d7f69a2f2152de4235975d040d12; tt_scid=rwo8KN0BYLaPk.cfskZactm8DyXLjMf4EtgfeBuNWdLTjlSW7YMh4umszHAggIDwce62' \
  --compressed >> DY_live_data_list2.txt
EOF;
  $startTime = getMicrotime();
  exec($curlRequest);
  $endTime_1 = getMicrotime();
  $dataString = file_get_contents('./DY_live_data_list2.txt');

  return $dataString;
}




function DYLiveDataInteract1($roomid){
unlink('./DY_live_data_interact1.txt');
// 1.拉取DY数据.
$curlRequest = <<<EOF

curl 'https://union.bytedance.com/ark/api/data/pugna_component/data/v2/anchor/live/room_stats_top_list?roomID={$roomid}&top=50&rankType=4&offset=0&limit=5&msToken=7GjU5CZ7XXyV22pLeh28arPq-I0tsd21Sp8kS3igYxQJXf9gg-Es6DD5_8OsNPV1kw9r8UbgGCjPCxbACtDfCfHSF65TAZzt8jgm27r53F7paOp5xQv6&X-Bogus=DFSzswVLdcVdU52CtPseAl9WX7nk&_signature=_02B4Z6wo00001r3mazQAAIDCPeSRd1bRDxa95m-AAMqLPvUQmm8Sb2U7.pzVoni5.NwUWosfB3zkKvnKeqdqdqpXYfDj1Dlkz5y-ql0nyq4H4uQnfaK4Mlo.JKQXVOQmDDjlePR9NCS8dOxr24' \
  -H 'authority: union.bytedance.com' \
  -H 'accept: application/json, text/plain, */*' \
  -H 'accept-language: zh-CN,zh;q=0.9' \
  -H 'content-type: application/json;charset=utf-8' \
  -H 'cookie: s_v_web_id=verify_lmn7bwt9_sFBW7FHq_SIw6_4KHB_Ba5f_UMeMMOzr1I22; csrf_session_id=9e28417361f560b3257178824864fc2a; passport_csrf_token=5183deb729c64b5a06ca605261c27617; passport_csrf_token_default=5183deb729c64b5a06ca605261c27617; msToken=Ai0xhZAv0_wpd0S7On-EYpN23GafXdx_v76Gw-h23aM7DiZ8Cwphn2V7o4rcaD_8ctfmjtjj-hIEI1qvm1cLVG7kjXcpmBjX4hOh0DJJaKsf3Bfqw_ol; ttcid=0c3f4dbd16e8466592b34494931da83e16; passport_csrf_token_wap_state=bc529d473gASoVCgoVPZIGQ5NzY2MjkzZTYyYjE0MGY0OWJmODkzZTRhZTE3YzE4oU6goVYBoUkAoUQAoUHRBvqhTQChSLN1bmlvbi5ieXRlZGFuY2UuY29toVIColBM0QTcpkFDVElPTqChTNl2aHR0cHM6Ly91bmlvbi5ieXRlZGFuY2UuY29tL29wZW4vP2NvZGU9ZDZhNmE4ZTNkOTA3YTE4M21jbEV5ZGVTdFlIVVZSbm1Keko2JnN0YXRlPTQzNzkyMDIzLThhNTYtNDlmYy04NGNlLWQ1NmE5ZTcxODk2YqFU2SA2Mzg5MzIzMTk0NTA1MGI1MjEwZWZiYWMwY2I1Mzk4ZqFXAKFGAKJTQQChVcI%253D; odin_tt=5afc688f8e87a0fd70cf51496a374ac1e183195d3b2e1292f570690574902641f62d269ef0059e059d3f7fa39dbefb3dc050f121694230aef96e6457f56cb1ed; n_mh=EPIf2WY6hm-Orm6jOEVhhsjDia8IHuqSLsKFWl1Tf8c; passport_auth_status=23946e20458b63c2c23d0c9d173cb006%2C; passport_auth_status_ss=23946e20458b63c2c23d0c9d173cb006%2C; sid_guard=e8ab8317684a26ef5406c8cf32ab0030%7C1694939791%7C2592000%7CTue%2C+17-Oct-2023+08%3A36%3A31+GMT; uid_tt=7245099a8c5fad344d69f429e229a3ca; uid_tt_ss=7245099a8c5fad344d69f429e229a3ca; sid_tt=e8ab8317684a26ef5406c8cf32ab0030; sessionid=e8ab8317684a26ef5406c8cf32ab0030; sessionid_ss=e8ab8317684a26ef5406c8cf32ab0030; sid_ucp_v1=1.0.0-KDhlM2VjMjBjNjRhMWE5MzBmZjRlOGY2MmU2ZWY5MGY1NTY1ZWI1NWUKHgi4y7DYwo3pBRCP9ZqoBhj6DSAMMI7kipYGOAhAJhoCaGwiIGU4YWI4MzE3Njg0YTI2ZWY1NDA2YzhjZjMyYWIwMDMw; ssid_ucp_v1=1.0.0-KDhlM2VjMjBjNjRhMWE5MzBmZjRlOGY2MmU2ZWY5MGY1NTY1ZWI1NWUKHgi4y7DYwo3pBRCP9ZqoBhj6DSAMMI7kipYGOAhAJhoCaGwiIGU4YWI4MzE3Njg0YTI2ZWY1NDA2YzhjZjMyYWIwMDMw; store-region=cn-bj; store-region-src=uid; msToken=7GjU5CZ7XXyV22pLeh28arPq-I0tsd21Sp8kS3igYxQJXf9gg-Es6DD5_8OsNPV1kw9r8UbgGCjPCxbACtDfCfHSF65TAZzt8jgm27r53F7paOp5xQv6; ttwid=1%7C14elGxHRaK_8zO4Ji78lfNmtqyb1zZxM79XEQ7gnvVM%7C1694939793%7Ceea3a903a88fcf155e97337fa0cb796addc24d679f06c243c28e2d33bf82255d; node_sid=603673ea082194c6b0ae681e67db9250; tt_scid=y2-1gd2K-qbWPwzREcJJW9Vu.IFh9eAlfekztadFgSMdVElnwK4zn2E9AnyTCyCd257b' \
  -H 'referer: https://union.bytedance.com/open/portal/anchor/list/liveRecordDetail?appId=3000&anchorID=88677582480&roomID={$roomid}&enterFrom=liveRecord' \
  -H 'sec-ch-ua: "Chromium";v="106", "Google Chrome";v="106", "Not;A=Brand";v="99"' \
  -H 'sec-ch-ua-mobile: ?0' \
  -H 'sec-ch-ua-platform: "Windows"' \
  -H 'sec-fetch-dest: empty' \
  -H 'sec-fetch-mode: cors' \
  -H 'sec-fetch-site: same-origin' \
  -H 'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36' \
  -H 'x-appid: 3000' \
  -H 'x-csrf-token: undefined' \
  -H 'x-language: zh' \
  -H 'x-secsdk-csrf-token: 000100000001a09bef908d356206067f0de5021ad9dd14e0ea4fa902e1cbbf54517971ad12ce1785a2c2f3e46a1e' \
  --compressed >> DY_live_data_interact1.txt
EOF;

  $startTime = getMicrotime();
  exec($curlRequest);
  $endTime_1 = getMicrotime();
  $dataString = file_get_contents('./DY_live_data_interact1.txt');

  return $dataString;
}

function DYLiveDataInteract2($roomid){
unlink('./DY_live_data_interact2.txt');
// 1.拉取DY数据.
$curlRequest = <<<EOF
  curl 'https://union.bytedance.com/ark/api/data/pugna_component/data/v2/anchor/live/room_stats_top_list?roomID={$roomid}&top=50&rankType=4&offset=0&limit=5&msToken=whHx0-xK2MmE44QA0jbMZVYE0u7oQiztzf8MIK_4iAZL4vIt0TVV6rVbCWM9wXVHWj0ZBv_iu956BzThjIjGNHrYaP7C3aUCnVMXEnqantpjNzLX5aCQ&X-Bogus=DFSzswVuleEdUWkdtPn/8F9WX7Jh&_signature=_02B4Z6wo00001aQ8B1QAAIDBJD79FURBuJmkPAPAAAwI3TmJxMSuiz0c62O6xZDwqMD5b7mS1WEj2QQJfFM0G6VP1uP5Z0zrLUr206Vm7eHP6jOYqplNkCGZ76okHvkXBtWYdYUrmgy571Sjed' \
  -H 'authority: union.bytedance.com' \
  -H 'sec-ch-ua: ";Not A Brand";v="99", "Chromium";v="94"' \
  -H 'x-language: zh' \
  -H 'x-csrf-token: undefined' \
  -H 'x-secsdk-csrf-token: 0001000000016f63066b501f1b21015fb034fd55f6314b5c18004ab742cbe6c99ee7ba10adaf1785ff41ed707c81' \
  -H 'user-agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 Core/1.94.200.400 QQBrowser/11.8.5310.400' \
  -H 'content-type: application/json;charset=utf-8' \
  -H 'accept: application/json, text/plain, */*' \
  -H 'sec-ch-ua-mobile: ?0' \
  -H 'x-appid: 3000' \
  -H 'sec-ch-ua-platform: "Windows"' \
  -H 'sec-fetch-site: same-origin' \
  -H 'sec-fetch-mode: cors' \
  -H 'sec-fetch-dest: empty' \
  -H 'referer: https://union.bytedance.com/open/portal/anchor/list/liveRecordDetail?appId=3000&anchorID=4213790313556647&roomID={$roomid}&enterFrom=liveRecord' \
  -H 'accept-language: zh-CN,zh;q=0.9' \
  -H 'cookie: store-region-src=uid; passport_csrf_token=273394d19beee2abdc4b39e93beb8cbd; passport_csrf_token_default=273394d19beee2abdc4b39e93beb8cbd; s_v_web_id=verify_lmovwiry_lvMoaX0v_w7T0_4sPh_Bzra_PvAFCeB3E7bb; csrf_session_id=9e28417361f560b3257178824864fc2a; x-jupiter-uuid=16950414318842516; passport_csrf_token_wap_state=c4b07af63gASoVCgoVPZIDEwYWY2NDA2ZDNhNDBiYjZjNTFkOTEwMDI3ZTBkMzJjoU6goVYBoUkAoUQAoUHRBvqhTQChSLN1bmlvbi5ieXRlZGFuY2UuY29toVIColBM0QTcpkFDVElPTqChTNl1aHR0cHM6Ly91bmlvbi5ieXRlZGFuY2UuY29tL29wZW4_Y29kZT01YWNmN2Y3NTMyZTkwODVibFY5TTRNbEpsMnRNMVExTzhMaEgmc3RhdGU9OGY0Mjg4NDUtZTNhZC00ZGRmLWJlN2ItZTIyYWJlNDFmMDg5oVTZIDM4ZTM4MDAwYzdiOTQxNDAzZTE2OTZkNTA2YmYwYzBioVcAoUYAolNBAKFVwg%253D%253D; odin_tt=45b5689085e09ac22d0b9b3a73545d317d5346a2be63529c797ec32c0916523de8023c4a9c8a9dd37599c4a63772bb3f9bec1dcecfc450567908d5a5eb178507; n_mh=vcEaS7AMXidzvkifDt83PW243ktCyJ7qJ7RqIjQZIn4; passport_auth_status=550bc854aab0245dd01e919294ba0cb5%2C; passport_auth_status_ss=550bc854aab0245dd01e919294ba0cb5%2C; sid_guard=acc3e8aaa9789bea6dffe38a6e7192b8%7C1695041491%7C2592000%7CWed%2C+18-Oct-2023+12%3A51%3A31+GMT; uid_tt=f9ed4cc639349ead303cdb2719b2114f; uid_tt_ss=f9ed4cc639349ead303cdb2719b2114f; sid_tt=acc3e8aaa9789bea6dffe38a6e7192b8; sessionid=acc3e8aaa9789bea6dffe38a6e7192b8; sessionid_ss=acc3e8aaa9789bea6dffe38a6e7192b8; sid_ucp_v1=1.0.0-KDgzNWJlZjFjZTc0M2I3YzU1NTk5YTJhNGIxODFlZjRjZjE3NmM5MDcKHgjL-tDj643OBRDTj6GoBhj6DSAMMNmQr48GOAhAJhoCaGwiIGFjYzNlOGFhYTk3ODliZWE2ZGZmZTM4YTZlNzE5MmI4; ssid_ucp_v1=1.0.0-KDgzNWJlZjFjZTc0M2I3YzU1NTk5YTJhNGIxODFlZjRjZjE3NmM5MDcKHgjL-tDj643OBRDTj6GoBhj6DSAMMNmQr48GOAhAJhoCaGwiIGFjYzNlOGFhYTk3ODliZWE2ZGZmZTM4YTZlNzE5MmI4; store-region=cn-bj; ttwid=1%7CGPDn279159WKRmyxT_M6nusZoNMMAtPG95kCfViW7hI%7C1695041493%7Ca0fe7a628ff66a9941e033191942b1f8bfc3f5199bcbb7fac899d1b3d7a81f96; msToken=whHx0-xK2MmE44QA0jbMZVYE0u7oQiztzf8MIK_4iAZL4vIt0TVV6rVbCWM9wXVHWj0ZBv_iu956BzThjIjGNHrYaP7C3aUCnVMXEnqantpjNzLX5aCQ; node_sid=3e08d7f69a2f2152de4235975d040d12; tt_scid=rwo8KN0BYLaPk.cfskZactm8DyXLjMf4EtgfeBuNWdLTjlSW7YMh4umszHAggIDwce62' \
  --compressed >> DY_live_data_interact2.txt
EOF;

  $startTime = getMicrotime();
  exec($curlRequest);
  $endTime_1 = getMicrotime();
  $dataString = file_get_contents('./DY_live_data_interact2.txt');

  return $dataString;
}
<?php
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
define('S_PATH', dirname(__FILE__));
// ini_set("display_errors", "On");//打开错误提示
// ini_set("error_reporting",E_ALL);//显示所有错误

include_once S_PATH.'/../conf/core.fun.php';
include_once S_PATH.'/../class/database.php';
include_once S_PATH.'/../class/secretkey.php';  
include_once S_PATH.'/../class/MySql.php';  # mysql

// # 有几个脚本执行
// $num = exec("ps aux | grep 'TB_live_data.php' | grep -v grep | wc -l");
// if($num>1){
//   exit(date('Y-m-d').' 已经有脚本了');
// }

$TBdata = array();
for ($i=1; $i<=4; $i++) {
  $json = TBLiveDataList($i);
  echo $json.'<br>';
  // $json = str_replace(' mtopjsonp5(', '', $json);
  // $json = str_replace(')', '', $json);
  // $data = json_decode($json, true);

  // if(empty($data) || !isset($data['ret']['0']) || $data['ret']['0'] != "SUCCESS::调用成功"){
  //   exit('page: '.$i.', 抓取失败 json: '.$json);
  // }

  // foreach($data['data']['object']['data'] as $key=>$val){
  //   $TBdata[] = $val['fieldValues'];
  // }
}
echo 12312;die;
$titlearr = array('开播时间','场次标题','开播时长(分钟)','直播间浏览人数','直播间浏览次数','封面图点击率','人均停留时长(秒)','互动率','新增粉丝量','种草成交金额(元)','引导进店次数');
export_csv($TBdata, $titlearr, 'liveCsv');

function TBLiveDataList($page){
unlink('./TB_live_data_list1.txt');
// 1.拉取DY数据.
$curlRequest = <<<EOF
  curl 'https://h5api.m.taobao.com/h5/mtop.taobao.daren.agency.anchordata.anchordetail/1.0/?jsv=2.6.1&appKey=12574478&t=1697382931043&sign=472553c7e0765b27396c8341581c6f73&api=mtop.taobao.daren.agency.anchorData.anchorDetail&v=1.0&preventFallback=true&type=jsonp&dataType=jsonp&callback=mtopjsonp5&data=%7B%22page%22%3A{$page}%2C%22anchorId%22%3A%22JXUp1uxL0qway0Oy%2FmAaSg%3D%3D%22%2C%22period%22%3A%2215%22%2C%22queryDate%22%3A%222023-10-14%22%2C%22pageSize%22%3A10%2C%22roleId%22%3A163%7D' \
  -H 'authority: h5api.m.taobao.com' \
  -H 'accept: */*' \
  -H 'accept-language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6' \
  -H 'cookie: xlly_s=1; ali_apache_id=33.80.65.160.1697377383757.451522.6; cookie2=117bafcda0bed52540c8b8f90bd52da5; t=eabd56b699c7579fd770d038f950248e; _tb_token_=eea357a6e8614; _samesite_flag_=true; _m_h5_tk=0a943ccef3f948639ef7d3720e0ca5a2_1697384985981; _m_h5_tk_enc=29b1fa2060efc2ad52b65fdcffcc3a21; mt=ci=0_0; cna=E6K8G4SCgiACAT0w0WNww+LX; sgcookie=E100TRrbu5XWFYEoZop0a8HQZd5bw5JMUNtsS1HLTZLImdYHp%2FKVWbWYqkycUIgcWppPBqUy4P8ri0jkREizNFQk7gco2WIMycFlkc9iEUAMBtgGIdEJJRbDn1dd7Cp6xG1v; unb=1837214215; uc1=cookie16=W5iHLLyFPlMGbLDwA%2BdvAGZqLg%3D%3D&cookie21=Vq8l%2BKCLjhS4UhJVbhgU&cookie15=VT5L2FSpMGV7TQ%3D%3D&existShop=false&pas=0&cookie14=Uoe9agbZaopK3A%3D%3D; uc3=nk2=olbFpqCyJD2%2F4A%3D%3D&lg2=W5iHLLyFOGW7aA%3D%3D&id2=UonYtogW5T0AqQ%3D%3D&vt3=F8dCsGrBstxa5w%2FWOiw%3D; csg=73b37bbf; lgc=%5Cu6CA5%5Cu5DDD%5Cu7684%5Cu5C0F%5Cu79CB; cancelledSubSites=%5B%22hema%22%5D; cookie17=UonYtogW5T0AqQ%3D%3D; dnk=%5Cu6CA5%5Cu5DDD%5Cu7684%5Cu5C0F%5Cu79CB; skt=37ab4d5a4754c025; existShop=MTY5NzM3OTYzNg%3D%3D; uc4=id4=0%40UOEy0bqQu9EJBVEA3TdhsT%2BKLyuv&nk4=0%40oFRWMYsbExKjwzgfVsnLEWejgt%2Fe; tracknick=%5Cu6CA5%5Cu5DDD%5Cu7684%5Cu5C0F%5Cu79CB; _cc_=VT5L2FSpdA%3D%3D; _l_g_=Ug%3D%3D; sg=%E7%A7%8B50; _nk_=%5Cu6CA5%5Cu5DDD%5Cu7684%5Cu5C0F%5Cu79CB; cookie1=Vyh0sf3LHMp6VaaUbeJRQGV7LfhWRLQFW1SIegnyGfk%3D; l=fBOvEq4gPrWHLteTBO5Ihurza77TVIRb4RFzaNbMiIEGa1rG_nKptOCtvDWp8dtjgTCfEety4g7UPdLHR3fRwxDDBsu4xA7j3xvtaQtJe; tfstk=dHxB7cwzDWVCtRTdz8Mw16nIYRsSQBiVp86JnLEUeMIdFg9WGa7EtL81eQRiEvjee_OW_1Xy865zPudGsp7rZv2J1QRwYQSFzC6WaKHHNLDhe_dJN_8E7mJHKgj-gQiqupV4pghq8GY_Npjl2jya2qoXKI5t3k6oKY2jbVvE1lE_0My5Cm-b2uKtqw1Xpzr82h6CRyvdQaEVGItaPR_0VO1qCAaur0viwnC..; isg=BHp6kE2ay9miDEeyWIHQMoKuy6CcK_4F6-_vy4RzJo3YdxqxbLtOFUCBxwOrfHad' \
  -H 'referer: https://darenmcn.taobao.com/' \
  -H 'sec-ch-ua: "Microsoft Edge";v="117", "Not;A=Brand";v="8", "Chromium";v="117"' \
  -H 'sec-ch-ua-mobile: ?0' \
  -H 'sec-ch-ua-platform: "Windows"' \
  -H 'sec-fetch-dest: script' \
  -H 'sec-fetch-mode: no-cors' \
  -H 'sec-fetch-site: same-site' \
  -H 'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36 Edg/117.0.2045.36' \
  --compressed >> TB_live_data_list1.txt
EOF;
  $startTime = getMicrotime();
  exec($curlRequest);
  $endTime_1 = getMicrotime();
  $dataString = file_get_contents('./TB_live_data_list1.txt');

  return $dataString;
}



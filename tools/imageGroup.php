<?php
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
define('S_PATH', dirname(__FILE__));
error_reporting(E_ALL);

include_once S_PATH.'/class/database.php';  
include_once S_PATH.'/class/MySql.php';  # mysql

$num = exec("ps aux | grep 'imageGroup.php' | grep -v grep | wc -l");
if($num>1){
  exit(date('Y-m-d').' 已经有脚本了');
}

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

/*
$where = '1';
// $where .= ' and id=6';
$sql = "SELECT * FROM zf_images where {$where}";
$res = $mysql->doSql($sql);

$arr = array();
foreach($res as $key=>$val){
	$pattern = "/《(.*)》/"; 
	preg_match($pattern,$val['name'],$match);
	if(empty($match)){
		continue;
	}

	var_dump($match[1]);

	$tag = '';
	if(strpos($match[1],'英雄联盟') !== false){
		$tag = '英雄联盟';
	}else if(strpos($match[1],'守望先锋') !== false){
		$tag = '守望先锋';
	}else if(strpos($match[1],'王者荣耀') !== false){
		$tag = '王者荣耀';
	}else if(strpos($match[1],'三国杀') !== false){
		$tag = '三国杀';
	}else if(strpos($match[1],'尼尔机械纪元') !== false || strpos($match[1],'机械纪元') !== false){
		$tag = '尼尔机械纪元';
	}else if(strpos($match[1],'刀塔') !== false||strpos($match[1],'Dota') !== false){
		$tag = '刀塔';
	}else if(strpos($match[1],'第五人格') !== false||strpos($match[1],'identity v') !== false){
		$tag = '第五人格';
	}else if(strpos($match[1],'生化危机') !== false){
		$tag = '生化危机';
	}else if(strpos($match[1],'反恐精英') !== false){
		$tag = '反恐精英';
	}else if(strpos($match[1],'绝地求生') !== false){
		$tag = '绝地求生';
	}else if(strpos($match[1],'刺客信条') !== false){
		$tag = '刺客信条';
	}else if(strpos($match[1],'赛博朋克') !== false){
		$tag = '赛博朋克';
	}else if(strpos($match[1],'崩坏3') !== false){
		$tag = '崩坏3';
	}else if(strpos($match[1],'命运') !== false || strpos($match[1],'Fate') !== false){
		$tag = '命运';
	}else if(strpos($match[1],'古墓丽影') !== false){
		$tag = '古墓丽影';
	}else if(strpos($match[1],'星际争霸') !== false){
		$tag = '星际争霸';
	}else if(strpos($match[1],'堡垒之夜') !== false){
		$tag = '堡垒之夜';
	}else if(strpos($match[1],'彩虹六号') !== false){
		$tag = '彩虹六号';
	}else if(strpos($match[1],'最终幻想') !== false){
		$tag = '最终幻想';
	}else if(strpos($match[1],'暗黑破坏神') !== false){
		$tag = '暗黑破坏神';
	}else if(strpos($match[1],'虚幻争霸') !== false){
		$tag = '虚幻争霸';
	}else if(strpos($match[1],'掠食') !== false){
		$tag = '掠食';
	}else if(strpos($match[1],'地平线') !== false){
		$tag = '地平线';
	}else if(strpos($match[1],'战神') !== false){
		$tag = '战神';
	}else if(strpos($match[1],'真人快打') !== false){
		$tag = '真人快打';
	}else if(strpos($match[1],'侠盗猎车手') !== false){
		$tag = '侠盗猎车手';
	}else if(strpos($match[1],'街头霸王') !== false){
		$tag = '街头霸王';
	}else if(strpos($match[1],'文明') !== false){
		$tag = '文明';
	}else if(strpos($match[1],'炉石传说') !== false){
		$tag = '炉石传说';
	}else if(strpos($match[1],'雷神之锤') !== false){
		$tag = '雷神之锤';
	}else if(strpos($match[1],'极限竞速') !== false){
		$tag = '极限竞速';
	}else if(strpos($match[1],'神秘海域') !== false){
		$tag = '神秘海域';
	}else if(strpos($match[1],'国土防线') !== false){
		$tag = '国土防线';
	}else if(strpos($match[1],'风暴英雄') !== false){
		$tag = '风暴英雄';
	}else if(strpos($match[1],'全面战争') !== false){
		$tag = '全面战争';
	}else if(strpos($match[1],'星际公民') !== false){
		$tag = '星际公民';
	}else if(strpos($match[1],'漫威') !== false){
		$tag = '漫威';
	}else if(strpos($match[1],'质量效应') !== false){
		$tag = '质量效应';
	}else if(strpos($match[1],'生还者') !== false){
		$tag = '生还者';
	}else if(strpos($match[1],'坦克世界') !== false){
		$tag = '坦克世界';
	}else if(strpos($match[1],'战锤') !== false){
		$tag = '战锤';
	}else if(strpos($match[1],'神之浩劫') !== false){
		$tag = '神之浩劫';
	}else if(strpos($match[1],'德军总部') !== false){
		$tag = '德军总部';
	}else if(strpos($match[1],'极品飞车') !== false){
		$tag = '极品飞车';
	}else if(strpos($match[1],'穿越火线') !== false){
		$tag = '穿越火线';
	}else if(strpos($match[1],'魔兽世界') !== false){
		$tag = '魔兽世界';
	}else if(strpos($match[1],'战舰世界') !== false){
		$tag = '战舰世界';
	}else if(strpos($match[1],'咒语力量') !== false){
		$tag = '咒语力量';
	}else if(strpos($match[1],'泰坦陨落') !== false){
		$tag = '泰坦陨落';
	}else if(strpos($match[1],'铁拳') !== false){
		$tag = '铁拳';
	}else if(strpos($match[1],'战地') !== false){
		$tag = '战地';
	}else if(strpos($match[1],'毁灭战士') !== false){
		$tag = '毁灭战士';
	}else if(strpos($match[1],'传说对决') !== false){
		$tag = '传说对决';
	}else if(strpos($match[1],'魔兽争霸') !== false){
		$tag = '魔兽争霸';
	}else if(strpos($match[1],'战争机器') !== false){
		$tag = '战争机器';
	}else if(strpos($match[1],'美国末日') !== false){
		$tag = '美国末日';
	}else if(strpos($match[1],'神界原罪') !== false){
		$tag = '神界原罪';
	}else if(strpos($match[1],'星球大战') !== false){
		$tag = '星球大战';
	}else if(strpos($match[1],'马里奥') !== false){
		$tag = '马里奥';
	}else if(strpos($match[1],'故土') !== false){
		$tag = '故土';
	}else if(strpos($match[1],'Darksiders') !== false){
		$tag = '暗黑血统';
	}else if(strpos($match[1],'荣耀战魂') !== false){
		$tag = '荣耀战魂';
	}else if(strpos($match[1],'不义联盟') !== false){
		$tag = '不义联盟';
	}else if(strpos($match[1],'幽灵行动') !== false){
		$tag = '幽灵行动';
	}else if(strpos($match[1],'帕拉贡') !== false){
		$tag = '帕拉贡';
	}else if(strpos($match[1],'杀手') !== false){
		$tag = '杀手';
	}else if(strpos($match[1],'恶灵附身') !== false){
		$tag = '恶灵附身';
	}else if(strpos($match[1],'飙酷车神') !== false){
		$tag = '飙酷车神';
	}else if(strpos($match[1],'开拓者') !== false){
		$tag = '开拓者';
	}else if(strpos($match[1],'群星') !== false){
		$tag = '群星';
	}else if(strpos($match[1],'索尼克') !== false){
		$tag = '索尼克';
	}else if(strpos($match[1],'乐高') !== false){
		$tag = '乐高';
	}else if(strpos($match[1],'全境封锁') !== false){
		$tag = '全境封锁';
	}else if(strpos($match[1],'虚幻竞技场') !== false){
		$tag = '虚幻竞技场';
	}else if(strpos($match[1],'星战前夜') !== false){
		$tag = '星战前夜';
	}else if(strpos($match[1],'塞尔达') !== false){
		$tag = '塞尔达';
	}else if(strpos($match[1],'剑圣') !== false){
		$tag = '剑圣';
	}else if(strpos($match[1],'上古卷轴') !== false){
		$tag = '上古卷轴';
	}else if(strpos($match[1],'巫师') !== false){
		$tag = '巫师';
	}else if(strpos($match[1],'无尽对决') !== false){
		$tag = '无尽对决';
	}else if(strpos($match[1],'羞辱') !== false){
		$tag = '羞辱';
	}else if(strpos($match[1],'银河霸主') !== false){
		$tag = '银河霸主';
	}else if(strpos($match[1],'泰坦工业') !== false){
		$tag = '泰坦工业';
	}else if(strpos($match[1],'革命曲途') !== false){
		$tag = '革命曲途';
	}else if(strpos($match[1],'永恒空间') !== false){
		$tag = '永恒空间';
	}else if(strpos($match[1],'黑手党') !== false){
		$tag = '黑手党';
	}else if(strpos($match[1],'崩解') !== false){
		$tag = '崩解';
	}else if(strpos($match[1],'废土') !== false){
		$tag = '废土';
	}else if(strpos($match[1],'哥特舰队') !== false){
		$tag = '哥特舰队';
	}else if(strpos($match[1],'超神英雄') !== false){
		$tag = '超神英雄';
	}else if(strpos($match[1],'僵尸部队') !== false){
		$tag = '僵尸部队';
	}else if(strpos($match[1],'超级房车赛') !== false){
		$tag = '超级房车赛';
	}else if(strpos($match[1],'尘埃拉力赛') !== false){
		$tag = '尘埃拉力赛';
	}else if(strpos($match[1],'孤岛惊魂') !== false){
		$tag = '孤岛惊魂';
	}else if(strpos($match[1],'光环') !== false){
		$tag = '光环';
	}else if(strpos($match[1],'耻辱') !== false){
		$tag = '耻辱';
	}else if(strpos($match[1],'光晕') !== false){
		$tag = '光晕';
	}else if(strpos($match[1],'天堂') !== false){
		$tag = '天堂';
	}else if(strpos($match[1],'宝可梦') !== false){
		$tag = '宝可梦';
	}else if(strpos($match[1],'半衰期') !== false){
		$tag = '半衰期';
	}else if(strpos($match[1],'战争之影') !== false){
		$tag = '战争之影';
	}else if(strpos($match[1],'鬼哭邦') !== false){
		$tag = '鬼哭邦';
	}else if(strpos($match[1],'无畏') !== false){
		$tag = '无畏';
	}else if(strpos($match[1],'装甲战') !== false){
		$tag = '装甲战';
	}else if(strpos($match[1],'罗宾逊') !== false){
		$tag = '罗宾逊';
	}else if(strpos($match[1],'战争雷霆') !== false){
		$tag = '战争雷霆';
	}else if(strpos($match[1],'明日之后') !== false){
		$tag = '明日之后';
	}else if(strpos($match[1],'阿尔比恩') !== false){
		$tag = '阿尔比恩';
	}else if(strpos($match[1],'黎明杀机') !== false){
		$tag = '黎明杀机';
	}else if(strpos($match[1],'荒野大镖客') !== false){
		$tag = '荒野大镖客';
	}else if(strpos($match[1],'命令与征服') !== false){
		$tag = '命令与征服';
	}else if(strpos($match[1],'腐化') !== false){
		$tag = '腐化';
	}else if(strpos($match[1],'枪火游侠') !== false){
		$tag = '枪火游侠';
	}else if(strpos($match[1],'小马宝莉') !== false){
		$tag = '小马宝莉';
	}else if(strpos($match[1],'使命召唤') !== false){
		$tag = '使命召唤';
	}else if(strpos($match[1],'黑道圣徒') !== false){
		$tag = '黑道圣徒';
	}else if(strpos($match[1],'永远的7日之都') !== false){
		$tag = '永远的7日之都';
	}else if(strpos($match[1],'明日方舟') !== false){
		$tag = '明日方舟';
	}else{
		// $tag1 = $match[1];
	}

	if(empty($tag)){
		continue;
	}
	$tagsql = "SELECT * FROM zf_image_tag where name='{$tag}' limit 1";
	$tagData = $mysql->doSql($tagsql);
	if(empty($tagData)){
		$insert = array();
		$insert['name'] = $tag;
		$insert['createtime'] = date('Y-m-d H:i:s');
		$mysql->insert('zf_image_tag',$insert);

		$tagsql = "SELECT * FROM zf_image_tag where name='{$tag}' limit 1";
		$tagData = $mysql->doSql($tagsql);
	}

	$tagupdatesql = 'update zf_images set `tag`='.$tagData['0']['id'].' where id='.$val['id'];
	$mysql->doSql($tagupdatesql);
}
die;
*/
$where = '1';
$where .= ' and (tag is null or tag="")';
$sql = "SELECT * FROM zf_images where {$where}";
$res = $mysql->doSql($sql);

$tagsql = "SELECT * FROM zf_image_tag";
$tagData = $mysql->doSql($tagsql);

$arr = array();
foreach($res as $key=>$val){
	var_dump($val['name']);

	$tagid = 0;
	foreach($tagData as $tk=>$tv){
		if(strpos($val['name'],$tv['name']) !== false){
			$tagid = $tv['id'];
			break;
		}
	}

	if(empty($tagid)){
		continue;
	}

	$tagupdatesql = 'update zf_images set `tag`='.$tagid.' where id='.$val['id'];
	$mysql->doSql($tagupdatesql);
}
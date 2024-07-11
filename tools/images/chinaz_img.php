<?php
date_default_timezone_set("PRC");
header("Content-type: text/html; charset=utf-8");
include_once '../index.php';

error_reporting('E_NOTICE');
// ini_set("display_errors", "On");//打开错误提示
// ini_set("error_reporting",E_ALL);//显示所有错误

include_once S_PATH.'/conf/core.fun.php';
include_once S_PATH.'/class/simple_html_dom.php'; # html 解析
include_once S_PATH.'/class/database.php';
include_once S_PATH.'/class/MySql.php';  # mysql

# 有几个脚本执行
$num = exec("ps aux | grep 'chinaz_img.php' | grep -v grep | wc -l");
if($num>1){
  exit(date('Y-m-d').' 已经有脚本了');
}

/*
# 测试用例
include_once 'simple_html_dom.php';
$url = 'http://www.phpernote.com/';
$html = file_get_html($url);
$logoImg = $html->find('img#logoImg', 0);

// 获取 logo 图片地址
echo $logoImg->src; //输出：/images/logo.gif
// 获取 logo 的链接地址
echo $logoImg->parent()->href; //输出：http://www.phpernote.com/
// 输出最新文章列表
$left = $html->find('#left_sidebar ul', 0);
echo $left->children(2)->find('ul', 0)->innertext;
*/

class chinazImgAnlysis
{
	public $url = 'https://sc.chinaz.com/tupian/dongman.html';
	public $html = '';

	public function __construct()
	{
		$this->html = file_get_html($this->url);
		# 解析 html 
		$dom = $this->html_analysis_label('div', '', 'com-img-txt-list');

		# 整理
		$domLs = $dom[0]->children;
		foreach($domLs as $dkey=>$dval){
			var_dump($dval);die;
		}
	}

	# 解析html 并返回标签结构
	public function html_analysis_label($label='', $id='', $class='') {

		$style = $find = '';
		if (!empty($id)) {
			$style = '#'.$id;
		}
		if (!empty($class)) {
			$style = '.'.$class;
		}

		if (!empty($label)) {
			$find = $label.$style;
		}

		if (empty($style) || empty($find)) {
			return false;
		}

		$dom = $this->html->find($find);

		return $dom;
	}
}


$dom = new chinazImgAnlysis();

$dom->html_analysis_label();
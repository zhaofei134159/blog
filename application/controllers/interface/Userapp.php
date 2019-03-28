<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Userapp extends Home_Controller{

	public $blogId = 2; 
	public $wxprogramappid = 'wxc0c4a718e3688f4f';
	public $wxprogramappsecret = '47737efeb2ac9bd4bcfd87b0674f51b4';

	public function __construct(){
		parent::__construct();

		$this->load->model('zf_blog_model');
		$this->load->model('zf_work_model');
		$this->load->model('zf_cate_model');
		$this->load->model('zf_tag_model');
		$this->load->model('zf_user_model');
        $this->load->library('pager');
		$this->load->helper('wxbizdatacrypt');
	}

	public function getuserinfo()
	{
		$appid = $this->wxprogramappid;
		$appsecret  = $this->wxprogramappsecret;//小程序的 app secret (在微信小程序管理后台获取)

		$userInfo = $_POST['userInfo'];
		$encryptedData = $_POST['encryptedData'];
		$iv = $_POST['iv'];
		$code = $_POST['code'];
		$grant_type = "authorization_code"; //授权（必填）
		 
		$params = "appid=".$appid."&secret=".$appsecret."&js_code=".$code."&grant_type=".$grant_type;
		$url = "https://api.weixin.qq.com/sns/jscode2session?".$params;
		$res = json_decode($this->httpGet($url),true);
		$sessionKey = $res['session_key'];//取出json里对应的值

		$pc = new WXBizDataCrypt($appid, $sessionKey);
		$errCode = $pc->decryptData($encryptedData, $iv, $data);

		if ($errCode == 0) {
		    print($data);
		} else {
		    print($errCode);
		}
		
	}


	function httpGet($url) {
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($curl, CURLOPT_URL, $url);
	    $res = curl_exec($curl);
	    curl_close($curl);
	    return $res;
  	}
	
}
<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ziweiuserapp extends Home_Controller{

	public $blogId = 1; 
	public $wxprogramappid = 'wxc0c4a718e3688f4f';
	public $wxprogramappsecret = '47737efeb2ac9bd4bcfd87b0674f51b4';

	public function __construct(){
		parent::__construct();
	
		# 引入数据
		$this->load->helper('ziwei');
		$this->load->config('ziwei');

        $this->fortune = $this->load->database('fortune', TRUE);


		$this->load->helper('htmlrepair');
		// $this->load->model('ziwei_palace_info');
		// $this->load->model('ziwei_star_palace_info');
		// $this->load->model('ziwei_starlight_info');
        $this->load->library('pager');

		$this->load->helper('wxbizdatacrypt');
	}

	public function getuserinfo()
	{
		$appid = $this->wxprogramappid;
		$appsecret  = $this->wxprogramappsecret;//小程序的 app secret (在微信小程序管理后台获取)

		$userInfo = $_POST['userInfo'];
		$encryptedData = $_POST['encryptedData'];
		$iv = urldecode($_POST['iv']);
		$code = $_POST['code'];
		$grant_type = "authorization_code"; //授权（必填）
		 
		$params = "appid=".$appid."&secret=".$appsecret."&js_code=".$code."&grant_type=".$grant_type;
		$url = "https://api.weixin.qq.com/sns/jscode2session?".$params;
		$res = json_decode($this->httpGet($url),true);
		$sessionKey = $res['session_key'];//取出json里对应的值

		$pc = new WXBizDataCrypt($appid, $sessionKey);
		$errCode = $pc->decryptData($encryptedData, $iv, $data);

		if($errCode!=0){
			$arr = array();
			$arr['flag'] = false;
			$arr['msg'] = '用户信息获取错误, 错误:'.$errCode;
		  	echo json_encode($arr); 
		  	die;
		}

		$result = json_decode($data,true);
		# 用户信息获取成功
		$phone_where = "weixin_openid='".$result['openId']."'";
		$ziweiUser = $this->fortune->query("SELECT * from ziwei_user where {$phone_where}");
		if(empty($ziweiUser['0'])){
			$headimg = $this->_save_external_user_avatar($result['avatarUrl']);
			$login_stat = rand(1000,9999);

			$ziwei_user = array();
			$ziwei_user['login_stat'] = $login_stat;
			$ziwei_user['password'] = md5(md5($result['openId']).$login_stat);
			$ziwei_user['sex'] = $result['gender'];
			$ziwei_user['name'] =  $result['nickName'];
			$ziwei_user['nikename'] =  $result['nickName'];
			$ziwei_user['headimg'] = $headimg;
			$ziwei_user['weixin_openid'] = $result['openId'];
			$ziwei_user['user_type'] = 4;
			$ziwei_user['ctime'] = time();
			$ziwei_user['utime'] = time();

			$keyLs = implode(',', array_keys($ziwei_user));
			$valueLs = "'".implode("', '", array_values($ziwei_user))."'";

			$insertSql = "insert into ziwei_user({$keyLs) values({$valueLs})";

			$this->fortune->query($insertSql);
		}else{
			$ziwei_user = $ziweiUser;
		}

		$arr = array();
		$arr['flag'] = true;
		$arr['msg'] = '用户信息获取成功';
		$arr['data'] = $ziwei_user;

		outputJson($arr);
	}

	public function openidGetUserinfo()
	{
		$openid = $_POST['openid'];
		if(empty($openid)){
			$arr = array();
			$arr['flag'] = false;
			$arr['msg'] = '用户openid为空';
		  	echo json_encode($arr); 
		  	die;
		}

		$phone_where = "weixin_openid='".$openid."'";
		$ziweiUser = $this->fortune->query("SELECT * from ziwei_user where {$phone_where}");
		
		$arr = array();
		$arr['flag'] = true;
		$arr['msg'] = '用户信息获取成功';
		$arr['data'] = $ziweiUser[0];
	  	
		outputJson($arr);
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


    /****
    * @desc 保存用户的头像
    * @param $url
    * @return string
    */
    private function _save_external_user_avatar($url){	

        $content = $this->_curl_get_request($url);
            
        if ($content != false) {
            $save_dir = PUBLIC_URL.'headimg';
            $save_dir = trim($save_dir,'/');

            $file_name = date('YmdHis') . rand(10000, 99999) . '.jpg';
            $save_path = $save_dir . '/' . $file_name;

            file_put_contents($save_path, $content);
            $web_path = $save_dir . '/' . $file_name;

            return $web_path;
        }
        else
        {
            return '';
        }
    }

    private function _curl_get_request($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,  $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $return_str = curl_exec($ch);
        curl_close($ch);
        $format_result = json_decode($return_str, true);
        return $format_result ? $format_result : $return_str;
    }

    public function setMesssageImage(){
		$file = $_FILES['file'];

		echo upload_img($file,'messageimg');
		die;
    }

	
}
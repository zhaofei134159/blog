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
		$blogUser = $this->zf_user_model->select_one($phone_where);
		if(empty($blogUser)){
			$headimg = $this->_save_external_user_avatar($result['avatarUrl']);

			$zf_user = array();
			$login_stat = rand(1000,9999);
			$zf_user['login_stat'] = $login_stat;
			$zf_user['password'] = md5(md5($result['openId']).$login_stat);
			$zf_user['sex'] = $result['gender'];
			$zf_user['name'] =  $result['nickName'];
			$zf_user['nikename'] =  $result['nickName'];
			$zf_user['headimg'] = $headimg;
			$zf_user['weixin_openid'] = $result['openId'];
			$zf_user['user_type'] = 4;
			$zf_user['ctime'] = time();
			$zf_user['utime'] = time();
			$uid = $this->zf_user_model->insert($zf_user);
		}else{
			$zf_user = $blogUser;
		}

		$arr = array();
		$arr['flag'] = true;
		$arr['msg'] = '用户信息获取成功';
		$arr['data'] = $zf_user;
	  	echo json_encode($arr); 
	  	die;
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
        if (strpos($url, 'http://') !== 0){
            return '';
        }

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

	
}
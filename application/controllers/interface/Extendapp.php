<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set("Asia/Shanghai"); //设置时区

class Extendapp extends Home_Controller{

	# 图文识别的appKey
	public $picToWordAppkey = '9PZah23T4Yaa1pePDzCdCzwR';
	public $picToWordSecretkey = 'ehvwNTa1Y3VbTjXEEfbEF57eeRX2s2uj';

	public function __construct(){
		parent::__construct();

		$this->load->helper('common');
		$this->load->helper('htmlrepair');
		$this->load->model('zf_blog_model');
		$this->load->model('zf_work_model');
		$this->load->model('zf_cate_model');
		$this->load->model('zf_tag_model');
		$this->load->model('zf_user_model');
        $this->load->library('pager');
	}

	public function picToWord(){
		$file = $_FILES['file'];

		$picFile = upload_img($file,'picToWord');

		# 获取百度的 access_token
		$result = $this->getBdAccessToken();
		$resultArr = json_decode($result,true);
	    if(!isset($resultArr['access_token']) || empty($resultArr['access_token'])){
	    	$callback = array('error'=>'百度token获取错误','errorNo'=>'101');
	    	exit(json_encode($callback));
	    }

	    # 获取百度图文识别后的返回
		$token = $resultArr['access_token'];
		$url = 'https://aip.baidubce.com/rest/2.0/ocr/v1/webimage?access_token=' . $token;
		// $img = file_get_contents($picFile);
		// $img = base64_encode($img);
		$bodys = array(
		    // 'image' => $img,
		    'url' => 'http://blog.myfeiyou.com/'.$picFile,
		    'detect_direction' => true,
		    'detect_language' => true,
		);
		$res = $this->request_post($url, $bodys);

		var_dump($res);

	}

	/**
	* 获取百度的access_token
	* @return 百度返回
	*/
	function getBdAccessToken(){

		$url = 'https://aip.baidubce.com/oauth/2.0/token';
	    $post_data['grant_type'] = 'client_credentials';
	    $post_data['client_id'] = $this->picToWordAppkey;
	    $post_data['client_secret'] = $this->picToWordSecretkey;
	    $o = "";
	    foreach ( $post_data as $k => $v ) 
	    {
	    	$o.= "$k=" . urlencode( $v ). "&" ;
	    }
	    $post_data = substr($o,0,-1);
	    $res = $this->request_post($url, $post_data);

	    return $res;
	}

	/**
	* 发起http post请求(REST API), 并获取REST请求的结果
	* @param string $url
	* @param string $param
	* @return - http response body if succeeds, else false.
	*/
	function request_post($url = '', $param = '')
	{
		if (empty($url) || empty($param)) {
			return false;
		}

		$postUrl = $url;
		$curlPost = $param;
		// 初始化curl
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $postUrl);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		// 要求结果为字符串且输出到屏幕上
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		// post提交方式
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
		// 运行curl
		$data = curl_exec($curl);
		curl_close($curl);

		return $data;
	}
	
}

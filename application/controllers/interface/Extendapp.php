<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set("Asia/Shanghai"); //设置时区

class Extendapp extends Home_Controller{

	# 图文识别的appKey
	public $picToWordAppId = '11521585';
	public $picToWordAppkey = '9PZah23T4Yaa1pePDzCdCzwR';
	public $picToWordSecretkey = 'ehvwNTa1Y3VbTjXEEfbEF57eeRX2s2uj';

	# 语音处理
	public $voiceAppId = '20349172';
	public $voiceAppkey = 'GY3XkTZKNwElpcTknlWUSo0A';
	public $voiceSecretkey = 'N51MrcfuKMrhGhF3F9Du8EgMt4GZgmdn';

	public function __construct(){
		parent::__construct();

		$this->load->helper('common');
		$this->load->helper('htmlrepair');
		$this->load->model('zf_blog_model');
		$this->load->model('zf_work_model');
		$this->load->model('zf_cate_model');
		$this->load->model('zf_tag_model');
		$this->load->model('zf_user_model');
        $this->load->library('voice/aipSpeech',array($this->voiceAppId,$this->voiceAppkey,$this->voiceSecretkey));
	}

	// 图文转换文字
	public function picToWord(){
		$file = $_FILES['file'];

		$picFile = upload_img($file,'picToWord');

		# 获取百度的 access_token
		$result = $this->getBdAccessToken();
		$resultArr = json_decode($result,true);
	    if(!isset($resultArr['access_token']) || empty($resultArr['access_token'])){
	    	$callback = array('errorMsg'=>'百度token获取错误','errorNo'=>'101');
	    	exit(json_encode($callback));
	    }

	    # 获取百度图文识别后的返回
		$token = $resultArr['access_token'];
		$url = 'https://aip.baidubce.com/rest/2.0/ocr/v1/webimage?access_token=';
		$wordRes = $this->getBdPicToWord($url,$token,$picFile);
		$wordResArr = json_decode($wordRes,true);
		if(empty($wordResArr['words_result_num'])){
			$url = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general_basic?access_token=';
			$wordRes = $this->getBdPicToWord($url,$token,$picFile);
			$wordResArr = json_decode($wordRes,true);
		}
		
		if(empty($wordResArr['words_result_num'])){
			$callback = array('errorMsg'=>'未识别出文字','errorNo'=>'109');
	    	exit(json_encode($callback));
		}

		@unlink($picFile);

		$callback = array('errorMsg'=>'','errorNo'=>'0','seccuss'=>$wordResArr);
    	exit(json_encode($callback));
	}

	// 录音转换文字
	public function voiceToWord(){
		// $file = $_FILES['file'];
		// $picFile = upload_file($file,'voiceToWord');
		$picFile = 'public/public/voiceToWord/2020061115390652348.mp3';

		// 你的 APPID AK SK 
		var_dump($this->voiceAppId);
		var_dump($this->voiceAppkey);
		var_dump($this->voiceSecretkey);
		$word = $this->AipSpeech->asr(file_get_contents($picFile), 'mp3', 16000, array(
		    'dev_pid' => 1537,
		));
		var_dump($word);
		// @unlink($picFile);

		// $callback = array('errorMsg'=>'','errorNo'=>'0','seccuss'=>$wordResArr);
  //   	exit(json_encode($callback));
	}

	/**
	* 获取百度图文信息
	* @return 图文信息
	*/
	function getBdPicToWord($url,$token,$file){

		$url = $url . $token;
		$bodys = array(
		    'url' => 'http://blog.myfeiyou.com/'.$file,
		    'detect_direction' => true,
		    'detect_language' => true,
		);
		$res = $this->request_post($url, $bodys);

		return $res;
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

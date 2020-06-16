<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set("Asia/Shanghai"); //设置时区

class Extendapp extends Home_Controller{

	# 图文识别的appKey
	public $picToWordAppId = '';
	public $picToWordAppkey = '';
	public $picToWordSecretkey = '';

	# 语音处理
	public $voiceAppId = '';
	public $voiceAppkey = '';
	public $voiceSecretkey = '';

	//腾讯
	public $secretId = '';
	public $secretKey = '';
	
	public function __construct(){
		parent::__construct();

		$this->load->helper('common');
		$this->load->helper('htmlrepair');
		$this->load->model('zf_blog_model');
		$this->load->model('zf_work_model');
		$this->load->model('zf_cate_model');
		$this->load->model('zf_tag_model');
		$this->load->model('zf_user_model');
		$this->load->config('secretkey');

        $this->picToWordAppId = $this->config->item('picToWordAppId');
        $this->picToWordAppkey = $this->config->item('picToWordAppkey');
        $this->picToWordSecretkey = $this->config->item('picToWordSecretkey');

        $this->voiceAppId = $this->config->item('voiceAppId');
        $this->voiceAppkey = $this->config->item('voiceAppkey');
        $this->voiceSecretkey = $this->config->item('voiceSecretkey');

        $this->secretId = $this->config->item('secretId');
        $this->secretKey = $this->config->item('secretKey');


		$voiceArr = array(
			'appId'=>$this->voiceAppId,
			'apiKey'=>$this->voiceAppkey,
			'secretKey'=>$this->voiceSecretkey
		);
        $this->load->library('voice/AipSpeech',$voiceArr,'my_speech');
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
		$blogUrl = 'https://blog.myfeiyou.com/';

		// $file = $_FILES['file'];
		// $voiceFile = upload_file($file,'voiceToWord');
		// $voiceFile = 'public/public/voiceToWord/2020061516045033604.mp3';

		# 百度不支持MP3 
		// $word = $this->my_speech->asr(file_get_contents($blogUrl.$pcmFile), 'pcm', 16000, array(
		//     'lan' => 'zh',
		// ));

		# 使用腾讯
		
		// sort($params)
		// $srcStr  = 'GETasr.tencentcloudapi.com/?'.http_build_query($params);
		// $signStr = base64_encode(hash_hmac('sha1', $srcStr, $this->config->item('SecretKey'), true));


		$params = array();
		$params['Action'] = 'CreateRecTask';
		$params['Version'] = '2019-06-14';
		$params['Timestamp'] = time();
		$params['Nonce'] = rand(10000000,99999999);
		$params['ChannelNum'] = 1;
		$params['EngineModelType'] = '16k_zh';
		$params['ResTextFormat'] = 1;
		$params['SourceType'] = 0;
		$params['Url'] = 'https://blog.myfeiyou.com/public/public/voiceToWord/2020061516045033604.mp3';
		ksort($params);

		$signStr = "GETasr.tencentcloudapi.com/?";
		foreach ( $params as $key => $value ) {
		    $signStr = $signStr . $key . "=" . $value . "&";
		}
		$signStr = substr($signStr, 0, -1);

		$signature  = base64_encode(hash_hmac('sha1', $signStr, $this->secretKey, true));
		echo $signature.PHP_EOL;
		die;



		if(!empty($word['err_no'])){
			$callback = array('errorMsg'=>$word['err_msg'],'errorNo'=>$word['err_no']);
			exit(json_encode($callback));
		}

		// @unlink($voiceFile);

		$wordArr = array();
		$wordArr['voicePath'] = $voiceFile;
		$wordArr['word'] = $word['result'];

		$callback = array('errorMsg'=>$word['err_msg'],'errorNo'=>'0','seccuss'=>$wordArr);
    	exit(json_encode($callback));
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

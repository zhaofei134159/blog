<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set("Asia/Shanghai"); //设置时区

class Refuseapp extends Home_Controller{

	# 京东
	public $refuseAppKey = '';
	public $refuseSecretKey = '';
	
	# 请求地址	
	public	$blogUrl = 'https://blog.myfeiyou.com/';

	public function __construct(){
		parent::__construct();

		$this->load->helper('common');
		$this->load->helper('htmlrepair');
		$this->load->model('Zf_garbage_voice_model');
		$this->load->config('secretkey');

        $this->refuseAppKey = $this->config->item('refuseAppKey');
        $this->refuseSecretKey = $this->config->item('refuseSecretKey');
	}

	public function refuseWordSearchDiscern(){
		$search = $_GET['search'];
		
		if(empty($search)){
			$callback = array('errorMsg'=>'输入文字不能为空','errorNo'=>2001);
	    	exit(json_encode($callback));
		}

		# 获取毫秒时间戳
		$timestamp = $this->getMillisecond();

		$url = 'https://aiapi.jd.com/jdai/garbageTextSearch?';
		$query = array();
		$query['appkey'] = $this->refuseAppKey;
		$query['timestamp'] = $timestamp;
		$query['sign'] = $this->sign($timestamp);

		$url .= $this->getUrlString($query);

		$param = array();
		$param['text'] = $search;
		$param['cityId'] = '110000';

		$header = array('Content-Type:application/json;charset=UTF-8');

		$result = $this->request($url,json_encode($param),$header);
		// @unlink($picFile);
		var_dump($result);

		$resultArr = json_decode($result,true);
		if($resultArr['result']['status']!=0){
			$callback = array('errorMsg'=>$resultArr['result']['message'],'errorNo'=>$resultArr['result']['status']);
	    	exit(json_encode($callback));
		}

		foreach($resultArr['result']['garbage_info'] as $key=>$val){
			$resultArr['result']['garbage_info'][$key]['ps'] = str_replace('投放建议：','',$val['ps']); 
		}
		$success = array();
		$success['garbage_info'] = $resultArr['result']['garbage_info'];
		$success['textSearch'] = $search;

		$callback = array('errorMsg'=>$resultArr['result']['message'],'errorNo'=>'0','success'=>$success);
    	exit(json_encode($callback));
	}

	public function refuseEntityDiscern(){
		$file = $_FILES['file'];
		$picFile = upload_img($file,'refuseImg');

		# 获取毫秒时间戳
		$timestamp = $this->getMillisecond();

		$url = 'https://aiapi.jd.com/jdai/garbageImageSearch?';
		$query = array();
		$query['appkey'] = $this->refuseAppKey;
		$query['timestamp'] = $timestamp;
		$query['sign'] = $this->sign($timestamp);

		$url .= $this->getUrlString($query);

		$param = array();
		$param['imgBase64'] = $this->imgBase64(BLOGURL.'/'.$picFile);
		$param['cityId'] = '110000';

		$header = array('Content-Type:application/json;charset=UTF-8');

		$result = $this->request($url,json_encode($param),$header);
		// @unlink($picFile);

		$resultArr = json_decode($result,true);
		if($resultArr['result']['status']!=0){
			$callback = array('errorMsg'=>$resultArr['result']['message'],'errorNo'=>$resultArr['result']['status']);
	    	exit(json_encode($callback));
		}

		foreach($resultArr['result']['garbage_info'] as $key=>$val){
			$resultArr['result']['garbage_info'][$key]['ps'] = str_replace('投放建议：','',$val['ps']); 
		}
		$success = array();
		$success['garbage_info'] = $resultArr['result']['garbage_info'];
		$success['imgFile'] = $this->blogUrl.$picFile;

		$callback = array('errorMsg'=>$resultArr['result']['message'],'errorNo'=>'0','success'=>$success);
    	exit(json_encode($callback));
	}

	public function refuseVoiceDiscern(){
		// $file = $_FILES['file'];
		// $voiceFile = upload_file($file,'refuseVoice');

		$voiceFile = 'public/public/refuseVoice/1592546147033.mp3';

		# 获取毫秒时间戳
		$timestamp = $this->getMillisecond();

		$url = 'https://aiapi.jd.com/jdai/garbageVoiceSearch?';
		$query = array();
		$query['appkey'] = $this->refuseAppKey;
		$query['timestamp'] = $timestamp;
		$query['sign'] = $this->sign($timestamp);
		$url .= $this->getUrlString($query);

		$propertyArr = array();
		$propertyArr['autoend'] = false;
		$propertyArr['encode']['channel'] = 1;
		$propertyArr['encode']['format'] = 'mp3';
		$propertyArr['encode']['sample_rate'] = 16000;
		$propertyArr['encode']['post_process'] = 0; # 开启后 一千 = 1000 
		$propertyArr['platform'] = 'Linux';
		$propertyArr['version'] = '0.0.0.1';

		$header = array(
			'cityId:110000',
			'property:'.json_encode($propertyArr),
		);

		$result = $this->request($url,'',$header,BLOGURL.'/'.$voiceFile);

		$resultArr = json_decode($result,true);
		if($resultArr['result']['status']!=0){
			$callback = array('errorMsg'=>$resultArr['result']['message'],'errorNo'=>$resultArr['result']['status']);
	    	exit(json_encode($callback));
		}

		foreach($resultArr['result']['garbage_info'] as $key=>$val){
			$resultArr['result']['garbage_info'][$key]['ps'] = str_replace('投放建议：','',$val['ps']);
		}
		$success = array();
		$success['garbage_info'] = $resultArr['result']['garbage_info'];
		$success['voiceFile'] = $this->blogUrl.$voiceFile;

		$callback = array('errorMsg'=>$resultArr['result']['message'],'errorNo'=>'0','success'=>$success);
    	exit(json_encode($callback));
	}

	/**
	* 发起http post请求(REST API), 并获取REST请求的结果
	* @param string $url
	* @param string $param
	* @return - http response body if succeeds, else false.
	*/
	public function request($url = '', $param = '', $header = array(), $file = '')
	{
		if (empty($url)) {
			return false;
		}

		// 初始化curl
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
	    if(!empty($header)){
	        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	    }
		// 要求结果为字符串且输出到屏幕上
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		// post提交方式
		if(!empty($param)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
		}
		if(!empty($file)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
			$fileData = array(
				'file'  => curl_file_create($file, mime_content_type($file), 'file'),
			);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $fileData);
		}
		// 运行curl
		$data = curl_exec($curl);
		curl_close($curl);

		return $data;
	}

	public function getMillisecond(){
		list($t1, $t2) = explode(' ', microtime());
		return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
	}

	public function imgBase64($file){
		$base64 = '';
	    if ($fp = fopen($file, "rb", 0)) {
	        $gambar = fread($fp, filesize($file));
	        fclose($fp);
	        $base64 = chunk_split(base64_encode($gambar));//这个是不带逗号的前面的base64编码
	    }

	    return $base64;
	}

	public function voiceData($file){
		$gambar = '';
	    if ($fp = fopen($file, "rb", 0)) {
	        $gambar = fread($fp, filesize($file));
	        fclose($fp);
	    }
	    return $gambar;
	}

	public function sign($timestamp){
		$str = MD5($this->refuseSecretKey.$timestamp);
		$encode = mb_detect_encoding($str, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
	    if($encode == 'UTF-8'){
	        return $str;
	    }else{
	        return mb_convert_encoding($str, 'UTF-8', $encode);
	    }
	} 

	/**
	 *数组 转化url参数
	 * @param string
	 * @return mixed
	 */
	public function getUrlString($array_query)
	{
	    $tmp = array();
	    foreach($array_query as $k=>$param)
	    {
	        $tmp[] = $k.'='.$param;
	    }
	    $params = implode('&',$tmp);
	    return $params;
	}

}
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

	public function refuseEntityDiscern(){
		// $file = $_FILES['file'];
		// $picFile = upload_img($file,'refuseImg');

		$picFile = 'public/public/refuseImg/2020061818592092484.jpg';

		# 获取毫秒时间戳
		$timestamp = $this->getMillisecond();

		$url = 'https://aiapi.jd.com/jdai/garbageImageSearch?';
		$query = array();
		$query['appkey'] = $this->refuseAppKey;
		$query['timestamp'] = $timestamp;
		$query['sign'] = MD5($this->refuseSecretKey.$timestamp);

		$url .= $this->getUrlString($query);

		$param = array();
		$param['imgBase64'] = $this->imgBase64(BLOGURL.'/'.$picFile);
		$param['cityId'] = '110000';

		$header = array('Content-Type:application/json;charset=UTF-8');

		$result = $this->request($url,$param,$header);
		var_dump($url);
		var_dump($result);die;
		// @unlink($picFile);

		$callback = array('errorMsg'=>'','errorNo'=>'0','seccuss'=>$wordResArr);
    	exit(json_encode($callback));
	}


	/**
	* 发起http post请求(REST API), 并获取REST请求的结果
	* @param string $url
	* @param string $param
	* @return - http response body if succeeds, else false.
	*/
	function request($url = '', $param = array(), $header = array())
	{
		if (empty($url)) {
			return false;
		}

		$postUrl = $url;
		$curlPost = $param;
		// 初始化curl
		$curl = curl_init();
	    if(!empty($header)){
	        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	        curl_setopt($curl, CURLOPT_HEADER, 0);//返回response头部信息
	    }
		curl_setopt($curl, CURLOPT_URL, $postUrl);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		// 要求结果为字符串且输出到屏幕上
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		// post提交方式
		if(!empty($curlPost)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
		}
		// 运行curl
		$data = curl_exec($curl);
		curl_close($curl);

		return $data;
	}

	private function getMillisecond(){
		list($t1, $t2) = explode(' ', microtime());
		return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
	}

	private function imgBase64($file){
		$base64 = '';
	    if ($fp = fopen($file, "rb", 0)) {
	        $gambar = fread($fp, filesize($file));
	        fclose($fp);
	        $base64 = chunk_split(base64_encode($gambar));//这个是不带逗号的前面的base64编码
	    }

	    return $base64;
	}

	/**
	 *数组 转化url参数
	 * @auth xieyang
	 * @date 2018年5月10日 13:51:31
	 * @param string
	 * @return mixed
	 */
	private function getUrlString($array_query)
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
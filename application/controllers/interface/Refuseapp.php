<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set("Asia/Shanghai"); //设置时区

class Extendapp extends Home_Controller{

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

	} 


}
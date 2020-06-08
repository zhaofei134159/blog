<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set("Asia/Shanghai"); //设置时区

class Extendapp extends Home_Controller{

	public function __construct(){
		parent::__construct();

		$this->load->helper('htmlrepair');
		$this->load->model('zf_blog_model');
		$this->load->model('zf_work_model');
		$this->load->model('zf_cate_model');
		$this->load->model('zf_tag_model');
		$this->load->model('zf_user_model');
        $this->load->library('pager');
	}

	public function picToWord(){
		$code = $_FILES['upload_file'];//获取小程序传来的图片
		if(!is_uploaded_file($_FILES['upload_file']['tmp_name'])) {  
			exit('error');
		}

		echo $_FILES['upload_file']['tmp_name'];
	}
	
}
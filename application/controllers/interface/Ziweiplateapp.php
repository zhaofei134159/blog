<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ziweiplateapp extends Home_Controller{

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

	public function index()
	{
		var_dump($_POST);die;
	}
}
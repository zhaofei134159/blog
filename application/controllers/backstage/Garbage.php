<?php 
/*
* 垃圾分类
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Garbage extends Admin_Controller {

	public $user_type = array();

	public function __construct(){
		parent::__construct();
		$this->load->helper('common');
        $this->load->library('pager');
		$this->load->model('zf_garbage_detail_model');
		$this->load->model('zf_garbage_type_model');
		$this->load->config('app');

		$user_type = $this->config->item('user_type');
		$this->load->vars('user_type',$user_type);

		//如果没有 则退出登录
		if(empty($this->adminid)){
			header('location:'.ADMIN_URL.'login/unlogin');
		}
	}

	public function index(){
		$post = $this->input->post();

		$where = ' 1 ';
		$garbageType = $this->zf_garbage_type_model->select($where,'*','createtime desc');

		$data = array(
				'garbageType' => $garbageType,
				'post'=>$post,
			);
		
		$this->load->view(ADMIN_URL.'garbage/index',$data);
	}

}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Friend extends Home_Controller{

	public function __construct(){
		parent::__construct();

		$this->load->model('zf_friend_model');
        $this->load->library('pager');
	}

	public function index()
	{
		$pagesize = 30;
		$offset = 0;
		
		$where = 'is_del=0';

		$friends = $this->zf_friend_model->select($where,'*','');

		$data = array(
				'friends'=>$friends,
			);
		$this->load->view(HOME_URL.'friend/index',$data);
	}
}
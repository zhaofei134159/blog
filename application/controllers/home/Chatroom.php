<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chatroom extends Home_Controller{

	public function __construct(){
		parent::__construct();

	}

	public function index()
	{
	
		$data = array();
		$this->load->view(HOME_URL.'chatroom/index',$data);
	}
}
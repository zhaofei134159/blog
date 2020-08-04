<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends Admin_Controller {

	public function __construct(){
		parent::__construct();

		if(empty($this->adminid)){
			header('location:'.ADMIN_URL.'login/unlogin');
		}
	}

	public function index()
	{
		$this->load->view(ADMIN_URL.'index');
	}

	


}

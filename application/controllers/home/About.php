<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends Home_Controller{

	public function __construct(){
		parent::__construct();

	}

	public function index()
	{
		$this->load->view(HOME_URL.'about/index');
	}
}
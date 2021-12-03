<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game extends Home_Controller{

	public function __construct(){
		parent::__construct();

        $this->load->library('pager');
	}

	public function hitbricks()
	{
		$data = array();
		$this->load->view(HOME_URL.'game/hitbricks',$data);
	}

	public function miner()
	{
		$data = array();
		$this->load->view(HOME_URL.'game/miner',$data);
	}
}
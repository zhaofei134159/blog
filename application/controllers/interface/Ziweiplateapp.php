<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'/../joint/class/lunar/vendor/autoload.php';
use com\nlf\calendar\util\LunarUtil;
use com\nlf\calendar\Lunar;
use com\nlf\calendar\Solar;


class Ziweiplateapp extends Home_Controller{

	public function __construct(){
		parent::__construct();

		$this->load->helper('ziwei');
		$this->load->config('ziwei');


		var_dump($this->config);
	}

	public function index()
	{
		var_dump($_POST);die;
	}
}
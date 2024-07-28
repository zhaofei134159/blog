<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ziwei_palace_info extends MYF_model {

	public function __construct(){
		parent::__construct();
		$this->init('ziwei_palace_info','id');
	}
}
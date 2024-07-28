<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ziwei_starlight_info_model extends MYF_model {

	public function __construct(){
		parent::__construct();
		$this->init('ziwei_starlight_info','id');
	}
}
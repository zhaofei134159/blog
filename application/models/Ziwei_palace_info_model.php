<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ziwei_palace_info_model extends Myf_model {

	public function __construct(){
		parent::__construct();
		$this->init('ziwei_palace_info','id');
	}
}
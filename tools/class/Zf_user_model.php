<?php 

class Zf_user_model extends My_Model {

	public function __construct(){
		parent::__construct();
		$this->init('zf_user','id');
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zf_cate_model extends My_Model {

	public function __construct(){
		parent::__construct();
		$this->init('zf_cate','id');
	}
}
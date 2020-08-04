<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zf_garbage_detail_model extends My_Model {

	public function __construct(){
		parent::__construct();
		$this->init('zf_garbage_detail','id');
	}
}
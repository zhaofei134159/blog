<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zf_image_tag_model extends My_Model {

	public function __construct(){
		parent::__construct();
		$this->init('zf_image_tag','id');
	}
}
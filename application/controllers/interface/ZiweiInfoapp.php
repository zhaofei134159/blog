<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ZiweiInfoapp extends Home_Controller{

	public function __construct(){
		parent::__construct();

		# 引入数据
		$this->load->helper('ziwei');
		$this->load->config('ziwei');

        $this->fortune = $this->load->database('fortune', TRUE);


		$this->load->helper('htmlrepair');
		// $this->load->model('ziwei_palace_info');
		// $this->load->model('ziwei_star_palace_info');
		// $this->load->model('ziwei_starlight_info');
        $this->load->library('pager');
	}

	public function index()
	{
		$palace = $this->fortune->query("SELECT * from ziwei_palace_info WHERE 1 LIMIT 20");

		$data = array(
				'palace'=>$palace->result(),
			);
		outputJson($data);
	}

	public function infoDetail(){
		$itemId = $_POST['itemId'];
		$itemType = $_POST['itemType'];

		$table = '';
		if($itemType == 'palace'){
			$table = 'ziwei_palace_info';
		}

		$palaceInfo = $this->fortune->query("SELECT * from {$table} WHERE id={$itemId} LIMIT 20");

		//点击量
		// $work['browse_num'] += 1;
		// $this->zf_work_model->update(array('browse_num'=>$work['browse_num']),$where);

		$data = array(
				'palaceInfo'=>$palaceInfo,
			);
		outputJson($data);
	}
}
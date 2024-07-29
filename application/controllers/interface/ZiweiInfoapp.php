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
		$star = $this->fortune->query("SELECT id,name,info,cate2,galaxy,lucky_type,known_saying,appearance,personality,advantage,body_parts from ziwei_starlight_info where info!='' LIMIT 50");
		$palaceStar = $this->fortune->query("SELECT * from ziwei_star_palace_info WHERE 1 LIMIT 1000");

		$data = array(
				'palace'=>$palace->result(),
				'star'=>$star->result(),
				'palaceStar'=>$palaceStar->result(),
			);
		outputJson($data);
	}

	public function infoDetail(){
		$itemId = $_POST['itemId'];
		$itemType = $_POST['itemType'];

		$table = '';
		$select = '*';
		if($itemType == 'palace'){
			$table = 'ziwei_palace_info';
		} else if ($itemType == 'star'){
			$select = "id,name,info,cate2,galaxy,lucky_type,known_saying,appearance,personality,advantage,body_parts from ziwei_starlight_info";
			$table = 'ziwei_starlight_info';
		} else if ($itemType == 'palaceStar'){
			$table = 'ziwei_star_palace_info';
		}

		$palaceInfo = $this->fortune->query("SELECT {$select} from {$table} WHERE id={$itemId}");

		// 点击量
		// $work['browse_num'] += 1;
		// $this->zf_work_model->update(array('browse_num'=>$work['browse_num']),$where);

		$data = array(
				'palaceInfo'=>$palaceInfo->result()['0'],
			);
		outputJson($data);
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Work extends Home_Controller{

	public function __construct(){
		parent::__construct();

		$this->load->model('zf_famou_work_model');
		$this->load->model('zf_famou_work_info_model');
        $this->load->library('pager');
		$this->load->helper('common');
		$this->load->config('app');
 		
 		if(empty($_SERVER['HTTP_REFERER'])){
			$callback = array('errorMsg'=>'go to home','errorNo'=>'1003');
	    	exit(json_encode($callback));
 		}

 		$HTTP_REFERER = trim($_SERVER['HTTP_REFERER'],'http://');
 		$HTTP_REFERER = trim($HTTP_REFERER,'https://');
 		$referer = array('104.243.18.161:8080','104.243.18.161:8081','104.243.18.161:8082','books.myfeiyou.com');
		if(!in_array($HTTP_REFERER,$referer)){
			$callback = array('errorMsg'=>'go to home','errorNo'=>'1004');
	    	exit(json_encode($callback));
		} 
	}

	public function index()
	{
		$get = $this->input->get();
		$page = !empty($get['page'])?$get['page']:1;

		$pagesize = 12;
		$offset = ($page-1)*$pagesize;

		$where = '1 and is_del=0';
		$worksCount = $this->zf_famou_work_model->count($where);

		$works = $this->zf_famou_work_model->get_list($where,'*','ctime desc',$pagesize,$offset);

		$data = array();
		$data['worksCount'] = $worksCount;
		$data['pagesize'] = $pagesize;
		$data['works'] = $works;

		$callback = array('errorMsg'=>'','errorNo'=>'0','seccuss'=>$data);
    	exit(json_encode($callback));
	}

	public function getFamouWorkInfo(){
		$post = $this->input->post();
		$workId = $post['workId'];
		if(empty($workId)){
			$callback = array('errorMsg'=>'参数错误','errorNo'=>'1010');
			exit(json_encode($callback));	
		}

		# workInfo
		$where = '1 and is_del=0';
		$where .= ' and id='.$workId;
		$work = $this->zf_famou_work_model->select_one($where);

		# workChapter
		$info_where = '1 and work_id='.$workId;
		$workInfo = $this->zf_famou_work_info_model->select($info_where,'*','index asc');

		$data = array();
		$data['work'] = $work;
		$data['workInfo'] = $workInfo;

		$callback = array('errorMsg'=>'','errorNo'=>'0','seccuss'=>$data);
		exit(json_encode($callback));
	}
}
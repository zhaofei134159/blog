<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Work extends Home_Controller{

	public function __construct(){
		parent::__construct();

		$this->load->model('zf_famou_work_model');
		$this->load->model('zf_famou_work_info_model');
        $this->load->library('pager');
		$this->load->helper('common');
		$this->load->config('app');

		$headers = array();
		foreach ($_SERVER as $key => $value) {
		    if ('HTTP_' == substr($key, 0, 5)) {
		        $headers[str_replace('_', '-', substr($key, 5))] = $value;
		    }
		}    
		echo '<pre>';
		print_r($headers);  
	}

	public function index()
	{
		$offset = 0;
		$pagesize = 12;

		$where = '1 and is_del=0';
		$worksCount = $this->zf_famou_work_model->count($where);

		$works = $this->zf_famou_work_model->select($where,'*','ctime desc',$offset,$pagesize);

		$data = array();
		$data['worksCount'] = $worksCount;
		$data['works'] = $works;

		$callback = array('errorMsg'=>'','errorNo'=>'0','seccuss'=>$data);
    	exit(json_encode($callback));
	}
}
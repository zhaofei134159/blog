<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Work extends Home_Controller{

	public function __construct(){
		parent::__construct();

		$this->load->model('zf_famou_work_model');
		$this->load->model('zf_famou_work_info_model');
        $this->load->library('pager');
		$this->load->helper('common');
		$this->load->config('app');
	}

	public function index()
	{
		$pagesize = 20;
		$offset = 0;
		
		$where = '1 and is_del=0';

        $leave_count = $this->zf_famou_work_model->count($where);
        list($offset, $leave_htm) = $this->pager->pagestring($leave_count, $pagesize);
		$leaves = $this->zf_famou_work_model->get_list($where,'*','ctime desc',$pagesize, $offset);

		$data = array(
				'leaves'=>$leaves,
				'leave_htm'=>$leave_htm,
			);
		$this->load->view(HOME_URL.'work/index',$data);
	}
}
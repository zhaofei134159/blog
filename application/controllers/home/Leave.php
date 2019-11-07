<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leave extends Home_Controller{

	public function __construct(){
		parent::__construct();

		$this->load->model('zf_leave_model');
		$this->load->model('zf_user_model');
        $this->load->library('pager');
		$this->load->helper('common');
		$this->load->config('app');
	}

	public function index()
	{
		$pagesize = 30;
		$offset = 0;
		
		$where = 'is_del=0 and is_show=0';

        $leave_count = $this->zf_leave_model->count($where);
        list($offset, $leave_htm) = $this->pager->pagestring($leave_count, $pagesize);
		$leaves = $this->zf_leave_model->get_list($where,'*','ctime desc',$pagesize, $offset);

		$users = $this->zf_user_model->select('1=1');
		$userList = array();
		foreach($users as $k=>$v){
			$userList[$v['id']] = $v;
		}

		$data = array(
				'leaves'=>$leaves,
				'leave_htm'=>$leave_htm,
				'userList'=>$userList,
			);
		$this->load->view(HOME_URL.'leave/index',$data);
	}

	public function leave_save(){
		$post = $this->input->post();
		$data = array();

  		$map = array();
  		$map['leave_id'] = '0';
  		$map['uid'] = $this->home['id'];
  		$map['content'] = $post['Input_text'];
  		$map['ctime'] = date('Y-m-d H:i:s');
        $uid = $this->zf_leave_model->insert($map);

		$data['flog']=1; 
		$data['msg']='留言成功'; 
		$data['data']=array(); 
		return_json($data);
	}
}
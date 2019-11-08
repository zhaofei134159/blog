<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leave extends Home_Controller{

	public function __construct(){
		parent::__construct();

		$this->load->model('zf_leave_model');
		$this->load->model('zf_user_model');
		$this->load->model('zf_leave_fabulous_model');
        $this->load->library('pager');
		$this->load->helper('common');
		$this->load->config('app');
	}

	public function index()
	{
		$pagesize = 20;
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

	public function leave_fabulous(){
		$post = $this->input->post();
		$data = array();

		$leaveFab = $this->zf_leave_fabulous_model->select_one('leave_id='.$post['id'].' and uid='.$this->home['id']);

		if(!empty($leaveFab)){
			$fabulousNum = intval($post['num'])-1;

			$this->zf_leave_fabulous_model->delete('id='.$leaveFab['id']);

			//点击量
			$this->zf_leave_model->update(array('fabulous'=>$fabulousNum),'id='.$post['id']);

		}else{
			$fabulousNum = intval($post['num'])+1;

			$insert = array();
			$insert['leave_id'] = $post['id'];
			$insert['uid'] = $this->home['id'];
			$this->zf_leave_fabulous_model->insert($insert);

			//点击量
			$this->zf_leave_model->update(array('fabulous'=>$fabulousNum),'id='.$post['id']);
		}


		$data['flog']=1; 
		$data['msg']='成功'; 
		$data['data']=array('num'=>$fabulousNum); 
		return_json($data);

	}
}
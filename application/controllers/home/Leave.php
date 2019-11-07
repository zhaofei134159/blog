<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leave extends Home_Controller{

	public function __construct(){
		parent::__construct();

		$this->load->model('zf_leave_model');
		$this->load->model('zf_user_model');
        $this->load->library('pager');
	}

	public function index()
	{
		$pagesize = 30;
		$offset = 0;
		
		$where = 'is_del=0 and is_show=0';

		$friends = $this->zf_leave_model->select($where,'*','');

		$users = $this->zf_user_model->select('1','*','');
		$userList = array();
		foreach($users as $k=>$v){
			$userList[$v['id']] = $v;
		}

		$data = array(
				'friends'=>$friends,
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
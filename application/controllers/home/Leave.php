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
		var_dump($post);

		$where = '(phone='.$post['account'].' or email='.$post['account'].') and is_del=0';
		$user = $this->zf_user_model->select_one($where);

		$new_password = md5(md5($post['password']).$user['login_stat']); 

		if($user['password']!=$new_password){
			$data['flog']=0; 
			$data['msg']='账号或密码错误'; 
			$data['data']=array(); 
			return_json($data);
		}

		$this->user_session($user);

		$data['flog']=1; 
		$data['msg']='登录成功'; 
		$data['data']=array(); 
		return_json($data);
	}
}
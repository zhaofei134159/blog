<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Admin_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('zf_admin_model');
		$this->load->helper('common');
	}

	public function index()
	{
		$this->load->view(ADMIN_URL.'login/index');
	}

	//执行登录
	public function do_login(){
		$post = $this->input->post();
		$data = array();

		$where = 'phone='.$post['phone'].' and user_type=1 and is_del=0';
		$admin = $this->zf_admin_model->select_one($where);
		
		if(empty($admin)){
			$data['flog']=-1; 
			$data['msg']='账号不存在'; 
			$data['data']=array(); 
			return_json($data);
		}

		$new_password = md5(md5($post['password']).$admin['login_stat']); 

		if($admin['password']!=$new_password){
			$data['flog']=-1; 
			$data['msg']='账号或密码错误'; 
			$data['data']=array(); 
			return_json($data);
		}

		$this->user_session($admin);

		$data['flog']=1; 
		$data['msg']='欢迎来到zf.ren'; 
		$data['data']=array(); 
		return_json($data);
	}

	public function user_session($admin=array()){

		if(empty($admin)){
			//清除session 
	        session_destroy();
		}else{
	        // 记录登录会话标识
			$_SESSION['admin_user_key'] = true;
	        $_SESSION['admin_user_info'] = $admin;
		}
	}  


	//退出登录
	public function unlogin(){

		$this->user_session();

		header('location:'.ADMIN_URL.'/login/');
	}


}

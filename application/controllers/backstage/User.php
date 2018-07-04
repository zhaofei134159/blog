<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends Admin_Controller {

	public $user_type = array();

	public function __construct(){
		parent::__construct();
		$this->load->helper('common');
        $this->load->library('pager');
		$this->load->model('zf_user_model');
		$this->load->config('app');

		$user_type = $this->config->item('user_type');

		$this->load->vars('user_type',$user_type);

		//如果没有 则退出登录
		if(empty($this->adminid)){
			header('location:'.ADMIN_URL.'login/unlogin');
		}
	}

	public function index(){
        $pagesize = 15;
        $offset = 0;

        $post = $this->input->post();

		$where = ' 1=1 ';
		if(!empty($post['search'])){
			$where .= ' and (name like "%'.$post['search'].'%"';
			$where .= ' or nikename like "%'.$post['search'].'%"';
			$where .= ' or phone like "%'.$post['search'].'%")';
		}
		if(!empty($post['user_type'])){
			$where .= ' and user_type='.$post['user_type'];
		}
		if(isset($post['is_del'])&&$post['is_del']!=-1){
			$where .= ' and is_del='.$post['is_del'];
		}

        $count = $this->zf_user_model->count($where);

        list($offset, $page_htm) = $this->pager->pagestring($count, $pagesize);

		$admins = $this->zf_user_model->get_list($where,'*','ctime desc',$pagesize, $offset);

		$data = array(
				'admins'=>$admins,
				'page_htm'=>$page_htm,
				'post'=>$post,
			);

		$this->load->view(ADMIN_URL.'user/index',$data);
	}

	public function edit()
	{	
		$id = $this->input->get('id');
		$admin = array();
		if(!empty($id)){
			$admin = $this->zf_user_model->select_one('id='.$id);
		}

		$data = array(
				'admin'=>$admin,
			);

		$this->load->view(ADMIN_URL.'user/edit',$data);
	}

	public function update(){
		$post = $this->input->post();

		$login_stat = rand(1000,9999);
		$post['password'] = md5(md5($post['password']).$login_stat);
		$post['login_stat'] = $login_stat;

		if(isset($post['id'])&&!empty($post['id'])){
			$id = $post['id'];
			unset($post['id']);

			$this->zf_user_model->update($post,'id='.$id);
		}else{
			$this->zf_user_model->insert($post);
		}


		header('location:'.ADMIN_URL.'user/');
	}

	//检测手机号是否存在
	public function check_phone(){
		$post = $this->input->post();

		$flog = 0;
		$msg = '不存在';
		$admin = $this->zf_user_model->select_one('phone='.$post['phone']);
		if(!empty($admin)){
			$flog = 1;
			$msg = '存在';
		}

		$data = array(
				'flog'=>$flog,
				'msg'=>$msg,
			);
		return_json($data);
	}

	public function state(){
		$post = $this->input->post();

		$is_del = 0;

		$admin = $this->zf_user_model->select_one('id='.$post['id']);
		if($admin['is_del']==$is_del){
			$is_del = 1;
		}

		$this->zf_user_model->update(array('is_del'=>$is_del),'id='.$post['id']);

		$data = array(
				'flog'=>1,
				'msg'=>'成功',
			);
		return_json($data);
	}

}
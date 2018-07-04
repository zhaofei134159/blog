<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_cate extends Admin_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('common');
		$this->load->model('zf_admin_model');
		$this->load->model('zf_user_model');
		$this->load->model('zf_cate_model');

        $this->load->library('pager');

		if(empty($this->adminid)){
			header('location:'.ADMIN_URL.'login/unlogin');
		}
	}

	public function index()
	{
		$pagesize = 15;
		$offset = 0;
		$uid = $this->input->get('uid',0);
		$post = $this->input->post();

		$where = ' 1=1';
		if(!empty($uid)){
			$where .= ' and uid='.$uid;
		}
		if(!empty($post['search'])){
			$where .= ' and (id='.$post['search'];
			$where .= ' or title='.$post['search'];
			$where .= ' or desc='.$post['search'].')';
		}
		if(isset($post['is_del'])&&$post['is_del']!='-1'){
			$where .= ' and is_del='.$post['is_del'];
		}

        $count = $this->zf_cate_model->count($where);

        list($offset, $page_htm) = $this->pager->pagestring($count, $pagesize);

		$cates = $this->zf_cate_model->get_list($where,'*','ctime desc',$pagesize, $offset);

		foreach($cates as $key=>$cate){
			$cates[$key]['user'] = $this->zf_user_model->select_one('id='.$cate['uid']);
		}


		$data = array(
				'cates' => $cates,
				'page_htm'=> $page_htm,
				'post'=>$post,
			);
		$this->load->view(ADMIN_URL.'user_cate/index',$data);
	}


	public function edit(){
		$id = $this->input->get('id');
		$data = array();

		if(!empty($id)){
			$cate = $this->zf_cate_model->select_one('id='.$id);
			$data['cate'] = $cate;
		}


		$this->load->view(ADMIN_URL.'user_cate/edit',$data);
	}

	public function update(){
		$post = $this->input->post();
		$post['utime'] = time();

		if(isset($post['id'])&&!empty($post['id'])){
			$id = $post['id'];
			unset($post['id']);

			$this->zf_cate_model->update($post,'id='.$id);
		}else{
			$post['ctime'] = time();
			$this->zf_cate_model->insert($post);
		}

		header('location:'.ADMIN_URL.'user_cate');
	}

	public function state(){
		$post = $this->input->post();

		$is_del = 0;

		$admin = $this->zf_cate_model->select_one('id='.$post['id']);
		if($admin['is_del']==$is_del){
			$is_del = 1;
		}

		$this->zf_cate_model->update(array('is_del'=>$is_del),'id='.$post['id']);

		$data = array(
				'flog'=>1,
				'msg'=>'成功',
			);
		return_json($data);
	}


}
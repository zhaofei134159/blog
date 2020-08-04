<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_blog extends Admin_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('common');
		$this->load->model('zf_admin_model');
		$this->load->model('zf_user_model');
		$this->load->model('zf_cate_model');
		$this->load->model('zf_blog_model');

        $this->load->library('pager');

		if(empty($this->adminid)){
			header('location:'.ADMIN_URL.'login/unlogin');
		}
	}

	public function index(){

		$pagesize = 15;
		$offset = 0;
		$uid = $this->input->get('uid',0);
		$post = $this->input->post();

		$where = ' 1=1';
		if(!empty($uid)){
			$where .= ' and uid='.$uid;
		}
		if(!empty($post['search'])){
			$where .= ' and (id="'.$post['search'].'"';
			$where .= ' or name="'.$post['search'].'"';
			$where .= ' or desc="'.$post['search'].'")';
		}
		if(isset($post['is_del'])&&$post['is_del']!='-1'){
			$where .= ' and is_del='.$post['is_del'];
		}

        $count = $this->zf_blog_model->count($where);

        list($offset, $page_htm) = $this->pager->pagestring($count, $pagesize);

		$blogs = $this->zf_blog_model->get_list($where,'*','ctime desc',$pagesize, $offset);

		foreach($blogs as $key=>$blog){
			$blogs[$key]['user'] = $this->zf_user_model->select_one('id='.$blog['uid']);
		}


		$data = array(
				'blogs' => $blogs,
				'page_htm'=> $page_htm,
				'post'=>$post,
			);
		$this->load->view(ADMIN_URL.'user_blog/index',$data);
	}

	public function state(){
		$post = $this->input->post();

		$is_del = 0;

		$admin = $this->zf_blog_model->select_one('id='.$post['id']);
		if($admin['is_del']==$is_del){
			$is_del = 1;
		}

		$this->zf_blog_model->update(array('is_del'=>$is_del),'id='.$post['id']);

		$data = array(
				'flog'=>1,
				'msg'=>'成功',
			);
		return_json($data);
	}
}
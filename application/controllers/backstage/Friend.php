<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Friend extends Admin_Controller{

	public function __construct(){
		parent::__construct();

		$this->load->model('zf_friend_model');
        $this->load->library('pager');
		$this->load->helper('common');

		//如果没有 则退出登录
		if(empty($this->adminid)){
			header('location:'.ADMIN_URL.'login/unlogin');
		}
	}

	public function index()
	{
		$pagesize = 15;
		$offset = 0;

        $post = $this->input->post();
		
		$where = ' 1=1';
		if(!empty($post['search'])){
			$where .= ' and (id="'.$post['search'].'"';
			$where .= ' or title="'.$post['search'].'")';
		}
		if(isset($post['is_del'])&&$post['is_del']!='-1'){
			$where .= ' and is_del='.$post['is_del'];
		}

        $friend_count = $this->zf_friend_model->count($where);
        list($offset, $friend_htm) = $this->pager->pagestring($friend_count, $pagesize);
		$friends = $this->zf_friend_model->get_list($where,'*','ctime desc',$pagesize, $offset);

		$data = array(
				'friends'=>$friends,
				'friend_htm'=>$friend_htm,
				'post'=>$post,
			);
		$this->load->view(ADMIN_URL.'friend/index',$data);
	}

	public function edit(){
		$id = $this->input->get('id');
		$data = array();

		if(!empty($id)){
			$friend = $this->zf_friend_model->select_one('id='.$id);
			$data['friend'] = $friend;
		}

		$this->load->view(ADMIN_URL.'friend/edit',$data);
	}

	public function update(){
		$post = $this->input->post();
		$file = $_FILES['img'];

		if(isset($post['id'])&&!empty($post['id'])){
			$id = $post['id'];
			unset($post['id']);
		}
		if(!empty($post['img']['0'])){
			$post['img'] = upload_img($file,'friendimg');
		}


		if(isset($id)&&!empty($id)){
			$this->zf_friend_model->update($post,'id='.$id);
		}else{
			$post['ctime'] = date('Y-m-d H:i:s');
			$this->zf_friend_model->insert($post);
		}

		header('location:'.ADMIN_URL.'friend/');
	}

	public function state(){
		$post = $this->input->post();

		$is_del = 0;

		$friend = $this->zf_friend_model->select_one('id='.$post['id']);
		if($friend['is_del']==$is_del){
			$is_del = 1;
		}

		$this->zf_friend_model->update(array('is_del'=>$is_del),'id='.$post['id']);

		$data = array(
				'flog'=>1,
				'msg'=>'成功',
			);
		return_json($data);
	}
}
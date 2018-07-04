<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ad extends Admin_Controller {

	public $user_type = array();

	public function __construct(){
		parent::__construct();
		$this->load->helper('common');
        $this->load->library('pager');
		$this->load->model('zf_user_model');
		$this->load->model('zf_ad_model');
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

		$where = ' 1=1';
		if(!empty($post['search'])){
			$where .= ' and (id="'.$post['search'].'"';
			$where .= ' or name="'.$post['search'].'")';
		}
		if(isset($post['is_del'])&&$post['is_del']!='-1'){
			$where .= ' and is_del='.$post['is_del'];
		}

        $count = $this->zf_ad_model->count($where);

        list($offset, $page_htm) = $this->pager->pagestring($count, $pagesize);

		$ads = $this->zf_ad_model->get_list($where,'*','ctime desc',$pagesize, $offset);

		$data = array(
				'ads' => $ads,
				'page_htm'=> $page_htm,
				'post'=>$post,
			);
		$this->load->view(ADMIN_URL.'ad/index',$data);
	}

	public function edit(){
		$id = $this->input->get('id');
		$data = array();

		if(!empty($id)){
			$ad = $this->zf_ad_model->select_one('id='.$id);
			$data['ad'] = $ad;
		}


		$this->load->view(ADMIN_URL.'ad/edit',$data);
	}


	public function state(){
		$post = $this->input->post();

		$is_del = 0;

		$admin = $this->zf_ad_model->select_one('id='.$post['id']);
		if($admin['is_del']==$is_del){
			$is_del = 1;
		}

		$this->zf_ad_model->update(array('is_del'=>$is_del),'id='.$post['id']);

		$data = array(
				'flog'=>1,
				'msg'=>'成功',
			);
		return_json($data);
	}
}
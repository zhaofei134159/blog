<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class home_app extends Home_Controller{

	public function __construct(){
		parent::__construct();

		$this->load->model('zf_blog_model');
		$this->load->model('zf_user_model');
		$this->load->model('zf_tag_model');
		$this->load->model('zf_work_model');
	}

	public function index()
	{
		$blogs = $this->zf_blog_model->get_list('is_del=0','*','',20,0);
		if(!empty($blogs)){
			foreach($blogs as $bk=>$blog){
				$blogs[$bk]['user'] = $this->zf_user_model->select_one('id='.$blog['uid']);
			}
		}

				//标签
		$tags = $this->zf_tag_model->get_list('is_del=0','*','',40,0);

		//热门的文章 点击量多的
		$works = $this->zf_work_model->get_list('is_del=0','*','browse_num desc',10,0);
		if(!empty($works)){
			foreach($works as $wk=>$work){
				$works[$wk]['user'] = $this->zf_user_model->select_one('id='.$work['uid']);
			}
		}

		// 轮播
		$lun_ad = $this->zf_work_model->get_list('is_del=0 and img!=""','*','browse_num desc',4,0);

		$data = array(
				'blogs'=>$blogs,
				'tags'=>$tags,
				'works'=>$works,
				'lun_ad'=>$lun_ad,
			);
		
		return $data;
	}
}
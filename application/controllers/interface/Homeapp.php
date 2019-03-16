<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Homeapp extends Home_Controller{

	public $blogId = 2; 

	public function __construct(){
		parent::__construct();

		$this->load->model('zf_blog_model');
		$this->load->model('zf_work_model');
		$this->load->model('zf_cate_model');
		$this->load->model('zf_tag_model');
		$this->load->model('zf_user_model');
        $this->load->library('pager');
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

		echo json_encode($data);
	}

	public function lookWork(){
		$blogId = $this->blogId;
		$workId = $_GET['workId'];

		$where = 'blog_id='.$blogId.' and is_del=0';
		$where .= ' and id='.$workId;
		$work = $this->zf_work_model->select_one($where);
		$work['user'] = $this->zf_user_model->select_one('id='.$work['uid']);
		
		//点击量
		$work['browse_num'] += 1;
		$this->zf_work_model->update(array('browse_num'=>$work['browse_num']),$where);


		//标签
		$tag_where = 'blog_id='.$blogId.'  and is_del=0';
		if(!empty($work['tag_ids'])){
			$tag_where .= ' and id in('.$work['tag_ids'].')';
		}
		$tags = $this->zf_tag_model->select($tag_where);

		//分类
		$cates = $this->zf_cate_model->get_list('blog_id='.$blogId.' and is_del=0','*','',5,0);

		//相关文章
		$relevant = $this->work_relevant($work['tag_ids'],$blogId,$workId);
		

		$data = array(
				'cates'=>$cates,
				'tags'=>$tags,
				'work'=>$work,
				'relevant'=>$relevant
			);

		echo json_encode($data);
	}

	//相关文章
	function work_relevant($tag_ids,$id,$wid){
		$data = array();
		if(empty($tag_ids)){
			return $data;
		}

		$relevant_where = 'blog_id='.$id.' and id != '.$wid.' and is_del=0 and (';
		$tag_idarr=explode(',',$tag_ids);
		foreach($tag_idarr as $key=>$val){
			$where .= ' FIND_IN_SET('.$val.',tag_ids) or';
		}
		$relevant_where .= trim($where,'or');

		$relevant_where .= ')';

		$relevant = $this->zf_work_model->get_list($relevant_where,'*','',5,0);
		
		return $relevant;
	}

	
}
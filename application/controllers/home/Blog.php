<?php 
error_reporting(E_ALL^E_NOTICE^E_WARNING);
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends Home_Controller{

	public function __construct(){
		parent::__construct();

		$this->load->model('zf_blog_model');
		$this->load->model('zf_work_model');
		$this->load->model('zf_cate_model');
		$this->load->model('zf_tag_model');
		$this->load->model('zf_user_model');
        $this->load->library('pager');

		$id = $this->uri->segment(3);
		if(empty($id)){
			show_error('参数错误!',500,'路径问题');
		}
		$blog = $this->zf_blog_model->select_one('id='.$id);

		if(empty($blog)){
			show_error('您可能需要先去<a href="'.HOME_URL.'user/blog_setting">博客设置</a>中是设置下!',500,'博客错误');
		}
		if($blog['is_del']!=0){
			show_error('对不起,当前博客有违规内容,详情询问邮件：blogfamily@163.com ',500,'博客错误');
		}
		if($blog['blog_switch']==0){
			show_error('对不起,当前博客正在维护中，请耐心等待!',500,'博客错误');
		}

		$this->load->vars('blog',$blog);
	}

	public function index($id)
	{

		//热门的文章 点击量多的
		$works = $this->zf_work_model->get_list('blog_id='.$id.' and is_del=0','*','browse_num desc',4,0);
		//标签
		$tags = $this->zf_tag_model->get_list('blog_id='.$id.' and is_del=0','*','',40,0);
		//分类
		$cates = $this->zf_cate_model->get_list('blog_id='.$id.' and is_del=0','*','',5,0);


		$data = array(
				'works'=>$works,
				'tags'=>$tags,
				'cates'=>$cates,
			);

		$this->load->view(HOME_URL.'blog/index',$data);
	}

	//博客文章列表页
	public function art_list($id){
		$pagesize = 12;
		$offset = 0;

		$c = $this->input->get('cate');
		$t = $this->input->get('tag');
		

		$where = 'blog_id='.$id.' and is_del=0';
		if(!empty($c)){
			$where .= ' and cate_id='.$c;
		}
		if(!empty($t)){
			$tag_idarr=explode(',',trim($t,','));
			$where .= " and (";
			foreach($tag_idarr as $key=>$val){
				$tag_where .= ' FIND_IN_SET('.$val.',tag_ids) or';
			}
			$where .= trim($tag_where,'or');
			$where .= ')';
		}
        $work_count = $this->zf_work_model->count($where);
        list($offset, $work_htm) = $this->pager->pagestring($work_count, $pagesize);
		$works = $this->zf_work_model->get_list($where,'*','browse_num desc',$pagesize, $offset);

		//标签
		$tags = $this->zf_tag_model->get_list('blog_id='.$id.' and is_del=0','*','',100,0);
		//分类
		$cates = $this->zf_cate_model->get_list('blog_id='.$id.' and is_del=0','*','',50,0);

		$data = array(
				'cates'=>$cates,
				'tags'=>$tags,
				'works'=>$works,
				'work_htm'=>$work_htm,
				'c'=>$c,
				't'=>$t,
			);
		$this->load->view(HOME_URL.'blog/list',$data);
	}

	public function detail($id,$wid){
		
		$where = 'blog_id='.$id.' and is_del=0';
		$where .= ' and id='.$wid;
		$work = $this->zf_work_model->select_one($where);
		$work['user'] = $this->zf_user_model->select_one('id='.$work['uid']);
		
		//点击量
		$work['browse_num'] += 1;
		$this->zf_work_model->update(array('browse_num'=>$work['browse_num']),$where);


		//标签
		$tag_where = 'blog_id='.$id.'  and is_del=0';
		if(!empty($work['tag_ids'])){
			$tag_where .= ' and id in('.$work['tag_ids'].')';
		}
		$tags = $this->zf_tag_model->select($tag_where);

		//分类
		$cates = $this->zf_cate_model->get_list('blog_id='.$id.' and is_del=0','*','',5,0);

		//相关文章
		$relevant = $this->work_relevant($work['tag_ids'],$id,$wid);
		

		$data = array(
				'cates'=>$cates,
				'tags'=>$tags,
				'work'=>$work,
				'relevant'=>$relevant
			);
		$this->load->view(HOME_URL.'blog/detail',$data);
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

	//关于页面
	public function about($id){

		$this->load->view(HOME_URL.'blog/about');
	}
}
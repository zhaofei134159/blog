<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Work extends Home_Controller{

	public function __construct(){
		parent::__construct();

		$this->load->model('zf_famou_work_model');
		$this->load->model('zf_famou_work_info_model');
		$this->load->model('zf_famou_work_node_model');
		$this->load->model('zf_famou_work_tag_model');
		$this->load->model('zf_images_model');
		$this->load->model('zf_image_tag_model');

        $this->load->library('pager');
		$this->load->helper('common');
		$this->load->config('app');
 		
 		if(empty($_SERVER['HTTP_REFERER'])){
			$callback = array('errorMsg'=>'go to home','errorNo'=>'1003');
	    	exit(json_encode($callback));
 		}

 		$HTTP_REFERER = trim($_SERVER['HTTP_REFERER'],'http://');
 		$HTTP_REFERER = trim($HTTP_REFERER,'https://');
 		$HTTP_REFERER = explode('/',$HTTP_REFERER)[0];
 		$referer = array('104.243.18.161:8080','104.243.18.161:8081','104.243.18.161:8082','books.myfeiyou.com');
		if(!in_array($HTTP_REFERER,$referer)){
			$callback = array('errorMsg'=>'go to home','errorNo'=>'1004');
	    	exit(json_encode($callback));
		}
	}

	public function index()
	{
		$get = $this->input->get();
		$page = !empty($get['page'])?$get['page']:1;

		$pagesize = 12;
		$offset = ($page-1)*$pagesize;

		$where = '1 and is_del=0';
		$worksCount = $this->zf_famou_work_model->count($where);

		$works = $this->zf_famou_work_model->get_list($where,'*','ctime desc',$pagesize,$offset);

		$data = array();
		$data['worksCount'] = $worksCount;
		$data['pagesize'] = $pagesize;
		$data['works'] = $works;

		$callback = array('errorMsg'=>'','errorNo'=>'0','seccuss'=>$data);
    	exit(json_encode($callback));
	}

	public function getFamouWorkInfo(){
		$get = $this->input->get();
		$workId = $get['workId'];
		if(empty($workId)){
			$callback = array('errorMsg'=>'参数错误','errorNo'=>'1010');
			exit(json_encode($callback));	
		}

		# workInfo
		$where = '1 and is_del=0';
		$where .= ' and id='.$workId;
		$work = $this->zf_famou_work_model->select_one($where);

		# workChapter
		$info_where = '1 and work_id='.$workId;
		$workInfo = $this->zf_famou_work_info_model->select($info_where,'id,work_id,index,title,extract','index asc');
		foreach($workInfo as $key=>$val){
			if(empty($val['extract'])){
				$workInfo[$key]['extract'] = '';
			}
		}

		$data = array();
		$data['work'] = $work;
		$data['workInfo'] = $workInfo;

		$callback = array('errorMsg'=>'','errorNo'=>'0','seccuss'=>$data);
		exit(json_encode($callback));
	}

	public function getFamouWorkCont(){
		$get = $this->input->get();
		$workId = $get['workId'];
		$chapterId = $get['chapterId'];
		if(empty($workId)||empty($chapterId)){
			$callback = array('errorMsg'=>'参数错误','errorNo'=>'1010');
			exit(json_encode($callback));	
		}

		# workInfo
		$where = '1 and is_del=0';
		$where .= ' and id='.$workId;
		$work = $this->zf_famou_work_model->select_one($where);

		# workChapter
		$info_where = '1';
		$info_where .= ' and work_id='.$workId;
		$info_where .= ' and id='.$chapterId;
		$workInfo = $this->zf_famou_work_info_model->select_one($info_where);

		# 
		$workTag = array('id'=>'');
		if(!empty($work['tag_id'])){
			$tag_where = '1';
			$tag_where .= ' and id='.$work['tag_id'];
			$workTag = $this->zf_famou_work_tag_model->select_one($tag_where);
		}

		# 
		$node_where = '1';
		$node_where .= ' and work_id='.$workId;
		$node_where .= ' and work_info_id='.$chapterId;
		$workNode = $this->zf_famou_work_node_model->select_one($node_where);
		if(empty($workNode)){
			$workNode['translate'] = '';
			$workNode['node'] = '';
			$workNode['annotate'] = '';
		}else{
			if(empty($workNode['translate'])){
				$workNode['translate'] = '';
			}
			if(empty($workNode['node'])){
				$workNode['node'] = '';
			}
			if(empty($workNode['annotate'])){
				$workNode['annotate'] = '';
			}
		}

		$data = array();
		$data['work'] = $work;
		$data['workInfo'] = $workInfo;
		$data['workNode'] = $workNode;
		$data['workTag'] = $workTag;

		$callback = array('errorMsg'=>'','errorNo'=>'0','seccuss'=>$data);
		exit(json_encode($callback));
	}

	public function getPictureList(){
		$get = $this->input->get();
		$page = !empty($get['page'])?$get['page']:1;
		$tag = !empty($get['tag'])?$get['tag']:'';

		$imgTag = $this->zf_image_tag_model->get_list('name!=""','*','count desc','10',0);
		

		$pagesize = 50;
		$offset = ($page-1)*$pagesize;

		$where = 'name!=""';
		if(!empty($tag)){
			$where .= ' and tag='.$tag;
		}

		$images = $this->zf_images_model->get_list($where,'*','id desc',$pagesize,$offset);
		foreach($images as $key=>$val){
			$wide_src = explode('/',$val['wide_path']);
			$images[$key]['wide_src'] = 'http://blog.myfeiyou.com/public/public/netImage/'.$wide_src[count($wide_src)-1];

			$narrow_src = explode('/',$val['narrow_path']);
			$images[$key]['narrow_src'] = 'http://blog.myfeiyou.com/public/public/netImage/'.$narrow_src[count($narrow_src)-1];
		}

		$data = array();
		$data['images'] = $images;
		$data['imgTag'] = $imgTag;

		$callback = array('errorMsg'=>'','errorNo'=>'0','seccuss'=>$data);
    	exit(json_encode($callback));
	}
}
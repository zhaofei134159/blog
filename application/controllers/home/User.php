<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends Home_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->helper('common');
		$this->load->model('zf_user_model');
		$this->load->model('zf_blog_model');
		$this->load->model('zf_cate_model');
		$this->load->model('zf_tag_model');
		$this->load->model('zf_work_model');
		$this->load->config('app');
        $this->load->library('pager');


		if(empty($this->homeid)||empty($this->home['id'])){
			header('location:'.HOME_URL.'login/unlogin');
		}
		
		$blog = $this->zf_blog_model->select_one('uid='.$this->home['id']);
		$this->load->vars('blog',$blog);
	}

	public function index()
	{
		$user = $this->zf_user_model->select_one('id='.$this->home['id']);
		$data = array(
				'user'=>$user,
			);
		$this->load->view(HOME_URL.'user/index',$data);
	}

	// 用户信息修改
	public function update_info(){
		$post = $this->input->post();
		$file = $_FILES['headimg'];

		$res = array();
		$user = $this->zf_user_model->select_one('id='.$this->home['id']);

		if($file['error']!=4){
			$res['headimg'] = upload_headimg($file,$user['headimg']);
		}

		$res['nikename'] = $post['nikename'];
		$res['sex'] = $post['sex'];
		$res['phone'] = $post['phone'];

		$this->zf_user_model->update($res,'id='.$this->home['id']);

		header('location:'.HOME_URL.'user/index');
	}

	//
	function check_phone(){
		$post = $this->input->post();

		$flog = 0;
		$msg = '不存在';
		$user = $this->zf_user_model->select_one('phone='.$post['phone'].' and id!='.$this->home['id']);
		if(!empty($user)){
			$flog = 1;
			$msg = '存在';
		}

		$data = array(
				'flog'=>$flog,
				'msg'=>$msg,
			);
		return_json($data);
	}

	public function blog_setting(){

		$user = $this->zf_user_model->select_one('id='.$this->home['id']);
		$user_blog = $this->zf_blog_model->select_one('uid='.$this->home['id']);

		$data = array(
				'user'=>$user,
				'user_blog'=>$user_blog
			);
		$this->load->view(HOME_URL.'user/blog_setting',$data);
	}

	public function resend(){
		$this->load->helper('email');

		$id = $this->input->post('id');
		$user = $this->zf_user_model->select_one('id='.$id);
		$email = $user['email'];

		if(empty($email)){
			$data = array(
					'flog'=>0,
					'msg'=>'无绑定邮箱',
				);
			return_json($data);
		}
		// 配置信息
		$smtpserver = $this->config->item('smtpserver');
		$smtpserverport = $this->config->item('smtpserverport');
		$smtpusermail = $this->config->item('smtpusermail');
		$smtpuser = $this->config->item('smtpuser');
		$smtppass = $this->config->item('smtppass');
		$mailtype = $this->config->item('mailtype');
		

		$smtpemailto = $email;//发送给谁
		$mailtitle = '博客之家【blogfamily】注册成功';//邮件主题
		//邮件内容
		$mailcontent = "<h2>博客之家,注册成功</h2>";
		$mailcontent .= "<h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;点击 <a href='http:/blog.myfeiyou.com/home/login/activate_email?addr=".base64_encode($email)."&start=".base64_encode('blogfamily')."&token=".base64_encode($email.'zhaofei')."'>链接</a> 激活邮箱成为博主，就可以发表属于自己的文章。（如果不是本人操作，请忽略本条信息）</h3>";
		
		//************************ 配置信息 ****************************
		$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
		$smtp->debug = false;//是否显示发送的调试信息
		$state = @$smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);

		//这儿 默认都发送成功了  没有记录没发成功的状态 以后再说
		$data = array(
			'flog'=>1,
			'msg'=>'成功发送',
		);
		return_json($data);
	}

	public function blog_update_setting(){
		$post = $this->input->post();
		$blog = $this->zf_blog_model->select_one('uid='.$post['uid']);

		if(empty($blog)){
			$post['ctime'] = time();
			$this->zf_blog_model->insert($post);
		}else{
			$uid = $post['uid'];
			unset($post['uid']);
			$this->zf_blog_model->update($post,'uid='.$uid);
		}

		header('location:'.HOME_URL.'user/blog_setting');
	}


	public function blog_info(){

		$user = $this->zf_user_model->select_one('id='.$this->home['id']);
		$offset = 0;
		$pagesize = 8;

		//博客分类
		$cate_where = 'uid='.$this->home['id'];
		$cate_where .= ' and is_del=0';
        $cate_count = $this->zf_cate_model->count($cate_where);
        list($offset, $cate_htm) = $this->pager->pagestring($cate_count, $pagesize);
		$cates = $this->zf_cate_model->get_list($cate_where,'*','ctime desc',$pagesize, $offset);

		//博客文章
		$work_where = 'uid='.$this->home['id'];
		$work_where .= ' and is_del=0';
        $work_count = $this->zf_work_model->count($work_where);
        list($offset, $work_htm) = $this->pager->pagestring($work_count, $pagesize);
		$works = $this->zf_work_model->get_list($work_where,'*','ctime desc',$pagesize, $offset);


		$data = array(
				'user'=>$user,
				'cates'=>$cates,
				'cate_htm'=>$cate_htm,
				'works'=>$works,
				'work_htm'=>$work_htm,
			);

		$this->load->view(HOME_URL.'user/blog_info',$data);
	}


	public function blog_work_info(){
		$post = $this->input->post();
		$post['title'] = empty($post['title'])?'':$post['title'];
		$post['cate'] = empty($post['cate'])?'all':$post['cate'];

		$user = $this->zf_user_model->select_one('id='.$this->home['id']);
		$offset = 0;
		$pagesize = 8;

		//博客分类
		$cate_where = 'uid='.$this->home['id'];
		$cate_where .= ' and is_del=0';
		$cates = $this->zf_cate_model->select($cate_where,'*','ctime desc');

		//博客文章
		$work_where = 'uid='.$this->home['id'];
		$work_where .= ' and is_del=0';
		if(!empty($post['title'])){
			$work_where .= ' and title like "%'.$post['title'].'%"';
		}
		if(!empty($post['cate'])&&$post['cate']!='all'){
			$work_where .= ' and cate_id='.$post['cate'];
		}
        $work_count = $this->zf_work_model->count($work_where);
        list($offset, $work_htm) = $this->pager->pagestring($work_count, $pagesize);
		$works = $this->zf_work_model->get_list($work_where,'*','ctime desc',$pagesize, $offset);

		foreach($works as $key=>$work){
			$works[$key]['cate']=$this->zf_cate_model->select_one('id='.$work['cate_id'].' and is_del=0');
		}


		$data = array(
				'post'=>$post,
				'cates'=>$cates,
				'user'=>$user,
				'works'=>$works,
				'work_htm'=>$work_htm,
			);

		$this->load->view(HOME_URL.'user/blog_work_info',$data);
	}

	public function blog_info_edit(){
		$get = $this->input->get();
		$user = $this->zf_user_model->select_one('id='.$this->home['id']);

		$cate = array();
		if(!empty($get['id'])){
			$id = base64_decode($get['id']);
			$cate = $this->zf_cate_model->select_one('id='.$id);
			if(empty($cate)){
				show_error('没有找到对应的分类!',500,'分类信息错误');
			}
		}

		$data = array(
				'user'=>$user,
				'cate'=>$cate,
			);
		$this->load->view(HOME_URL.'user/blog_info_edit',$data);
	}

	public function blog_info_update(){
		$post = $this->input->post();

		$post['utime'] = time();
	

		if(empty($post['id'])){
			$id = $post['id'];
			unset($post['id']);

			$post['is_del']=0;
			$post['ctime']=time();
			$cate_count = $this->zf_cate_model->insert($post);
		}else{
			$id = $post['id'];
			unset($post['id']);
			$cate_count = $this->zf_cate_model->update($post,'id='.$id);
		}

		header('location:'.HOME_URL.'user/blog_info');
	}
	public function blog_info_del(){
		$get = $this->input->get();


		if(empty($get['id'])){
			show_error('参数错误!',500,'路径问题');
		}

		$id = base64_decode($get['id']);
		$cate = $this->zf_cate_model->select_one('id='.$id);
		if(empty($cate)){
			show_error('没有找到对应的分类!',500,'分类信息错误');
		}

		$this->zf_cate_model->update(array('is_del'=>1),'id='.$id);

		header('location:'.HOME_URL.'user/blog_info');
	}

	public function blog_work_edit(){
		$get = $this->input->get();
		$user = $this->zf_user_model->select_one('id='.$this->home['id']);

		$cates = $this->zf_cate_model->select('uid='.$this->home['id'].' and is_del=0');

		$work = array();
		$tags = array();
		if(!empty($get['id'])){
			$id = base64_decode($get['id']);
			$work = $this->zf_work_model->select_one('id='.$id);
			if(empty($work)){
				show_error('没有找到对应的文章!',500,'文章信息错误');
			}
			if(!empty($work['tag_ids'])){
				$tags = $this->zf_tag_model->select('id in('.$work['tag_ids'].') and is_del=0');
			}
		}

		$data = array(
				'user'=>$user,
				'work'=>$work,
				'cates'=>$cates,
				'tags'=>$tags,
			);
		$this->load->view(HOME_URL.'user/blog_work_edit',$data);
	}


	public function blog_work_update(){
		$post = $this->input->post();
		$post['utime'] = time();

		$file = $_FILES['headimg'];


		if(!empty($post['tags'])){
			$post['tag_ids'] = $this->check_tag($post['tags'],$post['uid'],$post['blog_id']);
		}

		$post['tags']='';
		// $post['desc']=$post['test-editormd-html-code'];
		unset($post['tags']);
		// unset($post['test-editormd-markdown-doc']);
		// unset($post['test-editormd-html-code']);

		$id = $post['id'];
		unset($post['id']);

		if($file['error']!=4){
			if(!empty($id)){
				$work = $this->zf_work_model->select_one('id='.$id);
				$post['img'] = upload_workimg($file,$work['img']);
			}else{
				$post['img'] = upload_workimg($file);
			}
		}


		if(empty($id)){
			$post['is_del']=0;
			$post['ctime']=time();
			$cate_count = $this->zf_work_model->insert($post);
		}else{
			$cate_count = $this->zf_work_model->update($post,'id='.$id);
		}

		header('location:'.HOME_URL.'user/blog_work_info');
	}


	public function blog_work_del(){
		$get = $this->input->get();


		if(empty($get['id'])){
			show_error('参数错误!',500,'路径问题');
		}

		$id = base64_decode($get['id']);
		$cate = $this->zf_work_model->select_one('id='.$id);
		if(empty($cate)){
			show_error('没有找到对应的分类!',500,'分类信息错误');
		}

		$this->zf_work_model->update(array('is_del'=>1),'id='.$id);

		header('location:'.HOME_URL.'user/blog_work_info');
	}

	function check_tag($tags,$uid,$blogid){

		$tag_ids = '';
		foreach($tags as $tag){
			if(empty($tag)){
				continue;
			}
			$zftag = $this->zf_tag_model->select_one('name="'.$tag.'" and uid='.$uid.' and blog_id='.$blogid.' and is_del=0');
			if(empty($zftag)){
				$data = array(
						'name'=>$tag,
						'uid'=>$uid,
						'blog_id'=>$blogid,
						'ctime'=>time(),
						'utime'=>time(),
					);
				$tag_ids .= $this->zf_tag_model->insert($data).',';
			}else{
				$tag_ids .= $zftag['id'].',';
			}
		}

		return trim($tag_ids,',');
	} 

	/*
	* 博客统计
	*/
	function statistic(){

		/*$user = $this->zf_user_model->select_one('id='.$this->home['id']);

		//博客文章
		$work_where = 'uid='.$this->home['id'];
		$work_where .= ' and is_del=0';
		if(!empty($post['title'])){
			$work_where .= ' and title like "%'.$post['title'].'%"';
		}
		if(!empty($post['cate'])&&$post['cate']!='all'){
			$work_where .= ' and cate_id='.$post['cate'];
		}
        $work_count = $this->zf_work_model->count($work_where);
        list($offset, $work_htm) = $this->pager->pagestring($work_count, $pagesize);
		$works = $this->zf_work_model->get_list($work_where,'*','ctime desc',$pagesize, $offset);

		foreach($works as $key=>$work){
			$works[$key]['cate']=$this->zf_cate_model->select_one('id='.$work['cate_id'].' and is_del=0');
		}*/


		$data = array();

		$this->load->view(HOME_URL.'user/blog_statistic',$data);
	}

}
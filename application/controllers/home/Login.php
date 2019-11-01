<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Home_Controller{

	public $class_arr = array(
                'weibo'=>'weibo_uid',
                'weixin'=>'weixin_openid',
                'qq'=>'qq_openid',
            );

	public function __construct(){
		parent::__construct();

		$this->load->model('zf_user_model');
		$this->load->helper('common');
		$this->load->helper('email');
		$this->load->config('app');

	
	}

	public function index()
	{
		if(!empty($this->homeid)){
			header('location:'.HOME_URL);
		}
		$this->load->view(HOME_URL.'login/index');
	}

	//执行登录
	public function do_login(){
		$post = $this->input->post();
		$data = array();

		$where = '(phone='.$post['account'].' or email='.$post['account'].') and is_del=0';
		$user = $this->zf_user_model->select_one($where);

		$new_password = md5(md5($post['password']).$user['login_stat']); 

		if($user['password']!=$new_password){
			$data['flog']=0; 
			$data['msg']='账号或密码错误'; 
			$data['data']=array(); 
			return_json($data);
		}

		$this->user_session($user);

		$data['flog']=1; 
		$data['msg']='登录成功'; 
		$data['data']=array(); 
		return_json($data);
	}

	public function user_session($user=array()){

		if(empty($user)){
			//清除session 
	        session_destroy();
		}else{
	        // 记录登录会话标识
			$_SESSION['home_user_key'] = true;
	        $_SESSION['home_user_info'] = $user;
		}
	}  


	//退出登录
	public function unlogin(){

		$this->user_session();

		header('location:'.HOME_URL.'login/');
	}

	//判断手机号邮箱是否存在
	function do_register(){
		$post = $this->input->post();
		$data = array();
		$res = array();

		$phone_where = 'phone='.$post['phone'];
		$phone_user = $this->zf_user_model->select_one($phone_where);

		if(!empty($phone_user)){
			$data['flog']=0; 
			$data['msg']='手机号已存在,可直接登录!'; 
			$data['data']=array(); 
			return_json($data);	
		}

		$email_where = 'email="'.$post['email'].'"';
		$email_user = $this->zf_user_model->select_one($email_where);

		if(!empty($email_user)){
			$data['flog']=0; 
			$data['msg']='邮箱已存在,可直接登录!'; 
			$data['data']=array(); 
			return_json($data);	
		}

		if($post['password']!=$post['repassword']){
			$data['flog']=0; 
			$data['msg']='两次输入的密码不一致'; 
			$data['data']=array(); 
			return_json($data);
		}

		//都不存在 可以注册
		$login_stat = rand(1000,9999);
		$res['login_stat'] = $login_stat;
		$res['password'] = md5(md5($post['password']).$login_stat);
		$res['email'] = $post['email'];
		$res['phone'] = $post['phone'];
		$res['name'] =  $post['phone'];
		$res['user_type'] = 1;
		$res['ctime'] = time();
		$res['utime'] = time();

		$uid = $this->zf_user_model->insert($res);
		$user = $this->zf_user_model->select_one('id='.$uid);


		//给注册的邮箱发邮件
		$this->register_email($post['email']);

		//注册的账号登陆网站
		$this->user_session($user);

		$data['flog']=1; 
		$data['msg']='注册成功!'; 
		$data['data']=array(); 
		return_json($data);	
	}


	//发邮件
	function register_email($email){
		if(empty($email)){
			show_error('请输入正确的邮箱信息!',500,'邮箱信息错误');
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
		$mailcontent .= "<h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;点击 <a href='http://blog.myfeiyou.com/home/login/activate_email?addr=".base64_encode($email)."&start=".base64_encode('blogfamily')."&token=".base64_encode($email.'zhaofei')."'>链接</a> 激活邮箱成为博主，就可以发表属于自己的文章。（如果不是本人操作，请忽略本条信息）</h3>";
		
		//************************ 配置信息 ****************************
		$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
		$smtp->debug = false;//是否显示发送的调试信息
		$state = @$smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);

		//这儿 默认都发送成功了  没有记录没发成功的状态 以后再说
	}

	function activate_email(){
		$get = $this->input->get();

		$email = base64_decode($get['addr']);
		$start = base64_decode($get['start']);
		$token = $get['token'];

		if($start!='blogfamily'||$token!=base64_encode($email.'zhaofei')){
			show_error('请点击邮箱中的邮件链接进行激活邮箱！',500,'激活邮箱错误');
		}

		$where = 'email="'.$email.'"';
		$user = $this->zf_user_model->select_one($where);

		if(empty($user)){
			show_error('找不到对应的邮箱信息，请重新确认下邮箱信息，亦可以给我发送邮件，我们会在三个工作日内与您联系,谢谢合作！',500,'邮箱信息错误');
		}

		$res['is_activate'] = 1;
		$res['user_type'] = 2;

		$this->zf_user_model->update($res,'id='.$user['id']);

		header('location:'.HOME_URL);
	}

    //微博登录
    function weibo_login(){
    	include("application/helpers/saetv2.ex.class.php");

        $appkey = $this->config->item('weibo_appkey');
        $appsecret = $this->config->item('weibo_appsecret');

        $login = $this->config->item('weibo_login');

        $sae = new SaeTOAuthV2($appkey,$appsecret);

        $code_url = $sae->getAuthorizeURL($login);

        header('location:'.$code_url);
    }

    //微博回调
    function weibo_web(){
    	include("application/helpers/saetv2.ex.class.php");

        $login = $this->config->item('weibo_login');
    	$appkey = $this->config->item('weibo_appkey');
    	$appsecret = $this->config->item('weibo_appsecret');
    	$code = $this->input->get_post('code');

		$sae = new SaeTOAuthV2($appkey,$appsecret);

    	if (isset($code)) {
			$keys = array();
			$keys['code'] = $code;
			$keys['redirect_uri'] = $login;
			try {
				$token = $sae->getAccessToken('code', $keys );
			} catch (OAuthException $e) {
				//微博登录错误的跳转页面
				$this->load->view(HOME_URL.'login/web_error',array('type'=>'微博','login'=>'weibo'));
			}
		}
		
		//token不为空
		if(!empty($token)){

			$client = new SaeTClientV2($appkey,$appsecret,$token['access_token']);
			// $ms  = $client->home_timeline(); // done
			$uid_get = $client->get_uid();

			if(!empty($uid_get['uid'])){
				$uid = $uid_get['uid'];
				$user_message = $client->show_user_by_id($uid);

				//判断微博账号是否在都学网已经存在且是否有手机号
				$weibo_user = $this->user_data('weibo',$user_message);


				//微博账号存在且有手机号
				if($weibo_user['flag']==1){
					header('location:'.HOME_URL);
				}else{
					// $this->load->view(HOME_URL.'login/bind_user_phone',array('user'=>$weibo_user['data'],'type'=>'weibo'));
                    header('location:'.HOME_URL.'login/bind_user_phone?user='.base64_encode($weibo_user['data']['id']).'&type='.base64_encode('weibo'));
				}
			}

		}else{
			$this->load->view(HOME_URL.'login/web_error',array('type'=>'微博','login'=>'weibo'));
		}
    }


    //腾讯QQ登录
    function qq_login(){
    	//判断是PC的还是手机网页访问，如果是手机则需要传入mobile 默认为PC
        $web_type = $this->input->get('web_type');
        if(empty($web_type)||$web_type!='mobile'){
            $web_type = 'pc';
        }
        $appkey = $this->config->item('qq_appkey');
        $appsecret = $this->config->item('qq_appsecret');
        $login = $this->config->item('qq_login');
        $response_type = 'code';
        $state = base64_encode('zf');

        header('location:https://graph.qq.com/oauth2.0/authorize?response_type='.$response_type.'&client_id='.$appkey.'&redirect_uri='.urlencode($login).'&state='.$state.'&display='.$web_type);
    }

    //qq登录
    function qq_web(){
        $code = $this->input->get('code');
        $state = $this->input->get_post('state');

        $appkey = $this->config->item('qq_appkey');
        $appsecret = $this->config->item('qq_appsecret');
        $login = $this->config->item('qq_login');

        if(base64_encode('zf')==$state&&!empty($code)){
            //获取access token
            $token_url = 'https://graph.qq.com/oauth2.0/token?client_id='.$appkey.'&client_secret='.$appsecret.'&code='.$code.'&redirect_uri='.urlencode($login).'&grant_type=authorization_code';

            $token_info = $this->_curl_get_request($token_url);
            if(empty($token_info)||!strstr($token_info,'access_token')){
                //登录错误的跳转页面
                $this->load->view(HOME_URL.'login/web_error',array('type'=>'腾讯QQ','login'=>'qq'));
            }

            $token = array();
            parse_str($token_info,$token);

            if(!isset($token['access_token'])&&empty($token['access_token'])){
                $this->load->view(HOME_URL.'login/web_error',array('type'=>'腾讯QQ','login'=>'qq'));
            }

            $access_token = $token['access_token'];

            $user_url = 'https://graph.qq.com/oauth2.0/me?access_token='.$access_token;
            $user_info = $this->_curl_get_request($user_url);

            $user_info = str_replace('callback(','',$user_info);
            $user_info = str_replace(');','',$user_info);
            $user_info = json_decode($user_info,true);

            if(!array_key_exists("openid",$user_info)){
                $this->load->view(HOME_URL.'login/web_error',array('type'=>'腾讯QQ','login'=>'qq'));
            }

            $qq_user_url = 'https://graph.qq.com/user/get_user_info?access_token='.$access_token.'&oauth_consumer_key='.$appkey.'&openid='.$user_info['openid'];
            $qq_user_info = $this->_curl_get_request($qq_user_url);

            if($qq_user_info['ret']<0){
                $this->load->view(HOME_URL.'login/web_error',array('type'=>'腾讯QQ','login'=>'qq'));
            }

            $user_data = array(
                    'id'=>$user_info['openid'],
                    'name'=>$qq_user_info['nickname'],
                    'gender'=>($qq_user_info['gender']=='男')?'m':'w',
                    'avatar_large'=>$qq_user_info['figureurl_qq_1'],
                );
            $qq_user = $this->user_data('qq',$user_data);

            //微信账号存在且有手机号
            if($qq_user['flag']==1){
                header('location:'.HOME_URL);
            }else{
                // $this->load->view('external_login/bind_user_phone',array('user'=>$weixin_user['data'],'type'=>'weixin'));
                header('location:'.HOME_URL.'login/bind_user_phone?user='.base64_encode($qq_user['data']['id']).'&type='.base64_encode('qq'));
            }

        }else{
            //微信登录错误的跳转页面
            $this->load->view(HOME_URL.'login/web_error',array('type'=>'腾讯QQ','login'=>'qq'));
        }
    }

    # github登录
    function github_login(){

        $cliend_id = $this->config->item('cliend_id');
        $cliend_secret = $this->config->item('cliend_secret');
        $cliend_login = $this->config->item('cliend_login');

        header('location:https://github.com/login/oauth/authorize?client_id='.$cliend_id.'&scope=user:email');
    }

    function github_web(){
        $code = $this->input->get('code');

        $cliend_id = $this->config->item('cliend_id');
        $cliend_secret = $this->config->item('cliend_secret');
        $cliend_login = $this->config->item('cliend_login');

        if(!empty($code)){
            //获取access token
            $token_url = 'https://github.com/login/oauth/access_token?client_id='.$cliend_id.'&client_secret='.$cliend_secret.'&code='.$code;

            $token_info = $this->_curl_get_request($token_url);
            var_dump($token_info);

            if(empty($token_info)||!strstr($token_info,'access_token')){
                //登录错误的跳转页面
                $this->load->view(HOME_URL.'login/web_error',array('type'=>'github','login'=>'github'));
            }

            $token = array();
            parse_str($token_info,$token);
            var_dump($token);

            if(!isset($token['access_token'])&&empty($token['access_token'])){
                $this->load->view(HOME_URL.'login/web_error',array('type'=>'github','login'=>'github'));
            }

            $access_token = $token['access_token'];

            $user_url = 'https://api.github.com/users?Authorization=token '.$access_token;
            $header = array();
            $header[] = "Accept: application/vnd.github.v3.full+json";
            $header[] = "Authorization: token ".$access_token;
            $user_info = $this->_curl_get_request($user_url,$header);
            var_dump($user_info);die;

            $user_info = str_replace('callback(','',$user_info);
            $user_info = str_replace(');','',$user_info);
            $user_info = json_decode($user_info,true);

            if(!array_key_exists("openid",$user_info)){
                $this->load->view(HOME_URL.'login/web_error',array('type'=>'腾讯QQ','login'=>'qq'));
            }

            $qq_user_url = 'https://graph.qq.com/user/get_user_info?access_token='.$access_token.'&oauth_consumer_key='.$appkey.'&openid='.$user_info['openid'];
            $qq_user_info = $this->_curl_get_request($qq_user_url);

            if($qq_user_info['ret']<0){
                $this->load->view(HOME_URL.'login/web_error',array('type'=>'腾讯QQ','login'=>'qq'));
            }

            $user_data = array(
                    'id'=>$user_info['openid'],
                    'name'=>$qq_user_info['nickname'],
                    'gender'=>($qq_user_info['gender']=='男')?'m':'w',
                    'avatar_large'=>$qq_user_info['figureurl_qq_1'],
                );
            $qq_user = $this->user_data('qq',$user_data);

            //微信账号存在且有手机号
            if($qq_user['flag']==1){
                header('location:'.HOME_URL);
            }else{
                // $this->load->view('external_login/bind_user_phone',array('user'=>$weixin_user['data'],'type'=>'weixin'));
                header('location:'.HOME_URL.'login/bind_user_phone?user='.base64_encode($qq_user['data']['id']).'&type='.base64_encode('qq'));
            }

        }else{
            //微信登录错误的跳转页面
            $this->load->view(HOME_URL.'login/web_error',array('type'=>'github','login'=>'github'));
        }
    }

    /**
    * 判断微博账号是否在网站已经存在且是否有手机号
    * 1.若存在且有手机号，则直接登录
    * 2.若存在但无手机号，则需要绑定手机号
    * 3.若不存在，则需要插入微博账号，且绑定手机号 
    */
    protected function user_data($type,$user_data){
 
        $class_arr = $this->class_arr;

    	$login_user = $this->zf_user_model->select_one($class_arr[$type].'="'.$user_data['id'].'"');
    	//微博uid在都学网中有账号 且手机号存在是，则直接登录
    	if(!empty($login_user) && !empty($login_user['email'])){
    		//登录机制
            $this->_external_session_login($login_user,$user_data);

            return array('flag'=>1,'msg'=>'有账号且有手机号');

    	}else if(!empty($login_user) && empty($login_user['email'])){
    		//微博uid在都学网中有账号 但手机号不存在

    		//登录机制
            $this->_external_session_login($login_user,$user_data);

            return array('flag'=>2,'msg'=>'有账号但无手机号','data'=>$login_user);

    	}else if(empty($login_user)){
    		//无微博账号

    		$data = array(
    				'id'=>$user_data['id'],
    				'nickname'=>$user_data['name'],
    				'sex'=>($user_data['gender']=='m')?1:2,
    			);

			$login_user = $this->_external_auth_register($type,$data);

            $this->_external_session_login($login_user,$user_data);

            return array('flag'=>3,'msg'=>'无账号无手机号','data'=>$login_user);
    	}

    }


    //第三方注册
    protected function _external_auth_register($type,$user_data){
    	$external_uid = $user_data['id'];

    	$class_arr = $this->class_arr;

        $map['name'] = $login_account = $type.'_'.$external_uid;
        $exist_user = $this->zf_user_model->select_one('name = "'. $login_account .'" OR '.$class_arr[$type].' = "'.$external_uid.'"');

        if ($exist_user != false)
        {
            return $exist_user;
        }

        $login_salt = rand(11111, 99999);
        $password = chr(rand(65, 90)) .rand(11111, 99999) . chr(rand(65, 90));
        $map['nikename'] = $user_data['nickname'];
        $map['sex'] = $user_data['sex'];
        $map['login_stat'] = $login_salt;
        $map['password'] = md5(md5($password) . $login_salt);
        $map['ctime'] = time();
        $map['utime'] = time();
		$map['phone'] = '';
		$map['user_type'] = 3;
        $class = $class_arr[$type];
        $map[$class] = $external_uid;

        $uid = $this->zf_user_model->insert($map);
        $user = $this->zf_user_model->select_one('id='.$uid);

		//注册的账号登陆网站
		$this->user_session($user);

        return $user;
    }




    //登录机制
    protected function _external_session_login($login_user,$user_data){
		$this->user_session($login_user);

        if (empty($login_user['headimg']))  // 更新用户头像 at 2016-06-01
        {	
        	if(!empty($user_data['avatar_large'])){
                $headimg = $this->_save_external_user_avatar($user_data['avatar_large']);
                $this->zf_user_model->update(array('headimg' => $headimg), 'id =' . $login_user['id']);
        	}
        }
    }

    /****
    * @desc 保存用户的头像
    * @param $url
    * @return string
    */
    private function _save_external_user_avatar($url){	
        if (strpos($url, 'http://') !== 0){
            return '';
        }

        $content = $this->_curl_get_request($url);
            
        if ($content != false) {
            $save_dir = PUBLIC_URL.'headimg';
            $save_dir = trim($save_dir,'/');

            $file_name = date('YmdHis') . rand(10000, 99999) . '.jpg';
            $save_path = $save_dir . '/' . $file_name;

            file_put_contents($save_path, $content);
            $web_path = $save_dir . '/' . $file_name;

            return $web_path;
        }
        else
        {
            return '';
        }
    }


    //curl
    private function _curl_get_request($url,$header=array()){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,  $url);
        if(!empty($header)){
            curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $return_str = curl_exec($ch);
        curl_close($ch);
        $format_result = json_decode($return_str, true);
        return $format_result ? $format_result : $return_str;
    }

    //绑定页面
    public function bind_user_phone(){
    	$get = $this->input->get();
        $class_arr = $this->class_arr;

    	$type = base64_decode($get['type']);
    	$id = base64_decode($get['user']);

    	$user = $this->zf_user_model->select_one('id='.$id);

    	if(!array_key_exists($type,$class_arr)||empty($user)){
			header('location:'.HOME_URL);
    	}

    	$data = array(
    			'type'=>$type,
    			'user'=>$user,
    		);

    	$this->load->view(HOME_URL.'login/bind_user_phone',$data);
    }

    //绑定
    public function do_bind(){
    	$post = $this->input->post();

        $class_arr = $this->class_arr;

   		$class = array(
            'weibo'=>'微博',
            'weixin'=>'微信',
            'qq'=>'腾讯QQ',
        );

        $type = $post['type'];
        $fen = $class[$type];
        $field = $class_arr[$type];

		$data = array();
		$res = array();

		$email_user = $this->zf_user_model->select_one('email="'.$post['email'].'"');
        $login_user = $this->zf_user_model->select_one('id='.$post['login_uid']);

       if(empty($login_user)||empty($login_user[$field])){
            $this->load->view(HOME_URL.'login/web_error',array('type'=>$fen,'login'=>$type));
        }

		if(!empty($email_user)){
		 	//老用户登录绑定
            $password = md5(md5($post['password']).$email_user['login_stat']);
            if($email_user['password']!=$password){
                $data['flog']=0; 
				$data['msg']='您输入的密码与账号不匹配!'; 
				$data['data']=array(); 
				return_json($data);	
            }

            $update_data['name'] = $email_user['email'];
            $update_data['email'] = $email_user['email'];
            $update_data[$field] = $login_user[$field];

            if(empty($email_user['headimg'])){
                $update_data['headimg'] = $login_user['headimg'];
            }
            if(empty($email_user['nikename'])){
                $update_data['nikename'] = $login_user['nikename'];
            }
            
            $this->zf_user_model->update($update_data, 'id = ' . $email_user['id']);
            $this->zf_user_model->update(array($field => '',), 'id = ' . $login_user['id']);

            // 删除微信数据
            $this->zf_user_model->delete('id =' .$login_user['id']);

			//注册的账号登陆网站
			$this->user_session($email_user);

	        if($email_user['is_activate']==0){
				//给注册的邮箱发邮件
				$this->register_email($post['email']);
	        }

		}else{

            // 检查用户
            $login_stat = rand(11111, 99999);
            $new_password = md5(md5($post['password']) . $login_stat);
            $new_data = array(
                'name' => $post['email'],
                'email' => $post['email'],
                'login_stat' => $login_stat,
                'password' => $new_password,
            );
            $this->zf_user_model->update($new_data, 'id='.$post['login_uid']);

			//注册的账号登陆网站
			$this->user_session($login_user);

	        if($login_user['is_activate']==0){
				//给注册的邮箱发邮件
				$this->register_email($post['email']);
	        }

		}

		$data['flog']=1; 
		$data['msg']='绑定成功!'; 
		$data['data']=array(); 
		return_json($data);	
    }
}
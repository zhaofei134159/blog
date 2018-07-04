<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
		//CI 的session 太麻烦了  有好多问题  暂时用原生
		// $this->load->library('session');
		$this->load->helper('url');
		
		$this->init();
	}

	public function init(){
		
	}
	
}

//后台总控制器
class Admin_Controller extends MY_Controller{

	public $adminid = '';
	public function __construct(){
		parent::__construct();
		// 如果没有登录跳转到登录页面
        if (isset($_SESSION['admin_user_key']) && $_SESSION['admin_user_key']==true) {
            $this->adminid = $_SESSION['admin_user_info']['id'];
        }

		
	}

}

//前台总控制器
class Home_Controller extends MY_Controller{

	public $homeid = '';
	public $home = array();
	public function __construct(){
		parent::__construct();
		// 如果没有登录跳转到登录页面
        if (isset($_SESSION['home_user_key']) && $_SESSION['home_user_key']==true) {
            $this->homeid = $_SESSION['home_user_info']['id'];
            $this->home = $_SESSION['home_user_info'];
        }
		$this->load->vars('home',$this->home);
	}
}
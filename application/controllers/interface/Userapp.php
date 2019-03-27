<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Userapp extends Home_Controller{

	public $blogId = 2; 
	public $wxprogramappid = 'wxc0c4a718e3688f4f';
	public $wxprogramappsecret = '47737efeb2ac9bd4bcfd87b0674f51b4';

	public function __construct(){
		parent::__construct();

		$this->load->model('zf_blog_model');
		$this->load->model('zf_work_model');
		$this->load->model('zf_cate_model');
		$this->load->model('zf_tag_model');
		$this->load->model('zf_user_model');
        $this->load->library('pager');
		$this->load->helper('wxbizdatacrypt');
	}

	public function getuserinfo()
	{
		print_r($_POST);die;
		$appid = $this->wxprogramappid;
		$sessionKey = $this->wxprogramappsecret;

		$userInfo = $_POST['userInfo'];
		$encryptedData = $_POST['encryptedData'];
		$iv = $_POST['iv'];

		$pc = new WXBizDataCrypt($appid, $sessionKey);
		$errCode = $pc->decryptData($encryptedData, $iv, $data );

		if ($errCode == 0) {
		    print($data);
		} else {
		    print($errCode);
		}
		
	}

	
}
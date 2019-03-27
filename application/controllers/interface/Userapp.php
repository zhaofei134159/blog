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

	public function index()
	{
		$appid = 'wx4f4bc4dec97d474b';
		$sessionKey = 'tiihtNczf5v6AKRyjwEUhQ==';

		$encryptedData="CiyLU1Aw2KjvrjMdj8YKliAjtP4gsMZM
		                QmRzooG2xrDcvSnxIMXFufNstNGTyaGS
		                9uT5geRa0W4oTOb1WT7fJlAC+oNPdbB+
		                3hVbJSRgv+4lGOETKUQz6OYStslQ142d
		                NCuabNPGBzlooOmB231qMM85d2/fV6Ch
		                evvXvQP8Hkue1poOFtnEtpyxVLW1zAo6
		                /1Xx1COxFvrc2d7UL/lmHInNlxuacJXw
		                u0fjpXfz/YqYzBIBzD6WUfTIF9GRHpOn
		                /Hz7saL8xz+W//FRAUid1OksQaQx4CMs
		                8LOddcQhULW4ucetDf96JcR3g0gfRK4P
		                C7E/r7Z6xNrXd2UIeorGj5Ef7b1pJAYB
		                6Y5anaHqZ9J6nKEBvB4DnNLIVWSgARns
		                /8wR2SiRS7MNACwTyrGvt9ts8p12PKFd
		                lqYTopNHR1Vf7XjfhQlVsAJdNiKdYmYV
		                oKlaRv85IfVunYzO0IKXsyl7JCUjCpoG
		                20f0a04COwfneQAGGwd5oa+T8yO5hzuy
		                Db/XcxxmK01EpqOyuxINew==";

		$iv = 'r7BXXKkLb8qrSNn05n0qiA==';

		$pc = new WXBizDataCrypt($appid, $sessionKey);
		$errCode = $pc->decryptData($encryptedData, $iv, $data );

		if ($errCode == 0) {
		    print($data . "\n");
		} else {
		    print($errCode . "\n");
		}
		
	}

	
}
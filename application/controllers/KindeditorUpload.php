<?php
header("Content-type: text/html; charset=utf8");
set_time_limit(0);
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once $_SERVER['DOCUMENT_ROOT'].'/resource/js/kindeditor/php/JSON.php';


class KindeditorUpload extends CI_Controller {

    public $docroot = "";

    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('Util');
        $this->load->library('image_lib');
        $this->docroot = $_SERVER['DOCUMENT_ROOT'];
    }
    
    /*
    * 执行上传图片
    */
    public function doupfile(){
        $filepar   =  Util::get('filepar');
        $filepar   =  unserialize(urldecode($filepar));
        $result = array();
        $result['allowed_extensions'] = $filepar['allowed_extensions'];
        $result['error'] = 0;
        $iserror = false;
        //判断上传	
        if(!isset($_FILES['imgFile']['size']) || !isset($_FILES['imgFile']['name'])){
                self::alert("上传失败！");
                $iserror = true;
        }
        
        //判断文件名是否合法
        if(count(explode('.', $_FILES['imgFile']['name'])) < 0){
                self::alert("文件命名不合法！");
                $iserror = true;
        }
        
        
        //取扩展名
        $fileext = Util::get_extension($_FILES['imgFile']['name']);
        $fileext = strtolower($fileext);
        
        //判断大小
        if(Util::byteChange($_FILES['imgFile']['size'], 'MB') > 5){
                self::alert("文件大小不能超过5MB！");
                $iserror = true;
        }
        
        //判断扩展名
        if(!empty($result['allowed_extensions'])){
            $extensions = explode('|', $result['allowed_extensions']);
            if(!in_array($fileext, $extensions)){
                self::alert("只能上传扩展名为".$result['allowed_extensions']."的文件");
                $iserror = true;
            }
        }
        
        //设置上传路径
        $uploadpath = $filepar['path'].'/'.date('Y-m-d').'/';

        $config['upload_path']   = '.'.$uploadpath;
        Util::makeDir($config['upload_path']);
        $config['max_size']      = '0';                        //0为不限制
        $config['remove_spaces'] = TRUE;                       //文件名中的空格将被替换为下划线
        $config['overwrite']     = $filepar['overwrite'];       //是否覆盖
        $config['encrypt_name']  = $filepar['encrypt_name'];    //是否重命名
        $config ['allowed_types'] = $filepar['allowed_extensions'];
        $this->load->library('Upload2', $config);
        if(!$iserror){
            $field_name = "imgFile";
            if (!$this->upload2->do_upload($field_name)){
                $error = $this->upload2->display_errors();
                self::alert("上传失败1！".$error);
            }else{
                $data = $this->upload2->data();
                $filepath = $uploadpath.$data['file_name'];                
                //生成缩略图
                if(isset($filepar['thumb'])){
                    foreach($filepar['thumb'] as $k => $v){
                        //$filepar['thumb'][$k]['source_image'] = $_SERVER['DOCUMENT_ROOT'].$filepath;
                        self::imgResize($_SERVER['DOCUMENT_ROOT'].$filepath, $v['width'], $v['height'], $v['thumb_marker']);
                    }
                }
                //返回
              	$json = new Services_JSON();
              	echo $json->encode(array('error' => 0, 'url' => $filepath));
              	exit;
            }
        }
    }
    
    /*
    * 显示上传图片表单
    */
    public function upfile(){
        //相关参数
        $filepar           =  $_GET['filepar'];
        $result = array(
            'filepar' => unserialize(urldecode($filepar))
        ); 
        $this->load->view( "_common/upfile", $result );
    }
    
    
    
    /*
    * 缩略图
    */
    public function imgResize($source_image, $toW=200, $toH=200, $thumb_marker = '_thumb') {
      
      
              $info = "";
        //返回含有4个单元的数组，0-宽，1-高，2-图像类型，3-宽高的文本描述。
        //失败返回false并产生警告。
        $data = getimagesize($source_image, $info);

        //将文件载入到资源变量im中
        switch ($data[2]) //1-GIF，2-JPG，3-PNG
        {
        case 1:
                if(!function_exists("imagecreatefromgif"))
                {
                        echo "the GD can't support .gif, please use .jpeg or .png! <a href='javascript:history.back();'>back</a>";
                        exit();
                }
                $im = imagecreatefromgif($source_image);
                break;
               
        case 2:
                if(!function_exists("imagecreatefromjpeg"))
                {
                        echo "the GD can't support .jpeg, please use other picture! <a href='javascript:history.back();'>back</a>";
                        exit();
                }
                $im = imagecreatefromjpeg($source_image);
                break;
                 
        case 3:
                $im = imagecreatefrompng($source_image);    
                break;
        }



        //计算缩略图的宽高
        $srcW = imagesx($im);
        $srcH = imagesy($im);
        $srcWH = $srcW / $srcH;

        if(empty($toW))  $toW = (int)($toH * ($srcW / $srcH));
        
        if(empty($toH))  $toH = (int)($toW * ($srcH / $srcW));



        $thumb_config['image_library']   =  'gd2';
        $thumb_config['source_image']    =  $source_image;
        $thumb_config['create_thumb']    =  TRUE;
        $thumb_config['maintain_ratio']  =  TRUE;
        $thumb_config['quality']         =  100;  //设置图像的品质。品质越高，图像文件越大 
        
        $thumb_config['width']           =  $toW;
        $thumb_config['height']          =  $toH;
        $thumb_config['thumb_marker']    =  $thumb_marker;
        $this->image_lib->initialize($thumb_config);
        $this->image_lib->resize();
        $this->image_lib->clear();
    }

    function alert($msg) {
        //header('Content-type: text/html; charset=UTF-8');
        $json = new Services_JSON();
        echo $json->encode(array('error' => 1, 'message' => $msg));
        exit;
    }


    /*
     * 上传菜谱成品图
    */
    public function upCookImageFile(){
        $fpath = $_GET['fpath'];
        if(!empty($fpath)){
            $path  =  $fpath;
        }else{
            $path  =  "/uploads/";
        }
        
        $result = array();
        $result['allowed_extensions'] = 'gif|jpg|png';
        
        $result['error'] = 0;
        $iserror = false;
        //判断上传	
        if(!isset($_FILES['uploadFile']['size']) || !isset($_FILES['uploadFile']['name'])){
                $iserror = true;
        }
        
        //判断文件名是否合法
        if(count(explode('.', $_FILES['uploadFile']['name'])) >= 3){
                $iserror = true;
        }
        
        //取扩展名
        $fileext = Util::get_extension($_FILES['uploadFile']['name']);
        $fileext = strtolower($fileext);
        
        //判断大小
        if(Util::byteChange($_FILES['uploadFile']['size'], 'MB') > 500){
                self::alert("文件大小不能超过500MB！");
                $iserror = true;
        }
        
        //判断扩展名
        if(!empty($result['allowed_extensions'])){
            $extensions = explode('|', $result['allowed_extensions']);
            if(!in_array($fileext, $extensions)){
                $iserror = true;
            }
        }
        
        //设置上传路径
        $uploadpath = $path.date('Y-m-d').'/';

        $config['upload_path']   = '.'.$uploadpath;
        Util::makeDir($config['upload_path']);
        $config['max_size']      = '0';                        //0为不限制
        $config['remove_spaces'] = TRUE;                       //文件名中的空格将被替换为下划线
        $config['overwrite']     = FALSE;                      //是否覆盖
        $config['encrypt_name']  = TRUE;                       //是否重命名
        $config ['allowed_types'] = $result['allowed_extensions'];

        $this->load->library('Upload2', $config);
        if(!$iserror){
            $field_name = 'uploadFile';
            if (!$this->upload2->do_upload($field_name)){
                $error = $this->upload2->display_errors();
                echo $error;
                $result = array(
                    'code'  => 'Error'
                );
                header('Content-Type:text/html; charset=utf-8');
                echo json_encode($result);
                die();
            }else{
                $data = $this->upload2->data();
                $filepath = $uploadpath.$data['file_name'];
                //返回
                $result = array(
                    'code'   => 'Success',
                    'imgUrl' => $filepath
                );
                header('Content-Type:text/html; charset=utf-8');
                echo json_encode($result);
                die();
            }
        }else{
            $result = array(
                'code'  => 'Error'
            );
            header('Content-Type:text/html; charset=utf-8');
            echo json_encode($result);
            die();
        }
    }

}
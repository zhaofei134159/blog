<?php

	/*
	 * PHP upload demo for Editor.md
     *
     * @FileName: upload.php
     * @Auther: Pandao
     * @E-mail: pandao@vip.qq.com
     * @CreateTime: 2015-02-13 23:20:04
     * @UpdateTime: 2015-02-14 14:52:50
     * Copyright@2015 Editor.md all right reserved.
	 */

    //header("Content-Type:application/json; charset=utf-8"); // Unsupport IE
    header("Content-Type:text/html; charset=utf-8");
    header("Access-Control-Allow-Origin: *");

    require("editormd.uploader.class.php");

    error_reporting(E_ALL & ~E_NOTICE);
    date_default_timezone_set('Asia/Shanghai');
	
	// $path     = __DIR__ . DIRECTORY_SEPARATOR;
	// $url      = dirname($_SERVER['PHP_SELF']) . '/';
	// $savePath = realpath($path . '../uploads/') . DIRECTORY_SEPARATOR;
	// $saveURL  = $url . '../uploads/';

    $php_path = __DIR__ . DIRECTORY_SEPARATOR;
    $php_url = dirname($_SERVER['PHP_SELF']) . '/';

    //文件保存目录路径
    $savePath = realpath($php_path . '../attached/') . DIRECTORY_SEPARATOR;;
    //文件保存目录URL
    $saveURL = $php_url . '../attached/';

	$formats  = array(
		'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp')
	);

    $name = 'editormd-image-file';

    if (isset($_FILES[$name]))
    {        
        $imageUploader = new EditorMdUploader($savePath, $saveURL, $formats['image'],1,date('YmdHis'));  // Ymdhis表示按日期生成文件名，利用date()函数
        
        $imageUploader->config(array(
            'maxSize' => 1024,        // 允许上传的最大文件大小，以KB为单位，默认值为1024
            'cover'   => true         // 是否覆盖同名文件，默认为true
        ));
        
        if ($imageUploader->upload($name))
        {
            $imageUploader->message('上传成功！', 1);
        }
        else
        {
            $imageUploader->message('上传失败！', 0);
        }
    }
?>
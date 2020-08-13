<?php

//返回json的方法
function return_json($data){
	echo json_encode($data,JSON_UNESCAPED_UNICODE);
	die;
}


//上传图片
function upload_headimg($file,$url=''){
	//
	if(!empty($url)){
		@unlink($url);
	}

	$file_arr = explode('.',$file['name']);
	$zhui = array_pop($file_arr);

    $save_dir = PUBLIC_URL.'headimg';
    $save_dir = trim($save_dir,'/');

    $file_name = date('YmdHis') . rand(10000, 99999) . '.'.$zhui;
    $save_path = $save_dir . '/' . $file_name;

    move_uploaded_file($file['tmp_name'],$save_path);
    $web_path = $save_dir . '/' . $file_name;

    return $web_path;
}


//上传图片
function upload_workimg($file,$url=''){
	//
	if(!empty($url)){
		@unlink($url);
	}

	$file_arr = explode('.',$file['name']);
	$zhui = array_pop($file_arr);

    $save_dir = PUBLIC_URL.'workimg';
    $save_dir = trim($save_dir,'/');

    $file_name = date('YmdHis') . rand(10000, 99999) . '.'.$zhui;
    $save_path = $save_dir . '/' . $file_name;

    move_uploaded_file($file['tmp_name'],$save_path);
    $web_path = $save_dir . '/' . $file_name;

    return $web_path;
}

// 聊天图片
function upload_img($file,$address,$url=''){
	//
	if(!empty($url)){
		@unlink($url);
	}

	$file_arr = explode('.',$file['name']);
	$zhui = array_pop($file_arr);

    $save_dir = PUBLIC_URL.$address;
    $save_dir = trim($save_dir,'/');

    $file_name = date('YmdHis') . rand(10000, 99999) . '.'.$zhui;
    $save_path = $save_dir . '/' . $file_name;

    move_uploaded_file($file['tmp_name'],$save_path);
    $web_path = $save_dir . '/' . $file_name;

    return $web_path;
}

function upload_file($file,$address,$url=''){
    //
    if(!empty($url)){
        @unlink($url);
    }

    $file_arr = explode('.',$file['name']);
    $zhui = array_pop($file_arr);

    $save_dir = PUBLIC_URL.$address;
    $save_dir = trim($save_dir,'/');

    $file_name = date('YmdHis') . rand(10000, 99999) . '.'.$zhui;
    $save_path = $save_dir . '/' . $file_name;

    move_uploaded_file($file['tmp_name'],$save_path);
    $web_path = $save_dir . '/' . $file_name;

    return $web_path;
}

function voiceFormatConversion($url,$format,$address){
    $data = json_decode(file_get_contents('http://api.rest7.com/v1/sound_convert.php?url=' . $url . '&format='.$format));

    if (@$data->success !== 1)  
    {
        die('Failed');
    }
    $wave = file_get_contents($data->file);

    $save_dir = PUBLIC_URL.$address;
    $save_dir = trim($save_dir,'/');

    $file_name = date('YmdHis') . rand(10000, 99999) . '.'.$format;
    $save_path = $save_dir . '/' . $file_name;

    file_put_contents($save_path, $wave);
    
    return $save_path;
}

function getDateList($startDate,$endDate){
    //获得该时间段内所有日期列表
    $dateList = array();
    for($i = strtotime($startDate); $i <= strtotime($endDate); $i += 86400)
    {
        $ThisDate = date("Y-m-d",$i);
        $dateList[] = $ThisDate;
    }
    
    return $dateList;
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
                        
    /*
    * 不走权限验证的控制器
    */
    $config['excludeController'] = array(
        'main' => array('index', 'leftmenu', ''),
        'formy' => array('index', 'useredit', 'changepas', ''),
        'daq_xiachufang' => array('index', 'getUrlLists', 'caiji'),
        'agent_user' => array('userradio'),
    );

    $config['code'] = array(
        'is_use' => array(
            0 => '未使用', 
            1 => '已发放', 
            2 => '已使用'
        ),
        'is_del' => array(
            1  => '正常', 
            -1 => '删除'
        ),
        
    );

    $config['bankname'] = array(
        '1' => '中国工商银行',
        '2' => '中国农业银行',
        '3' => '中国银行',
        '4' => '中国建设银行',
        '5' => '交通银行',
        '6' => '招商银行',
        '7' => '中国民生银行',
        '8' => '中国光大银行',
        '9' => '中信银行',
        '10' => '华夏银行',
        '11' => '兴业银行',
        '12' => '广发银行'    
    );

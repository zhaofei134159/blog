<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="“blogfamily”，创建一个属于自己的博客，在这里你可以分享自己的知识，记录的生活，为自己加油" />
    <meta name="keywords"  content="blogfamily, 博客, 博客之家, zf, blog" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <title>blogfamily</title>

    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="<?=HOME_PUBLIC_URL?>css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="<?=HOME_PUBLIC_URL?>css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="<?=HOME_PUBLIC_URL?>css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='<?=HOME_PUBLIC_URL?>css/blogfamily.css' rel='stylesheet' type='text/css' />
    <link href='<?=HOME_PUBLIC_URL?>css/familyfooter.css' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="<?=PUBLIC_URL?>css/pagestring.css" />

    
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="<?=HOME_PUBLIC_URL?>js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="<?=HOME_PUBLIC_URL?>js/bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <!--<script src="<?=HOME_PUBLIC_URL?>js/custom.js"></script>-->
    
    <!-- 看板娘 -->
    <link rel="stylesheet" type="text/css" href="<?=HOME_PUBLIC_URL?>assets/waifu.css"/>

	<link rel="shortcut icon" href="<?=PUBLIC_URL?>/images/favicon.ico" />

    
    <!-- baidu -->
    <script>
        var _hmt = _hmt || [];
        (function() {
          var hm = document.createElement("script");
          hm.src = "https://hm.baidu.com/hm.js?8d3b80d7e903f894ecf86db2cb9f3890";
          var s = document.getElementsByTagName("script")[0]; 
          s.parentNode.insertBefore(hm, s);
        })();
    </script>

</head>
<body>
    <div class="navbar navbar-inverse set-radius-zero" >
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/home">
					blogfamily
                </a>
            </div>

        </div>
    </div>
    <!--
    <section class="menu-section">
        <div class="container">
            <div  class="row">
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissable">
                        <p> 【宝塔服务器面板】
                            <a href="https://www.bt.cn/?invite_code=MV95c2Vxa3c=" target="__black" style="color: #3c763d;border-bottom: dashed 0.6px #ad410e;">点我领取</a>,
                         属于你的3188元礼包 </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    -->
    <!-- LOGO HEADER END-->
    <section class="menu-section">
        <div class="container">
            <div class="row ">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse ">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <li><a href="<?=HOME_URL_HTTP?>" class="menu-top-active">首页</a></li>
                            <?php if(empty($home)){?>
                                <li><a href="<?=HOME_URL_HTTP?>login">登录</a></li>
                            <?php }else{?>
                                <li>
                                    <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> <?=!empty($home['nikename'])?$home['nikename']:$home['name']?> <i class="fa fa-angle-down"></i></a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                        <li role="presentation" ><a role="menuitem" tabindex="-1" href="<?=HOME_URL_HTTP?>user">个人信息</a></li>
                                        <li role="presentation" ><a role="menuitem" tabindex="-1" href="<?=HOME_URL_HTTP?>login/unlogin">退出</a></li>
                                    </ul>
                                </li>
                            <?php }?>
                            <li><a href="<?=HOME_URL_HTTP?>friend" class="menu-top-active">友链</a></li>
                            <?php if(!empty($home['id'])&&$home['id']==122){?>
                            <li><a href="<?=HOME_URL_HTTP?>chatroom" class="menu-top-active">聊天室</a></li>
                            <?php }?>
                            <li><a href="https://books.myfeiyou.com" class="menu-top-active">图书馆</a></li>
                            <li><a href="<?=HOME_URL_HTTP?>leave" class="menu-top-active">留言板</a></li>
                            <li><a href="<?=HOME_URL_HTTP?>about" class="menu-top-active">关于我们</a></li>
                        </ul>
                    </div>
                </div>

            </div>
            <div  class="row ">
                <div class="col-md-12">
                    <div class="alert alert-dismissable" id="alert" style="display:none;">
                        <span class="close" onclick="abandon()">×</span>
                        <p id="myModalBody"> </p>
                    </div>
                </div>
                <script type="text/javascript">
                    function abandon(){
                        myModalBody.html('');
                        myModal.removeClass('alert-danger');
                        myModal.removeClass('alert-success');
                        myModal.css('display','none');
                    }
                </script>
            </div>
        </div>
    </section>

    

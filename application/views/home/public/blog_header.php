<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=empty($blog['name'])?'':$blog['name']?></title>
    <meta name="viewport" content="width=device-width">

    <meta name="description" content="<?=empty($blog['desc'])?'':$blog['desc']?>" />
    <meta name="keywords"  content="<?=empty($blog['name'])?'':$blog['name']?>, 博客, 博客之家,zf, blog" />


    <!-- Bootstrap styles -->
    <link rel="stylesheet" href="<?=PUBLIC_URL?>blog/css/bootstrap.min.css">
   
    <!-- Font-Awesome -->
    <link rel="stylesheet" href="<?=PUBLIC_URL?>blog/css/font-awesome/css/font-awesome.min.css">

    <!-- Google Webfonts -->
    <link href='<?=PUBLIC_URL?>blog/css/open.css' rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link rel="stylesheet" href="<?=PUBLIC_URL?>blog/css/style.css" id="theme-styles">
    
    <link rel="stylesheet" href="<?=PUBLIC_URL?>css/pagestring.css" />

    <!--[if lt IE 9]>      
        <script src="js/vendor/google/html5-3.6-respond-1.1.0.min.js"></script>
    <![endif]-->

    
    <script src="<?=PUBLIC_URL?>blog/js/jquery.min.js"></script>
    <script src="<?=PUBLIC_URL?>blog/js/bootstrap.min.js"></script>
    <script src="<?=PUBLIC_URL?>blog/js/modernizr.js"></script>
    
    <link rel="shortcut icon" href="<?=PUBLIC_URL?>/images/favicon.ico" />
    
</head>
<body>
    <header>
        <div class="widewrapper masthead">
            <div class="container">
                <a href="<?=HOME_URL?>blog/<?=$blog['id']?>" id="logo">
                    <!-- <img src="img/logo.png" alt="clean Blog"> -->
                    <h2><?=empty($blog['name'])?'':$blog['name']?></h2> 
                </a>

                <div id="mobile-nav-toggle" class="pull-right">
                    <a href="#" data-toggle="collapse" data-target=".clean-nav .navbar-collapse">
                        <i class="fa fa-bars"></i>
                    </a>
                </div>

                <nav class="pull-right clean-nav">
                    <div class="collapse navbar-collapse">
                        <ul class="nav nav-pills navbar-nav">
                            <li>
                                <a href="<?=HOME_URL?>blog/<?=$blog['id']?>/about">关于</a>
                            </li>
                            <li>
                                <?php if(empty($home)){?>
                                <a href="<?=HOME_URL?>login" target="__black">登录</a>
                                <?php }else{?>
                                <a href="<?=HOME_URL?>user" target="__black">个人中心</a>
                                <?php }?>
                            </li>                        
                        </ul>
                    </div>
                </nav>        

            </div>
        </div>

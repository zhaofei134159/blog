<!DOCTYPE html>
<html lang="en">

<head>
<title>zf.ren 后台</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<link rel="stylesheet" href="<?=ADMIN_PUBLIC_URL?>css/bootstrap.min.css" />
<link rel="stylesheet" href="<?=ADMIN_PUBLIC_URL?>css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="<?=ADMIN_PUBLIC_URL?>css/fullcalendar.css" />
<link rel="stylesheet" href="<?=ADMIN_PUBLIC_URL?>css/maruti-style.css" />
<link rel="stylesheet" href="<?=ADMIN_PUBLIC_URL?>css/maruti-media.css" class="skin-color" />
<link rel="stylesheet" href="<?=PUBLIC_URL?>css/alertify.css" />
<link rel="stylesheet" href="<?=PUBLIC_URL?>css/pagestring.css" />


<script src="<?=PUBLIC_URL?>js/jquery-1.9.1.min.js"></script>
<script src="<?=ADMIN_PUBLIC_URL?>js/excanvas.min.js"></script> 
<script src="<?=ADMIN_PUBLIC_URL?>js/jquery.min.js"></script> 
<script src="<?=ADMIN_PUBLIC_URL?>js/jquery.ui.custom.js"></script> 
<script src="<?=ADMIN_PUBLIC_URL?>js/bootstrap.min.js"></script> 
<script src="<?=ADMIN_PUBLIC_URL?>js/jquery.flot.min.js"></script> 
<script src="<?=ADMIN_PUBLIC_URL?>js/jquery.flot.resize.min.js"></script> 
<script src="<?=ADMIN_PUBLIC_URL?>js/jquery.peity.min.js"></script> 
<script src="<?=ADMIN_PUBLIC_URL?>js/fullcalendar.min.js"></script> 
<script src="<?=ADMIN_PUBLIC_URL?>js/maruti.js"></script> 
<script src="<?=ADMIN_PUBLIC_URL?>js/maruti.dashboard.js"></script> 
<script src="<?=ADMIN_PUBLIC_URL?>js/maruti.chat.js"></script> 
<script src="<?=PUBLIC_URL?>js/jquery.alertify.js"></script>
<link rel="shortcut icon" href="<?=PUBLIC_URL?>images/favicon.ico" />

</head>
<body>

<!--Header-part-->
<div id="header">
  <h1>zf.ren 后台</h1>
</div>
<!--close-Header-part--> 

<!--top-Header-messaages-->
<div class="btn-group rightzero"> 
  <a class="top_message tip-left" title="Manage Files">
    <i class="icon-file"></i>
  </a> 
  <a class="top_message tip-bottom" title="Manage Users">
    <i class="icon-user"></i>
  </a> 
  <a class="top_message tip-bottom" title="Manage Comments">
    <i class="icon-comment"></i>
    <span class="label label-important">5</span>
  </a> 
  <a class="top_message tip-bottom" title="Manage Orders">
    <i class="icon-shopping-cart"></i>
  </a> 
</div>
<!--close-top-Header-messaages--> 

<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li class="" ><a title="" href="<?=ADMIN_URL?>admin/edit?id=<?=$this->adminid?>"><i class="icon icon-user"></i> <span class="text">用户信息</span></a></li>
    <!-- <li class=" dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-envelope"></i> <span class="text">站内信</span> <span class="label label-important">5</span> <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a class="sAdd" title="" href="#">new message</a></li>
        <li><a class="sInbox" title="" href="#">inbox</a></li>
        <li><a class="sOutbox" title="" href="#">outbox</a></li>
        <li><a class="sTrash" title="" href="#">trash</a></li>
      </ul>
    </li> -->
    <li class=""><a title="" href="#"><i class="icon icon-cog"></i> <span class="text">网站设置</span></a></li>
    <li class=""><a title="" href="javascript:;" onclick="unlogin()" ><i class="icon icon-share-alt"></i> <span class="text">退出登录</span></a></li>
  </ul>
</div>
<div id="search">
  <input type="text" placeholder="Search here..."/>
  <button type="submit" class="tip-left" title="Search"><i class="icon-search icon-white"></i></button>
</div>
<!--close-top-Header-menu-->

<div id="sidebar">
  <div id="search">
    <input type="text" placeholder="Search here..."/><button type="submit" class="tip-bottom" title="Search"><i class="icon-search icon-white"></i></button>
  </div>
  <a href="#" class="visible-phone"><i class="icon icon-th-list"></i>  admin 菜单 </a>
  <ul>
    <li class=""> <a href="<?=ADMIN_URL?>index"> <i class="icon icon-home"></i> <span class="text">首页</span></a></li>
    <li class=""> <a href="<?=ADMIN_URL?>admin"> <i class="icon-user"></i> <span class="text">管理员管理</span></a></li>
    <li class="submenu"> 
      <a href="#"> <i class="icon-user"></i> <span class="text">用户管理</span></a>
      <ul>
        <li><a href="<?=ADMIN_URL?>user">用户管理</a></li>
        <li><a href="<?=ADMIN_URL?>user_blog">博客管理</a></li>
        <li><a href="<?=ADMIN_URL?>user_cate">分类管理</a></li>
        <li><a href="<?=ADMIN_URL?>user_tag">标签管理</a></li>
        <li><a href="<?=ADMIN_URL?>user_work">文章管理</a></li>
      </ul>
    </li>
    <li class="submenu"> 
      <a href="#"> <i class="icon-user"></i> <span class="text">网站管理</span></a>
      <ul>
        <li><a href="<?=ADMIN_URL?>ad">轮播管理</a></li>
      </ul>
    </li>
    <!-- <li class="submenu"> <a href="#"><i class="icon icon-file"></i> <span>Addons</span> <span class="label">3</span></a>
      <ul>
        <li><a href="gallery.html">Gallery</a></li>
        <li><a href="calendar.html">Calendar</a></li>
        <li><a href="chat.html">Chat option</a></li>
      </ul>
    </li> -->
  </ul>
</div>
<script type="text/javascript">
  function unlogin(){
      var message = "<p>确认退出?</p><br/>";

      alertify.confirm(message, function (e) {
          if(e) {
            window.location.href = '/backstage/login/unlogin';
          } 
      });
     
  } 
</script>
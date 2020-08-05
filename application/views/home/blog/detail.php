<?php 
    $this->load->view('home/public/blog_header');
?>
  
        <div class="widewrapper subheader">
            <div class="container">
                <div class="clean-breadcrumb" style="font-size:20px;">
                    <a href="<?=HOME_URL?>blog/<?=$blog['id']?>" style="margin:0 5px">首页</a>
                    <span class="separator">&#x2F;</span>
                    <a href="<?=HOME_URL?>blog/<?=$blog['id']?>/art_list">文章列表</a>
                    <span class="separator">&#x2F;</span>
                    <a href="<?=HOME_URL?>blog/<?=$blog['id']?>/detail/4">文章详情</a>
                </div>

                <div class="clean-searchbox">
                   <!--  <form action="#" method="get" accept-charset="utf-8">
                        <input class="searchfield" id="searchbox" type="text" placeholder="Search">
                        <button class="searchbutton" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </form> -->
                </div>
            </div>
        </div>
    </header>


    <div class="widewrapper main">
        <div class="container">
            <div class="row">
                <div class="col-md-8 blog-main">
                    <article class="blog-post">
                        <header>
                            <?php if(!empty($work['img'])){?>
                            <div class="lead-image">
                                <img src="/<?=$work['img']?>" style="width:100%;height:380px;" alt="" class="img-responsive">
                            </div>
                            <?php }?>
                        </header>
                        <div class="body" style="overflow: hidden;">
                            <h1><?=$work['title']?></h1>
                            <div class="meta">
                                <i class="fa fa-user"></i> <?=empty($work['user']['nikename'])?'':$work['user']['nikename']?> 博主
                                <i class="fa fa-calendar"></i><?=date('Y-m-d',$work['ctime'])?>
                                <!-- <i class="fa fa-comments"></i><span class="data"><a href="#comments">3 Comments</a></span> -->
                            </div>
                            <p>
                               <?=$work['desc']?>
                            </p>
                        </div>
                    </article>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <div>
                        <!--PC和WAP自适应版-->
                        <div id="SOHUCS" sid="<?=$work['id']?>" ></div> 
                        <script type="text/javascript"> 
                            (function(){ 
                            var appid = 'cyun2Y8TL'; 
                            var conf = 'prod_4783902b002c2721cb4383210ac0fb20'; 
                            var width = window.innerWidth || document.documentElement.clientWidth; 
                            if (width < 960) { 
                            window.document.write('<script id="changyan_mobile_js" charset="utf-8" type="text/javascript" src="http://changyan.sohu.com/upload/mobile/wap-js/changyan_mobile.js?client_id=' + appid + '&conf=' + conf + '"><\/script>'); } else { var loadJs=function(d,a){var c=document.getElementsByTagName("head")[0]||document.head||document.documentElement;var b=document.createElement("script");b.setAttribute("type","text/javascript");b.setAttribute("charset","UTF-8");b.setAttribute("src",d);if(typeof a==="function"){if(window.attachEvent){b.onreadystatechange=function(){var e=b.readyState;if(e==="loaded"||e==="complete"){b.onreadystatechange=null;a()}}}else{b.onload=a}}c.appendChild(b)};loadJs("http://changyan.sohu.com/upload/changyan.js",function(){window.changyan.api.config({appid:appid,conf:conf})}); } })(); 
                        </script>
                        
                    </div>
                </div>
                <aside class="col-md-4 blog-aside">
                    
                    <div class="aside-widget">
                        <header>
                            <h3>相关文章</h3>
                        </header>
                        <div class="body">
                            <ul class="clean-list">
                                 <?php 
                                    if(!empty($tags)){
                                        foreach($relevant as $rekey=>$reval){
                                ?>
                                    <li><a href="<?=HOME_URL?>blog/<?=$blog['id']?>/detail/<?=$reval['id']?>"><?=$reval['title']?></a></li>
                                <?php 
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                

                    <div class="aside-widget">
                        <header>
                            <h3>当前文章标签</h3>
                        </header>
                        <div class="body clearfix">
                            <ul class="tags">
                                 <?php 
                                    if(!empty($tags)){
                                        foreach($tags as $tk=>$tag){
                                ?>
                                    <li><a href="<?=HOME_URL?>blog/<?=$blog["id"]?>/art_list?tag=<?=$tag['id']?>,"><?=$tag['name']?></a></li>
                                <?php            
                                        }
                                    }
                                ?>                          
                            </ul>
                        </div>
                    </div>

                    <div class="aside-widget">
                        <header>
                            <h3>分享</h3>
                        </header>
                        <div class="body clearfix">
                            <div class="bshare-custom"><a title="分享到QQ空间" class="bshare-qzone"></a><a title="分享到新浪微博" class="bshare-sinaminiblog"></a><a title="分享到人人网" class="bshare-renren"></a><a title="分享到腾讯微博" class="bshare-qqmb"></a><a title="分享到网易微博" class="bshare-neteasemb"></a><a title="更多平台" class="bshare-more bshare-more-icon more-style-addthis"></a><span class="BSHARE_COUNT bshare-share-count">0</span></div><script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/button.js#style=-1&amp;uuid=&amp;pophcol=2&amp;lang=zh"></script><a class="bshareDiv" onclick="javascript:return false;"></a><script type="text/javascript" charset="utf-8" src="https://static.bshare.cn/b/bshareC0.js"></script>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
<?php 
    $this->load->view('home/public/blog_footer');
?>
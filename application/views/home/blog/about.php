<?php 
    $this->load->view('home/public/blog_header');
?>
  
        <div class="widewrapper subheader">
            <div class="container">
                <div class="clean-breadcrumb" style="font-size:20px;">
                    <a href="<?=HOME_URL?>blog/<?=$blog['id']?>" style="margin:0 5px">首页</a>
                    <span class="separator">&#x2F;</span>
                    <a href="<?=HOME_URL?>blog/<?=$blog['id']?>/about">博客博主介绍</a>
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


    <div class="widewrapper main" style="min-height:500px;">
        <div class="container">
            <div class="row">
                <div class="col-md-8 blog-main">
                    <article class="blog-post">
                        <div class="body" style="overflow: hidden;">
                            <p>
                               <?=$blog['desc']?>
                            </p>
                        </div>
                    </article>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                </div>
                <aside class="col-md-4 blog-aside">

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
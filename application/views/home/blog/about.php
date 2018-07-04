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
                            <div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a><a href="#" class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"></a></div>
                            <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"32"},"share":{},"image":{"viewList":["qzone","tsina","tqq","weixin","sqq"],"viewText":"分享到：","viewSize":"16"},"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["qzone","tsina","tqq","weixin","sqq"]}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
<?php 
    $this->load->view('home/public/blog_footer');
?>
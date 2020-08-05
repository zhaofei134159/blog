<?php 
    $this->load->view('home/public/blog_header');
?>

        <div class="widewrapper subheader">
            <div class="container">
                <div class="clean-breadcrumb" style="font-size:20px;">
                    <a href="<?=HOME_URL?>blog/<?=$blog['id']?>" style="margin:0 5px">首页</a>
                </div>

                <div class="clean-searchbox">
                    <!-- <form action="#" method="get" accept-charset="utf-8">
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
                    <div class="row">
                        <?php 
                            if(!empty($works)){
                                foreach($works as $wk=>$work){
                        ?>
                        <div class="col-md-6 col-sm-6">
                            <article class="blog-teaser">
                                <header>
                                    <?php if(!empty($work['img'])){?>
                                    <img src="/<?=$work['img']?>" style="width:100%;height:200px;" alt="">
                                    <?php }else{?>
                                    <img src="/public/public/workimg/2014312311231233123.jpg"  style="width:100%;height:200px;" alt="">
                                    <?php }?>
                                    <br>
                                    <h3><a href="<?=HOME_URL?>blog/<?=$blog['id']?>/detail/<?=$work['id']?>"><?=$work['title']?></a></h3>
                                    <span class="meta"><?=date('Y-m-d H:i',$work['ctime'])?></span>
                                    <hr>
                                </header>
                                <div class="body">
                                <?php 
                                    if(!empty($work['desc'])){
                                        $desc = strip_tags($work['desc']);
                                        echo mb_substr($desc,0,30,'utf-8');
                                    }
                                ?>
                                </div>
                                <div class="clearfix">
                                    <a href="<?=HOME_URL?>blog/<?=$blog['id']?>/detail/<?=$work['id']?>" class="btn btn-clean-one">详情</a>
                                </div>
                            </article>
                        </div>
                        <?php 
                                }
                            }
                        ?>
                    </div>
                </div>
                <aside class="col-md-4 blog-aside">
                    
                  <!--   <div class="aside-widget">
                      <header>
                          <h3>Featured Post</h3>
                      </header>
                      <div class="body">
                          <ul class="clean-list">
                              <li><a href="">Clean - Responsive HTML5 Template</a></li>
                              <li><a href="">Responsive Pricing Table</a></li>
                              <li><a href="">Yellow HTML5 Template</a></li>
                              <li><a href="">Blackor Responsive Theme</a></li>
                              <li><a href="">Portfolio Bootstrap Template</a></li>
                              <li><a href="">Clean Slider Template</a></li>
                          </ul>
                      </div>
                  </div>
                                  
                  <div class="aside-widget">
                      <header>
                          <h3>Related Post</h3>
                      </header>
                      <div class="body">
                          <ul class="clean-list">
                              <li><a href="">Blackor Responsive Theme</a></li>
                              <li><a href="">Portfolio Bootstrap Template</a></li>
                              <li><a href="">Clean Slider Template</a></li>
                              <li><a href="">Clean - Responsive HTML5 Template</a></li>
                              <li><a href="">Responsive Pricing Table</a></li>
                              <li><a href="">Yellow HTML5 Template</a></li>
                          </ul>
                      </div>
                  </div> -->
                    <div class="aside-widget">
                        <header>
                            <h3>分类</h3>
                        </header>
                        <div class="body clearfix">
                            <ul class="tags">
                                <?php 
                                    if(!empty($cates)){
                                        foreach($cates as $ck=>$cate){
                                ?>
                                    <li><a href="<?=HOME_URL?>blog/<?=$blog["id"]?>/art_list?cate=<?=$cate['id']?>"><?=$cate['title']?></a></li>
                                <?php            
                                        }
                                    }
                                ?>                        
                            </ul>
                        </div>
                    </div>
                    <div class="aside-widget">
                        <header>
                            <h3>标签</h3>
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
                            <div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a><a href="#" class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"></a></div>
                            <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"32"},"share":{},"image":{"viewList":["qzone","tsina","tqq","weixin","sqq"],"viewText":"分享到：","viewSize":"16"},"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["qzone","tsina","tqq","weixin","sqq"]}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='/public/home/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
                        </div>
                    </div>

                </aside>
            </div>  
        </div>
    </div>

<?php 
    $this->load->view('home/public/blog_footer');
?>
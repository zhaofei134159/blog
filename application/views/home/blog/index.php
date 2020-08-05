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
                            <div class="bshare-custom"><a title="分享到QQ空间" class="bshare-qzone"></a><a title="分享到新浪微博" class="bshare-sinaminiblog"></a><a title="分享到人人网" class="bshare-renren"></a><a title="分享到腾讯微博" class="bshare-qqmb"></a><a title="分享到网易微博" class="bshare-neteasemb"></a><a title="更多平台" class="bshare-more bshare-more-icon more-style-addthis"></a><span class="BSHARE_COUNT bshare-share-count">0</span></div><script type="text/javascript" charset="utf-8" src="https://static.bshare.cn/b/button.js#style=-1&amp;uuid=&amp;pophcol=2&amp;lang=zh"></script><a class="bshareDiv" onclick="javascript:return false;"></a><script type="text/javascript" charset="utf-8" src="https://static.bshare.cn/b/bshareC0.js"></script>
                        </div>
                    </div>

                </aside>
            </div>  
        </div>
    </div>

<?php 
    $this->load->view('home/public/blog_footer');
?>
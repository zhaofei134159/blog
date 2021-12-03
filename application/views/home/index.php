<?php 
	$this->load->view('home/public/top');
?>
<!-- MENU SECTION END-->
<div class="content-wrapper">
    <div class="container">
        <!-- <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">最佳博客</h4>
             </div>
        </div>    -->          
        <hr>
        <div class="row">
          	<div class="col-md-8 col-sm-12 col-xs-12">
                <div id="carousel-example" class="carousel slide slide-bdr" data-ride="carousel" >
                    <div class="carousel-inner">
                        <!--<div class="item active">

                            <img src="<?=HOME_PUBLIC_URL?>/img/1.jpg" width="100%" alt="" />
                           
                        </div>
                        <div class="item">
                            <img src="<?=HOME_PUBLIC_URL?>/img/2.jpg" width="100%" alt="" />
                          
                        </div>
                        <div class="item">
                            <img src="<?=HOME_PUBLIC_URL?>/img/3.jpg" width="100%" alt="" />
                           
                        </div>-->
                       <?php foreach($lun_ad as $lk=>$lv){?>
                            <div class="item <?=($lk==0)?'active':''?>">
                                <a href="<?=HOME_URL_HTTP?>blog/<?=$lv['blog_id']?>/detail/<?=$lv['id']?>" target="__black">
                                    <img src="/<?=$lv['img']?>"  style="width:100%;height:300px;" alt="<?=$lv['title']?>" />
                                </a>
                            </div>
                        <?php }?>
                    </div>
                    <!--INDICATORS-->
                    <ol class="carousel-indicators">
                        <?php foreach($lun_ad as $lk=>$lv){?>
                            <li data-target="#carousel-example" data-slide-to="<?=$lk?>" class="<?=($lk==0)?'active':''?>"></li>
                        <?php }?>
                    </ol>
                <!--PREVIUS-NEXT BUTTONS-->
                 	<a class="left carousel-control" href="#carousel-example" data-slide="prev">
				    	<span class="glyphicon glyphicon-chevron-left"></span>
				  	</a>
				 	<a class="right carousel-control" href="#carousel-example" data-slide="next">
				    	<span class="glyphicon glyphicon-chevron-right"></span>
				  	</a>
            	</div>
         	</div>

            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="panel panel-primary ">
                    <div class="panel-heading">
                        标签
                    </div>
                    <div class="panel-body chat-widget-main">
                        <?php 
                            if(!empty($tags)){
                                foreach($tags as $tag){
                        ?>
                            <a href="<?=HOME_URL_HTTP?>blog/<?=$tag['blog_id']?>/art_list?tag=<?=$tag['id']?>," target="__black" class="btn btn-default btn-sm" style="margin: 2px 0px;"><?=$tag['name']?></a>
                        <?php 
                                }
                            }
                        ?>
                    </div>

                </div>
            </div>
       	</div>
		<hr>
		
        <div class="row">
           	<div class="col-md-9 col-sm-12 col-xs-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                     	文章
                    </div>
                    <div class="panel-body">
                        <ul class="media-list">
                        <?php 
                            if(!empty($works)){
                                foreach($works as $work){
                                $headimg = PUBLIC_URL.'headimg/timg.jpg';
                                if(!empty($work['user']['headimg'])){
                                    $headimg = '/'.$work['user']['headimg'];
                                }
                        ?>
                            <li class="media">
                                <a class="pull-left" href="<?=HOME_URL_HTTP?>blog/<?=$work['blog_id']?>/" target="__black">
                                 <img class="media-object img-circle img-comments" src="<?=$headimg?>" width="80" />
                               </a>
                                <div class="media-body">
                                    <h4 class="media-heading" style="margin-bottom:0px;">
                                        <a href="<?=HOME_URL_HTTP?>blog/<?=$work['blog_id']?>/detail/<?=$work['id']?>" target="__black" style="color:#F07818;">
                                            <?=$work['title']?>
                                        </a>
                                    </h4>
                                    <a href="<?=HOME_URL_HTTP?>blog/<?=$work['blog_id']?>/detail/<?=$work['id']?>" target="__black">
                                        <p>
                                            <?php 
                                                if(!empty($work['desc'])){
                                                    $desc = strip_tags($work['desc']);
                                                    echo mb_substr($desc,0,100,'utf-8');
                                                }
                                            ?>
                                        </p>
                                    </a>
                                </div>
                            </li>
                        <?php 
                                }
                            }
                        ?>
				    	</ul>
                	</div>
            	</div>
        	</div>

            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="panel panel-default ">
                    <div class="panel-heading">
                        我的游戏
                    </div>
                    <div class="panel-body chat-widget-main" style="margin: auto 0;">
                        <a href="<?=HOME_URL_HTTP?>game/hitbricks" target="__black" class="btn btn-default btn-sm" style="display: block;margin: 2px 0px;"> 打砖块 </a>
                        <a href="<?=HOME_URL_HTTP?>game/miner" target="__black" class="btn btn-default btn-sm" style="display: block;margin: 2px 0px;"> 黄金矿工 </a>
                        <hr>
                    </div>
                </div>
            </div>


		 <!--    <div class="col-md-4 col-sm-12 col-xs-12">
	            <div class="panel panel-info">
	                <div class="panel-heading">
	                    反馈
	                </div>
	                <div class="panel-body">
	                    <form role="form">
                            <div class="form-group">
                                <label>你的名称：</label>
                                <input class="form-control" type="text" />
                            </div>
                        	<div class="form-group">
                                <label>你的邮箱：</label>
                                <input class="form-control" type="text" />
                            </div>
                         	<div class="form-group">
                                <label>想和我们说的话：</label>
                                <input class="form-control" type="text" style="min-height:100px;" />
                            </div>
                                                           
                            <button type="submit" class="btn btn-success">发送</button>
                            <button type="reset" class="btn btn-primary">取消</button>

                        </form>
                    </div>
                </div>
            </div> -->


		</div>
		
    </div>
</div>

<?php
	$this->load->view('home/public/footer');
?>

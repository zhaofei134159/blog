<?php 
	$this->load->view('home/public/top');
?>
<!-- MENU SECTION END-->
<div class="content-wrapper" style="min-height:600px;">
    <div class="container">
       <!--  <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">最佳博客</h4>
             </div>
        </div>   --> 
        <hr>
        <div class="row">
            <?php 
              $this->load->view('home/public/info_nav');
            ?>
             <div class="col-md-9 col-sm-12 col-xs-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        文章列表 
                        <a href="<?=HOME_URL_HTTP?>user/blog_work_edit">
                            <span style="float:right;padding-bottom:3px;" class="btn btn-success btn-sm">添加</span>
                        </a>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <form action="<?=HOME_URL?>user/blog_work_info" method="POST" class="form-horizontal">
                                标题: <input type="text" name="title" value="<?=$post['title']?>">
                                分类：
                                <select name="cate" id="cate" style="height: 33px;min-width: 90px;">
                                    <option value="all">全部</option>
                                    <?php foreach($cates as $ck=>$cv){?>
                                    <option value="<?=$cv['id']?>" <?php if($post['cate']==$cv['id']){?> selected <?php }?> ><?=$cv['title']?></option>
                                    <?php }?>
                                </select>
                                <input type="submit" value="搜索">
                            </form>
                            <br>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade active in" id="profile">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>标题</th>
                                            <th>分类</th>
                                            <th>图</th>
                                            <th>创建时间</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($works as $work){?>
                                        <tr>
                                            <td><?=$work['title']?></td>
                                            <td><?=$work['cate']['title']?></td>
                                            <td>
                                                <?php if(!empty($work['img'])){?>
                                                    <img src="/<?=$work['img']?>" style="width:60px;" alt="<?=$work['title']?>"> 
                                                <?php }else{?>
                                                    无
                                                <?php }?>
                                            </td>
                                            <td><?=date('Y-m-d H:i',$work['ctime'])?></td>
                                            <td>
                                              <a href="<?=HOME_URL_HTTP?>user/blog_work_edit?id=<?=base64_encode($work['id'])?>"><button class="btn btn-primary btn-sm"><i class="fa fa-edit "></i>编辑</button></a>

                                              <a href="<?=HOME_URL_HTTP?>user/blog_work_del?id=<?=base64_encode($work['id'])?>"><button class="btn btn-danger btn-sm"><i class="fa fa-pencil"></i>删除</button></a>
                                            </td>
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                                <div>
                                    <?=$work_htm?>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
    </div>
</div>
<script type="text/javascript"> 
    var myModal = $('#alert');
    var myModalBody = $('#myModalBody');

</script>
<?php
	$this->load->view('home/public/footer');
?>
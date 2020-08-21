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
                        分类列表 
                        <a href="<?=HOME_URL_HTTP?>user/blog_info_edit">
                            <span style="float:right;padding-bottom:3px;" class="btn btn-success btn-sm">添加</span>
                        </a>
                    </div>
                    <div class="panel-body">
                        
                        <div class="tab-content">
                            <div class="tab-pane fade active in" id="home">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>标题</th>
                                            <th>描述</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($cates as $cate){?>
                                        <tr>
                                            <td><?=$cate['title']?></td>
                                            <td><?=mb_substr($cate['desc'],0,10,'utf-8')?></td>
                                            <td>
                                              <a href="<?=HOME_URL_HTTP?>user/blog_info_edit?id=<?=base64_encode($cate['id'])?>"><button class="btn btn-primary btn-sm"><i class="fa fa-edit "></i>编辑</button></a>

                                              <a href="<?=HOME_URL_HTTP?>user/blog_info_del?id=<?=base64_encode($cate['id'])?>"><button class="btn btn-danger btn-sm"><i class="fa fa-pencil"></i>删除</button></a>
                                            </td>
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                                <div>
                                    <?=$cate_htm?>
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
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
                        我的博客
                    </div>
                    <div class="panel-body">
                        <div class="tab-pane fade active in" id="home">
                            <h4 style="padding-top:5px;line-height:35px;">
                              分类<?=(empty($cate)?'添加':'修改')?>
                              <a href="<?=HOME_URL?>user/blog_info">
                                <span style="float:right;" class="btn btn-default btn-sm">返回</span>
                              </a>
                            </h4>
                            <form action="<?=HOME_URL?>user/blog_info_update"  method="post" onsubmit="return bloginfoForm()">
                                <div class="form-group  col-md-12">
                                    <label>分类名称：</label>
                                    <input type="text" class="form-control" name="title" value="<?=empty($cate['title'])?'':$cate['title']?>">
                                    <input type="hidden" class="form-control" name="uid" value="<?=empty($user['id'])?'':$user['id']?>">
                                    <input type="hidden" class="form-control" name="id" value="<?=empty($cate['id'])?'':$cate['id']?>">
                                    <input type="hidden" class="form-control" name="blog_id" value="<?=empty($blog['id'])?'':$blog['id']?>">
                                </div>
                                <div class="form-group  col-md-12">
                                    <label>分类描述：</label>
                                    <textarea class="form-control" name="desc" style="min-height:100px;resize: none;" /><?=empty($cate['desc'])?'':$cate['desc']?></textarea>
                                </div>
                                <div class="form-group  col-md-12">
                                    <label></label>
                                    <input type="submit" class="btn btn-success" value="提交">
                                    <button type="reset" class="btn btn-primary">取消</button>
                                </div>
                            </form>   
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

    function bloginfoForm(){
        var title = $('input[name="title"]').val();
        if(title==''){
            myModalBody.html('标题不能为空!');
            myModal.addClass('alert-danger');
            myModal.css('display','block');
            return false;
        }
    }

</script>
<?php
	$this->load->view('home/public/footer');
?>
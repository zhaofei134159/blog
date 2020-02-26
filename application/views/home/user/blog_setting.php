<?php 
	$this->load->view('home/public/top');
?>
<link rel="stylesheet" href="<?=PUBLIC_URL?>kindeditor/themes/default/default.css" />
<link rel="stylesheet" href="<?=PUBLIC_URL?>kindeditor/plugins/code/prettify.css" />
<script charset="utf-8" src="<?=PUBLIC_URL?>kindeditor/kindeditor.js"></script>
<script charset="utf-8" src="<?=PUBLIC_URL?>kindeditor/lang/zh_CN.js"></script>
<script charset="utf-8" src="<?=PUBLIC_URL?>kindeditor/plugins/code/prettify.js"></script>
<script>
    KindEditor.ready(function(K) {
        var editor1 = K.create('textarea[name="desc"]', {
            cssPath : '<?=PUBLIC_URL?>kindeditor/plugins/code/prettify.css',
            uploadJson : '<?=PUBLIC_URL?>kindeditor/php/upload_json.php',
            fileManagerJson : '<?=PUBLIC_URL?>kindeditor/php/file_manager_json.php',
            allowFileManager : true,
            afterCreate : function() {
                var self = this;
                K.ctrl(document, 13, function() {
                    self.sync();
                    K('form[name=example]')[0].submit();
                });
                K.ctrl(self.edit.doc, 13, function() {
                    self.sync();
                    K('form[name=example]')[0].submit();
                });
            }
        });
        prettyPrint();
    });
</script>
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
                <div class="panel panel-warning">
                  <div class="panel-heading">
                    博客配置
                  </div>
                  <div class="panel-body">
                      <?php if($user['is_activate']!=1){?>

                        尚未激活邮箱，需要激活邮箱，才可以建立属于自己的博客。
                        <hr>             
                        未收到激活邮件。&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-success btn-sm" onclick="resend(<?=$user['id']?>)">重新发送</button>
                        <hr>
                        <span style="color:#d9534f;">若点击重新发送后，提示无绑定邮箱，则需要您重新登陆一下，登陆时会让您绑定邮箱。</span>
                        <hr>
                        谢谢合作。                        
                      <?php }else{?>
                      <!-- onsubmit="return bloginfoForm()" -->
                        <form action="<?=HOME_URL?>user/blog_update_setting"  method="post" enctype="multipart/form-data">
                          <div class="form-group  col-md-12">
                              <label>博客名称：</label>
                              <input type="text" class="form-control" name="name" value="<?=empty($user_blog['name'])?'':$user_blog['name']?>">
                              <input type="hidden" class="form-control" name="uid" value="<?=empty($user['id'])?'':$user['id']?>">
                          </div>
                          <div class="form-group  col-md-12">
                              <label>博客开关：</label>
                              
                              <label>
                                  <input type="radio" name="blog_switch" id="optionsRadios1" value="1" <?=($user_blog['blog_switch']==1)?'checked':''?>>开
                              </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                              <label>
                                  <input type="radio" name="blog_switch" id="optionsRadios1" value="0" <?=($user_blog['blog_switch']==0)?'checked':''?>>关
                              </label>
                          </div>
                          <div class="form-group  col-md-12">
                              <label>博客描述：</label>
                              <textarea class="form-control col-md-12" name="desc" id="desc" style="min-height:100px;visibility:hidde" /><?=empty($user_blog['desc'])?'':$user_blog['desc']?></textarea>
                          </div>
                          <div class="form-group  col-md-12">
                            <label></label>
                              <input type="submit" class="btn btn-success" value="发送">
                              <button type="reset" class="btn btn-primary">取消</button>
                          </div>
                        </form>   
                      <?php }?>
                  </div>
                </div>
            </div>
        </div>
		
    </div>
</div>
<script type="text/javascript"> 
    var myModal = $('#alert');
    var myModalBody = $('#myModalBody');

    function resend(id){
      $.ajax({
          type: "POST",
          url: "<?=HOME_URL?>user/resend",
          async: false,
          data: {id:id},
          dataType: "json",
          success: function(res){
            var cl = 'alert-success';
            if(res.flog!=1){
                cl = 'alert-danger';
            }
              myModalBody.html(res.msg);
              myModal.addClass(cl);
              myModal.css('display','block');
          }
      });
    }
</script>
<?php
	$this->load->view('home/public/footer');
?>
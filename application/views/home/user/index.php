<?php 
	$this->load->view('home/public/top');
?>
<!-- MENU SECTION END-->
<div class="content-wrapper" style="min-height:500px;">
    <div class="container">
       <!--  <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">最佳博客</h4>
             </div>
        </div>   --> 
        <?php 
          $headimg = PUBLIC_URL.'headimg/timg.jpg';
          if(!empty($user['headimg'])){
            $headimg = '/'.$user['headimg'];
          }
        ?>
        <hr>
        <div class="row">
            <?php 
              $this->load->view('home/public/info_nav');
            ?>
            <div class="col-md-9 col-sm-12 col-xs-12">
                <div class="panel panel-warning">
                  <div class="panel-heading">
                    用户信息
                  </div>
                  <div class="panel-body">
                    <form action="<?=HOME_URL?>user/update_info" onsubmit="return infoForm()" method="post" enctype="multipart/form-data">
                      <div class="form-group col-md-4">
                        <div class="">
                          <label>头像：</label>
                          <img src="<?=$headimg?>" id="head" width="100" alt="">
                        </div>
                      </div>
                      <div class="form-group col-md-8">
                        上传的头像支持：png、jpg、jpeg 格式,但大小要小于4M。
                        <br>
                        <br>
                        <span style="display:none;"><input type="file" name="headimg"></span>
                        <button type="button" onclick="upload_file()" class="btn btn-success">上传头像</button>
                      </div>
                      <div class="form-group  col-md-12">
                          <label>名称：</label>
                          <input type="text" class="form-control" name="nikename" value="<?=empty($user['nikename'])?'':$user['nikename']?>">
                      </div>
                      <div class="form-group  col-md-12">
                          <label>邮箱：</label>
                          <input type="text" class="form-control" disabled name="email" value="<?=empty($user['email'])?'':$user['email']?>"> 
                      </div>
                      <div class="form-group  col-md-12">
                          <label>手机号：</label>
                          <input type="text" class="form-control" name="phone" value="<?=empty($user['phone'])?'':$user['phone']?>">
                      </div>
                      <div class="form-group  col-md-12">
                          <label>性别：</label>
                          
                          <label>
                              <input type="radio" name="sex" id="optionsRadios1" value="1" <?=(!empty($user['sex'])&&$user['sex']==1)?'checked':''?>>男
                          </label>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                          <label>
                              <input type="radio" name="sex" id="optionsRadios1" value="2" <?=(!empty($user['sex'])&&$user['sex']==2)?'checked':''?>>女
                          </label>
                      </div>
                      <div class="form-group  col-md-12">
                        <label></label>
                          <input type="submit" class="btn btn-success" value="发送">
                          <button type="reset" class="btn btn-primary">取消</button>
                      </div>
                    </form>                              
                  </div>
                </div>
            </div>
        </div>
		
    </div>
</div>
<script type="text/javascript"> 
    var myModal = $('#alert');
    var myModalBody = $('#myModalBody');

    function upload_file(){
        $('input[name="headimg"]').click();
    }

    $('input[name="headimg"]').change(function(){
        var arr = ['jpeg','.jpg','.png','.JPG','.PNG','JPEG'];
        var src = $(this).val();
        var zhui = src.slice(-4);
        switch(zhui){
            case 'jpeg':
            case '.jpg':
            case '.png':
                break;
            default :
                myModalBody.html('上传图片格式错误或者没上传任何图片!');
                myModal.addClass('alert-danger');
                myModal.css('display','block');
                return false;
                break;
        }
        var objUrl = getObjectURL(this.files[0]) ;
        if (objUrl) {
            $("#head").attr("src", objUrl) ;
        }
    });


    function getObjectURL(file) {
        var url = null ; 
        if (window.createObjectURL!=undefined) { // basic
            url = window.createObjectURL(file) ;
        } else if (window.URL!=undefined) { // mozilla(firefox)
            url = window.URL.createObjectURL(file) ;
        } else if (window.webkitURL!=undefined) { // webkit or chrome
            url = window.webkitURL.createObjectURL(file) ;
        }
        return url;
    }

    function infoForm(){
      var phone = $('input[name="phone"]').val();
      var phone_reg = /^1[74358]\d{9}$/;
      if (phone!=''&&!phone_reg.test(phone)) {
        myModalBody.html('手机号格式错误!');
        myModal.addClass('alert-danger');
        myModal.css('display','block');
        return false;
      }
      var flog = 0;
      $.ajax({
          type: "POST",
          url: "<?=HOME_URL?>user/check_phone",
          async: false,
          data: {phone:phone},
          dataType: "json",
          success: function(res){
              flog = res.flog;
          }
      });
      if(flog){
          myModalBody.html('手机号已存在!');
          myModal.addClass('alert-danger');
          myModal.css('display','block');
          return false;
      }

    }
</script>
<?php
	$this->load->view('home/public/footer');
?>
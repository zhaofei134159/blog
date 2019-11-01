<?php 
	$this->load->view('home/public/top');
?>
<!-- FONT AWESOME STYLE  -->
<link href="<?=HOME_PUBLIC_URL?>css/leave.css" rel="stylesheet" />
<!-- MENU SECTION END-->
<div class="content-wrapper" style="min-height:600px;">
    <div class="container">
        <div class="row pad-botm">
          <div class="col-md-12">
              <h3 class="header-line">留言板</h3><br>
          </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
 			          <div class="Main">

                    <?php if(empty($this->homeid)||empty($this->home['id'])){?>
                    <div class="Input_Box" style="border-color: rgb(204, 204, 204); box-shadow: none;text-align:center;line-height:204px;font-size:15px;">
                        请先 <a href="<?=HOME_URL_HTTP?>login">登录</a>, 在留言
                    </div>
                    <?php }else{?>
                    <div class="Input_Box" style="border-color: rgb(204, 204, 204); box-shadow: none;">
                        <textarea class="Input_text"></textarea>
                        <div class="faceDiv" style="margin-top: 0px;"> 
                        </div>
                        <div class="Input_Foot">
                            <a class="imgBtn" href="javascript:void(0);"></a>
                            <a class="postBtn">确定</a> 
                        </div>
                    </div>
                    <?php }?>

                    <div class="leaveAll">
                       去问问企鹅完全二我为
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<script src="<?=HOME_PUBLIC_URL?>js/leave.js"></script>
<script type="text/javascript">
    $('.postBtn').click(function(){
        // 判断是否登录

        var Input_text = $('.Input_text').val();
        console.log(Input_text);
    })
</script>

<?php
	$this->load->view('home/public/footer');
?>
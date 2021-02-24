<?php 
	$this->load->view('home/public/top');
?>

<style type="text/css">
    #chatroom{width:100%;height:450px;background:red;}
    #chatroominput{width:100%;height:200px;background:green;}
</style>
<!-- MENU SECTION END-->
<div class="content-wrapper" style="min-height:600px;">
    <div class="container">

        <div class="row">
            <div class="col-md-10 col-sm-10 col-xs-10">
 				<div class="panel panel-default">
                    <div class="panel-heading">
                       聊天室
                    </div>
                    <div class="panel-body text-center recent-users-sec" id="chatroom">
                        
                    </div>
                    <div class="panel-body text-center recent-users-sec" id="chatroominput">
                        <?php if(empty($this->homeid)||empty($this->home['id'])){?>
                        <div class="Input_Box" style="border-color: rgb(204, 204, 204); box-shadow: none;text-align:center;line-height:204px;font-size:15px;">
                            请先 <a href="<?=HOME_URL_HTTP?>login">登录</a> (建议使用github登录), 在留言
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
                    </div>
     			</div>
            </div>
        </div>   

    </div>
</div>

<?php
	$this->load->view('home/public/footer');
?>
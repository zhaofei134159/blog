<?php 
	$this->load->view('home/public/top');
?>
<!-- MENU SECTION END-->
<div class="content-wrapper" style="min-height:600px;">
    <div class="container">
        <!-- <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">最佳博客</h4>
             </div>
        </div>    -->          
        <hr>
        <div class="row pad-botm">
          	<div class="col-md-12 col-sm-12 col-xs-12">
                <h4 class="header-line" style="text-align:center;"><?=$type?>登录错误</h4><br>
         	</div>
       	</div>
        <div class="row">
            <div class="col-md-12">
                <h5  style="text-align:center;"><a href="<?=HOME_URL?>login/<?=$login?>_login"><button class="btn btn-success">重新登陆</button></a></h5>
            </div>
        </div>
		<hr>
		
    </div>
</div>

<?php
	$this->load->view('home/public/footer');
  die;
?>
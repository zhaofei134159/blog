<?php 
	$this->load->view('home/public/top');
?>

<style type="text/css">
    #chatroom{width:680px;height:550px;margin:100px auto;background:red;}
    #chatroominput{width:680px;height:150px;margin:100px auto;background:green;}
</style>
<!-- MENU SECTION END-->
<div class="content-wrapper" style="min-height:600px;">
    <div class="container">

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
 				<div class="panel panel-default">
                    <div class="panel-heading">
                       聊天室
                    </div>
                    <div class="panel-body text-center recent-users-sec" id="chatroom">
                        
                    </div>
                    <div class="panel-body text-center recent-users-sec" id="chatroominput">
                        
                    </div>
     			</div>
            </div>
        </div>   

    </div>
</div>

<?php
	$this->load->view('home/public/footer');
?>
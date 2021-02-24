<?php 
	$this->load->view('home/public/top');
?>

<style type="text/css">
    #chatroom{width:100%;height:550px;background:red;}
    #chatroominput{width:100%;height:150px;background:green;}
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
                        
                    </div>
     			</div>
            </div>
        </div>   

    </div>
</div>

<?php
	$this->load->view('home/public/footer');
?>
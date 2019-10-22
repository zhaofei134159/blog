<?php 
	$this->load->view('home/public/top');
?>

<style type="text/css">
#nav{width:980px;height:350px;margin:100px auto;}
#nav ul li{list-style:none;background:rgba(0,0,0,.5);height:105px;width:180px;float:left;margin:30px 5px;position:relative;}
#nav ul li:before{
	content:"";height:105px;width:180px;background:rgba(0,0,0,.5);position:absolute;top:0px;left:0px;
	transform:rotate(60deg);
	-webkit-transform:rotate(60deg);
	-moz-transform:rotate(60deg);
}
#nav ul li:after{
	content:"";height:105px;width:180px;background:rgba(0,0,0,.5);position:absolute;top:0px;left:0px;z-index:1;
	transform:rotate(-60deg);
	-webkit-transform:rotate(-60deg);
	-moz-transform:rotate(-60deg);
}
#nav ul li.mar{margin-left:100px;}
#nav ul li img{
	top:0px;left:0px;right:0px;bottom:0px;margin:auto;z-index:2;position:absolute;
	transition:1s;
	-webkit-transition:1s;
	-moz-transition:1s;
}
#nav ul li img:hover{
	-webkit-transform:rotate(360deg) scale(1.5); 
	-moz-transform:rotate(360deg) scale(1.5);
	-ms-transform:rotate(360deg) scale(1.5);
	-o-transform:rotate(360deg) scale(1.5);
}
</style>
<!-- MENU SECTION END-->
<div class="content-wrapper" style="min-height:600px;">
    <div class="container">

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
 				<div class="panel panel-default">
                    <div class="panel-heading">
                       友链
                    </div>
                    <div class="panel-body text-center recent-users-sec" id="nav">
                        <ul>

                        <?php 
                            if(!empty($friends)){
                                foreach($friends as $key=>$friend){
                                $headimg = PUBLIC_URL.'headimg/admin.jpg';
                                if(!empty($friend['img'])){
                                    $headimg = '/'.$friend['img'];
                                }
                                $mar = 0;
                                if(($key-5)/9==0){
                                	$mar = 1;
                                }
                        ?>
							<li <?php if($mar)?>class="mar"<?php }?>>
	                            <a href="<?=$friend['addr']?>" target="__black">
	                                <img class="img-thumbnail" src="<?=$headimg?>" width="70" title="<?=$friend['title']?>"/>
	                            </a>
							</li>
                        <?php 
                                }
                            }
                        ?>
                        </ul>
                    </div>
     			</div>
            </div>
        </div>   

    </div>
</div>

<?php
	$this->load->view('home/public/footer');
?>
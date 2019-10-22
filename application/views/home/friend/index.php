<?php 
	$this->load->view('home/public/top');
?>
<!-- MENU SECTION END-->
<div class="content-wrapper" style="min-height:600px;">
    <div class="container">

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
 				<div class="panel panel-default">
                    <div class="panel-heading">
                       友链
                    </div>
                    <div class="panel-body text-center recent-users-sec">
                        <?php 
                            if(!empty($friends)){
                                foreach($friends as $friend){
                                $headimg = PUBLIC_URL.'headimg/admin.jpg';
                                if(!empty($friend['img'])){
                                    $headimg = '/'.$friend['img'];
                                }
                        ?>
                            <a href="/<?=$friend['addr']?>" target="__black">
                                <img class="img-thumbnail" src="<?=$headimg?>" width="70" title="<?=$friend['title']?>"/>
                            </a>
                        <?php 
                                }
                            }
                        ?>
                    </div>
     			</div>
            </div>
        </div>   

    </div>
</div>

<?php
	$this->load->view('home/public/footer');
?>
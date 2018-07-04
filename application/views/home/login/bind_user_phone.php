<?php 
    $this->load->view('home/public/top');

    $class = array(
            'weibo'=>'微博',
            'weixin'=>'微信',
            'qq'=>'腾讯QQ',
        );
?>

<script src="<?=PUBLIC_URL?>js/jquery-1.9.1.min.js"></script>
<script src="<?=ADMIN_PUBLIC_URL?>js/jquery.min.js"></script>  
<script src="<?=HOME_PUBLIC_URL?>js/user.js"></script>


<div class="content-wrapper" style="min-height:500px;">
    <div class="container">
		<hr>
		<div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
	            <div class="panel panel-success">
	                <div class="panel-heading">
	                   	<?=$class[$type]?>登陆成功,绑定邮箱
	                </div>
	                <div class="panel-body">
                    	<div class="form-group">
                            <label>邮箱：</label>
                            <input class="form-control" name="bind_email" type="text" />
                        </div>
                        <div class="form-group">
                            <label>密码：</label>
                            <input class="form-control" type="password" name="bind_password" />
                            <input type="hidden" name="login_uid" value="<?=$user['id']?>">
                            <input type="hidden" name="type" value="<?=$type?>">
                        </div>
                                                       
                        <input type="submit" onclick="do_bind()" class="btn btn-success" value="绑定" />
                    </div>
                </div>
            </div>

		</div>

    </div>
</div>


<?php
	$this->load->view('home/public/footer');
?>
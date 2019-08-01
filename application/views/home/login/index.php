<?php 
	$this->load->view('home/public/top');
?>

<script src="<?=PUBLIC_URL?>js/jquery-1.9.1.min.js"></script>
<script src="<?=ADMIN_PUBLIC_URL?>js/jquery.min.js"></script>  
<script src="<?=HOME_PUBLIC_URL?>js/user.js"></script>


<div class="content-wrapper" style="min-height:550px;">
    <div class="container">
		<hr>
		<div class="row">

            <div class="col-md-6 col-sm-12 col-xs-12">
	            <div class="panel panel-info">
	                <div class="panel-heading">
	                   	注册
	                </div>
	                <div class="panel-body">
                        <div class="form-group">
                            <label>手机号：</label>
                            <input class="form-control" name="phone" type="text" />
                        </div>
                    	<div class="form-group">
                            <label>邮箱：</label>
                            <input class="form-control" name="email" type="text" />
                        </div>
                        <div class="form-group">
                            <label>密码：</label>
                            <input class="form-control" type="password" name="password" />
                        </div>
						<div class="form-group">
                            <label>确认密码：</label>
                            <input class="form-control" type="password" name="repassword" />
                        </div>
                                                       
                        <input type="submit" onclick="do_register()" class="btn btn-success" value="登录" />
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-12 col-xs-12">
	            <div class="panel panel-success">
	                <div class="panel-heading">
	                    登录
	                </div>
	                <div class="panel-body">
                        <div class="form-group">
                            <label>账号：</label>
                            <input class="form-control" type="text" name="login_account" placeholder="邮箱/手机号"/>
                        </div>
                    	<div class="form-group">
                            <label>密码：</label>
                            <input class="form-control" type="password" name="login_password"/>
                        </div>
                                                       
                        <input type="submit" onclick="do_login()" class="btn btn-success" value="登录" />

                    </div>
                    <div class="panel-body">
                   		<div class="form-group">
                            <label>第三方登录：</label>
                        </div>
                        <a href="<?=HOME_URL?>login/qq_login" target="__block"><img src="<?=PUBLIC_URL?>images/qq_login.png" width="32" alt="使用腾讯QQ账号登录" title="腾讯QQ账号登录"></a>
                        &nbsp;
                        <a href="<?=HOME_URL?>login/weibo_login" target="__block"><img src="<?=PUBLIC_URL?>images/weibo32.png" alt="使用微博账号登录" title="微博账号登录"></a>
                    </div>
                </div>
            </div>
		</div>

    </div>
</div>


<?php
	$this->load->view('home/public/footer');
?>

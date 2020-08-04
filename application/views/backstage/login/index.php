<!DOCTYPE html>
<html lang="en">
    
<head>
        <title>zf.ren 后台 Login</title><meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="<?=ADMIN_PUBLIC_URL?>css/bootstrap.min.css" />
		<link rel="stylesheet" href="<?=ADMIN_PUBLIC_URL?>css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="<?=ADMIN_PUBLIC_URL?>css/maruti-login.css" />
        <link rel="stylesheet" href="<?=PUBLIC_URL?>css/alertify.css" />

        <script src="<?=PUBLIC_URL?>js/jquery-1.9.1.min.js"></script>
        <script src="<?=ADMIN_PUBLIC_URL?>js/jquery.min.js"></script>  
        <script src="<?=ADMIN_PUBLIC_URL?>js/maruti.login.js"></script> 
        <script src="<?=PUBLIC_URL?>js/jquery.alertify.js"></script>
        <link rel="shortcut icon" href="<?=PUBLIC_URL?>images/favicon.ico" />

    </head>
    <body>
        <div id="logo">
            <img src="<?=ADMIN_PUBLIC_URL?>img/login-logo.png" alt="" />
        </div>
        <div id="loginbox">            
            <div id="loginform" class="form-vertical">
				 <div class="control-group normal_text"><h3>zf.ren 后台 Login</h3></div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on"><i class="icon-user"></i></span><input type="text" placeholder="Username" name="phone" />
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on"><i class="icon-lock"></i></span><input type="password" placeholder="Password" name="password" />
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <span class="pull-left"><a href="#" class="flip-link btn btn-warning" id="to-recover">忘记密码?</a></span>
                    <span class="pull-right"><input type="submit" onclick="do_login()" class="btn btn-success" value="登录" /></span>
                </div>
            </div>
            <form id="recoverform" action="#" class="form-vertical">
				<p class="normal_text">Enter your e-mail address below and we will send you instructions <br/><font color="#FF6633">how to recover a password.</font></p>
				
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on"><i class="icon-envelope"></i></span><input type="text" placeholder="E-mail address" />
                        </div>
                    </div>
               
                <div class="form-actions">
                    <span class="pull-left"><a href="#" class="flip-link btn btn-warning" id="to-login">&laquo; Back to login</a></span>
                    <span class="pull-right"><input type="submit" class="btn btn-info" value="Recover" /></span>
                </div>
            </form>
        </div>
        
    
    <script type="text/javascript">
        function do_login(){
            var phone = $('input[name="phone"]').val();
            var password = $('input[name="password"]').val();

            if(phone==''){
                alertify.alert("<p>账号不能为空</p><br/>");
                return false; 
            }

            var myreg = /^1[3|4|5|7|8]\d{9}$/g; 
            if(!myreg.test(phone)) 
            { 
                alertify.alert("<p>账号格式错误</p><br/>");
                return false; 
            }

            if(password==''){
                alertify.alert("<p>密码不能为空</p><br/>");
                return false; 
            }


            $.post('/backstage/login/do_login',{phone:phone,password:password},function(res){
                if(res.flog==1){
                    alertify.alert("<h3>登录成功</h3><p>"+res.msg+"</p><br/>");
                    setTimeout(function(){
                        window.location.href = '/backstage/index/index';
                    },2000)
                }else{
                    alertify.alert("<h3>登录失败</h3><p>"+res.msg+"</p><br/>");
                }
            },'json')
        }

    </script>

    </body>
</html>

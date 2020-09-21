var myModal = $('#alert');
var myModalBody = $('#myModalBody');

//登录
function do_login(){
	var login_account =$('input[name="login_account"]').val();
	var login_password =$('input[name="login_password"]').val();

	if(login_account==''){
		myModalBody.html('账号不能为空!');
		myModal.addClass('alert-danger');
		myModal.css('display','block');
        return false; 
	}

	if(login_password==''){
		myModalBody.html('密码不能为空!');
		myModal.addClass('alert-danger');
		myModal.css('display','block');
        return false; 
	}


	$.ajax({
         type: "POST",
         url: "/home/login/do_login",
         async: false,
         data: {account:login_account,password:login_password},
         dataType: "json",
         success: function(res){
         	if(res.flog!=1){
				myModalBody.html(res.msg);
				myModal.addClass('alert-danger');
				myModal.css('display','block');
	            return false;
		  	}else{
		  		myModalBody.html(res.msg);
				myModal.addClass('alert-success');
				myModal.css('display','block');
				
				var hrefUrl = '/home/index/index';
				if(res.data.httpReferer!=undefined){
					hrefUrl = res.data.httpReferer;
				}
				console.log(hrefUrl)
				return false;
	           	setTimeout(function(){
                    window.location.href = hrefUrl;
                },2000)
		  	}
         }
    });
}


//注册
function do_register(){
	var phone = $('input[name="phone"]').val();
	var email = $('input[name="email"]').val();
	var password = $('input[name="password"]').val();
	var repassword = $('input[name="repassword"]').val();
	

	if(phone==''){
		myModalBody.html('手机号不能为空!');
		myModal.addClass('alert-danger');
		myModal.css('display','block');
        return false; 
	}

	if(email==''){
		myModalBody.html('邮箱不能为空!');
		myModal.addClass('alert-danger');
		myModal.css('display','block');
        return false; 
	}

	if(password==''){
		myModalBody.html('密码不能为空!');
		myModal.addClass('alert-danger');
		myModal.css('display','block');
        return false; 
	}

	if(repassword==''){
		myModalBody.html('确认密码不能为空!');
		myModal.addClass('alert-danger');
		myModal.css('display','block');
        return false; 
	}

    var myreg = /^1[3|4|5|7|8]\d{9}$/g; 
    if(!myreg.test(phone)) 
    { 
		myModalBody.html('手机号格式错误!');
		myModal.addClass('alert-danger');
		myModal.css('display','block');
        return false; 
    }

    var myema =  /^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.){1,4}[a-z]{2,3}$/i;
	if(!myema.test(email)) 
    { 
		myModalBody.html('邮箱格式错误!');
		myModal.addClass('alert-danger');
		myModal.css('display','block');
        return false; 
    }

    if(repassword!=password){
		myModalBody.html('两次输入的密码不相同!');
		myModal.addClass('alert-danger');
		myModal.css('display','block');
        return false;
    }

	$.ajax({
         type: "POST",
         url: "/home/login/do_register",
         async: false,
         data: {phone:phone,email:email,password:password,repassword:repassword},
         dataType: "json",
         success: function(res){
         	if(res.flog!=1){
				myModalBody.html(res.msg);
				myModal.addClass('alert-danger');
				myModal.css('display','block');
	            return false;
		  	}else{
		  		myModalBody.html(res.msg);
				myModal.addClass('alert-success');
				myModal.css('display','block');
	           	
	           	var hrefUrl = '/home/index/index';
				if(res.data.httpReferer!=undefined){
					hrefUrl = res.data.httpReferer;
				}
	           	setTimeout(function(){
                    window.location.href = hrefUrl;
                },2000)
		  	}
         }
    });

}

//绑定
function do_bind(){
	var email = $('input[name="bind_email"]').val();
	var password = $('input[name="bind_password"]').val();
	var login_uid = $('input[name="login_uid"]').val();
	var type = $('input[name="type"]').val();
	


	if(email==''){
		myModalBody.html('邮箱不能为空!');
		myModal.addClass('alert-danger');
		myModal.css('display','block');
        return false; 
	}

	if(password==''){
		myModalBody.html('密码不能为空!');
		myModal.addClass('alert-danger');
		myModal.css('display','block');
        return false; 
	}


    var myema =  /^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.){1,4}[a-z]{2,3}$/i;
	if(!myema.test(email)) 
    { 
		myModalBody.html('邮箱格式错误!');
		myModal.addClass('alert-danger');
		myModal.css('display','block');
        return false; 
    }


	$.ajax({
         type: "POST",
         url: "/home/login/do_bind",
         async: false,
         data: {email:email,password:password,login_uid:login_uid,type:type},
         dataType: "json",
         success: function(res){
         	if(res.flog!=1){
				myModalBody.html(res.msg);
				myModal.addClass('alert-danger');
				myModal.css('display','block');
	            return false;
		  	}else{
		  		myModalBody.html(res.msg);
				myModal.addClass('alert-success');
				myModal.css('display','block');
	           	setTimeout(function(){
                    window.location.href = '/home/index/index';
                },2000)
		  	}
         }
    });

}
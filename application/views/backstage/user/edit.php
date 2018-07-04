<?php 
  $this->load->view('backstage/public/top');
?>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb">
			<a href="<?=ADMIN_URL?>index" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>首页</a>
			<a href="<?=ADMIN_URL?>user/index">用户管理</a>
			<a href="#" class="current"><?=(empty($admin)?'添加':'修改')?>信息</a>
		</div>
	</div>
	
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span12">
				<div class="widget-box">
					<div class="widget-title">
						<span class="icon">
							<i class="icon-info-sign"></i>									
						</span>
						<h5> <?=(empty($admin)?'添加':'修改')?>  信息 </h5>
					</div>
					<div class="widget-content nopadding">
						<form class="form-horizontal" method="post" action="<?=ADMIN_URL?>user/update" onsubmit="return validateForm()" name="form" id="form" novalidate="novalidate">
							<?php if(!empty($admin)){?>
								<div class="control-group">
									<label class="control-label">UID</label>
									<div class="controls">
										<?=$admin['id']?>
										<input type="hidden" name="id" value="<?=$admin['id']?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">手机号</label>
									<div class="controls">
										<?=$admin['phone']?>
										<input type="hidden" name="phone" id="phone" value="<?=$admin['phone']?>">
									</div>
							</div>
							<?php }else{?>
								<div class="control-group">
									<label class="control-label">手机号</label>
									<div class="controls">
										<input type="text" name="phone" id="phone" value="" />
										<input type="hidden" name="id" value="" />
									</div>
								</div>
							<?php }?>

							<div class="control-group">
								<label class="control-label">姓名</label>
								<div class="controls">
									<input type="text" name="name" value="<?=(empty($admin['name'])?'':$admin['name'])?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">昵称</label>
								<div class="controls">
									<input type="text" name="nikename" value="<?=(empty($admin['nikename'])?'':$admin['nikename'])?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">邮箱</label>
								<div class="controls">
									<input type="text" name="email" value="<?=(empty($admin['email'])?'':$admin['email'])?>" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label">密码</label>
								<div class="controls">
									<input type="text" name="password" id="password" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">用户类型</label>
								<div class="controls ">
									<select name="user_type" id="user_type">
										<option value="0">请选择。。</option>
										<?php foreach($user_type as $uk=>$ut){?>
										<option value="<?=$uk?>" <?=(!empty($admin)&&$admin['user_type']==$uk)?'selected':''?>><?=$ut?></option>
										<?php }?>
									</select>
								</div>
							</div>

							<div class="form-actions">
								<label class="control-label"></label>
								<div class="controls ">
									<input type="submit" value="提交" class="btn btn-success">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>			
		</div>
	</div>
</div>

<script>
	function validateForm(){
		var user_type = $('#user_type').val();
		var phone = $('#phone').val();
		var id = $('input[name="id"]').val();
		var email = $('input[name="email"]').val();
	  	var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
     	isok= reg.test(email);
     	if(!isok){
 		 	alertify.alert("<p>邮箱不正确，请重新填写</p><br/>");
     		return false;
     	}

     	if(user_type=='0'){
			alertify.alert("<p>请选择用户类型</p><br/>");
     		return false;
     	}
		var phone_reg = /^1[74358]\d{9}$/;
		if (!phone_reg.test(phone)) {
			alertify.alert("<p>手机号格式错误，请重新填写</p><br/>");
			return false;
		}

		if(id==''){
			var flog = 0;
			$.ajax({
	             type: "POST",
	             url: "<?=ADMIN_URL?>user/check_phone",
	             async: false,
	             data: {phone:phone},
	             dataType: "json",
	             success: function(res){
	             	flog = res.flog;
	             }
	        });

	       	if(flog){
				alertify.alert("<p>手机号已存在</p><br/>");
	        	return false;
	       	}
		}
	}
</script>

<?php 
  $this->load->view('backstage/public/footer');
?>
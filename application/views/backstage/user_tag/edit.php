<?php 
  $this->load->view('backstage/public/top');
?>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb">
			<a href="<?=ADMIN_URL?>index" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>首页</a>
			<a href="<?=ADMIN_URL?>user_tag/index">用户标签管理</a>
			<a href="#" class="current"><?=(empty($tag)?'添加':'修改')?>信息</a>
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
						<h5> <?=(empty($tag)?'添加':'修改')?>  信息 </h5>
					</div>
					<div class="widget-content nopadding">
						<form class="form-horizontal" method="post" action="<?=ADMIN_URL?>user_tag/update" onsubmit="return validateForm()" name="form" id="form" novalidate="novalidate">
							<?php if(!empty($tag)){?>
								<div class="control-group">
									<label class="control-label">ID</label>
									<div class="controls">
										<?=$tag['id']?>
										<input type="hidden" name="id" value="<?=$tag['id']?>">
									</div>
								</div>
							<?php }?>

							<div class="control-group">
								<label class="control-label">用户ID</label>
								<div class="controls">
									<input type="text" name="uid" value="<?=(empty($tag['uid'])?'':$tag['uid'])?>" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label">名称</label>
								<div class="controls">
									<input type="text" name="name" value="<?=(empty($tag['name'])?'':$tag['name'])?>" />
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

<?php 
  $this->load->view('backstage/public/footer');
?>
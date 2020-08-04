<?php 
  $this->load->view('backstage/public/top');
?>
<link rel="stylesheet" href="<?=PUBLIC_URL?>kindeditor/themes/default/default.css" />
<link rel="stylesheet" href="<?=PUBLIC_URL?>kindeditor/plugins/code/prettify.css" />
<script charset="utf-8" src="<?=PUBLIC_URL?>kindeditor/kindeditor.js"></script>
<script charset="utf-8" src="<?=PUBLIC_URL?>kindeditor/lang/zh_CN.js"></script>
<script charset="utf-8" src="<?=PUBLIC_URL?>kindeditor/plugins/code/prettify.js"></script>
<script>
	KindEditor.ready(function(K) {
		var editor1 = K.create('textarea[name="desc"]', {
			cssPath : '<?=PUBLIC_URL?>kindeditor/plugins/code/prettify.css',
			uploadJson : '<?=PUBLIC_URL?>kindeditor/php/upload_json.php',
			fileManagerJson : '<?=PUBLIC_URL?>kindeditor/php/file_manager_json.php',
			allowFileManager : true,
			afterCreate : function() {
				var self = this;
				K.ctrl(document, 13, function() {
					self.sync();
					K('form[name=example]')[0].submit();
				});
				K.ctrl(self.edit.doc, 13, function() {
					self.sync();
					K('form[name=example]')[0].submit();
				});
			}
		});
		prettyPrint();
	});
</script>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb">
			<a href="<?=ADMIN_URL?>index" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>首页</a>
			<a href="<?=ADMIN_URL?>user_cate/index">用户分类管理</a>
			<a href="#" class="current"><?=(empty($cate)?'添加':'修改')?>信息</a>
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
						<h5> <?=(empty($cate)?'添加':'修改')?>  信息 </h5>
					</div>
					<div class="widget-content nopadding">
						<form class="form-horizontal" method="post" action="<?=ADMIN_URL?>user_cate/update" onsubmit="return validateForm()" name="form" id="form" novalidate="novalidate">
							<?php if(!empty($cate)){?>
								<div class="control-group">
									<label class="control-label">ID</label>
									<div class="controls">
										<?=$cate['id']?>
										<input type="hidden" name="id" value="<?=$cate['id']?>">
									</div>
								</div>
							<?php }?>

							<div class="control-group">
								<label class="control-label">用户ID</label>
								<div class="controls">
									<input type="text" name="uid" value="<?=(empty($cate['uid'])?'':$cate['uid'])?>" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label">标题</label>
								<div class="controls">
									<input type="text" name="title" value="<?=(empty($cate['title'])?'':$cate['title'])?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">描述</label>
								<div class="controls">
									<textarea name="desc" style="width:700px;height:200px;visibility:hidden;"><?=(empty($cate['desc'])?'':$cate['desc'])?></textarea>
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
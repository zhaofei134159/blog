<?php 
  $this->load->view('backstage/public/top');
?>

<div id="content">
	<div id="content-header">
		<div id="breadcrumb">
			<a href="<?=ADMIN_URL?>index" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>首页</a>
			<a href="<?=ADMIN_URL?>friend/index">网站广告管理</a>
			<a href="#" class="current"><?=(empty($friend)?'添加':'修改')?>信息</a>
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
						<h5> <?=(empty($friend)?'添加':'修改')?>  信息 </h5>
					</div>
					<div class="widget-content nopadding">
						<form class="form-horizontal" method="post" action="/index.php<?=ADMIN_URL?>friend/update" onsubmit="return validateForm()" name="form" id="form" novalidate="novalidate">
							<?php if(!empty($friend)){?>
								<div class="control-group">
									<label class="control-label">ID</label>
									<div class="controls">
										<?=$friend['id']?>
										<input type="hidden" name="id" value="<?=$friend['id']?>">
									</div>
								</div>
							<?php }?>

							<div class="control-group">
								<label class="control-label">名称</label>
								<div class="controls">
									<input type="text" name="title" value="<?=(empty($friend['title'])?'':$friend['title'])?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">描述:</label>
								<div class="controls">
									<input type="text" name="cont" value="<?=(empty($friend['cont'])?'':$friend['cont'])?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">url:</label>
								<div class="controls">
									<input type="text" name="addr" value="<?=(empty($friend['addr'])?'':$friend['addr'])?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">图片:</label>
								<div class="controls">
                  					<img src="" id="img" width="100" alt="">
									<input type="file" name="img[]" class="img">
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
<script type="text/javascript">
	
    $('.img').change(function(){
        var arr = ['jpeg','.jpg','.png','.JPG','.PNG','JPEG'];
        var src = $(this).val();
        var zhui = src.slice(-4);
        switch(zhui){
            case 'jpeg':
            case '.jpg':
            case '.png':
                break;
            default :
                myModalBody.html('上传图片格式错误或者没上传任何图片!');
                myModal.addClass('alert-danger');
                myModal.css('display','block');
                return false;
                break;
        }
        var objUrl = getObjectURL(this.files[0]) ;
        if (objUrl) {
            $("#img").attr("src", objUrl) ;
        }
    });


    function getObjectURL(file) {
        var url = null ; 
        if (window.createObjectURL!=undefined) { // basic
            url = window.createObjectURL(file) ;
        } else if (window.URL!=undefined) { // mozilla(firefox)
            url = window.URL.createObjectURL(file) ;
        } else if (window.webkitURL!=undefined) { // webkit or chrome
            url = window.webkitURL.createObjectURL(file) ;
        }
        return url;
    }
</script>

<?php 
  $this->load->view('backstage/public/footer');
?>
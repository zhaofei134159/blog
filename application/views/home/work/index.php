<?php 
    $this->load->view('home/public/top');
?>
<!-- MENU SECTION END-->
<div class="content-wrapper" style="min-height:600px;">
    <div class="container">
        <hr>
        <div class="row">
            <div class="col-md-1 col-sm-12 col-xs-12">
                <div class="panel panel-success">
                  <div class="panel-body" style="width:100%;text-align:center;">
                      <a href="<?=HOME_URL_HTTP?>user">
                        <h4><i class="fa fa-user"></i></h4>
                      </a>
                  </div>
                </div>
            </div>
            <div class="col-md-10 col-sm-12 col-xs-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        分类列表 
                        <a href="<?=HOME_URL_HTTP?>user/blog_info_edit">
                            <span style="float:right;padding-bottom:3px;" class="btn btn-success btn-sm">添加</span>
                        </a>
                    </div>
                    <div class="panel-body">
                    </div>
                </div>
            </div>
            <div class="col-md-1 col-sm-12 col-xs-12">
                <div class="panel panel-success">
                  <div class="panel-body" style="width:100%;text-align:center;">
                      <a href="<?=HOME_URL_HTTP?>user">
                        <h4><i class="fa fa-user"></i></h4>
                      </a>
                  </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
<script type="text/javascript"> 
    var myModal = $('#alert');
    var myModalBody = $('#myModalBody');

</script>
<?php
    $this->load->view('home/public/footer');
?>
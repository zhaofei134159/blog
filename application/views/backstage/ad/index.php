<?php 
  $this->load->view('backstage/public/top');
?>

<div id="content">
    <div id="content-header">
      <div id="breadcrumb">
        <a href="<?=ADMIN_URL?>index" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="<?=ADMIN_URL?>user_work/index" class="current">网站广告管理</a>
      </div>
    </div>

    <div class="container-fluid">
      <form action="#" method="post" class="form-horizontal">
          <div class="control-group">
          <label class="control-label">搜索：</label>
          <div class="controls ">
            <input type="text" name="search" class="span2" value="<?=(empty($post['search'])?'':$post['search'])?>" placeholder="名称/ID" />
            <select name="is_del" class="span2">
              <option value="-1">请选择。。</option>
              <option value="1" <?=(isset($post['is_del'])&&$post['is_del']==1)?'selected':''?>>已删除</option>
              <option value="0" <?=(isset($post['is_del'])&&$post['is_del']==0)?'selected':''?>>未删除</option>
            </select>
            <input type="submit" class="btn" value="搜索">
          </div>
        </div>
      </form>
      <div class="row-fluid">
        
          <div class="span12">
            <div class="widget-box">
              <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
                <h5>广告列表</h5>
                <span style="line-height:35px;float:right;padding-right:20px;">
                  <a href="<?=ADMIN_URL?>ad/edit">
                     <i class="icon-plus"></i>添加
                  </a>
                </span>
              </div>
              <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>名称</th>
                      <th>是否删除？</th>
                      <th>创建时间</th>
                      <th>修改时间</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($ads as $key=>$ad){?>
                     <tr>
                        <td><?=$ad['id']?></td>
                        <td><?=$ad['name']?></td>
                        <td>
                            <button onclick="check_del(<?=$ad['id']?>)" class="btn btn-mini <?=($ad['is_del']==0)?'btn-success':'btn-warning'?>">
                              <?=($ad['is_del']==0)?'否':'是'?>
                            </button>
                        </td>
                        <td><?=date('Y-m-d H:i',$ad['ctime'])?></td>
                        <td><?=date('Y-m-d H:i',$ad['utime'])?></td>
                        <td style="text-align:center;"> <a href="<?=ADMIN_URL?>ad/edit?id=<?=$ad['id']?>">修改信息</a></td>
                      </tr>
                    <?php }?>
                  </tbody>
                </table>
              </div>
            </div>

            <div>
              <?=$page_htm?>
              </div>
        </div>
    </div>
    </div>
</div>
<script>
  function check_del(id){
    var message = "<p>确认?</p><br/>";

      alertify.confirm(message, function (e) {
          if(!e) {
             return false;
          } 
      $.ajax({
               type: "POST",
               url: "<?=ADMIN_URL?>user_blog/state",
               async: false,
               data: {id:id},
               dataType: "json",
               success: function(res){
                  alertify.alert("<p>成功</p><br/>");
                  window.location.reload();
               }
          });
      });
  }
</script>

<?php 
  $this->load->view('backstage/public/footer');
?>
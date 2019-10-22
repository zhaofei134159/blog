<?php 
  $this->load->view('backstage/public/top');
?>

<div id="content">
    <div id="content-header">
      <div id="breadcrumb">
        <a href="<?=ADMIN_URL?>index" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="<?=ADMIN_URL?>friend/index" class="current">网站友链管理</a>
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
                <h5>友链列表</h5>
                <span style="line-height:35px;float:right;padding-right:20px;">
                  <a href="<?=ADMIN_URL?>friend/edit">
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
                      <th>描述</th>
                      <th>图</th>
                      <th>地址</th>
                      <th>是否删除？</th>
                      <th>创建时间</th>
                      <th>修改时间</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($friends as $key=>$friend){?>
                     <tr>
                        <td><?=$friend['id']?></td>
                        <td><?=$friend['title']?></td>
                        <td><?=$friend['cont']?></td>
                        <td><img src="/<?=$friend['img']?>" width="60px" height="60px"></td>
                        <td><a href="<?=$friend['addr']?>" target="__black"><?=$friend['addr']?></a></td>
                        <td>
                            <button onclick="check_del(<?=$friend['id']?>)" class="btn btn-mini <?=($friend['is_del']==0)?'btn-success':'btn-warning'?>">
                              <?=($friend['is_del']==0)?'否':'是'?>
                            </button>
                        </td>
                        <td><?=$friend['ctime']?></td>
                        <td><?=$friend['utime']?></td>
                        <td style="text-align:center;"> <a href="<?=ADMIN_URL?>friend/edit?id=<?=$friend['id']?>">修改信息</a></td>
                      </tr>
                    <?php }?>
                  </tbody>
                </table>
              </div>
            </div>

            <div>
              <?=$friend_htm?>
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
               url: "<?=ADMIN_URL?>friend/state",
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
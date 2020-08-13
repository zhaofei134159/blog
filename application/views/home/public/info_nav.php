<div class="col-md-3 col-sm-12 col-xs-12">
    <div class="panel panel-success">
      <div class="panel-body" style="width:100%;text-align:center;">
          <a href="<?=HOME_URL_HTTP?>user">
            <h4><i class="fa fa-user"></i> 用户信息</h4>
          </a>
          <a href="<?=HOME_URL_HTTP?>user/blog_setting">
            <h4><i class="fa fa-cog"></i> 博客配置</h4>
          </a>
          <?php if($user['is_activate']==1){?>
            <?php if(!empty($blog)){?>
            <a href="<?=HOME_URL_HTTP?>blog/<?=empty($blog['id'])?'':$blog['id']?>" target="__block">
              <h4><i class="fa fa-recycle"></i> 我的博客</h4>
            </a>
            <a href="<?=HOME_URL_HTTP?>user/blog_info">
              <h4><i class="fa fa-flag"></i> 我的分类</h4>
            </a>
            <a href="<?=HOME_URL_HTTP?>user/blog_work_info">
              <h4><i class="fa fa-briefcase"></i> 我的文章</h4>
            </a>
            <a href="" target="__block">
              <h4><i class="fa Statistics"></i> </h4>
            </a>
            <?php }?>
          <?php }?>
      </div>
    </div>
</div>
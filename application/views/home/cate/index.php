<?php 
  $this->load->view('backstage/public/top');
?>
<!-- <link rel="stylesheet" type="text/css" href="<?=PUBLIC_URL?>css/bootstrap.min.css"> -->
<link rel="stylesheet" type="text/css" href="<?=PUBLIC_URL?>css/tree_style.css">
<script type="text/javascript" src="<?=PUBLIC_URL?>js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
$(function(){
    $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
    $('.tree li.parent_li > span').on('click', function (e) {
        var children = $(this).parent('li.parent_li').find(' > ul > li');
        if (children.is(":visible")) {
            children.hide('fast');
            $(this).attr('title', 'Expand this branch').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
        } else {
            children.show('fast');
            $(this).attr('title', 'Collapse this branch').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
        }
        e.stopPropagation();
    });
});
</script>

<div id="content">
    <div id="content-header">
    <div id="breadcrumb">
      <a href="<?=ADMIN_URL?>index" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>首页</a>
      <a href="<?=ADMIN_URL?>user_cate_work/index" class="current">分类文章管理</a>
    </div>
  </div>
  <div class="row-fluid">
    <div class="span12">
      <div class="tree well">
        <ul>
          <li class="parent_li">
            <span title="Collapse this branch"><i class="icon-folder-open"></i> Parent</span> <a href="http://www.17sucai.com/preview/119666/2014-05-07/20140507_090158051/index.html">Goes somewhere</a>
            <ul>
              <li class="parent_li">
                <span title="Collapse this branch"><i class="icon-minus-sign"></i> Child</span> <a href="http://www.17sucai.com/preview/119666/2014-05-07/20140507_090158051/index.html">Goes somewhere</a>
                <ul>
                  <li>
                    <span><i class="icon-leaf"></i> Grand Child</span> <a href="http://www.17sucai.com/preview/119666/2014-05-07/20140507_090158051/index.html">Goes somewhere</a>
                  </li>
                </ul>
              </li>
              <li class="parent_li">
                <span title="Collapse this branch"><i class="icon-minus-sign"></i> Child</span> <a href="http://www.17sucai.com/preview/119666/2014-05-07/20140507_090158051/index.html">Goes somewhere</a>
                <ul>
                  <li>
                    <span><i class="icon-leaf"></i> Grand Child</span> <a href="http://www.17sucai.com/preview/119666/2014-05-07/20140507_090158051/index.html">Goes somewhere</a>
                  </li>
                  <li class="parent_li">
                    <span title="Collapse this branch"><i class="icon-minus-sign"></i> Grand Child</span> <a href="http://www.17sucai.com/preview/119666/2014-05-07/20140507_090158051/index.html">Goes somewhere</a>
                    <ul>
                      <li class="parent_li">
                        <span title="Collapse this branch"><i class="icon-minus-sign"></i> Great Grand Child</span> <a href="http://www.17sucai.com/preview/119666/2014-05-07/20140507_090158051/index.html">Goes somewhere</a>
                        <ul>
                          <li>
                            <span><i class="icon-leaf"></i> Great great Grand Child</span> <a href="http://www.17sucai.com/preview/119666/2014-05-07/20140507_090158051/index.html">Goes somewhere</a>
                          </li>
                          <li>
                            <span><i class="icon-leaf"></i> Great great Grand Child</span> <a href="http://www.17sucai.com/preview/119666/2014-05-07/20140507_090158051/index.html">Goes somewhere</a>
                          </li>
                         </ul>
                      </li>
                      <li>
                        <span><i class="icon-leaf"></i> Great Grand Child</span> <a href="http://www.17sucai.com/preview/119666/2014-05-07/20140507_090158051/index.html">Goes somewhere</a>
                      </li>
                      <li>
                        <span><i class="icon-leaf"></i> Great Grand Child</span> <a href="http://www.17sucai.com/preview/119666/2014-05-07/20140507_090158051/index.html">Goes somewhere</a>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <span><i class="icon-leaf"></i> Grand Child</span> <a href="http://www.17sucai.com/preview/119666/2014-05-07/20140507_090158051/index.html">Goes somewhere</a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<?php 
  $this->load->view('backstage/public/footer');
?>
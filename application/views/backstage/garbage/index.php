<?php 
  $this->load->view('backstage/public/top');
?>

<link rel="stylesheet" type="text/css" href="<?=PUBLIC_URL?>css/public.css">
<div id="content">
    <div id="content-header">
      <div id="breadcrumb">
        <a href="<?=ADMIN_URL?>index" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="<?=ADMIN_URL?>user_work/index" class="current">垃圾分类</a>
      </div>
    </div>

    <div class="container-fluid">
      <div class="layout-container">
        <div class="layout-cont">
          <div class="layout-cont-main">
            <div class="add-newclass">
              <div class="category-cont">
                <!--大分类-->
                <div class="big-box category-box">
                  <div class="big-category category">
                    <input type="text" readonly="readonly" value="电器">
                    <div class="btns">
                      <a><img src="./icon_add.png"></a>
                      <a><img src="./icon_edit.png"></a>
                      <a><img src="./icon_close.png"></a>
                      <a class="up-btn"><img src="./icon_bottom.png"></a>
                    </div>
                  </div>
                  <!--子分类-->
                  <div class="in-box category-box">
                    <div class="in-category category">
                      <input type="text" readonly="readonly" value="空调">
                      <div class="btns">
                        <a><img src="./icon_add.png"></a>
                        <a><img src="./icon_edit.png"></a>
                        <a><img src="./icon_close.png"></a>
                        <a class="up-btn"><img src="./icon_bottom.png"></a>
                      </div>
                    </div>
                    <!--小分类-->
                    <div class="small-box category-box">
                      <div class="small-category category">
                        <input type="text" readonly="readonly" value="A163">
                        <div class="btns">
                          <a><img src="./icon_edit.png"></a>
                          <a><img src="./icon_close.png"></a>
                        </div>
                      </div>
                      <div class="small-category category">
                        <input type="text" readonly="readonly" value="A201">
                        <div class="btns">
                          <a><img src="./icon_edit.png"></a>
                          <a><img src="./icon_close.png"></a>
                        </div>
                      </div>
                      <div class="small-category category">
                        <input type="text" readonly="readonly" value="B85">
                        <div class="btns">
                          <a><img src="./icon_edit.png"></a>
                          <a><img src="./icon_close.png"></a>
                        </div>
                      </div>
                      <div class="small-category category">
                        <input type="text" readonly="readonly" value="B127">
                        <div class="btns">
                          <a><img src="./icon_edit.png"></a>
                          <a><img src="./icon_close.png"></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="in-box category-box">
                    <div class="in-category category">
                      <input type="text" readonly="readonly" value="洗衣机">
                      <div class="btns">
                        <a><img src="./icon_add.png"></a>
                        <a><img src="./icon_edit.png"></a>
                        <a><img src="./icon_close.png"></a>
                      </div>
                    </div>
                  </div>
                  <div class="in-box category-box">
                    <div class="in-category category">
                      <input type="text" readonly="readonly" value="冰箱">
                      <div class="btns">
                        <a><img src="./icon_add.png"></a>
                        <a><img src="./icon_edit.png"></a>
                        <a><img src="./icon_close.png"></a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="big-box category-box">
                  <div class="big-category category">
                    <input type="text" readonly="readonly" value="厨具">
                    <div class="btns">
                      <a><img src="./icon_add.png"></a>
                      <a><img src="./icon_edit.png"></a>
                      <a><img src="./icon_close.png"></a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="operation-btns">
                <a href="https://www.17sucai.com/preview/731884/2018-08-08/%E4%B8%8A%E4%B8%8B%E6%8B%89%E7%B4%A2/addCategory.html" class="layout-btn">+ 添加新分类</a>
                <input type="submit" class="layout-btn subbtn" value="保存">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<script> 
  $('.category-cont').on('click', '.up-btn', function() {
      var _this = $(this);
      _this.toggleClass('up-btn-on');
      var category = _this.parents('.category');
      category.parent().height('auto');
      category.siblings('.category-box').stop().slideToggle(300);
    })
</script>

<?php 
  $this->load->view('backstage/public/footer');
?>
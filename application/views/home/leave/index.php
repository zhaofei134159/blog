<?php 
	$this->load->view('home/public/top');
?>
<!-- FONT AWESOME STYLE  -->
<link href="<?=HOME_PUBLIC_URL?>css/leave.css" rel="stylesheet" />
<!-- MENU SECTION END-->
<div class="content-wrapper" style="min-height:600px;">
    <div class="container">
        <div class="row pad-botm">
          <div class="col-md-12">
              <h3 class="header-line">留言板</h3><br>
          </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="panel-body">
                    <ul class="media-list">
                    <?php 
                            $headimg = PUBLIC_URL.'headimg/timg.jpg';
                    ?>
                        <li class="media">
                            <a class="pull-left" href="<?=HOME_URL_HTTP?>blog" target="__black">
                             <img class="media-object img-circle img-comments" src="<?=$headimg?>" width="80" />
                           </a>
                            <div class="media-body">
                                <h4 class="media-heading" style="margin-bottom:0px;">
                                    <a href="<?=HOME_URL_HTTP?>blog" target="__black" style="color:#F07818;">
                                       232132132
                                    </a>
                                </h4>
                                <a href="<?=HOME_URL_HTTP?>blog" target="__black">
                                    <p>
                                        这为全文为我得到in第五期恩低年级三弟啊恩kin房地局去哪玩都砍死你开单的卡巴覅捷安达卡萨诺俯瞰SDK去哪网是我得你请我肯我群殴马拉松的玛莎拉蒂马萨拉蒂JS阿里度假建瓯可是军队斯安达价格比我能打开是你的擦拭的 我我的奶加拿大卡苏宁电器我教你 诶就这为全文为我得到in第五期恩低年级三弟啊恩kin房地局去哪玩都砍死你开单的卡巴覅捷安达卡萨诺俯瞰SDK去哪网是我得你请我肯我群殴马拉松的玛莎拉蒂马萨拉蒂JS阿里度假建瓯可是军队斯安达价格比我能打开是你的擦拭的 我我的奶加拿大卡苏宁电器我教你 诶就这为全文为我得到in第五期恩低年级三弟啊恩kin房地局去哪玩都砍死你开单的卡巴覅捷安达卡萨诺俯瞰SDK去哪网是我得你请我肯我群殴马拉松的玛莎拉蒂马萨拉蒂JS阿里度假建瓯可是军队斯安达价格比我能打开是你的擦拭的 我我的奶加拿大卡苏宁电器我教你 诶就
                                    </p>
                                </a>
                                <div style="float:right;margin-top:5px;">
                                    <span style="margin: 3px 7px;" onclick=""><img src="/public/home/img/dianzan.png" alt="点赞" style="width:20px;"></span>
                                    <span style="margin: 3px 7px;" onclick=""><img src="/public/home/img/huifu.png" alt="回复" style="width:20px;"></span>
                                </div>
                            </div>
                        </li>
                    <?php 
                    ?>
                    </ul>
                </div>
 			    <div class="Main">

                    <?php if(empty($this->homeid)||empty($this->home['id'])){?>
                    <div class="Input_Box" style="border-color: rgb(204, 204, 204); box-shadow: none;text-align:center;line-height:204px;font-size:15px;">
                        请先 <a href="<?=HOME_URL_HTTP?>login">登录</a> (建议使用github登录), 在留言
                    </div>
                    <?php }else{?>
                    <div class="Input_Box" style="border-color: rgb(204, 204, 204); box-shadow: none;">
                        <textarea class="Input_text"></textarea>
                        <div class="faceDiv" style="margin-top: 0px;"> 
                        </div>
                        <div class="Input_Foot">
                            <a class="imgBtn" href="javascript:void(0);"></a>
                            <a class="postBtn">确定</a> 
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="<?=HOME_PUBLIC_URL?>js/leave.js"></script>
<script type="text/javascript">
    $('.postBtn').click(function(){
        // 判断是否登录
        var Input_text = $('.Input_text').val();
        console.log(Input_text);

        $.ajax({
             type: "POST",
             url: "/home/leave/leave_save",
             async: false,
             data: {'Input_text':Input_text},
             dataType: "json",
             success: function(res){
                if(res.flog!=1){
                    myModalBody.html(res.msg);
                    myModal.addClass('alert-danger');
                    myModal.css('display','block');
                    return false;
                }else{
                    myModalBody.html(res.msg);
                    myModal.addClass('alert-success');
                    myModal.css('display','block');
                    setTimeout(function(){
                        window.location.href = '/home/leave';
                    },2000)
                }
             }
        });
    })
</script>

<?php
	// $this->load->view('home/public/footer');
?>
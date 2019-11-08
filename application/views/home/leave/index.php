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
                            if(!empty($leaves)){
                                foreach($leaves as $val){
                                    $headimg = '/'.$userList[$val['uid']]['headimg'];
                    ?>
                        <li class="media">
                            <a class="pull-left" href="#" target="__black">
                                <img class="media-object img-circle img-comments" src="<?=$headimg?>" width="80" />
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading" style="margin-bottom:0px;">
                                    <a href="#" target="__black" style="color:#F07818;">
                                        <?=$userList[$val['uid']]['nikename'];?>
                                    </a>
                                </h4>
                                <p>
                                   &nbsp;&nbsp;&nbsp;&nbsp;
                                   <?=$val['content']?>
                                </p>
                                <div style="float:right;margin-top:5px;">
                                    <span style="margin: 7px;font-size: 10px;display: block;float: left;line-height: 18px;"><?=$val['ctime']?></span>
                                    <span style="margin: 7px;cursor:pointer;" onclick="fabulous('<?=$val['id'];?>')">
                                        <img src="/public/home/img/dianzan.png" alt="点赞" style="width:20px;">
                                        (<span id="<?=$val['id'];?>_fabulous"><?=$val['fabulous'];?></span>)
                                    </span>
                                    <span style="margin: 7px;cursor:pointer;" onclick=""><img src="/public/home/img/huifu.png" alt="回复" style="width:20px;">(0)</span>
                                </div>
                            </div>
                        </li>
                    <?php 
                        }
                    }
                    ?>
                    </ul>
                    <div style="margin-top:20px;">
                        <?=$leave_htm;?>
                    </div>
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
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<script src="<?=HOME_PUBLIC_URL?>js/leave.js"></script>
<script type="text/javascript">
    $('.postBtn').click(function(){
        // 判断是否登录
        var Input_text = $('.Input_text').val();

        var regex2 = /\[(.+?)\]/g;  // [] 中括号
        var gifArr = Input_text.match(regex2);
        var textArr = [];
        $.each(gifArr,function(i,index){
            var gif = index;
            gif = gif.replace('[','');
            gif = gif.replace(']','');
            textArr.push(gif);
        })

        var gifTextArr = {};
        for(var i=0;i<ImgIputHandler.facePath.length;i++){
            var imgPath = "/public/home/img/";
            var faceName = ImgIputHandler.facePath[i].faceName;

            console.log(faceName);
            console.log($.inArray(faceName,textArr));

            if($.inArray(faceName,textArr)>0){
                gifTextArr.faceName = "<img title="+faceName+" src="+imgPath+ImgIputHandler.facePath[i].facePath+" />";
            }
        }

        console.log(gifArr);
        console.log(textArr);
        console.log(gifTextArr);
        return false;


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
                    window.location.href = '/home/leave';
                }
             }
        });
    })
    function fabulous(id){
        var nums = Number($('#'+id+'_fabulous').html());
        console.log(nums);

        $.ajax({
             type: "POST",
             url: "/home/leave/leave_fabulous",
             async: false,
             data: {'nums':nums,'id':id},
             dataType: "json",
             success: function(res){
                console.log(res);
                $('#'+id+'_fabulous').html(res.data.num);
             }
        });
    }
</script>

<?php
	$this->load->view('home/public/footer');
?>
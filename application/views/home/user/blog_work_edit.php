<?php 
	$this->load->view('home/public/top');
?>
<link rel="stylesheet" href="<?=PUBLIC_URL?>kindeditor/themes/default/default.css" />
<link rel="stylesheet" href="<?=PUBLIC_URL?>kindeditor/plugins/code/prettify.css" />
<script charset="utf-8" src="<?=PUBLIC_URL?>kindeditor/kindeditor.js"></script>
<script charset="utf-8" src="<?=PUBLIC_URL?>kindeditor/lang/zh_CN.js"></script>
<script charset="utf-8" src="<?=PUBLIC_URL?>kindeditor/plugins/code/prettify.js"></script>


<!-- Optional theme -->
<link rel="stylesheet" href="<?=HOME_PUBLIC_URL?>editormd/css/editormd.css" />
<script src="<?=HOME_PUBLIC_URL?>editormd/editormd.js"></script>
<script>
    // KindEditor.ready(function(K) {
    //     var editor1 = K.create('textarea[name="desc"]', {
    //         cssPath : '<?=PUBLIC_URL?>kindeditor/plugins/code/prettify.css',
    //         uploadJson : '<?=PUBLIC_URL?>kindeditor/php/upload_json.php',
    //         fileManagerJson : '<?=PUBLIC_URL?>kindeditor/php/file_manager_json.php',
    //         allowFileManager : true,
    //         afterCreate : function() {
    //             var self = this;
    //             K.ctrl(document, 13, function() {
    //                 self.sync();
    //                 K('form[name=example]')[0].submit();
    //             });
    //             K.ctrl(self.edit.doc, 13, function() {
    //                 self.sync();
    //                 K('form[name=example]')[0].submit();
    //             });
    //         }
    //     });
    //     prettyPrint();
    // });
    var testEditor;

    $(function() {     
        
        $.get("<?=HOME_PUBLIC_URL?>editormd/examples/test.md", function(md){
            testEditor = editormd("test-editormd", {
                width: "90%",
                height: 740,
                path : '/public/home/editormd/lib/',
                markdown : md,
                codeFold : true,
                saveHTMLToTextarea : true, 
                searchReplace : true,
                htmlDecode : "style,script,iframe|on*", 
                emoji : true,
                taskList : true,
                tocm            : true,         // Using [TOCM]
                tex : true,                   // 开启科学公式TeX语言支持，默认关闭
                flowChart : true,             // 开启流程图支持，默认关闭
                sequenceDiagram : true,       // 开启时序/序列图支持，默认关闭,
                imageUpload : true,
                imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                imageUploadURL : "<?=HOME_PUBLIC_URL?>editormd/examples/php/upload.php",
                onload : function() {
                    console.log('onload', this);
                }
            });
        });  
    });
</script>
<!-- MENU SECTION END-->
<div class="content-wrapper" style="min-height:600px;">
    <div class="container">
       <!--  <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">最佳博客</h4>
             </div>
        </div>   --> 
        <hr>
        <div class="row">
            <?php 
              $this->load->view('home/public/info_nav');
            ?>
             <div class="col-md-9 col-sm-12 col-xs-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        我的博客
                    </div>
                    <div class="panel-body">
                        <div class="tab-pane fade active in" id="home">
                            <h4 style="padding-top:5px;line-height:35px;">
                              文章<?=(empty($work)?'添加':'修改')?>
                              <a href="<?=HOME_URL_HTTP?>user/blog_work_info">
                                <span style="float:right;" class="btn btn-default btn-sm">返回</span>
                              </a>
                            </h4>
                            <form action="<?=HOME_URL_HTTP?>user/blog_work_update"  method="post" onsubmit="return blogworkForm()" enctype="multipart/form-data">

                                <div class="form-group col-md-4">
                                    <div class="">
                                      <label>文章配图：</label>
                                      <img src="<?=empty($work['img'])?'':'/'.$work['img']?>" id="head" width="100" alt="">
                                    </div>
                                  </div>
                                <div class="form-group col-md-8">
                                    上传的头像支持：png、jpg、jpeg 格式,但大小要小于4M。
                                    <br>
                                    <br>
                                    <span style="display:none;"><input type="file" name="headimg"></span>
                                    <button type="button" onclick="upload_file()" class="btn btn-success">上传图片</button>
                                </div>

                                <div class="form-group  col-md-12">
                                    <label>文章标题：</label>
                                    <input type="text" class="form-control" name="title" value="<?=empty($work['title'])?'':$work['title']?>">
                                    <input type="hidden" class="form-control" name="uid" value="<?=empty($user['id'])?'':$user['id']?>">
                                    <input type="hidden" class="form-control" name="id" value="<?=empty($work['id'])?'':$work['id']?>">
                                    <input type="hidden" class="form-control" name="blog_id" value="<?=empty($blog['id'])?'':$blog['id']?>">
                                </div>
                                <div class="form-group  col-md-12">
                                    <label>文章分类：</label>
                                    <select class="form-control" name="cate_id" id="cate_id">
                                        <option value="0">请选择。。</option>
                                        <?php 
                                        if(!empty($cates)){
                                            foreach($cates as $cate){
                                        ?>
                                            <option value="<?=$cate['id']?>" <?=(!empty($work['cate_id'])&&$work['cate_id']==$cate['id'])?'selected':''?>><?=$cate['title']?></option>
                                        <?php 
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group  col-md-12">
                                    <label>文章标签 ：(点击按钮添加标签)</label>
                                    <div class="control">
                                        <span class="btn btn-success btn-xs" onclick="add_tag()">
                                            <i class="fa fa-edit "></i>标签
                                        </span>
                                        <span id="tags"> 
                                            <?php 
                                            $tagName = '';
                                            if(!empty($tags)){
                                                foreach($tags as $k=>$tag){
                                            ?>
                                            <span onclick="tag_del($(this),<?=$k?>)" style="padding:0px 5px;margin:0px 3px;" class="btn btn-default">
                                                <?=$tag['name']?>
                                                <input type="hidden" name="tags[<?=$k?>]" id="tag_<?=$k?>" value="<?=$tag['name']?>"/>
                                                <span style="color:red;">×</span>
                                            </span>
                                            <?php 
                                                $tagName .= $tag['name'].',';
                                                }
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <div class="col-md-12" id="tag" style="margin-top:4px;display:none;">
                                        <input type="text" id="tag_name"  class="form-control" placeholder="用逗号分隔，回车确认" />
                                    </div>
                                </div>
                                <div class="form-group  col-md-12">
                                    <label>文章内容：</label>
                                    <!-- <textarea class="form-control col-md-12" name="desc" id="desc" style="min-height:100px;visibility:hidde" /><?=empty($work['desc'])?'':$work['desc']?></textarea> -->
                                    <div id="test-editormd">
                                        <textarea style="display:none;">### Bootstrap 兼容测试</textarea>
                                    </div>
                                </div>
                                <div class="form-group  col-md-12">
                                    <label></label>
                                    <input type="submit" class="btn btn-success"  value="提交">
                                    <button type="reset" class="btn btn-primary">取消</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
    </div>
</div>
<script type="text/javascript"> 
    var myModal = $('#alert');
    var myModalBody = $('#myModalBody');
    var i = 0;
    var tagName = '<?=$tagName?>';

    function blogworkForm(){
        var title = $('input[name="title"]').val();
        var desc = $('#desc').val();
        var uid = $('input[name="uid"]').val();
        var id = $('input[name="id"]').val();
        var cate_id = $('#cate_id').val();

        if(title==''){
            myModalBody.html('文章标题不能为空!');
            myModal.addClass('alert-danger');
            myModal.css('display','block');
            return false;
        }

        if(cate_id=='0'){
            myModalBody.html('请选择一个分类!');
            myModal.addClass('alert-danger');
            myModal.css('display','block');
            return false;
        }
       
      
    }

    function upload_file(){
        $('input[name="headimg"]').click();
    }

    $('input[name="headimg"]').change(function(){
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
            $("#head").attr("src", objUrl) ;
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


    function add_tag(){
        if(i%2==0){
            $('#tag_name').val('');
            $('#tag').show();
        }else{
            $('#tag').hide();
        }
        i++;
    }

    document.onkeydown=function(event){
        var e = event || window.event || arguments.callee.caller.arguments[0];
        if(e && e.keyCode==27){ // 按 Esc 
            $('#tag').hide();
            i++;
        }
                    
        if(e && e.keyCode==13){ // enter 键
            tag_name();
            return false
        }
    };

    function tag_name(){
        tagName += $('#tag_name').val();
        if(tagName!=''){
            tagName += ',';
            tagName = tagName.replace(/，/ig,','); 
            var result = tagName.split(',');
            var html = '';

            $.each(result,function(i,index){
                if(index!=''){
                    html += '<span onclick="tag_del($(this),'+i+')" style="padding:0px 5px;margin:0px 3px;" class="btn btn-default">';
                    html += index;
                    html += '<input type="hidden" name="tags['+i+']" id="tag_'+i+'" value="'+index+'"/>';
                    html += '&nbsp;&nbsp;&nbsp;<span style="color:red;">×</span>'
                    html += '</span>';
                }
            })
            $('#tags').html(html);
        }

        $('#tag').hide();
        i++;
    }

    function tag_del(res,i){
        res.remove();
        // $('#tag_'+i).val('');
        tagName = tagName.replace(/，/ig,','); 
        var result = tagName.split(',');
        delete result[i];
        tagName = result.join();
        // $('tag_'+i).remove();
    }


</script>
<?php
	$this->load->view('home/public/footer');
?>
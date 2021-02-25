<?php 
	$this->load->view('home/public/top');
?>

<style type="text/css">
    #chatroom{width:100%;height:450px;background:red;}
    #chatroominput{width:100%;text-align:left;}
    #contInput{width: 90%;height: 45px;border: 1px #ccc solid; border-radius: 5px;}
    #contButton{width: 60px;height: 45px;margin-left: 15px;background: #fff;border: solid 1px #ccc;color: black;border-radius: 5px;}
    #contButton:hover {background: #9EEA6A;color: #fff;}
</style>
<!-- MENU SECTION END-->
<div class="content-wrapper" style="min-height:600px;">
    <div class="container">

        <div class="row">
            <div class="col-md-10 col-sm-10 col-xs-10">
 				<div class="panel panel-default">
                    <div class="panel-heading">
                       聊天室
                    </div>
                    <div class="panel-body text-center recent-users-sec" id="chatroom">
                        
                    </div>
                    <div class="panel-body text-center recent-users-sec" id="chatroominput">
                        <?php if(empty($this->homeid)||empty($this->home['id'])){?>
                        <div class="Input_Box" style="border-color: rgb(204, 204, 204); box-shadow: none;text-align:center;line-height:204px;font-size:15px;">
                            请先 <a href="<?=HOME_URL_HTTP?>login">登录</a> (建议使用github登录), 在留言
                        </div>
                        <?php }else{?>
                            <input type="text" name="message" id="contInput"> 
                            <button id="contButton" onclick="sendCont()">发送</button>
                        <?php }?>
                    </div>
     			</div>
            </div>
        </div>   
    </div>
</div>>
<script type="text/javascript">
    $(function(){
        socket_link();
        $(document).keyup(function (evnet) {
            if (evnet.keyCode == '13') {
                sendCont();
            }
        });
    })
    var socket;
    var userid = "<?=$this->home['id']?>";
    function socket_link(){
        var url = window.location.href;
        if(url.substr(0,7).toLowerCase() == "https://"){
            var socketUrl='wss://blog.myfeiyou.com/wss';
        }else{
            var socketUrl='ws://104.243.18.161:8000';
        }
        socket=new WebSocket(socketUrl);
        socket.onopen=function(){
            console.log('连接成功');
        }
        socket.onmessage=function(msg){
            // log(msg);
            console.log(msg);
        }
        socket.onclose=function(){
            console.log('断开连接');
        }
    }
    function dis(){
        socket.close();
        socket=null;
    }
    function log(res){
        var user = $('input[name="user"]').val();
        var data = res.data;
        var index = JSON.parse(data);
        var weizhi = 'left';
        var yonghuclass = 'from_user'
        if(index.user==user){
            weizhi = 'right';
            yonghuclass = 'by_myself';
        }
        var html = '';
        html += '<li class="span10 '+weizhi+' '+yonghuclass+'"> <a href="#" class="avatar"><img src="/resource/website/img/message_avatar1.png"/></a>';
        html += '<div class="message_wrap"  style="float:'+weizhi+';"> <span class="arrow"></span>';
        html += '<div class="info"> <a class="name"> 用户 '+index.user+' </a>';
        html += '</div>';
        html += '<div class="text">';
        html += index.msg;
        html += '</div>'
        html += '</div>'
        html += '</li>';

        $('.log').append(html);
        document.getElementById("content").scrollTop = document.getElementById("content").scrollHeight;
        // $('.log').html(html);
    }

    function sendCont(){
        var message = $('#contInput').val();
        if(message==''){
            alert('内容不能为空！');
            return false;
        }
        if(message.length>=24){
            alert('内容不能超过24个字符！');
            return false;
        }
        var jsonobj = {'type':'msg','userid':userid,'msg':message};
        var json = JSON.stringify(jsonobj);
        socket.send(json);
        
        $('#contInput').val('');
    }
</script>

<?php
	$this->load->view('home/public/footer');
?>
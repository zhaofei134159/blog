<?php 
	$this->load->view('home/public/top');
?>

<style type="text/css">
    #chatroom{width:100%;height:450px;padding:20px 30px; border-bottom:solid 1px #ccc;overflow-x: none;overflow-y: auto;}
    #chatroominput{width:100%;text-align:left;}
    #contInput{width: 90%;height: 45px;border: 1px #ccc solid; border-radius: 5px;}
    #contButton{width: 60px;height: 45px;margin-left: 15px;background: #fff;border: solid 1px #ccc;color: black;border-radius: 5px;}
    #contButton:hover {background: #9EEA6A;color: #fff;}

    /* bubble style */
    .core{border: solid 1px #ccc;height: 24px;width: 34%;margin: 10px auto;border-radius: 4px;background: #ccc;color: #fff;font-size: 12px;font-weight: bold;clear:both;}
    .sender{clear:both;}
    .sender div:nth-of-type(1){float: left;}
    .sender div:nth-of-type(2){background-color: #F8ECDD;color:black;float: left;margin: 0 20px 10px 15px;padding: 10px 10px 10px 0px;border-radius:7px;line-height: 30px;}
    .receiver div:first-child img, .sender div:first-child img{width:50px;height: 50px;border-radius:7px;}
    .receiver{clear:both;}
    .receiver div:nth-child(1){float: right;}
    .receiver div:nth-of-type(2){float:right;background-color:#9EEA6A;color:black;margin: 0 10px 10px 20px;padding: 10px 0px 10px 10px;border-radius:7px;line-height: 30px;}
    .left_triangle{height:0px;width:0px;border-width:8px;border-style:solid;border-color:transparent #F8ECDD transparent transparent;position: relative;left:-16px;top:7px;}
    .right_triangle{height:0px;width:0px;border-width:8px;border-style:solid;border-color:transparent transparent transparent #9EEA6A;position: relative;right:-16px;top:7px;}
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
                        <div class="Input_Box" style="border-color: rgb(204, 204, 204); box-shadow: none;text-align:center;font-size:15px;">
                            请先 <a href="<?=HOME_URL_HTTP?>login">登录</a> (建议使用github登录),才可以聊天
                        </div>
                        <?php }else{?>
                            <input type="text" name="message" id="contInput"> 
                            <button id="contButton" onclick="sendCont('msg')">发送</button>
                        <?php }?>
                    </div>
     			</div>
            </div>
        </div>   
    </div>
</div>
<script type="text/javascript">
    var socket;
    var userid = "<?php echo (isset($this->home['id'])&&!empty($this->home['id']))?$this->home['id']:''; ?>";
    var url = 'http://blog.myfeiyou.com/';

    $(function(){
        socket_link();

        $(document).keyup(function (evnet) {
            if (evnet.keyCode == '13' && userid!='') {
                sendCont('msg');
            }
        });
        // 心跳测试
        setInterval(function(){
            if(socket!=null && userid!=''){
                socket.send('ping'); 
            }
        },10000)

        // 关闭网页 断开连接
        window.onbeforeunload=function (){
            dis();
        }
    })
    function socket_link(){
        var url = window.location.href;
        if(url.substr(0,7).toLowerCase() == "http://"){
            var socketUrl = 'ws://118.195.174.120:8000';
        }else{
            var socketUrl = 'wss://blog.myfeiyou.com/wss';
        }
        socket=new WebSocket(socketUrl);
        socket.onopen=function(){
            console.log('连接成功');

            // 进入聊天室  发送消息
            if(userid==''){
                sendCont('record','进入聊天室');
            }
            sendCont('start','进入聊天室');

        }
        socket.onmessage=function(msg){
            var data = JSON.parse(msg.data); 
            if(data.result.length!=0){
                log(data);
            }
        }
        socket.onclose=function(){
            console.log('断开连接');
        }

    }
    function dis(){
        sendCont('end','退出聊天室');
        socket.close();
        socket = null;
    }
    function log(res){
        var result = res.result;
        $.each(result,function(i,index){
            var html = '';
            if(index.msg_type=='in'){
                html += '<div class="core">';
                html +=  index.usNikename + ' 进入聊天室';
                html += '</div>';
            }else if(index.msg_type=='out'){
                html += '<div class="core">';
                html +=  index.usNikename + ' 退出聊天室';
                html += '</div>';
            }else if(index.msg_type=='text'){
                if(userid == index.userid){
                    html += '<div class="receiver">';
                    html += '<div>';
                    html += '<img src="'+url+index.usHeadimg+'" title="'+index.usNikename+'">';
                    html += '</div>';
                    html += '<div title="'+dateDiff(index.msg_time)+'">';
                    html += '<div class="right_triangle"></div>';
                    html += '<span>'+index.content+'</span>';
                    html += '</div>';
                    html += '</div>';
                }else{
                    html += '<div class="sender">';
                    html += '<div>';
                    html += '<img src="'+url+index.usHeadimg+'" title="'+index.usNikename+'">';
                    html += '</div>';
                    html += '<div title="'+dateDiff(index.msg_time)+'">';
                    html += '<div class="left_triangle"></div>';
                    html += '<span>'+index.content+'</span>';
                    html += '</div>';
                    html += '</div>';
                }
            }
            $('#chatroom').append(html);
            document.getElementById("chatroom").scrollTop = document.getElementById("chatroom").scrollHeight;
        })
    }

    function sendCont(msg_type,msg=''){
        if(msg_type=='msg'){
            var type = 'msg';
            var message = $('#contInput').val();
            if(message==''){
                alert('内容不能为空！');
                return false;
            }
        }else{
            var type = msg_type;
            var message = msg;
        }
        if(message.length>=24){
            alert('内容不能超过24个字符！');
            return false;
        }
        if(userid=='' && type!='record'){
            console.log('用户没有登录');
            return false;
        }
        var jsonobj = {'type':type,'userid':userid,'msg':message};
        var json = JSON.stringify(jsonobj);
        socket.send(json);
        
        $('#contInput').val('');
    }

    function dateDiff(timestamp) {
        // 补全为13位
        var arrTimestamp = (timestamp + '').split('');
        for (var start = 0; start < 13; start++) {
            if (!arrTimestamp[start]) {
                arrTimestamp[start] = '0';
            }
        }
        timestamp = arrTimestamp.join('') * 1;
     
        var minute = 1000 * 60;
        var hour = minute * 60;
        var day = hour * 24;
        var halfamonth = day * 15;
        var month = day * 30;
        var now = new Date().getTime();
        var diffValue = now - timestamp;
     
        // 如果本地时间反而小于变量时间
        if (diffValue < 0) {
            return '不久前';
        }
     
        // 计算差异时间的量级
        var monthC = diffValue / month;
        var weekC = diffValue / (7 * day);
        var dayC = diffValue / day;
        var hourC = diffValue / hour;
        var minC = diffValue / minute;
     
        // 数值补0方法
        var zero = function (value) {
            if (value < 10) {
                return '0' + value;
            }
            return value;
        };
     
        // 使用
        if (monthC > 12) {
            // 超过1年，直接显示年月日
            return (function () {
                var date = new Date(timestamp);
                return date.getFullYear() + '年' + zero(date.getMonth() + 1) + '月' + zero(date.getDate()) + '日';
            })();
        } else if (monthC >= 1) {
            return parseInt(monthC) + "月前";
        } else if (weekC >= 1) {
            return parseInt(weekC) + "周前";
        } else if (dayC >= 1) {
            return parseInt(dayC) + "天前";
        } else if (hourC >= 1) {
            return parseInt(hourC) + "小时前";
        } else if (minC >= 1) {
            return parseInt(minC) + "分钟前";
        }
        return '刚刚';
    };
</script>

<?php
	$this->load->view('home/public/footer');
?>
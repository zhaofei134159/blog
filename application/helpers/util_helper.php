<?php
class Util{   

    /*
    获取提交数据
    */
    public static function get($name, $defautval = null){
        if(!empty($_GET[$name])){
            return $_GET[$name];
        }else{
            return $defautval;
        }
    }

    /*
    获取提交数据
    */
    public static function post($name, $defautval = null){
        if(!empty($_POST[$name])){
            return $_POST[$name];
        }else{
            return $defautval;
        }
    }
    
    public static function getPar($name, $defautval = null){
        if(!empty($_GET[$name])){
            return $_GET[$name];
        }elseif(!empty($_POST[$name])){
            return $_POST[$name];
        }elseif(!empty($_SESSION[$name])){
            return $_SESSION[$name]; 
        }elseif(!empty($_COOKIE[$name])){
            return $_COOKIE[$name];
        }else{
            return $defautval;
        }
    }

    public static function setPar($name, $value, $scope = 'GET'){
        switch($scope){
            case 'GET' : 
                 $_GET[$name] = $value;
                 break;
            case 'POST' : 
                 $_POST[$name] = $value;
                 break;
            case 'SESSION' : 
                 $_SESSION[$name] = $value;
                 break;
            case 'COOKIE' : 
                 setcookie($name, $value);
                 break;
        }    
    }

    public static function is_weixin(){  
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {  
                return true;  
        }    
        return false;  
    }  

    /*
    * 页面跳转
    */
    public static function redirect($url, $messages = null, $target = null){
        if(!empty($url)){
           echo  '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
           echo "<script>";     
           if(!empty($messages)){
               echo "alert('$messages');";
           }
           $locationurl = "location.href='$url';";
           if(!empty($target)){
               $locationurl = $target . "." . $locationurl;
           }
           echo $locationurl;
           echo "</script>";
           die();
        }else{
            die("重定向链连错误.");
        }
    }
    
    /*
    * 积分
    */
    public static function jifen($code = '', $userid = 0, $objid = 0, $remark = ''){
        echo  '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo  '<script type="text/javascript" charset="utf-8" src="/resource/scripts/jquery-1.8.3.js"></script>';
        echo  '<script type="text/javascript" src="/resource/scripts/common.js"></script>';
        echo  "<script>";
        echo  "jifen('$code', $userid, $objid, '$remark');";
        echo  "</script>";
    }


    /*
    * 生成流水号
    */
    public static function getserialnumber(){
        $serialnumber = date("Y-m-d H-i-s");
        $serialnumber = str_replace("-","",$serialnumber);
        $serialnumber .= rand(1000,9999);
        $serialnumber = str_replace(" ","",$serialnumber);
        return $serialnumber;
    }


    /*
    * 生成订单号
    */
    public static function getordersn(){
        $ordersn = date("Y-m-d H-i-s");
        $ordersn = str_replace("-","",$ordersn);
        $ordersn .= rand(1000,9999);
        $ordersn = str_replace(" ","",$ordersn);
        return $ordersn;
    }

    /*
    * 生成提货单号
    */
    public static function getDeliveryBillCode(){
        $deliveryBillCode = date("Y-m-d H-i-s");
        $deliveryBillCode = str_replace("-","",$deliveryBillCode);
        $deliveryBillCode .= rand(1000,9999);
        $deliveryBillCode = 'THD-'.str_replace(" ","",$deliveryBillCode);
        return $deliveryBillCode;
    }
    
    /*
    * 生成发货单号
    */
    public static function getSendBillCode(){
        $sendBillCode = date("Y-m-d H-i-s");
        $sendBillCode = str_replace("-","",$sendBillCode);
        $sendBillCode .= rand(1000,9999);
        $sendBillCode = 'FHD-'.str_replace(" ","",$sendBillCode);
        return $sendBillCode;
    }

    /*
    * 生成充值单号
    */
    public static function getRecharge(){
        $Recharge = date("Y-m-d H-i-s");
        $Recharge = str_replace("-","",$Recharge);
        $Recharge .= rand(1000,9999);
        //$Recharge = str_replace(" ","",$Recharge);
        $Recharge = 'CZD-'.str_replace(" ","",$Recharge);
        return $Recharge;
    }
    
    /*
    * 生成附加单号
    */
    public static function getAdditionalCode(){
        $Recharge = date("Y-m-d H-i-s");
        $Recharge = str_replace("-","",$Recharge);
        $Recharge .= rand(1000,9999);
        //$Recharge = str_replace(" ","",$Recharge);
        $Recharge = 'FJD-'.str_replace(" ","",$Recharge);
        return $Recharge;
    }
    

    /*
    * 跳到上一页
    */
    public static function jumpback($msg){
        echo  '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $ouput = "<script>";
        $ouput .= "alert('" . $msg . "');";
        $ouput .= "history.back();";
        $ouput .= "</script>";
        echo $ouput;
        die();
    }
    
    /**
    * 创建指定目录下的文件夹
    * @param string 文件夹所在路径  
    * @return string 如果成功返回true,否则返回信息
    * @author wanglei
    */
    public static function makeDir($directoryName) {
        $directoryName = str_replace("\\","/",$directoryName);
        $dirNames = explode('/', $directoryName);
        $total = count($dirNames) ;
        $temp = '';
        for($i=0; $i< $total; $i++) {
        $temp .= $dirNames[$i].'/';
        if (!is_dir($temp)) {
            $oldmask = umask(0);
            if (!mkdir($temp, 0777)) exit("不能建立目录 $temp"); 
                umask($oldmask);
            }
        }
        return true;
    }


    /*
    * 字节转换
    */    
    function byteChange($size, $type){
        if($type == 'KB'){
            $size = floor(($size/1024)*100)/100;
        }else if($type == 'MB'){
            $size = floor(($size/1048576)*100)/100;
        }
        return $size;
    }
    
   /*
    * 获取扩展名
    */
    function get_extension($file) {
        return substr($file, strrpos($file, '.')+1);
    }

    public static function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')  { 
        if($code == 'UTF-8') 
        { 
            $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/"; 
            preg_match_all($pa, $string, $t_string); 
     
            if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."..."; 
            return join('', array_slice($t_string[0], $start, $sublen)); 
        } 
        else 
        { 
            $start = $start*2; 
            $sublen = $sublen*2; 
            $strlen = strlen($string); 
            $tmpstr = ''; 
     
            for($i=0; $i< $strlen; $i++) 
            { 
                if($i>=$start && $i< ($start+$sublen)) 
                { 
                    if(ord(substr($string, $i, 1))>129) 
                    { 
                        $tmpstr.= substr($string, $i, 2); 
                    } 
                    else 
                    { 
                        $tmpstr.= substr($string, $i, 1); 
                    } 
                } 
                if(ord(substr($string, $i, 1))>129) $i++; 
            } 
            if(strlen($tmpstr)< $strlen ) $tmpstr.= "..."; 
            return $tmpstr; 
        } 
    }



    function showplayer($path, $useFlashPlayer = false){
        //取扩展名
        $fileext = Util::get_extension($path);
        $fileext = strtolower($fileext);
        if($useFlashPlayer){
            if($fileext == 'swf'){
                echo "<a id='viewerPlaceHolder' style='width:680px;height:480px;display:block'></a> \n";
                echo "<script> \n";
                echo "  var fp = new FlexPaperViewer('/resource/js/FlexPaperViewer','viewerPlaceHolder', { config : { \n";
                echo "      SwfFile : escape('".$path."'), \n";
                echo "      Scale : 0.6, \n";
                echo "      ZoomTransition : 'easeOut', \n";
                echo "      ZoomTime : 0.5, \n";
                echo "      ZoomInterval : 0.2, \n";
                echo "      FitPageOnLoad : true, \n";
                echo "      FitWidthOnLoad : false, \n";
                echo "      FullScreenAsMaxWindow : false, \n";
                echo "      ProgressiveLoading : false, \n";
                echo "      MinZoomSize : 0.2, \n";
                echo "      MaxZoomSize : 5, \n";
                echo "      SearchMatchAll : false, \n";
                echo "      InitViewMode : 'SinglePage',  \n";
                echo "      ViewModeToolsVisible : true, \n";
                echo "      ZoomToolsVisible : true, \n";
                echo "      NavToolsVisible : true, \n";
                echo "      CursorToolsVisible : true, \n";
                echo "      SearchToolsVisible : true, \n";
                echo "      localeChain: 'en_US' \n";
                echo "    }}); \n";
                echo "</script> \n";
            }else{
                echo "格式错误,只允许swf格式文件！";
            }
        }else{
            if($fileext == 'gif' || $fileext == 'jpg' || $fileext == 'png'){
                echo "<img src='".$path."'>";
            }else if($fileext == 'swf'){
                echo '<embed id="top10movie" name="top10movie" pluginspage="http://www.macromedia.com/go/getflashplayer" src="'.$path.'" width="650" height="488" type="application/x-shockwave-flash" menu="false" quality="high" />' . " \n";
                echo '</embed />' . " \n";
            }else if($fileext == 'flv'){
                echo '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="500" height="400">' . "\n";
                echo '  <param name="movie" value="/resource/swf/Flvplayer.swf" />' . "\n";
                echo '  <param name="quality" value="high" />' . "\n";
                echo '  <param name="allowFullScreen" value="true" />' . "\n";
                echo '  <param name="FlashVars" value="vcastr_file='.$path.'" />' . "\n";
                echo '  <embed src="/resource/swf/Flvplayer.swf" allowfullscreen="true" flashvars="vcastr_file='.$path.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="500" height="400"></embed>' . "\n";
                echo '</object>' . "\n";
            }else{
                echo "格式错误,暂不支持格式文件！";
            }
        }
    }


    /**
    	 * Curl版本
    	 * 使用方法：
    	 * $post_string = "app=request&version=beta";
    	 * request_by_curl('http://facebook.cn/restServer.php',$post_string);
    */
    public function request_by_curl($remote_server, $key, $post_string)	{
        $ch = curl_init();        
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ''.$key.'=' . $post_string);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /*
    * 发送邮件
    */
    public function sendEmail($tos = false, $subject = false, $message = false, $fromemail = "yibaisteel@126.com", $fromname = "壹佰钢铁网现货交易中心") {
        $subject  = iconv('utf-8','gb2312',$subject);
        $message  = iconv('utf-8','gb2312',$message);
        $fromname = iconv('utf-8','gb2312',$fromname);
        
        $this->load->library('email');
        
        $config['protocol'] = 'smtp';  //邮件发送协议。
        //$config['charset'] = 'iso-8859-1';  //发送给QQ用户，要显示中文用 'iso-8859-1'
        $config['charset'] = 'gb2312';    //发送给163用户，要显示中文用'gb2312'
        $config['mailpath'] = 'news/index'; //服务器上 Sendmail 的实际路径。
        $config['smtp_host'] = 'smtp.126.com'; //服务器地址。
        $config['smtp_user'] = 'yibaisteel@126.com'; //用户账号。
        $config['smtp_pass'] = '100steel'; //密码。
        $config['mailtype'] = 'html';  //text 或 html
        //$config['smtp_port']= 25;   //SMTP 端口
        $this->email->initialize($config);
        
        $this->email->from($fromemail, $fromname);  //设置发件人email地址和名称:
        $this->email->to($tos); //设置收件人email地址(多个). 地址可以是单个、一个以逗号分隔的列表或是一个数组:
        //$this->email->cc('another@another-example.com'); //设置抄送(Carbon Copy / CC) email地址(多个). 类似to()函数, 地址可以是单个、一个以逗号分隔的列表或是一个数组.
        //$this->email->bcc('them@their-example.com');  //设置暗送(Blind Carbon Copy / BCC) email地址(多个). 类似to()函数, 地址可以是单个、一个以逗号分隔的列表或是一个数组.
        $this->email->subject($subject);  //设置email主题：
        $this->email->message($message);  //设置email正文部分：
        //$this->email->attach('D:/QCallServer/htdocs/CI/public/images/google.jpg'); //附件 路径需要使用绝对路径
        
        if($this->email->send()) {
            return true;
        }else{
            return false;
        }
        //echo $this->email->print_debugger();  //返回包含邮件内容的字符串，包括EMAIL头和EMAIL正文。用于调试。
    }

    /*
    * 计算时间差
    */
    public function timediff($begin_time,$end_time){
         if($begin_time < $end_time){
            $starttime = $begin_time;
            $endtime = $end_time;
         }else{
            $starttime = $end_time;
            $endtime = $begin_time;
         }
         $timediff = $endtime-$starttime;
         $days = intval($timediff/86400);
         $remain = $timediff%86400;
         $hours = intval($remain/3600);
         $remain = $remain%3600;
         $mins = intval($remain/60);
         $secs = $remain%60;
         $res = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);
         return $res;
    }
    
    /*
    * 分解时间
    */
    public function splittime($datetime){
        $weekday = array('日','一','二','三','四','五','六');
        $month = array('01' => '一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二');
        $array=explode(" ",$datetime);
        //处理日期串，拆分为数组形式
        $arr_d=explode("-",$array[0]);
        //处理时间串，拆分为数组形式
        $arr_t=explode(":",$array[1]);
        $_datetime = array(
            'y'        =>  $arr_d[0],
            'm'        =>  $arr_d[1],
            'd'        =>  $arr_d[2],
            'h'        =>  $arr_t[0],
            'i'        =>  $arr_t[1],
            's'        =>  $arr_t[2],
            'weekday'  =>  $weekday[date('w', strtotime($datetime))],
            'month'    =>  $month[date('n', strtotime($datetime))]
        );
        return $_datetime;
    }

    /*
    * 分享
    */
    public function jiathis($params = array()){
        $url      =  (empty($params['url'])?'':$params['url']);
        $title    =  (empty($params['title'])?'':$params['title']);
        $summary  =  (empty($params['summary'])?'':$params['summary']);
        $pic      =  (empty($params['pic'])?'':$params['pic']);
        $str = '';
        $str .= '<table><tr><td>'  . "\n";
        $str .= ' <div onmouseover="setShare( \''.$url.'\', \''.$title.'\', \''.$summary.'\', \''.$pic.'\' );">'  . "\n";
        $str .= ' <div class="jiathis_style">      '  . "\n";
        $str .= ' <a href="http://www.jiathis.com/share/?uid=1361404205368637" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank">分享</a>'  . "\n";
        $str .= ' <span class="jiathis_separator">|</span>'  . "\n";
        $str .= ' <a class="jiathis_button_qzone"></a>'  . "\n";
        $str .= ' <a class="jiathis_button_tsina"></a>'  . "\n";
        $str .= ' <a class="jiathis_button_kaixin001"></a>'  . "\n";
        $str .= ' <a class="jiathis_button_renren"></a>'  . "\n";
        $str .= ' </div>'  . "\n";
        $str .= '</td></tr></table>'  . "\n";
        echo $str;
    }

    /*
    *  图片加后缀
    */
    public function imgext($imgpath, $ext){
        if(empty($imgpath)){
            return "";
        }else{
            list($path, $ext2) = explode(".", $imgpath);
            return $path.$ext.".".$ext2;
        }
    }
    
        
    public function str_exist($txt, $str){
        if(ereg($str, $txt)){
            return true;
        }else{
            return false;
        }
    }

    /*
    * 创建PDF
    * @param $strContent 生成内容
    * @param $name 生成名称
    * @param $route 文件路径
    */
    function get_pdf($strContent,$route,$name){
        include(API_PATH."mypdf/mpdf.php");
        $mpdf=new mPDF('UTF-8','A4','','',15,15,44,15);
        $mpdf->useAdobeCJK = true; 
        $mpdf->SetAutoFont(AUTOFONT_ALL);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->showWatermarkText = true;
        $mpdf->SetAutoFont();
        $mpdf->WriteHTML($strContent);
        $mpdf->Output($route.$name.'.pdf');
    }
    
public static function get_real_ip(){
$ip=false;
if(!empty($_SERVER["HTTP_CLIENT_IP"])){
$ip = $_SERVER["HTTP_CLIENT_IP"];
}
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
for ($i = 0; $i < count($ips); $i++) {
    
//if (!eregi ("^(10|172.16|192.168).", $ips[$i])) {
if (!preg_match ("/^(10|172.16|192.168)./i", $ips[$i])) {
$ip = $ips[$i];
break;
}
}
}
return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}




    //获取文件目录列表,该方法返回数组
    public static function getDir($dir) {
        $dirArray[]=NULL;
        if (false != ($handle = opendir ( $dir ))) {
            $i=0;
            while ( false !== ($file = readdir ( $handle )) ) {
                //去掉"“.”、“..”以及带“.xxx”后缀的文件
                if ($file != "." && $file != ".."&&!strpos($file,".")) {
                    $dirArray[$i]=$file;
                    $i++;
                }
            }
            //关闭句柄
            closedir ( $handle );
        }
        return $dirArray;
    }

    //获取文件列表
    public static function getFile($dir) {
        $fileArray[]=NULL;
        if (false != ($handle = opendir ( $dir ))) {
            $i=0;
            while ( false !== ($file = readdir ( $handle )) ) {
                //去掉"“.”、“..”以及带“.xxx”后缀的文件
                if ($file != "." && $file != ".."&&strpos($file,".")) {
                    $fileArray[$i]=$file;
                    if($i==2000){
                        break;
                    }
                    $i++;
                }
            }
            //关闭句柄
            closedir ( $handle );
        }
        return $fileArray;
    }


}
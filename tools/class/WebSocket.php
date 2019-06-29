<?php

/** 
* WebSocket 自己封装的类库
* @param addr,port,callback,log
*        
* $users 客户列表
* 结构:
* $users = array(
*     [用户id]=>array('socket'=>[标示],'hand'=[是否握手-布尔值]),
*     [用户id]=>arr.....
* )      
*        
*/
class WebSocket{

	public $addr; //地址
	public $port; //端口
	public $log;  //是否开启日志 true为开启
	public $master; //当前资源
	public $sockets; 
	public $users; //用户数组
	public $callback; //回调函数

	public function __construct($addr,$port,$callback,$log=true){

        // 判断你是不是命令行运行
        $this->lines();

        error_reporting(E_ALL);
        set_time_limit(0);
        ob_implicit_flush();
        $this->log = $log;
        $this->callback = $callback;

        //监听端口
        $this->master = $this->Listen($addr,$port);
        $this->sockets = array('s'=>$this->master);

	}

	//监听方法
    public function Listen($addr,$port){
        $server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind($server, $addr, $port);
        socket_listen($server);
        socket_set_nonblock($server); //非阻塞
        $this->log('开始监听: '.$addr.' : '.$port);
        return $server;
    }

    //开始启动
	public function start(){
		while(true){
      		$changes = $this->sockets;
	      	//使用select非阻塞模式socket,读取客户端信息
	      	@socket_select($changes,$write=NULL,$except=NULL,NULL);
	      	foreach($changes as $sign){

	      		//如果为当前资源
		        if($sign == $this->master){
		          	$client=socket_accept($this->master);
		          	$this->sockets[] = $client;

		          	//组用户数组
		          	$user = array('socket'=>$client,'hand'=>false);
		          	$this->users[] = $user;

		          	//查询标示的数组下标为几，命名为ID
		          	$userid = $this->search($client);
		          	$usermsg = array('userid'=>$userid,'sign'=>$sign);
		          	$this->userreturn('in',$usermsg);
		        }else{
		        	// $len = 0 为正常退出 -1 为已经执行了，只不过失败了
		          	$len = socket_recv($sign,$buffer,2,0);
		          	$userid = $this->search($sign);
		          	$user = $this->users[$userid];
		          	if($len<7){
		            	$this->close($sign);
		            	$usermsg = array('userid'=>$userid,'sign'=>$sign);
		            	$this->userreturn('out',$usermsg);
		            	continue;
		          	}
		          	if(!$this->users[$userid]['hand']){//没有握手进行握手
		            	$this->handshake($userid,$buffer);
		          	}else{
	          			$read = '';
						while($flag = socket_recv($sign, $buffer, 2, 0)) {
							var_dump($flag);
						}
						$read = $this->uncode($buffer);
						var_dump($flag);
						var_dump($read);
		            	$usermsg = array('userid'=>$userid,'sign'=>$sign,'msg'=>$read);
	            		$this->userreturn('msg',$usermsg);
		          	}
		        }
	      	}
	    }
	}

	//回调函数
  	public function userreturn($type,$usermsg){
     	call_user_func($this->callback,$type,$usermsg);
    }

    //通过id推送信息(向单个用户发信息)
	public function idwrite($id,$msg){
		//没有这个标示
     	if(!$this->users[$id]['socket']){
     		return false;
     	}
      	$msg = $this->code($msg);
      	return socket_write($this->users[$id]['socket'],$msg,strlen($msg));
    }

    //发送给所有人
    public function allweite($msg){
    	foreach($this->users as $userid => $data){
    		$this->write($data['socket'],$msg);
    	}
    }

    //通过标示推送信息
    public function write($sige,$msg){
      	$msg = $this->code($msg);
      	return socket_write($sige,$msg,strlen($msg));
    }

	//握手
	public function handshake($userid,$buffer){
	    $buf  = substr($buffer,strpos($buffer,'Sec-WebSocket-Key:')+18);
	    $key  = trim(substr($buf,0,strpos($buf,"\r\n")));
	    $new_key = base64_encode(sha1($key."258EAFA5-E914-47DA-95CA-C5AB0DC85B11",true));
	    $new_message = "HTTP/1.1 101 Switching Protocols\r\n";
	    $new_message .= "Upgrade: websocket\r\n";
	    $new_message .= "Sec-WebSocket-Version: 13\r\n";
	    $new_message .= "Connection: Upgrade\r\n";
	    $new_message .= "Sec-WebSocket-Accept: " . $new_key . "\r\n\r\n";
	    socket_write($this->users[$userid]['socket'],$new_message,strlen($new_message));
	    $this->users[$userid]['hand']=true;
	    return true;
  	}

  	//
  	public function uncode($str){
	    $mask = array();  
	    $data = '';  
	    $msg = unpack('H*',$str);  
	    $head = substr($msg[1],0,2);  
	    if (hexdec($head{1}) === 8) {  
	      	$data = false;  
	    }else if (hexdec($head{1}) === 1){  
	    	if(substr($msg[1],2,2)=='fe'){
                $msg[1]=substr($msg[1],4);
            }else if(substr($msg[1],2,2)=='ff'){
                $msg[1]=substr($msg[1],16);
            }
	      	$mask[] = hexdec(substr($msg[1],4,2));
	      	$mask[] = hexdec(substr($msg[1],6,2));
	      	$mask[] = hexdec(substr($msg[1],8,2));
	      	$mask[] = hexdec(substr($msg[1],10,2));
	      	$s = 12;  
	      	$e = strlen($msg[1])-2;  
	      	$n = 0;
	      	for ($i=$s; $i<= $e; $i+= 2) {  
	        	$data .= chr($mask[$n%4]^hexdec(substr($msg[1],$i,2)));  
	        	$n++;  
	      	}  
	    }  
	    return $data;
  	}

  	public function code($msg){
      	$msg = preg_replace(array('/\r$/','/\n$/','/\r\n$/',), '', $msg);
     	 $frame = array();  
      	$frame[0] = '81';  
      	$len = strlen($msg);  
      	$frame[1] = $len<16?'0'.dechex($len):dechex($len);
      	$frame[2] = $this->ord_hex($msg);
      	$data = implode('',$frame);
      	return pack("H*", $data);
    }

    public function ord_hex($data){  
      	$msg = '';  
      	$l = strlen($data);  
      	for ($i= 0; $i<$l; $i++) {  
        	$msg .= dechex(ord($data{$i}));  
      	}  
      	return $msg;  
    }

  	//退出(通过标示断开连接)
  	public function close($sign){
	    $userid = array_search($sign, $this->sockets);
	    socket_close($sign);
	    unset($this->sockets[$userid]);
	    unset($this->users[$userid]);
  	}

	//通过标示遍历获取id
	public function search($sign){
	    foreach ($this->users as $userid=>$user){
	      	if($sign == $user['socket'])
	      	return $userid;
	    }
	    return false;
  	}

	public function lines(){
		if (substr(php_sapi_name(), 0, 3) !== 'cli') {
            $this->log('请通过命令行模式运行!');
        }
	}

	public function error($msg){
		$this->log($msg);
		die;
	}

    function log($msg){//控制台输出
      	if($this->log){
        	$msg = $msg."\r\n";
        	fwrite(STDOUT,$msg);
      	}
    }
}
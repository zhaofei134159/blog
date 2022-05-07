<?php

header("Content-type: text/html; charset=utf-8");

/*php摘要认证*/
// unset($_SERVER['PHP_AUTH_DIGEST']);

$realm = 'Restricted area';
 
//user => password
$users = array('admin' => 'mypass', 'guest' => 'guest');
 
// 指定 Digest 验证方式
if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
    setcookie('login', 1);  // 退出登录条件判断
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Digest username="admin", realm="' . $realm . '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5($realm) . '"');
     
    // 如果用户不输入密码点了取消
    die('您点了取消，无法登录');
     
}
 
// 验证用户登录信息
if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) || !isset($users[$data['username']])) {
    die('Wrong Credentials2!');
}
 
// 验证登录信息
$A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
$A2 = md5($_SERVER['REQUEST_METHOD'] . ':' . $data['uri']);
$valid_response = md5($A1 . ':' . $data['nonce'] . ':' . $data['nc'] . ':' . $data['cnonce'] . ':' . $data['qop'] . ':' . $A2);
// $data['response'] 是浏览器客户端的加密内容

if ($data['response'] != $valid_response) {
    die('Wrong Credentials1!');
}
 
// 用户名密码验证成功
echo '您的登录用户为: ' . $data['username'];
 
// 获取登录信息
function http_digest_parse($txt)
{
    // echo $txt;
    // protect against missing data
    $needed_parts = array('nonce' => 1, 'nc' => 1, 'cnonce' => 1, 'qop' => 1, 'username' => 1, 'uri' => 1, 'response' => 1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));
 
    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);
 
    foreach ($matches as $m) {
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }
 
    return $needed_parts ? false : $data;
}
 
/**
 * Desc: 下载文件
 * @param $url 文件url
 * @param $save_dir 保存目录
 * @param $file_name 文件名
 * @return string
 */
function download_file($url, $save_dir, $file_name)
{
    if (!file_exists($save_dir)) {
        mkdir($save_dir, 0775, true);
    }
    $file_src = $save_dir . $file_name;
    file_exists($file_src) && unlink($file_src);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    $file = curl_exec($ch);
    curl_close($ch);
    $resource = fopen($file_src, 'a');
    fwrite($resource, $file);
    fclose($resource);
    if (filesize($file_src) == 0) {
        unlink($file_src);
        return '';
    }
    return $file_src;
}

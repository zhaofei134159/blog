<?php 

class Htmlrepair{
  
  function fix_html_tags($input, $single_tags = array()) {
    $result = null;
    $stack = array();//标签栈
    $_single_tags = array('br', 'hr', 'img', 'input');//合理的单标签
 
    if ($single_tags && is_array($single_tags)) {
        $_single_tags = array_merge($_single_tags, $single_tags);
        $_single_tags = array_map('strtolower', $_single_tags);
        $_single_tags = array_unique($_single_tags);
    }
    //返回标签分隔之后的内容，包括标签本身
    $content = preg_split('/(<[^>]+>)/si', $input, null, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
 
    foreach ($content as $val) {
        //匹配未闭合的自闭合标签 如 <br> <hr> 等
        if (preg_match('/<(\w+)[^\/]*>/si', $val, $m) && in_array(strtolower($m[1]), $_single_tags) ) {
            $result .= "\r\n" . $val;
        }
        //匹配标准书写的自闭合标签，直接返回，不入栈
        else if (preg_match('/<(\w+)[^\/]*\/>/si', $val, $m)) {
            $result .= $val;
        }
        //匹配开始标签，并入栈
        else if (preg_match('/<(\w+)[^\/]*>/si', $val, $m)) {
            $result .= "\r\n" . str_repeat("\t", count($stack)) . $val;
            array_push($stack,  $m[1]);
        }
        //匹配闭合标签，匹配前先判断当前闭合标签是栈顶标签的闭合，优先闭合栈顶标签
        else if (preg_match('/<\/(\w+)[^\/]*>/si', $val, $m)) {
            //出栈，多余的闭合标签直接舍弃
            if (strtolower(end($stack)) == strtolower($m[1])) {
                array_pop($stack);
                $result .= $val;
            }
        } else {
            $result .= $val;
        }
    }
 
    //倒出所有栈内元素
    while ($stack) {
        $result .= "</".array_pop($stack).">";
        $result .= "\r\n";
    }
 
    return $result;
  }
}
// $str = "<div><table>x<tr>1s<td>测试<td>124"; 
// echo subHtml($str); 

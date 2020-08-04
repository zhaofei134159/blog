<?php
class TreeArray{
    /*
    * 返回树形数组
    */
    public static function getTreeArray($data, $cur_pid = 0, $level = 0, $strtag = '----'){
        $tree = array();
        foreach($data as $row) {
            $tree[$row['pid']][] = $row;
        }
        $newarray = array();        
        self::_getNewArray($tree, $newarray, $cur_pid, $level, $strtag);
        return $newarray;
    }
    function _getNewArray($tree, & $options, $cur_pid, $level, $strtag) {
        if(isset($tree[$cur_pid])) {
            foreach($tree[$cur_pid] as $pid => $row) {
                $tag = "";
           	    for($i = 0; $i < $level; $i ++) {
                    $tag .= $strtag;
           	    }
                $row['tag']   = $tag;
                $row['level'] = $level;
                $options[$row['id']] = $row;
                self::_getNewArray($tree, $options, $row['id'], $level + 1, $strtag);
            }
        }
    }



    /*
    * 返回嵌套数组
    */
    public static function getNestingArray($data, $idname, $pidname, $cur_pid = 0, $level = 0){
         array_multisort($data); 
        //定义目标数组
        $d = array();
        //定义索引数组，用于记录节点在目标数组的位置
        $ind = array();
        foreach($data as $v) {
            $v['child'] = array(); //给每个节点附加一个child项
            if($v[$pidname] == $cur_pid) {
                $i = count($d);
                $d[$i] = $v;
                $ind[$v[$idname]] =& $d[$i];
            }else {
                $i = count($ind[$v[$pidname]]['child']);
                $ind[$v[$pidname]]['child'][$i] = $v;
                $ind[$v[$idname]] =& $ind[$v[$pidname]]['child'][$i];
            }
        }
        return $d;
    }
    
   /*
    * 返回select数组
    */
    public static function getTreeSelect($data, $filedname = 'name', $defaultvalue = '', $cur_pid = 0, $level = 0, $strtag = '----'){
        $tree = array();
        foreach($data as $row) {
            $tree[$row['pid']][] = $row;
        }
        $newarray = array();        
        self::_getNewSelectArray($tree, $newarray, $cur_pid, $level, $strtag);
        $selectTree = "";
        $selectTree .= self::_getOption($newarray, $filedname, $defaultvalue);
        return $selectTree;
    }
    function _getOption($newarray, $filedname, $defaultvalue){
        $str = "";
        foreach($newarray as $item){
            $str .= "<option  value='". $item['id'] ."' ";
            if($item['id'] == $defaultvalue) $str .=  " selected ";
            $str .= " >";
            $str .= $item['tag'].$item[$filedname] ."</option>";	
        }	
        return $str;
    }
    function _getNewSelectArray($tree, & $options, $cur_pid, $level, $strtag) {
        if(isset($tree[$cur_pid])) {
            foreach($tree[$cur_pid] as $pid => $row) {
                $tag = "";
           	    for($i = 0; $i < $level; $i ++) {
                    $tag .= $strtag;
           	    }
                $row['tag']   = $tag;
                $row['level'] = $level;
                $options[$row['id']] = $row;
                self::_getNewSelectArray($tree, $options, $row['id'], $level + 1, $strtag);
            }
        }
    }
}
?>
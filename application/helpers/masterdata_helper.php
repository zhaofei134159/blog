<?php
class MasterData{

    /*
    * 更新推荐位
    */
    public static function upPosition($oldposids = false, $newposids = false, $dataid = false, $data = false){
        $CI = & get_instance();
        $CI->load->model('Position_Base_Model');
        $CI->load->model('Position_Data_Model');
        
        if(!empty($oldposids) && !empty($newposids)){
            $oldposidArr = explode(",", $oldposids);
            $newposidArr = explode(",", $newposids);
            $delposidArr = array_diff($oldposidArr, $newposidArr);//需删除的
            $addposidArr = array_diff($newposidArr, $oldposidArr);//需新增的
            $updposidArr = array_intersect($oldposidArr, $newposidArr);//需修改的
        }else if(!empty($oldposids)){
            //$updposidArr = explode(',', $oldposids);//需修改的
            $delposidArr = explode(',', $oldposids);//需删除的
        }else if(!empty($newposids)){
            $addposidArr = explode(',', $newposids);//需新增的
        }
        
        if(!empty($delposidArr)){ //删除
            $CI->Position_Data_Model->del("`position_base_id` in (".implode(',', $delposidArr).") AND `dataid` = '".$dataid."'"); 
        }
        if(!empty($updposidArr)){  //修改
            foreach($updposidArr as $k => $v){
                $posdata = $CI->Position_Data_Model->getOne("`position_base_id` = '".$v."' AND `dataid` = '".$dataid."'");
                if(!empty($posdata)){
                    $posdata['data'] = serialize($data);
                    $CI->Position_Data_Model->update($posdata, "`id` = '".$posdata['id']."'");
                }else{
                    $dataorder = $CI->Position_Data_Model->getMax("dataorder", "`position_base_id` = '".$v."'");
                    if(empty($dataorder)){
                        $dataorder = 1;
                    }else{
                        $dataorder = $dataorder+1;
                    }
                    $datas = array(
                        'position_base_id' => $v,
                        'dataid'           => $dataid,
                        'data'             => serialize($data),
                        'dataorder'        => $dataorder
                    );
                    $CI->Position_Data_Model->insert($datas);
                }
            }
        }
        if(!empty($addposidArr)){  //添加
            foreach($addposidArr as $k => $v){
                $posidata = $CI->Position_Data_Model->getOne("`position_base_id` = '".$v."' AND `dataid` = '".$dataid."'");
                if(empty($posidata)){
                    $dataorder = $CI->Position_Data_Model->getMax("dataorder", "`position_base_id` = '".$v."'");
                    if(empty($dataorder)){
                        $dataorder = 1;
                    }else{
                        $dataorder = $dataorder+1;
                    }
                    $datas = array(
                        'position_base_id' => $v,
                        'dataid'           => $dataid,
                        'data'             => serialize($data),
                        'dataorder'        => $dataorder
                    );
                    $CI->Position_Data_Model->insert($datas);
                }
                
                //删除 大于 最大保存条数 的数据 (按排序号最低的)
                $posbase = $CI->Position_Base_Model->getOne("`id` = $v"); 
                $posdatacount = $CI->Position_Data_Model->getCount("`position_base_id` = '".$v."'");
                if($posdatacount > $posbase['maxnum']){
                    $limit = $posdatacount - $posbase['maxnum'];
                    //$CI->Position_Data_Model->delLimit("`position_base_id` = '".$v."'", "`dataorder` ASC, `id` ASC",  $limit);
                    $delPosDatas = $CI->Position_Data_Model->getAll("`position_base_id` = '".$v."'", "`id`, `dataid`", "`dataorder` ASC, `id` ASC",  $limit, 0);
                    foreach($delPosDatas as $k2 => $v2){
                        $CI->Position_Data_Model->delLimit("`id` = '".$v2['id']."'");
                        //去掉数据表里推荐位
                        $tab = explode('_', $posbase['pos_type']);		
                        $model = ucfirst($tab[0]) . "_" . ucfirst($tab[1])."_Model";
                        $CI->load->model(''.$model.'');
                        $resource = $CI->$model->getOne("`id` = '".$v2['dataid']."'", "`position_base_check`");
                        if(!empty($resource['position_base_check'])){
                            if($resource['position_base_check'] == $v){
                                $resourcedata = array('position_base_check' => '');
                                $CI->$model->update($resourcedata, "`id` = '".$v2['dataid']."'");
                            }else{
                                $positionBaseCheckArr = explode(',', $resource['position_base_check']);
                                if(in_array($v, $positionBaseCheckArr)){
                                    $newPositionBaseCheckArr = array_diff($positionBaseCheckArr, array($v));
                                    $resourcedata = array('position_base_check' => implode(',', $newPositionBaseCheckArr));
                                    $CI->$model->update($resourcedata, "`id` = '".$v2['dataid']."'");
                                }
                            }
                        }
                    }
                }
            }
        }
    }


    /*
    * 获取关联表名称字段
    */
    public static function getRelName($id = false, $tablename = false, $colname = false){
        $CI = & get_instance();
        if(empty($id) || empty($tablename) || empty($colname)){
            return null;
        }
        list($i, $m, $n) = explode('_', $tablename);		
        $model = ucfirst($m) . "_" . ucfirst($n)."_Model";
        $function = "getOne";
        $CI->load->model(''.$model.'');
        $where = "`id` = '".$id."'";
        $record = $CI->$model->$function($where, "`".$colname."`");
        return $record[$colname];
    }

    /*
     * 显示关联表名称
     */
    public function getChoiceName($tablename = false, $name = false, $defaultvalues = false, $tag = '|'){
        if(empty($tablename) || empty($name) || empty($defaultvalues)){
            return null;
        }else{
            $CI = & get_instance();
            $where = "`id` in (".$defaultvalues.")";
            list($i, $m, $n) = explode('_', $tablename);		
            $model = ucfirst($m) . "_" . ucfirst($n)."_Model";
            $function = "getAll";
            $CI->load->model(''.$model.'');
            $records = $CI->$model->$function($where, $name);
            $str = "";
            foreach($records as $key => $v){
                $str .= "<span style='color:red;'>".$v[''.$name.'']."</span>";
                if($key < count($records)-1) $str .= $tag; 
            }
            return $str;
        }
        die();
    }


    /*
    * 获取创建人
    */
    public static function getCodeCreater($creater, $createtype = 0){
        $CI = & get_instance();
        $CI->load->model('Agent_User_Model');
        $CI->load->model('Admin_User_Model');
        if($createtype == 0){
            $user = $CI->Admin_User_Model->getOne("`id` = '".$creater."'");
            if(!empty($user)){
                return $user['realname'] . '_' . $user['user_name'];
            }else{
                return null;
            }
        }else if($createtype == 1){
            $user = $CI->Agent_User_Model->getOne("`id` = '".$creater."'");
            if(!empty($user)){
                return $user['relname'] . '_' . $user['id'];
            }else{
                return null;
            }
        }else{
            return null;
        }
    }
    
    
    
        
        
    
    /*
    * 获取权限组
    */
    public static function getGroup($id = false){
        $CI = & get_instance();
        $CI->load->model('Admin_Group_Model');
        $where = " `id` = '".$id."' ";
        $group = $CI->Admin_Group_Model->getOne($where);
        return $group;
    }
    

}

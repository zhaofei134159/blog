<?php

/***
 * @desc 数据绑定，请确保数据表示一对一的关系，如不是一对一关系，则不能保证数据完整性
 * @author zht
 * @param Array $src_rows 原数据，数组类型，引用传递
 * @param String $src_key_name 所要绑定的键名（或者说字段名）
 * @param String $bind_tab 关联数据表的表名
 * @param String $bind_key_name 关联数据表的与 $src_key_name 对应的键名
 * @param String $bind_fields 查询关联数据表的字段 
 * @date 2015/7/8
 **/
function bind(&$src_rows, $src_key_name, $bind_tab, $bind_key_name, $bind_fields = '*')
{
    // 数据表前缀
    $db_prefixs = array('crm_', 'ts_zy_', 'ts_');

    if (!$src_rows)
    {
        return ;
    }
    else if (!is_array($src_rows))
    {
        return ;
    } 

    $src_key_vals = array();
    foreach ($src_rows as $src_row)
    {
        // 确保唯一
        $src_key_vals[$src_row[$src_key_name]] = $src_row[$src_key_name];
    } 

    $ci = & get_instance();
    $ci->db->select($bind_fields);
    $ci->db->where_in($bind_key_name, $src_key_vals); 
    $bind_rows = $ci->db->get($bind_tab)->result_array();
    

    if ($bind_rows)
    {
        $bind_rows_map = array();
        foreach ($bind_rows as $bind_row)
        {
            $bind_rows_map[$bind_row[$bind_key_name]] = $bind_row;
        }

        // 设置关联数据在原数据中对应的键名
        $bind_item_name = str_replace($db_prefixs, '', $bind_tab);

        foreach ($src_rows as $src_row_key => $src_row)
        {
            if (isset($bind_rows_map[$src_row[$src_key_name]]))
            {
                $src_rows[$src_row_key][$bind_item_name] = $bind_rows_map[$src_row[$src_key_name]];
            }
        }
    }
 
}


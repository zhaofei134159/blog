<?php
defined( 'BASEPATH' ) 
		or  exit( 'No direct script access allowed' );

class  MY_Model extends CI_Model{
    //表名
	protected $table_name;
    //主键        
    protected $primary_key;
   
	/**
	 * construct
	 */
	function  __construct(){
		parent::__construct();
	}
	
    /*
    * 初始化
    */
    function iniTab( $table_name, $primary_key='id' ){
        $this->table_name = $table_name;
        $this->primary_key = $primary_key;
    }
	    
    /**
     * 新增数据
     * @date 数据
     * @table 表名
     */
    function insert($data, $table = '') {
        if(empty($table)) $table = $this->table_name;
        $this->db->insert ( $table, $data );
        return $this->db->insert_id ();
    }
    
    /**
     * 删除表信息
     * @where 条件
     * @table 表名
     */
    function delete( $where, $table = '') {
        if(empty($table)) $table = $this->table_name;
        $this->db->where ( $where );
        return $this->db->delete ( $table );
    }
    
    /**
     * 修改信息
     * @date 数据
     * @where 条件
     * @table 表名
     */
    function update($data, $where = 'id = 0', $table = '') {
        if(empty($table)) $table = $this->table_name;  
        return $this->db->update ( $table, $data, $where );
    }
    
    /**
     * 获得一条信息
     * @select 查询字段
     * @where 条件
     * @table 表名
     */
    function getone( $where = 'id >0',  $select = '*', $table = '') {
         if(empty($table)) $table = $this->table_name;      
         $row = array ();               
         $query = $this->db->select ( $select )->get_where ( $table, $where, 1, 0 );
      
          if ($query->num_rows () > 0) {             
                $row = $query->row_array ();
          }
          return $row;
    }
    
    /**
    * @desc 查询多条数据
    * @where 条件
    * @select 查询字段
    * @orderby 排序 默认id 降序
    * @limit 条数，默认15条
    * @offset 条数开始记录 默认从0开始 
     */
    function getlist($where = '', $select = '*', $orderby = '', $limit = 15, $offset = 0) {
        if(empty($this->table_name))
        {
            return array();
        }
        $table = $this->table_name;
        if(!$orderby)
        {
            $orderby = $this->primary_key.' desc'; 
        }
     
        $rows = array ();
        $this->db->select ( $select );
        $this->db->order_by ( $orderby );
        if($where) $this->db->where ( $where, NULL, FALSE ); 
        if (! empty ( $limit )) {
            $query = $this->db->get ( $table, $limit, $offset );
        } else {
            $query = $this->db->get ( $table );
        }
        $rows = $query->result_array ();
        return $rows;
    }
    
    /**
    * @desc 查询全部
    * @where 条件
    * @select 查询字段
    * @orderby 排序 默认id 降序            
    * @table 表名
     */
    function getall($where = '', $select = '*', $orderby = '', $table = '') {
        if(empty($table)) 
        {
            $table = $this->table_name; 
        }
        $rows = array ();
        $this->db->select ( $select );
        
        if($orderby == '') {
            $orderby = $this->primary_key . ' desc'; 
        }
        $this->db->order_by ( $orderby );

        if($where) $this->db->where ( $where, NULL, FALSE ); 
       
        $query = $this->db->get ( $table );
      
        $rows = $query->result_array ();
        return $rows;
    }
    
    /**
     * 统计数量
     * @table 表名
     * @where 条件      
     */
    function count($where = '', $table = '') {
        if(empty($table)) $table = $this->table_name;      
        if ($where) 
        {
            $this->db->where ($where );
        }
        $count = $this->db->count_all_results ($table);
        return $count;
    }
    
    /**
     * sql统计信息数量
     * @sql 执行的sql   
     */
    function countsql($sql ) { 
        $count = $this->db->query ( $sql )->num_rows ();     
        return $count;
    }

    /**
     * 只执行SQL语句
     */
    function setquery($sql) {
        $query = $this->db->query ( $sql );
        return $query;
    }

    /**
     * 执行SQL语句返回结果
     */
    function getquery($sql) {
        $query = $this->db->query ( $sql );
        $res = array ();
        if ($query->num_rows () > 0) {
            $res = $query->result_array ();
        }
        return $res;
    }
    
    /***
    * @desc 
    */
	function get_last_query()
    {
        return $this->db->last_query();
    }
}
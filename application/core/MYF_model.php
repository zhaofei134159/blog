<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MYF_Model extends CI_Model {
 
    var $table = '';  
 
    var $prikey = 'id';  
  
	public function __construct(){
		parent::__construct();
		$this->load->database('fortune', true);
	}

	public function init($table,$prikey){
		$this->table = $table;
		$this->prikey = $prikey;
	}

	/** 
     * 执行sql @xwlyun 
     * @param $sql 
     * @param bool $affect_num 是否返回影响行数 
     * @return mixed 
     */  
    function query($sql,$affect_num=false){  
        $query = $this->db->query($sql);  
        if($affect_num){  
            $query = $this->db->affected_rows();  
        }  
        return $query;  
    }  
  

	
	/**
	 * 返回多行数据 @xwlyun
	 * @param $sql
	 * @return mixed
	 */
	function getRows($sql){
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/**
	 * 返回单行数据 @xwlyun
	 * @param $sql
	 * @return mixed
	 */
	function getRow($sql){
		$data = $this->getRows($sql);
		return $data[0];
	}

	/**
	 * 返回单行首列数据 @xwlyun
	 * @param $sql
	 * @return mixed
	 */
	function getOne($sql){
		$data = $this->getRow($sql);
		return current($data);
	}
  
    /**
     * 新增数据
     * @date 数据
     * @table 表名
     */
    function insert($data, $table = '') {
        if(empty($table)) $table = $this->table;
        $this->db->insert ( $table, $data );
        return $this->db->insert_id ();
    }

    /**
     * 删除表信息
     * @where 条件
     * @table 表名
     */
    function delete( $where, $table = '') {
        if(empty($table)) $table = $this->table;
        $this->db->where ( $where );
        return $this->db->delete ( $table );
    }


	function update($data, $where = 'id = 0', $table = '') {
        if(empty($table)) $table = $this->table;  
        return $this->db->update ( $table, $data, $where );
    }

	/**
	 * 获取总数 @xwlyun
	 * @return mixed
	 */
	function count($where){
        $this->db->where($where);
		
		$this->db->from($this->table);
		
		return $this->db->count_all_results();
	}

	/**
	 * select (e.g. array('field1','field2',...) or 'filed1,filed2,...') @xwlyun
	 * @param string $select
	 * @return mixed
	 */
	function select($where,$select="*",$order=''){

		$this->db->select($select);
        $this->db->where($where);
		
		if(!empty($order)){
			$this->db->order_by($order);
		}
		
		$query = $this->db->get($this->table);

   		$data = $query->result_array();

		return $data;
	}

	function select_one($where,$select="*"){

		$this->db->select($select);

        $this->db->where($where);

		$query = $this->db->get($this->table);

    	$data = $query->row_array();
		return $data;
	}

	function get_list($where,$select="*",$order='',$pagesize=0,$offset=15){

		$this->db->select($select);
        $this->db->where($where);

		if(!empty($order)){
			$this->db->order_by($order);
		}
		
		$this->db->limit($pagesize,$offset);
		
		$query = $this->db->get($this->table);

		$data = $query->result_array();
		return $data;
	}
}
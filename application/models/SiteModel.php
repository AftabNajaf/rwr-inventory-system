<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SiteModel extends CI_Model{

//////////////////generic function area-- dont add project modal function//////fk//////
public function getRow($tableName,$cond = array())
	{
		if($cond) $this->db->where($cond);
		$query = $this->db->get($tableName);
		return $query->row_array();
	}
	public function getRows($tableName,$cond = array())
	{
		if($cond)
		$this->db->where($cond);
		$query = $this->db->get($tableName);
		// echo $this->db->last_query();
		return $query->result_array();
	}
	public function delRow($tableName,$cond = array())
	{ 
		$this->db->where($cond);
		$this->db->delete($tableName);
	}
	public function updateRow($tableName,$cond = array(),$data = array())
	{
		$this->db->where($cond);
		if($this->db->update($tableName,$data))
			return true;
	}
	public function getDistinct($tableName,$distinctField,$cond = array(),$selectField = FALSE)
	{
		$this->db->distinct($distinctField);
		if($selectField)
			$this->db->select($distinctField);
		if($cond)
			$this->db->where($cond);
		$query = $this->db->get($tableName);
		return $query->result_array();
	}
	public function getDistinctWhereIn($tableName,$distinctField,$whereIn,$cond = array(),$selectField = FALSE)
	{
		$this->db->distinct($distinctField);
		if($selectField)
			$this->db->select($distinctField);
		if($cond)
			$this->db->where($cond);
		$this->db->where_in($whereIn[0],$whereIn[1]);

		$query = $this->db->get($tableName);
		return $query->result_array();
	}
	public function getField($tableName,$cond = array(),$field)
	{
		$this->db->where($cond);
		$query = $this->db->get($tableName);
		$record = $query->row_array();
		if (!empty($record)) {
			return $record[$field];
		}
		else
			return 'Not found';
		
	}
	public function insertData($tableName,$data)
	{
		if($this->db->insert($tableName,$data))
		return true;
		// else
		// 	echo $this->db->_error_message();
	}
	public function insertReturn($tableName,$data)
	{
		if($this->db->insert($tableName,$data))
			return $this->db->insert_id();
		else
			return false;
	}
	
	public function myQuery($query,$obj_resp = FALSE)
	{
		$q =  $this->db->query($query);
		if($obj_resp)
			return $q->result_object();
		else
			return $q->result_array();

	}
	public function myQueryz($query)
		{
			$q =  $this->db->query($query);
				return $q->result_array();
		}

	public function getTableData($tableName)
	{
		$query = $this->db->get($tableName);
		 return $query->result_array();
	}
	public function getTableDataSorted($tableName,$order_field,$cond = array())
	{
		$this->db->order_by($order_field,'asc');
		$this->db->where($cond);
		$query = $this->db->get($tableName);
		//echo $this->db->last_query();
		 return $query->result_array();
	}

	public function getTableDataLimit($tableName,$limit,$start_index,$cond = array())
	{	$this->db->limit($limit,$start_index);
		if($cond)
			$this->db->where($cond);
		$query = $this->db->get($tableName);
		 return $query->result_array();
	}
	public function countRows($table,$cond = array())
	{
		$this->db->where($cond);
		return $this->db->count_all_results($table);
	}
	public function multiCondition($table,$conditions,$result = 'all')
	{
		$this->db->where($conditions);
		$query = $this->db->get($table);
		if($result == 'all')
			return $query->result_array();
		else
			return $query->row_array();
	}
	public function getOrderby($tableName,$orderField,$order = 'desc',$cond = array())
	{
		$this->db->order_by($orderField,$order);
		if($cond) 
			$this->db->where($cond);
		$query = $this->db->get($tableName);
		// echo $this->db->last_query();
			return $query->result_array();
	}
	public function getWhereIn($tableName,$whereIn,$cond = array())
	{
		if($cond) $this->db->where($cond);
		$this->db->where_in($whereIn[0],$whereIn[1]);
		$query = $this->db->get($tableName);
			return $query->result_array();

	}
	public function getWhereInMultiple($tableName,$whereInCond,$cond = array(),$sortArr = array(),$obj = FALSE)
	{
		if($sortArr)
			$this->db->order_by($sortArr[0],$sortArr[1]);

		if($cond) $this->db->where($cond);
		foreach ($whereInCond as $key => $value) {
			$this->db->where_in($key,$value);
		}

		$query = $this->db->get($tableName);
		// echo $this->db->last_query();
		if($obj)
		return $query->result_object();
		else
			return $query->result_array();
	}

//////////////////genric funcion area -------- ends///////////////////////////////////
///////////////// cms-related function/////////////////////////////////////////////////
	public function getSetting()
	{
		$query = $this->db->get('e_settings');
		return $query->row_array();
	}
	public function getPageData($page_slug)
	{	
		$this->db->where('slug',$page_slug);
		$query = $this->db->get('e_menu');
		return $query->row_array();
	}
	public function getPageDataByTable($tableName,$page_slug)
	{	
		$this->db->where('slug',$page_slug);
		$query = $this->db->get($tableName);
		return $query->row_array();
	}
	public function getMenuRows($tableName,$cond)
	{
		$this->db->order_by('displayorder','asc');
		$this->db->where('display','yes');
		$this->db->where($cond);
		$query = $this->db->get($tableName);
		return $query->result_array();
	}
///////////////// cms-related function area ends///////////////////////////////////////


	public function validateUser($email,$password)
	{	if($email == "" || $password == "") return false;
		$this->db->where('email',$email);
		$this->db->where('password',$password);
		$query = $this->db->get('e_users',1);
		return $query->row_array();
	}


}

?>
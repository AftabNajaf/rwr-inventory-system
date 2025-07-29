<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminModel extends CI_Model{
public function flash_overflow()
{
	$q = $this->db->query('SELECT e_fdata.fdata_id FROM e_fdata LEFT JOIN data on e_fdata.data_id = data.data_id where data.data_id is NULL');
	return $q->result_array();

}
//////////////////generic function area-- dont add project modal function//////fk//////
	public function getDataJoin($firstTable,$secTable,$matchingString,$cond = array())
	{
		$this->db->where($cond);
		$this->db->from($firstTable);
		$this->db->join($secTable,$matchingString);
		// $this->db->group_by('e_filters.filter_cat_id');
		$query = $this->db->get();

		// echo $this->db->last_query();
		return $query->result_array();
	}
	
	public function getGroupBy($tableName,$cond = array(),$groupField)
	{
		if($cond) $this->db->where($cond);
		$this->db->group_by($groupField);
		$this->db->query('SET SESSION sql_mode = ""');
		$query = $this->db->get($tableName);
		return $query->result_array();
	}
	
	public function insertReturn($tableName,$data)
	{
		if($this->db->insert($tableName,$data))
			return $this->db->insert_id();
		else
			return false;
	}
	
	public function myQueryz($query)
		{
			$q =  $this->db->query($query);
				return $q->result_array();
		}
	public function myQueryx($query)
		{
			$q =  $this->db->query($query);
			return $q->row_array();
		}	
public function getRow($tableName,$cond = array())
	{
		if($cond) $this->db->where($cond);
		$query = $this->db->get($tableName);
		return $query->row_array();
	}
	public function getRoww($tableName,$cond = array())
	{
		if($cond) $this->db->where($cond);
		$query = $this->db->get($tableName);
		return $query->result_array();
	}
	public function getRows($tableName,$cond = array())
	{
		$this->db->where($cond);
		$query = $this->db->get($tableName);
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
	function getItemAvailQty($value)
	{
		$catz = $this->myQueryx("SELECT i.id,i.item_name,i.item_functionality,i.part_number, i.item_serial_no,i.image,i.doc_list,i.item_description,i.supplier_id, i.supplier_ref,i.item_category, i.item_addl_remarks FROM e_items i where  i.id=".$value);
		$this->db->query('SET SESSION SQL_MODE=""');
		$catzQ = $this->myQueryx("SELECT sum(q.item_quantity) item_quantity, location, custodian FROM  e_items_qty q where q.item_id=".$value." order by id DESC");
		
		$availInStore=$txt ='';
	if($catz){
		 extract($catz);
				 
			 ###restock
			 $xx = $this->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('restock')");//
			if($xx[0]['item_quantity']>=0){} 
			else $xx[0]['item_quantity']=0;
			
			$restockedItems = $xx[0]['item_quantity'];
			
			###release
			 $xx = $this->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('release')");// group by from_instrument,from_instrument_no
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;
			
			$releaseItems = $xx[0]['item_quantity'];
			
			
			###TMP ISSUES
			$xx = $this->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('tmpissue') ");// group by instrument,instrument_no
			
			$yy = $this->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('restock') and from_instrument in ('tmpissue') ");// group by instrument,instrument_no
			if($yy[0]['item_quantity']>=0){} else $yy[0]['item_quantity']=0;
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;
			
			$tmpissueItems = $xx[0]['item_quantity']-$yy[0]['item_quantity'];
			
			###ADV ISSUES
			$xx = $this->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('adv')");// group by instrument,instrument_no
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;
			
			$advbookedItems = $xx[0]['item_quantity'];
			
			###MAT ISSUES
			$xx = $this->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('mat')");// group by instrument,instrument_no
			$yy = $this->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('restock') and from_instrument in ('mat') ");// group by instrument,instrument_no
			if($yy[0]['item_quantity']>=0){} else $yy[0]['item_quantity']=0;
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;
			
			$matissuedItems = $xx[0]['item_quantity']-$yy[0]['item_quantity'];
			
			###MAT ISSUES
			$xx = $this->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('mmov')");// group by instrument,instrument_no
			$yy = $this->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('restock') and from_instrument in ('mmov') ");// group by instrument,instrument_no
			if($yy[0]['item_quantity']>=0){} else $yy[0]['item_quantity']=0;
			
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;
			
			$mmrItems = $xx[0]['item_quantity']-$yy[0]['item_quantity'];
			
		
		  if($catzQ){
		 extract($catzQ); 
		 $availInStore = ($item_quantity-$tmpissueItems-$advbookedItems-$matissuedItems-$mmrItems-$releaseItems);//+$releaseItems+$restockedItems
		 		  
		}
	}
		return $availInStore;//echo $txt;
	
	}
	
	public function getCatPath($catid)
	{ 
		$data ="";
		while($catid>0){
			$query = "select * from e_categories where id = ".$catid;
			$item = $this->db->query($query)->row_array();
			$data = '<a onclick="setNewItemCat('.$item['id'].')">'.$item['title'].'</a> -- '.$data;
						
			$catid = $item['parentid'];
		}
		return rtrim($data, ' -- ');
		
	}
	public function getItemPath($catid)
	{ 
			$query = "select * from e_categories where id in (select item_category from e_items where id=".$catid.")";
			$item = $this->db->query($query)->row_array();
			return $this->getCatPath($item['id']);
			
		
	}	
	public function checkExistedRecord($tableName,$condString)
	{
		$query = "select * from $tableName where ".$condString;
		$record = $this->db->query($query)->row_array();
		if (!empty($record)) {
			//if exist 
			return true;
		}
		else
			return false;
	}
	
	public function checkExistFieldValue($tableName,$cond)
	{
		$this->db->where($cond);
		$query = $this->db->get($tableName);
		$record = $query->row_array();
		if (!empty($record)) {
			//if exist 
			return true;
		}
		else
			return false;
	}
	public function insertData($tableName,$data)
	{   
		if($this->db->insert($tableName,$data))
		return true;
		else
		print_r($this->db->error());
	}
	public function getTableData($tableName)
	{
		$query = $this->db->get($tableName);
		 return $query->result_array();
	}
	public function getTableDataLimit($tableName,$limit,$start_index)
	{	$this->db->limit($limit,$start_index);
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
		if($conditions)
		$this->db->where($conditions);
		$query = $this->db->get($table);
		if($result == 'all')
			return $query->result_array();
		else
			return $query->row_array();
	}
	public function fieldIncrement($tableName,$incrementArr = array(),$cond = array())
	{
		foreach ($incrementArr as $key=>$val){
		    $this->db->set($key, $val, FALSE);
		}
		$this->db->where($cond);
		$this->db->update($tableName);
	}
	public function getSumRows($tableName,$colName,$cond= array())
	{
		$this->db->select_sum($colName);
		$this->db->where($cond);
		$query = $this->db->get($tableName);
		//echo $this->db->last_query(); 
		return $query->row()->$colName;

	}
	public function getAvgRows($tableName,$colName,$cond= array())
	{
		$this->db->select_avg($colName);
		$this->db->where($cond);
		$query = $this->db->get($tableName);
		//echo $this->db->last_query(); 
		return $query->row()->$colName;

	}
	public function myQuery($query)
	{
		return $this->db->query($query);
	}
	public function field_exists($field,$tableName)
	{
		return $this->db->field_exists($field, $tableName);
	}

	public function getOrderby($tableName,$orderField,$order = 'desc',$cond = array())
	{
		$this->db->order_by($orderField,$order);
		if($cond) 
			$this->db->where($cond);
		$query = $this->db->get($tableName);
			return $query->result_array();
	}
	//ON 7oct18
	public function getWhereIn($tableName,$whereIn,$cond = array())
	{
		if($cond) $this->db->where($cond);
		$this->db->where_in($whereIn[0],$whereIn[1]);
		$query = $this->db->get($tableName);
			return $query->result_array();

	}
	public function getWhereInMultiple($tableName,$whereInCond,$cond = array(),$sortArr = array())
	{
		if($sortArr)
			$this->db->order_by($sortArr[0],$sortArr[1]);

		if($cond) $this->db->where($cond);
		foreach ($whereInCond as $key => $value) {
			$this->db->where_in($key,$value);
		}

		$query = $this->db->get($tableName);
		return $query->result_array();
	}
//////////////////genric funcion area -------- ends///////////////////////////////////
///////////////// cms-related function/////////////////////////////////////////////////
	public function validateLogin($username,$password)
	{
		$this->db->where('username',$username);
		$this->db->where('password',$password);
		$this->db->where('status','Active');
		//$this->db->where('emailverified','yes');
		$query = $this->db->get('e_admin');
		return $query->row_array();
	}
	
	public function getSetting()
	{
		$query = $this->db->get('e_settings');
		return $query->row_array();
	}
	
	
	
	
	#####filter form
	public function setFilterForm($title){
		$data = $this->AdminModel->getRow('e_filterz',array('pagename'=>$title));
		if($data)
		extract($data);
		else{
			$data = $this->AdminModel->getRow('e_filterz',array('pagename'=>'Dynamic duration'));
			extract($data);
		}
	if ($title == "Dynamic duration"): ?>
	<div id="dynamic_durationf">
	<div style="float:left; margin:2px">
    <?php $span_label = date("m/d/Y",time())." - ".date("m/d/Y",strtotime(date("Y-m-d",time())." 1 DAY ")); ?>
    <input type="hidden" value="<?=$title?>" id="title" name="title" >
    <input type="hidden" id="center_label" name="center_label" value="<?=$center_label?>" />
    <input type="hidden" readonly id="info_label" name="info_label" value="<?=$base_start?>" />
    <input type="hidden" id="span_label" name="span_label" value="<?=$span_label?>" />
    <input type="hidden" id="tspan" name="tspan" value="<?=$tspan?>" />
	 <input type="number" class="form-control" style="width:40px; height:22px" value="<?=$base_count?>" id="base_count" name="base_count" min="1" onchange="loadFilters()">
	</div>
	<div style="float:left; margin:2px">
	<select id="base_start" name="base_start" onchange="loadFilters()">
	<option value="DAY" <?php if($base_start=='DAY') echo 'selected'; ?> >Day(s)</option>
	<option value="WEEK" <?php if($base_start=='WEEK') echo 'selected'; ?> >Week(s)</option>
	<option value="MONTH" <?php if($base_start=='MONTH') echo 'selected'; ?> >Month(s)</option>
	<!--<option value="QUARTER" <?php if($base_start=='QUARTER') echo 'selected'; ?> >Quarter(s)</option>
	<option value="QUARTER" <?php //if($base_start=='DAY') echo 'selected'; ?> >Halfyear(s)</option>-->
	<option value="YEAR" <?php if($base_start=='YEAR') echo 'selected'; ?> >Year(s)</option>
	</select>
	</div>
	<div style="float:left; margin:2px"><?=$center_label?></div>
	<div style="float:left; margin:2px">
	<select id="base_end" name="base_end" onchange="loadFilters()">
	<option value="0" <?php if($base_end=='0') echo 'selected'; ?> >This</option>
	<option value="-1" <?php if($base_end=='-1') echo 'selected'; ?> >Last</option>
	<option value="+1" <?php if($base_end=='+1') echo 'selected'; ?> >Next</option>
	</select>
	</div>
	<div style="float:left; margin:2px;" id="info_label1"><?=$info_label?></div>
	
	</div>
	<?php elseif($title == "Advance Dynamic duration"): ?>
	<div id="advance_dynamic_durationf">
	<div style="float:left; margin:2px">
    <input type="hidden" value="<?=$title?>" id="title" name="title" >
    <input type="hidden" id="span_label" name="span_label" value="<?=$span_label?>" />
	 <input type="number" class="form-control" style="width:40px; height:22px" value="<?=$base_count?>" id="base_count" name="base_count" min="1" onchange="loadFilters()">
	</div>
	<div style="float:left; margin:2px">
	<select id="base_start" name="base_start" onchange="loadFilters()">
	<option value="DAY" <?php if($base_start=='DAY') echo 'selected'; ?> >Day(s)</option>
	<option value="WEEK" <?php if($base_start=='WEEK') echo 'selected'; ?> >Week(s)</option>
	<option value="MONTH" <?php if($base_start=='MONTH') echo 'selected'; ?> >Month(s)</option>
	<option value="QUARTER" <?php if($base_start=='QUARTER') echo 'selected'; ?> >Quarter(s)</option>
	<!--<option value="QUARTER" <?php //if($base_start=='DAY') echo 'selected'; ?> >Halfyear(s)</option>-->
	<option value="YEAR" <?php if($base_start=='YEAR') echo 'selected'; ?> >Year(s)</option>
	</select>
	</div>
	<div style="float:left; margin:2px">
	<select id="center_label" name="center_label" onchange="loadFilters()">
	<option value="Starting">Starting</option>
	<option value="Ending" <?php if($center_label=='Ending') echo 'selected'; ?> >Ending</option>
	</select>
	</div>
	<div style="float:left; margin:2px">
	<input type="number" class="form-control" style="width:40px; height:22px" value="<?=$base_end?>" id="base_end" name="base_end" min="1" onchange="loadFilters()">
	</div>
	<div style="float:left; margin:2px;">
	<select id="info_label" name="info_label" onchange="loadFilters()">
	<option value="DAY" <?php if($info_label=='DAY') echo 'selected'; ?> >Day(s)</option>
	<option value="WEEK" <?php if($info_label=='WEEK') echo 'selected'; ?> >Week(s)</option>
	<option value="MONTH" <?php if($info_label=='MONTH') echo 'selected'; ?> >Month(s)</option>
	<!--<option value="QUARTER" <?php // if($info_label=='QUARTER') echo 'selected'; ?> >Quarter(s)</option>
	<option value="QUARTER" <?php //if($base_count=='DAY') echo 'selected'; ?> >Halfyear(s)</option>-->
	<option value="YEAR" <?php if($info_label=='YEAR') echo 'selected'; ?> >Year(s)</option>
	</select>
	</div>
	<div style="float:left; margin:2px;">
	<select id="tspan" name="tspan" onchange="loadFilters()">
	<option value="Ago" >Ago</option>
	<option value="From now" <?php if($tspan=='From now') echo 'selected'; ?> >From now</option>
	</select>
	</div>    
	</div>

	<?php elseif($title == "Duration between specified dates"): ?>
	<div id="duration_between_specified_datesf">
	<div style="float:left; margin:2px">
     <input type="hidden" value="<?=$title?>" id="title" name="title" >
      <input type="hidden" id="tspan" name="tspan" value="<?=$tspan?>" />
      <input type="hidden" id="span_label" name="span_label" value="<?=$span_label?>" />
	 <input type="hidden" class="form-control" style="width:40px; height:22px" value="0" id="base_count" name="base_count" min="1">
	 <?=$center_label?>
	 <input type="hidden" id="center_label" name="center_label" value="<?=$center_label?>" />
	</div>
	<div style="float:left; margin:2px">
    
	<input type="text" class="form-control default_dtpp" style="width:100px;" id="base_start" name="base_start" value="<?=date('m-d-Y', strtotime('-14 days', time()));?>"   >
	</div>
	<div style="float:left; margin:2px min-width:120px">
	<?=$info_label?>
	<input type="hidden" id="info_label" name="info_label" value="<?=$info_label?>" />
	</div>
	<div style="float:left; margin:2px">
	<input type="text" class="form-control default_dtpp" style="width:100px;" id="base_end" name="base_end" value="<?=date("m-d-Y", time())?>"   >
	</div>
	</div>
    <script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
	<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
	<?php elseif($title == "Duration from exact date for a specified period"): ?>
	<div id="duration_from_exact_date_for_a_specified_periodf">
	<div style="float:left; margin:2px">
    <input type="hidden" value="<?=$title?>" id="title" name="title" >
      <input type="hidden" id="tspan" name="tspan" value="<?=$tspan?>" />
      <input type="hidden" id="span_label" name="span_label" value="<?=$span_label?>" />
	 <input type="hidden" class="form-control" style="width:40px; height:22px" value="0" id="base_count" name="base_count" min="1">
	 <?=$center_label?>
	 <input type="hidden" id="center_label" name="center_label" value="<?=$center_label?>" />
	</div>
	<div style="float:left; margin:2px">
	<input type="text" class="form-control default_dtpp" style="width:100px;" id="base_start" name="base_start" value="<?=date("m-d-Y", time())?>"   >
	</div>
	<div style="float:left; margin:2px">
	<input type="number" class="form-control" style="width:40px; height:22px" value="<?=$base_end?>" id="base_end" name="base_end" min="1" onchange="loadFilters()">
	</div>
	<div style="float:left; margin:2px; ">
	<select id="info_label" name="info_label" onchange="loadFilters()">
	<option value="DAY" <?php if($info_label=='DAY') echo 'selected'; ?> >Day(s)</option>
	<option value="WEEK" <?php if($info_label=='WEEK') echo 'selected'; ?> >Week(s)</option>
	<option value="MONTH" <?php if($info_label=='MONTH') echo 'selected'; ?> >Month(s)</option>
	<!--<option value="QUARTER" <?php if($info_label=='QUARTER') echo 'selected'; ?> >Quarter(s)</option>
	<option value="QUARTER" <?php //if($base_count=='DAY') echo 'selected'; ?> >Halfyear(s)</option>-->
	<option value="YEAR" <?php if($info_label=='YEAR') echo 'selected'; ?> >Year(s)</option>
	</select>
	</div>
	</div>

    <script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
	<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
	<?php else : ?>
	<div id="no_time_limitf">
	<div style="float:left; margin:2px">
    <?=$title?>
    	<input type="hidden" value="<?=$title?>" id="title" name="title" >
      	<input type="hidden" id="tspan" name="tspan" value="<?=$tspan?>" />
      	<input type="hidden" id="span_label" name="span_label" value="<?=$span_label?>" />
	 <input type="hidden" class="form-control" style="width:40px; height:22px" value="0" id="base_count" name="base_count" min="1">
	 <input type="hidden" class="form-control" style="width:40px; height:22px" value=" " id="base_start" name="base_start" >
	 <input type="hidden" class="form-control" style="width:40px; height:22px" value=" " id="center_label" name="center_label" >
	 <input type="hidden" class="form-control" style="width:40px; height:22px" value=" " id="base_end" name="base_end" >
	 <input type="hidden" class="form-control" style="width:40px; height:22px" value=" " id="info_label" name="info_label" >
	</div>
    </div>
	<?php endif;
	
	}
	
	#----------------------
	
	public function myReport($qry,$dateone,$datetwo,$addl_string){
		
		$this->db->query("SET SESSION sql_mode = ''");
		$wsdaily = $this->myQueryz($qry);
		
		$mydata ='';
		$mydata .='
  <div class="WordSection1">
  			<table class="table display product-overview" width="100%"  cellpadding="0" cellspacing="0" border="1" id="exptable" style="border:1px solid #000;  font-size:13px; font-family:Arial, Helvetica, sans-serif">
                  <thead style="background-color:#142563; color:#FFF">
                    <tr>
                        <th>Crew</th>
                        <th>Employee Name</th>
                        <th>Hours Worked</th>
                        <th>Amount $</th>
                    </tr>
                </thead>
                  <tbody id="dsheets">';
                 
				 foreach ($wsdaily as $row){
				  extract($row);
						$emp = $this->getRow('e_employees',$cond = array("id"=>$emp_id));
						$empname=$emp['first_name'].' '.$emp['last_name'];
						   
							$mydata .='<tr><td>'.getField("e_roles",array("id"=>$emp_category),"title").'</td><td><a href="'.base_url().'Worksheets/emp_summary/'.$emp_id.'">'.$empname.'</a></td><td>'.$emp_hours.'</td><td>'.$labor_cost.'</td></tr>';
				}	
				
				$mydata .='</tbody></table><br style="page-break-before: always">';
            
		    /**/foreach ($wsdaily as $row){
				  extract($row);
						$emp = $this->getRow('e_employees',$cond = array("id"=>$emp_id));
						$empname=$emp['first_name'].' '.$emp['last_name'];
				$mydata .='<table class="table display product-overview" width="100%"  cellpadding="0" cellspacing="0" border="1" id="exptable" style="border:1px solid #000;  font-size:13px; font-family:Arial, Helvetica, sans-serif">
              <thead style="background-color:#142563; color:#FFF">
                <tr>
                  <td align="center" colspan="3" style="background:#FFF; color:#000" id="span_label12"></td>
                  <td align="center" colspan="4" style="background:#093; font-size:15px" id="empname">'.$empname.'</td>
                </tr>
                <tr style="background-color:#142563; color:#FFF">
                  <td align="center">Work Date</td>
                  <td align="center">Hours/Day</td>
                  <td align="center">Builder</td>
                  <td align="center">Project Name</td>
                  <td align="center">Worksheet</td>
                  <td align="center">Hours/Project</td>
                  <td align="center">Labor Cost</td>
                </tr>
            </thead>';
			
             $zqry = "SELECT work_date, emp_id, emp_category,  emp_hours, emp_rate, builder_id,project_id,userid,updatedon,wsid, labor_cost FROM e_cpayroll WHERE work_date BETWEEN '".$dateone."' AND  '".$datetwo."' ".$addl_string." AND emp_id=".$emp_id." order by  work_date,emp_category,emp_id"; 
                            
			$wxdaily = $this->myQueryz($zqry);
			$tothours=$laborcost=0;
			foreach ($wxdaily as $row){ extract($row); $tothours	+=	$emp_hours; $laborcost	+=	$labor_cost;
            $mydata .='<tr>
                          <td align="center" nowrap>'.date("m-d-Y",strtotime($work_date)).'</td>
                          <td align="center">'.$emp_hours.'</td>
                          <td align="center">'.getField("e_builders",$cond = array('id'=>$builder_id),'builder_name').'</td>
                          <td align="center">'.getField("e_projects",$cond = array('id'=>$project_id),'project_name').'</td>
                          <td align="center">'.$wsid.'</td>
                          <td align="center">'.$emp_hours.'</td>
                          <td align="center">$ '.$labor_cost.'</td>
                          </tr>';
			}
            $mydata .='<tr style="background-color:#6CF; font-weight:bold;" >
                  <td align="center" colspan="5" >Total : </td>
                  <td align="center"> '.$tothours.'</td>
                  <td align="center">$'.$laborcost.'</td>    
                  </tr>
			</table><br style="page-break-before: always">';
			}
  	$mydata .='</div>';
	return $mydata;
	}
	

	##################################3333333333333
	/*function allposts_count()
    {   
        $query = $this
                ->db
                ->get('e_cpayroll');
    
        return $query->num_rows();  

    }
    
    function allposts($limit,$start,$col,$dir)
    {   
       $query = $this
                ->db
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('e_cpayroll');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }
   
    function posts_search($limit,$start,$search,$col,$dir)
    {
        $query = $this
                ->db
                ->like('wsid',$search)
                ->or_like('emp_id',$search)
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('e_cpayroll');
        
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function posts_search_count($search)
    {
        $query = $this
                ->db
                ->like('id',$search)
                ->or_like('emp_id',$search)
                ->get('e_cpayroll');
    
        return $query->num_rows();
    } */
	################################################
	

}
?>